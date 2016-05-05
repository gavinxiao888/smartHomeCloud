/*
 * Copyright (C) 2001-2003 FhG Fokus
 *
 * This file is part of Kamailio, a free SIP server.
 *
 * Kamailio is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version
 *
 * Kamailio is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the Free Software 
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

/*! \file
 *  \brief USRLOC - Usrloc record structure
 *  \ingroup usrloc
 *
 * - Module \ref usrloc
 */

#include "urecord.h"
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <string.h>
#include <curl/curl.h>
#include <json-c/json.h>
#include "../../mem/shm_mem.h"
#include "../../dprint.h"
#include "../../ut.h"
#include "../../hashes.h"
#include "../../tcp_conn.h"
#include "../../pass_fd.h"
#include "ul_mod.h"
#include "usrloc.h"
#include "utime.h"
#include "ul_callback.h"
#include "usrloc.h"

/*! contact matching mode */
int matching_mode = CONTACT_ONLY;
/*! retransmission detection interval in seconds */
int cseq_delay = 20;

str gateway_commonaddr;

/*!
 * \brief Create and initialize new record structure
 * \param _dom domain name
 * \param _aor address of record
 * \param _r pointer to the new record
 * \return 0 on success, negative on failure
 */
int new_urecord(str* _dom, str* _aor, urecord_t** _r)
{
	*_r = (urecord_t*)shm_malloc(sizeof(urecord_t));
	if (*_r == 0) {
		LM_ERR("no more share memory\n");
		return -1;
	}
	memset(*_r, 0, sizeof(urecord_t));

	(*_r)->aor.s = (char*)shm_malloc(_aor->len);
	if ((*_r)->aor.s == 0) {
		LM_ERR("no more share memory\n");
		shm_free(*_r);
		*_r = 0;
		return -2;
	}
	memcpy((*_r)->aor.s, _aor->s, _aor->len);
	(*_r)->aor.len = _aor->len;
	(*_r)->domain = _dom;
	(*_r)->aorhash = ul_get_aorhash(_aor);
	return 0;
}


/*!
 * \brief Free all memory used by the given structure
 *
 * Free all memory used by the given structure.
 * The structure must be removed from all linked
 * lists first
 * \param _r freed record list
 */
void free_urecord(urecord_t* _r)
{
	ucontact_t* ptr;

	while(_r->contacts) {
		ptr = _r->contacts;
		_r->contacts = _r->contacts->next;
		free_ucontact(ptr);
	}
	
	/* if mem cache is not used, the urecord struct is static*/
	if (db_mode!=DB_ONLY) {
		if (_r->aor.s) shm_free(_r->aor.s);
		shm_free(_r);
	}
}


/*!
 * \brief Print a record, useful for debugging
 * \param _f print output
 * \param _r printed record
 */
void print_urecord(FILE* _f, urecord_t* _r)
{
	ucontact_t* ptr;

	fprintf(_f, "...Record(%p)...\n", _r);
	fprintf(_f, "domain : '%.*s'\n", _r->domain->len, ZSW(_r->domain->s));
	fprintf(_f, "aor    : '%.*s'\n", _r->aor.len, ZSW(_r->aor.s));
	fprintf(_f, "aorhash: '%u'\n", (unsigned)_r->aorhash);
	fprintf(_f, "slot:    '%d'\n", _r->aorhash&(_r->slot->d->size-1));
	
	if (_r->contacts) {
		ptr = _r->contacts;
		while(ptr) {
			print_ucontact(_f, ptr);
			ptr = ptr->next;
		}
	}

	fprintf(_f, ".../Record...\n");
}


/*!
 * \brief Add a new contact in memory
 *
 * Add a new contact in memory, contacts are ordered by:
 * 1) q value, 2) descending modification time
 * \param _r record this contact belongs to
 * \param _c contact
 * \param _ci contact information
 * \return pointer to new created contact on success, 0 on failure
 */
ucontact_t* mem_insert_ucontact(urecord_t* _r, str* _c, ucontact_info_t* _ci)
{
	ucontact_t* ptr, *prev = 0;
	ucontact_t* c;

	if ( (c=new_ucontact(_r->domain, &_r->aor, _c, _ci)) == 0) {
		LM_ERR("failed to create new contact\n");
		return 0;
	}
	if_update_stat( _r->slot, _r->slot->d->contacts, 1);

	ptr = _r->contacts;

	if (!desc_time_order) {
		while(ptr) {
			if (ptr->q < c->q) break;
			prev = ptr;
			ptr = ptr->next;
		}
	}

	if (ptr) {
		if (!ptr->prev) {
			ptr->prev = c;
			c->next = ptr;
			_r->contacts = c;
		} else {
			c->next = ptr;
			c->prev = ptr->prev;
			ptr->prev->next = c;
			ptr->prev = c;
		}
	} else if (prev) {
		prev->next = c;
		c->prev = prev;
	} else {
		_r->contacts = c;
	}

	return c;
}


/*!
 * \brief Remove the contact from lists in memory
 * \param _r record this contact belongs to
 * \param _c removed contact
 */
void mem_remove_ucontact(urecord_t* _r, ucontact_t* _c)
{
	if (_c->prev) {
		_c->prev->next = _c->next;
		if (_c->next) {
			_c->next->prev = _c->prev;
		}
	} else {
		_r->contacts = _c->next;
		if (_c->next) {
			_c->next->prev = 0;
		}
	}
}	


/*!
 * \brief Remove contact in memory from the list and delete it
 * \param _r record this contact belongs to
 * \param _c deleted contact
 */
void mem_delete_ucontact(urecord_t* _r, ucontact_t* _c)
{
	mem_remove_ucontact(_r, _c);
	if_update_stat( _r->slot, _r->slot->d->contacts, -1);
	free_ucontact(_c);
}

static inline int is_valid_tcpconn(ucontact_t *c)
{
	if (c->tcpconn_id == -1)
		return 0; /* tcpconn_id is not present */
	else
		return 1; /* valid tcpconn_id */
}

static inline int is_tcp_alive(ucontact_t *c)
{
	struct tcp_connection *con = NULL;
	int rc = 0;

	if ((con = tcpconn_get(c->tcpconn_id, 0, 0, 0, 0))) {
		tcpconn_put(con); /* refcnt-- */
		rc = 1;
	}

	return rc;
}

/*!
 * \brief Close a TCP connection
 *
 * Requests the TCP main process to close the specified TCP connection
 * \param conid the internal connection ID
 */
static inline int close_connection(int conid) {
	struct tcp_connection *con;
	long msg[2];
	int n;
	if ((con = tcpconn_get(conid, 0, 0, 0, 0))) {
		msg[0] = (long)con;
		msg[1] = CONN_EOF;

		n = send_all(unix_tcp_sock, msg, sizeof(msg));
		tcpconn_put(con);
		if (unlikely(n <= 0)){
			LM_ERR("failed to send close request: %s (%d)\n", strerror(errno), errno);
			return 0;
		}
		return 1;
	}
	return 0;
}

/*!
 * \brief Expires timer for NO_DB db_mode
 *
 * Expires timer for NO_DB db_mode, process all contacts from
 * the record, delete the expired ones from memory.
 * \param _r processed record
 */
static inline void nodb_timer(urecord_t* _r)
{
	ucontact_t* ptr, *t;


	ptr = _r->contacts;

	while(ptr) {
		if (handle_lost_tcp && is_valid_tcpconn(ptr) && !is_tcp_alive(ptr)) {
			LM_DBG("tcp connection has been lost, expiring contact %.*s\n", ptr->c.len, ptr->c.s);
			ptr->expires = UL_EXPIRED_TIME;
		}

		if (!VALID_CONTACT(ptr, act_time)) {
			/* run callbacks for EXPIRE event */
			if (exists_ulcb_type(UL_CONTACT_EXPIRE))
				run_ul_callbacks( UL_CONTACT_EXPIRE, ptr);

			LM_DBG("Binding '%.*s','%.*s' has expired\n",
				ptr->aor->len, ZSW(ptr->aor->s),
				ptr->c.len, ZSW(ptr->c.s));

			if (close_expired_tcp && is_valid_tcpconn(ptr)) {
				close_connection(ptr->tcpconn_id);
			}

			t = ptr;
			ptr = ptr->next;

			mem_delete_ucontact(_r, t);
			update_stat( _r->slot->d->expires, 1);
		} else {
			ptr = ptr->next;
		}
	}
}


/*!
 * \brief Write through timer, used for WRITE_THROUGH db_mode
 *
 * Write through timer, used for WRITE_THROUGH db_mode. Process all
 * contacts from the record, delete all expired ones from the DB.
 * \param _r processed record
 * \note currently unused, this mode is also handled by the wb_timer
 */
static inline void wt_timer(urecord_t* _r)
{
	ucontact_t* ptr, *t;

	ptr = _r->contacts;

	while(ptr) {
		if (!VALID_CONTACT(ptr, act_time)) {
			/* run callbacks for EXPIRE event */
			if (exists_ulcb_type(UL_CONTACT_EXPIRE)) {
				run_ul_callbacks( UL_CONTACT_EXPIRE, ptr);
			}

			LM_DBG("Binding '%.*s','%.*s' has expired\n",
				ptr->aor->len, ZSW(ptr->aor->s),
				ptr->c.len, ZSW(ptr->c.s));

			if (close_expired_tcp && is_valid_tcpconn(ptr)) {
				close_connection(ptr->tcpconn_id);
			}

			t = ptr;
			ptr = ptr->next;

			if (db_delete_ucontact(t) < 0) {
				LM_ERR("deleting contact from database failed\n");
			}
			mem_delete_ucontact(_r, t);
			update_stat( _r->slot->d->expires, 1);
		} else {
			ptr = ptr->next;
		}
	}
}

/*!
 * \brief Write-back timer, used for WRITE_BACK db_mode
 *
 * Write-back timer, used for WRITE_BACK db_mode. Process
 * all contacts from the record, delete expired ones from the DB.
 * Furthermore it updates changed contacts, and also insert new
 * ones in the DB.
 * \param _r processed record
 */
static inline void wb_timer(urecord_t* _r)
{
	ucontact_t* ptr, *t;
	cstate_t old_state;
	int op;
	int res;

	ptr = _r->contacts;

	while(ptr) {
		if (handle_lost_tcp && is_valid_tcpconn(ptr) && !is_tcp_alive(ptr)) {
			LM_DBG("tcp connection has been lost, expiring contact %.*s\n", ptr->c.len, ptr->c.s);
			ptr->expires = UL_EXPIRED_TIME;
		}

		if (!VALID_CONTACT(ptr, act_time)) {
			/* run callbacks for EXPIRE event */
			if (exists_ulcb_type(UL_CONTACT_EXPIRE)) {
				run_ul_callbacks( UL_CONTACT_EXPIRE, ptr);
			}

			LM_DBG("Binding '%.*s','%.*s' has expired\n",
				ptr->aor->len, ZSW(ptr->aor->s),
				ptr->c.len, ZSW(ptr->c.s));
			update_stat( _r->slot->d->expires, 1);

			if (close_expired_tcp && is_valid_tcpconn(ptr)) {
				close_connection(ptr->tcpconn_id);
			}

			t = ptr;
			ptr = ptr->next;

			/* Should we remove the contact from the database ? */
			if (st_expired_ucontact(t) == 1) {
				if (db_delete_ucontact(t) < 0) {
					LM_ERR("failed to delete contact from the database"
							" (aor: %.*s)\n",
							t->aor->len, ZSW(t->aor->s));
				}
			}

			mem_delete_ucontact(_r, t);
		} else {
			/* Determine the operation we have to do */
			old_state = ptr->state;
			op = st_flush_ucontact(ptr);

			switch(op) {
			case 0: /* do nothing, contact is synchronized */
				break;

			case 1: /* insert */
				if (db_insert_ucontact(ptr) < 0) {
					LM_ERR("inserting contact into database failed"
							" (aor: %.*s)\n",
							ptr->aor->len, ZSW(ptr->aor->s));
					ptr->state = old_state;
				}
				break;

			case 2: /* update */
				if (ul_db_update_as_insert)
					res = db_insert_ucontact(ptr);
				else
					res = db_update_ucontact(ptr);
				if (res < 0) {
					LM_ERR("updating contact in db failed (aor: %.*s)\n",
							ptr->aor->len, ZSW(ptr->aor->s));
					ptr->state = old_state;
				}
				break;
			}

			ptr = ptr->next;
		}
	}
}


/*!
 * \brief Run timer functions depending on the db_mode setting.
 *
 * Helper function that run the appropriate timer function, depending
 * on the db_mode setting.
 * \param _r processed record
 */
void timer_urecord(urecord_t* _r)
{
	switch(db_mode) {
	case DB_READONLY:
	case NO_DB:         nodb_timer(_r);
						break;
	/* use also the write_back timer routine to handle the failed
	 * realtime inserts/updates */
	case WRITE_THROUGH: wb_timer(_r); /*wt_timer(_r);*/
						break;
	case WRITE_BACK:    wb_timer(_r);
						break;
	}
}


/*!
 * \brief Delete a record from the database
 * \param _r deleted record
 * \return 0 on success, -1 on failure
 */
int db_delete_urecord(urecord_t* _r)
{
	db_key_t keys[2];
	db_val_t vals[2];
	char* dom;

	keys[0] = &user_col;
	keys[1] = &domain_col;
	vals[0].type = DB1_STR;
	vals[0].nul = 0;
	vals[0].val.str_val.s = _r->aor.s;
	vals[0].val.str_val.len = _r->aor.len;

	if (use_domain) {
		dom = memchr(_r->aor.s, '@', _r->aor.len);
		vals[0].val.str_val.len = dom - _r->aor.s;

		vals[1].type = DB1_STR;
		vals[1].nul = 0;
		vals[1].val.str_val.s = dom + 1;
		vals[1].val.str_val.len = _r->aor.s + _r->aor.len - dom - 1;
	}

	if (ul_dbf.use_table(ul_dbh, _r->domain) < 0) {
		LM_ERR("use_table failed\n");
		return -1;
	}

	if (ul_dbf.delete(ul_dbh, keys, 0, vals, (use_domain) ? (2) : (1)) < 0) {
		LM_ERR("failed to delete from database\n");
		return -1;
	}

	return 0;
}


/*!
 * \brief Delete a record from the database based on ruid
 * \return 0 on success, -1 on failure
 */
int db_delete_urecord_by_ruid(str *_table, str *_ruid)
{
	db_key_t keys[1];
	db_val_t vals[1];

	keys[0] = &ruid_col;
	vals[0].type = DB1_STR;
	vals[0].nul = 0;
	vals[0].val.str_val.s = _ruid->s;
	vals[0].val.str_val.len = _ruid->len;

	if (ul_dbf.use_table(ul_dbh, _table) < 0) {
		LM_ERR("use_table failed\n");
		return -1;
	}

	if (ul_dbf.delete(ul_dbh, keys, 0, vals, 1) < 0) {
		LM_ERR("failed to delete from database\n");
		return -1;
	}

	if (ul_dbf.affected_rows(ul_dbh) == 0) {
	        return -2;
	}

	return 0;
}


/*!
 * \brief Release urecord previously obtained through get_urecord
 * \warning Failing to calls this function after get_urecord will
 * result in a memory leak when the DB_ONLY mode is used. When
 * the records is later deleted, e.g. with delete_urecord, then
 * its not necessary, as this function already releases the record.
 * \param _r released record
 */
void release_urecord(urecord_t* _r)
{
	if (db_mode==DB_ONLY) {
		free_urecord(_r);
	} else if (_r->contacts == 0) {
		mem_delete_urecord(_r->slot->d, _r);
	}
}
#if 0
char* Evy_gatewayAddress(){
	 LM_DBG("gateway_address = %p ---%s \n",gateway_address,gateway_address);
	return gateway_address;
}
#endif

int Evy_jsonParseData(char *data) {

	static char gateway_address[256]={0};
	char* str_ip;
	
	struct json_object* json_body_data = NULL;
	struct json_object* json_ip = NULL;

	json_body_data = json_tokener_parse(data);
	
	if(unlikely(json_body_data == NULL)) {
	
		LM_ERR("Error:json_body_data = NULL\n");
		return -1;
	}
	if(json_object_object_get_ex(json_body_data,"ip",&json_ip)==0){
	
		LM_ERR("Error: ip is null\n");
		return -1;
	}
	str_ip = (char*)json_object_get_string(json_ip);
	LM_DBG("ip = %s\n",str_ip);

	LM_DBG(".......................\n");

	strcpy(gateway_address,str_ip);
	gateway_commonaddr.s = gateway_address;
	gateway_commonaddr.len = strlen(gateway_address);
	LM_DBG("gateway_commonaddr = %.*s\n",gateway_commonaddr.len,gateway_commonaddr.s);

	LM_DBG(".......................\n");

	json_object_put(json_body_data);
	return 0;
	
}

int  Evy_jsonParse(char *buf) {

	char* str_code;
	char* str_data;
	
	struct json_object* json_body = NULL;
	struct json_object* json_code = NULL;
	struct json_object* json_data = NULL;

	json_body = json_tokener_parse(buf);
	
	if(unlikely(json_body == NULL)) {
	
		LM_ERR("Error:json_body = NULL\n");
		return -1;
	}

	if(json_object_object_get_ex(json_body,"code",&json_code)==0) {
	
		LM_ERR("Error: code is null\n");
		return -1;
	}
	if(json_object_object_get_ex(json_body,"data",&json_data)==0) {
	
		LM_ERR("Error: data is null\n");
		return -1;
	}
	
	str_code = (char*)json_object_get_string(json_code);
	str_data = (char*)json_object_get_string(json_data);

	LM_DBG("code = %s\n",str_code);
	LM_DBG("data = %s\n",str_data);
	
	if(Evy_jsonParseData(str_data) == -1) {
			
		LM_ERR("json_parse_data is Error\n");
	}

	json_object_put(json_body);
	return 0;
}

size_t Evy_callbackGetHead(void *ptr, size_t size, size_t nmemb, void *userp){

	strcat(userp,ptr);

	LM_DBG("%s\n",(char*)userp);

    Evy_jsonParse((char*)userp);//解析从http获取到的JSON数据

	return size*nmemb;
}

char* Evy_join(char *s1,char *s2) {

	char *result = malloc(strlen(s1) + strlen(s2) + 1);
	if (result == NULL)
		exit(1);
	strcpy(result,s1);
	strcat(result,s2);
	return result;
}
int  Evy_getGwLocation(char* gwip) {

	char buffer[1000]={0};
	char* addr = "http://ip.taobao.com/service/getIpInfo2.php?ip=";
	char* ip = NULL;
	ip = gwip;

	/*拼接http*/
	char* addr_ip = Evy_join(addr,ip);
	
	CURLcode return_code;
	return_code = curl_global_init(CURL_GLOBAL_ALL);
	if(CURLE_OK != return_code){
	
		LM_ERR("init libcurl failed\n");
		return -1;
	}
	
	CURL *easy_handle = curl_easy_init();
	if(NULL==easy_handle){
		LM_ERR("get a easy handle failed\n");
		return -1;
	}
    
	//curl_easy_setopt(easy_handle,CURLOPT_URL,"http://ip.taobao.com/service/getIpInfo2.php?ip=122.5.51.134");
	curl_easy_setopt(easy_handle,CURLOPT_URL,addr_ip);
	curl_easy_setopt(easy_handle,CURLOPT_WRITEFUNCTION,Evy_callbackGetHead);
	curl_easy_setopt(easy_handle,CURLOPT_WRITEDATA,buffer);
	//curl_easy_setopt(curl,CURLOPT_HTTPGET,"?test=string");
	
	curl_easy_perform(easy_handle);

	free(addr_ip);
	addr_ip = NULL;
	curl_easy_cleanup(easy_handle);
	curl_global_cleanup();

	return 0;
}
/*!获取网关的IP
 * 正确返回0，错误返回-1
 */

int  Evy_getGwIp(char*  _gwip){

	char  gatewayIp[256];

	/*获取网关的contact中的ip*/
	sscanf(_gwip,"%*[^@]@%[^:]",gatewayIp);
	LM_DBG("gatewayIp = %s\n",gatewayIp);
	
	if(Evy_getGwLocation(gatewayIp) == -1) {

		LM_ERR("Error:Evy_getGwLocation is wrong\n");
		return -1;
	}
	return 0;

}

/*!
 * \brief Create and insert new contact into urecord
 * \param _r record into the new contact should be inserted
 * \param _contact contact string
 * \param _ci contact information
 * \param _c new created contact
 * \return 0 on success, -1 on failure
 */
int insert_ucontact(urecord_t* _r, str* _contact, ucontact_info_t* _ci,
															ucontact_t** _c)
{
	str*  buf1;
	buf1 = _ci->c;
	LM_DBG("insert_ucontact starting...........\n");
	//LM_DBG("ruid = %.*s\n",_ci->ruid.len,_ci->ruid.s);
	//LM_DBG("user_agent = %.*s\n",_ci->user_agent->len,_ci->user_agent->s);
	//LM_DBG("server_id = %d\n",_ci->server_id);
	LM_DBG("c = %.*s\n",_ci->c->len,_ci->c->s);
	LM_DBG("buf1 = %s\n",buf1->s);
   
	if(Evy_getGwIp(buf1->s) == -1) {
		LM_ERR("Error:Evy_getGwIp is wrong\n");
		return -1;
	}
	LM_DBG("******gateway_commonaddr****** = %.*s\n",gateway_commonaddr.len,gateway_commonaddr.s);

	if ( ((*_c)=mem_insert_ucontact(_r, _contact, _ci)) == 0) {
		LM_ERR("failed to insert contact\n");
		return -1;
	}

	if (db_mode==DB_ONLY) {
		if (db_insert_ucontact(*_c) < 0) {
			LM_ERR("failed to insert in database\n");
			return -1;
		} else {
			(*_c)->state = CS_SYNC;
		}
	}

	if (exists_ulcb_type(UL_CONTACT_INSERT)) {
		run_ul_callbacks( UL_CONTACT_INSERT, *_c);
	}

	if (db_mode == WRITE_THROUGH) {
		if (db_insert_ucontact(*_c) < 0) {
			LM_ERR("failed to insert in database\n");
			return -1;
		} else {
			(*_c)->state = CS_SYNC;
		}
	}

	return 0;
}


/*!
 * \brief Delete ucontact from urecord
 * \param _r record where the contact belongs to
 * \param _c deleted contact
 * \return 0 on success, -1 on failure
 */
int delete_ucontact(urecord_t* _r, struct ucontact* _c)
{
	int ret = 0;

	if (exists_ulcb_type(UL_CONTACT_DELETE)) {
		run_ul_callbacks( UL_CONTACT_DELETE, _c);
	}

	if (st_delete_ucontact(_c) > 0) {
		if (db_mode == WRITE_THROUGH || db_mode==DB_ONLY) {
			if (db_delete_ucontact(_c) < 0) {
				LM_ERR("failed to remove contact from database\n");
				ret = -1;
			}
		}

		mem_delete_ucontact(_r, _c);
	}

	return ret;
}


int delete_urecord_by_ruid(udomain_t* _d, str *_ruid)
{
    if (db_mode != DB_ONLY) {
	LM_ERR("delete_urecord_by_ruid currently available only in db_mode=3\n");
	return -1;
    }

    return db_delete_urecord_by_ruid(_d->name, _ruid);
}


/*!
 * \brief Match a contact record to a contact string
 * \param ptr contact record
 * \param _c contact string
 * \return ptr on successfull match, 0 when they not match
 */
static inline struct ucontact* contact_match( ucontact_t* ptr, str* _c)
{
	while(ptr) {
		if ((_c->len == ptr->c.len) && !memcmp(_c->s, ptr->c.s, _c->len)) {
			return ptr;
		}
		
		ptr = ptr->next;
	}
	return 0;
}


/*!
 * \brief Match a contact record to a contact string and callid
 * \param ptr contact record
 * \param _c contact string
 * \param _callid callid
 * \return ptr on successfull match, 0 when they not match
 */
static inline struct ucontact* contact_callid_match( ucontact_t* ptr,
														str* _c, str *_callid)
{
	while(ptr) {
		if ( (_c->len==ptr->c.len) && (_callid->len==ptr->callid.len)
		&& !memcmp(_c->s, ptr->c.s, _c->len)
		&& !memcmp(_callid->s, ptr->callid.s, _callid->len)
		) {
			return ptr;
		}
		
		ptr = ptr->next;
	}
	return 0;
}

 /*!
+ * \brief Match a contact record to a contact string and path
+ * \param ptr contact record
+ * \param _c contact string
+ * \param _path path
+ * \return ptr on successfull match, 0 when they not match
+ */
static inline struct ucontact* contact_path_match( ucontact_t* ptr, str* _c, str *_path)
{
	/* if no path is preset (in REGISTER request) or use_path is not configured
	   in registrar module, default to contact_match() */
	if( _path == NULL) return contact_match(ptr, _c);

	while(ptr) {
		if ( (_c->len==ptr->c.len) && (_path->len==ptr->path.len)
		&& !memcmp(_c->s, ptr->c.s, _c->len)
		&& !memcmp(_path->s, ptr->path.s, _path->len)
		) {
			return ptr;
		}

		ptr = ptr->next;
	}
	return 0;
}

/*!
 * \brief Get pointer to ucontact with given contact
 * \param _r record where to search the contacts
 * \param _c contact string
 * \param _callid callid
 * \param _path path
 * \param _cseq CSEQ number
 * \param _co found contact
 * \return 0 - found, 1 - not found, -1 - invalid found, 
 * -2 - found, but to be skipped (same cseq)
 */
int get_ucontact(urecord_t* _r, str* _c, str* _callid, str* _path, int _cseq,
														struct ucontact** _co)
{
	ucontact_t* ptr;
	int no_callid;

	ptr = 0;
	no_callid = 0;
	*_co = 0;

	switch (matching_mode) {
		case CONTACT_ONLY:
			ptr = contact_match( _r->contacts, _c);
			break;
		case CONTACT_CALLID:
			ptr = contact_callid_match( _r->contacts, _c, _callid);
			no_callid = 1;
			break;
		case CONTACT_PATH:
			ptr = contact_path_match( _r->contacts, _c, _path);
			break;
		default:
			LM_CRIT("unknown matching_mode %d\n", matching_mode);
			return -1;
	}

	if (ptr) {
		/* found -> check callid and cseq */
		if ( no_callid || (ptr->callid.len==_callid->len
		&& memcmp(_callid->s, ptr->callid.s, _callid->len)==0 ) ) {
			if (_cseq<ptr->cseq)
				return -1;
			if (_cseq==ptr->cseq) {
				get_act_time();
				return (ptr->last_modified+cseq_delay>act_time)?-2:-1;
			}
		}
		*_co = ptr;
		return 0;
	}

	return 1;
}

/*
 * Get pointer to ucontact with given info (by address or sip.instance)
 */
int get_ucontact_by_instance(urecord_t* _r, str* _c, ucontact_info_t* _ci,
		ucontact_t** _co)
{
	ucontact_t* ptr;
	str i1;
	str i2;
	
	if (_ci->instance.s == NULL || _ci->instance.len <= 0) {
		return get_ucontact(_r, _c, _ci->callid, _ci->path, _ci->cseq, _co);
	}

	/* find by instance */
	ptr = _r->contacts;
	while(ptr) {
		if (ptr->instance.len>0 && _ci->reg_id==ptr->reg_id)
		{
			i1 = _ci->instance;
			i2 = ptr->instance;
			if(i1.s[0]=='<' && i1.s[i1.len-1]=='>') {
				i1.s++;
				i1.len-=2;
			}
			if(i2.s[0]=='<' && i2.s[i2.len-1]=='>') {
				i2.s++;
				i2.len-=2;
			}
			if(i1.len==i2.len && memcmp(i1.s, i2.s, i2.len)==0) {
				*_co = ptr;
				return 0;
			}
		}
		
		ptr = ptr->next;
	}
	return 1;
}

unsigned int ul_get_aorhash(str *_aor)
{
	return core_hash(_aor, 0, 0);
}
