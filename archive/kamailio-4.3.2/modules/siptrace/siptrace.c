/* 
 * siptrace module - helper module to trace sip messages
 *
 * Copyright (C) 2006 Voice Sistem S.R.L.
 * Copyright (C) 2009 Daniel-Constantin Mierla (asipto.com)
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
 * siptrace module - helper module to trace sip messages
 *
 */


#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <json-c/json.h>
#include <uuid/uuid.h>
#include "../../sr_module.h"
#include "../../dprint.h"
#include "../../ut.h"
#include "../../ip_addr.h"
#include "../../mem/mem.h"
#include "../../mem/shm_mem.h"
#include "../../lib/kmi/mi.h"
#include "../../rpc.h"
#include "../../rpc_lookup.h"
#include "../../lib/srdb1/db.h"
#include "../../parser/parse_content.h"
#include "../../parser/parse_from.h"
#include "../../parser/parse_cseq.h"
#include "../../pvar.h"
#include "../../modules/tm/tm_load.h"
#include "../../modules/sl/sl.h"
#include "../../str.h"
#include "../../onsend.h"
#include  "../../lib/cds/dstring.h"
#include  "../../lib/cds/memory.h"
#include  "../../lib/cds/sstr.h"
#include <curl/curl.h>
#include "../../modules/sipcapture/hep.h"

#ifdef STATISTICS
#include "../../lib/kcore/statistics.h"
#endif

#define MAX_NUM  1024
#define NR_KEYS 12
#define SIP_TRACE_TABLE_VERSION 4
#define XHEADERS_BUFSIZE 512
#define DEVICE_LOG_KEYS 		9
#define DEVICE_MANAGE_KEYS      7
//static char gw_version_buf[MAX_NUM];

static char id_buf[MAX_NUM];
static char device_type_buf[MAX_NUM];
static char bind_id_buf[MAX_NUM];

MODULE_VERSION

struct _siptrace_data {
	struct usr_avp *avp;
	int_str avp_value;
	struct search_state state;
	str body;
	str callid;
	str method;
	str status;
	char *dir;
	str fromtag;
	str fromip;
	str totag;
	str toip;
	char toip_buff[IP_ADDR_MAX_STR_SIZE+12];
	char fromip_buff[IP_ADDR_MAX_STR_SIZE+12];
	struct timeval tv;
#ifdef STATISTICS
	stat_var *stat;
#endif
};

struct tm_binds tmb;

/** SL API structure */
sl_api_t slb;

/* module function prototypes */
static int mod_init(void);
static int siptrace_init_rpc(void);
static int child_init(int rank);
static void destroy(void);
static int sip_trace(struct sip_msg*, struct dest_info*, char*);
static int Evy_webCtrl(struct sip_msg*, char*, char*);
//static int Evy_Gw_Ip(char *str);
static int fixup_siptrace(void ** param, int param_no);

static int sip_trace_store_db(struct _siptrace_data* sto);
static int trace_send_duplicate(char *buf, int len, struct dest_info*);

static void trace_onreq_in(struct cell* t, int type, struct tmcb_params *ps);
static void trace_onreq_out(struct cell* t, int type, struct tmcb_params *ps);
static void trace_onreply_in(struct cell* t, int type, struct tmcb_params *ps);
static void trace_onreply_out(struct cell* t, int type, struct tmcb_params *ps);
static void trace_sl_onreply_out(sl_cbp_t *slcb);
static void trace_sl_ack_in(sl_cbp_t *slcb);

static int trace_send_hep_duplicate(str *body, str *from, str *to, struct dest_info*);
static int pipport2su (char *pipport, union sockaddr_union *tmp_su, unsigned int *proto);

static struct mi_root* sip_trace_mi(struct mi_root* cmd, void* param );

static str db_url             = str_init(DEFAULT_DB_URL);
static str siptrace_table     = str_init("sip_trace");
static str date_column        = str_init("time_stamp");  /* 00 */
static str callid_column      = str_init("callid");      /* 01 */
static str traced_user_column = str_init("traced_user"); /* 02 */
static str msg_column         = str_init("msg");         /* 03 */
static str method_column      = str_init("method");      /* 04 */
static str status_column      = str_init("status");      /* 05 */
static str fromip_column      = str_init("fromip");      /* 06 */
static str toip_column        = str_init("toip");        /* 07 */
static str fromtag_column     = str_init("fromtag");     /* 08 */
static str direction_column   = str_init("direction");   /* 09 */
static str time_us_column     = str_init("time_us");     /* 10 */
static str totag_column       = str_init("totag");       /* 11 */



int trace_flag = 0;
int trace_on   = 0;
int trace_sl_acks = 1;

int trace_to_database = 1;
int trace_delayed = 0;

int hep_version = 1;
int hep_capture_id = 1;

int xheaders_write = 0;
int xheaders_read = 0;

str force_send_sock_str = {0, 0};
struct sip_uri * force_send_sock_uri = 0;

str    dup_uri_str      = {0, 0};
struct sip_uri *dup_uri = 0;

int *trace_on_flag = NULL;
int *trace_to_database_flag = NULL;

int *xheaders_write_flag = NULL;
int *xheaders_read_flag = NULL;

static unsigned short traced_user_avp_type = 0;
static int_str traced_user_avp;
static str traced_user_avp_str = {NULL, 0};

static unsigned short trace_table_avp_type = 0;
static int_str trace_table_avp;
static str trace_table_avp_str = {NULL, 0};

static str trace_local_ip = {NULL, 0};

int hep_mode_on = 0;

db1_con_t *db_con = NULL; 		/*!< database connection */
db_func_t db_funcs;      		/*!< Database functions */

/*! \brief
 * Exported functions
 */
static cmd_export_t cmds[] = {
	{"sip_trace", (cmd_function)sip_trace, 0, 0, 0, ANY_ROUTE},
    {"sip_trace", (cmd_function)sip_trace, 1, fixup_siptrace, 0, ANY_ROUTE},
	{"Evy_webCtrl", (cmd_function)Evy_webCtrl, 1, 0, 0, ANY_ROUTE},
	{0, 0, 0, 0, 0, 0}
};


/*! \brief
 * Exported parameters
 */
static param_export_t params[] = {
	{"db_url",             PARAM_STR, &db_url            },
	{"table",              PARAM_STR, &siptrace_table     },
	{"date_column",        PARAM_STR, &date_column        },
	{"callid_column",      PARAM_STR, &callid_column      },
	{"traced_user_column", PARAM_STR, &traced_user_column },
	{"msg_column",         PARAM_STR, &msg_column         },
	{"method_column",      PARAM_STR, &method_column      },
	{"status_column",      PARAM_STR, &status_column      },
	{"fromip_column",      PARAM_STR, &fromip_column      },
	{"toip_column",        PARAM_STR, &toip_column        },
	{"fromtag_column",     PARAM_STR, &fromtag_column     },
	{"totag_column",       PARAM_STR, &totag_column       },
	{"direction_column",   PARAM_STR, &direction_column   },
	{"trace_flag",         INT_PARAM, &trace_flag           },
	{"trace_on",           INT_PARAM, &trace_on             },
	{"traced_user_avp",    PARAM_STR, &traced_user_avp_str},
	{"trace_table_avp",    PARAM_STR, &trace_table_avp_str},
	{"duplicate_uri",      PARAM_STR, &dup_uri_str        },
	{"trace_to_database",  INT_PARAM, &trace_to_database    },
	{"trace_local_ip",     PARAM_STR, &trace_local_ip     },
	{"trace_sl_acks",      INT_PARAM, &trace_sl_acks        },
	{"xheaders_write",     INT_PARAM, &xheaders_write       },
	{"xheaders_read",      INT_PARAM, &xheaders_read        },
	{"hep_mode_on",        INT_PARAM, &hep_mode_on          },	 
    {"force_send_sock",    PARAM_STR, &force_send_sock_str	},
	{"hep_version",        INT_PARAM, &hep_version          },
	{"hep_capture_id",     INT_PARAM, &hep_capture_id       },	        
	{"trace_delayed",      INT_PARAM, &trace_delayed        },
	{0, 0, 0}
};

/*! \brief
 * MI commands
 */
static mi_export_t mi_cmds[] = {
	{ "sip_trace", sip_trace_mi,   0,  0,  0 },
	{ 0, 0, 0, 0, 0}
};


#ifdef STATISTICS
stat_var* siptrace_req;
stat_var* siptrace_rpl;

stat_export_t siptrace_stats[] = {
	{"traced_requests" ,  0,  &siptrace_req  },
	{"traced_replies"  ,  0,  &siptrace_rpl  },
	{0,0,0}
};
#endif

/*! \brief module exports */
struct module_exports exports = {
	"siptrace", 
	DEFAULT_DLFLAGS, /*!< dlopen flags */
	cmds,       /*!< Exported functions */
	params,     /*!< Exported parameters */
#ifdef STATISTICS
	siptrace_stats,  /*!< exported statistics */
#else
	0,          /*!< exported statistics */
#endif
	mi_cmds,    /*!< exported MI functions */
	0,          /*!< exported pseudo-variables */
	0,          /*!< extra processes */
	mod_init,   /*!< module initialization function */
	0,          /*!< response function */
	destroy,    /*!< destroy function */
	child_init  /*!< child initialization function */
};

struct _device_log {
	char uuid[36];
	int log_id;
	str user_id;
	int nodeid;
	struct timeval event_time;
	int command_code;
	str gateway_id;
	str content;
	int device_type;
};

struct _device_manage {
	char uuid[36];
	str manufactureid;
	str firmwareversion;
	str productid;
	str producttype;
	str gateway_id;
	str devicetype;
	str bind_id;
	//int node_id;
	int device_id;
	struct timeval inclusiontime;
	struct timeval exclusiontime;
};

static str user_id_column   		 = str_init("user_id");
//static str sn_code_column 	     = str_init("sn_code");
//static str gw_version_column       = str_init("gw_version");

//static str device_log_table          = str_init("device_log");
static str device_manage_table       = str_init("device_manage");
//static str device_full_table         = str_init("device_full");
//static str bind_remark_table         = str_init("bind_remark");
//static str user_gateway_bind_table     = str_init("user_gateway_bind");
           

static str id_column                 = str_init("id");              /* 00 */
//static str log_id_column             = str_init("logid");           /* 01 */
//static str userid_column             = str_init("userid");          /* 02 */
//static str nodeid_column             = str_init("nodeid");           /* 03 */
static str device_id_column          = str_init("device_id"); 
//static str event_time_column         = str_init("eventtime");       /* 04 */
//static str cmd_code_column           = str_init("cmdcode");         /* 05 */
//static str gatewayid_column          = str_init("gatewayid");        /* 06 */
//static str content_column            = str_init("content");         /* 07 */

static str manufacture_id_column       = str_init("manufacturer_id");   	 /* 01 */
static str device_type_column          = str_init("device_type");            /* 02 */
static str product_id_column           = str_init("product_id");             /* 03 */
static str product_type_column         = str_init("product_type");           /* 04 */
//static str node_id_column              = str_init("node_id");          		 /* 05 */
static str gateway_id_column           = str_init("gateway_id");             /* 06 */
//static str generic_column              = str_init("productid");            /* 07 */
//static str specific_column             = str_init("producttype");          /* 08 */
//static str firmwareversion_column      = str_init("firmware_version");  	 /* 09 */
//static str inclusiontime_column        = str_init("inclusiontime");    	 /* 10 */
static str init_time_column            = str_init("init_time");              /* 11 */
//static str exclusiontime_column		   = str_init("exclusiontime");	 		 /* 12 */
//static str update_time_column          = str_init("update_time");            /* 13 */
static str bind_id_column              = str_init("bind_id");                /* 14 */
static str version_column              = str_init("version");                /* 15 */

/* Parse Saccording to FORMAT and store binary time information in TP.
   The return value is a pointer to the firstunparsed character in S.  */
extern char*strptime (__const char *__restrict __s,
           __const char *__restrict __fmt,struct tm *__tp)
     __THROW;

/*! \brief Initialize siptrace module */
static int mod_init(void)
{
	pv_spec_t avp_spec;
	sl_cbelem_t slcb;

#ifdef STATISTICS
	/* register statistics */
	if (register_module_stats(exports.name, siptrace_stats)!=0)
	{
		LM_ERR("failed to register core statistics\n");
		return -1;
	}
#endif

	if(register_mi_mod(exports.name, mi_cmds)!=0)
	{
		LM_ERR("failed to register MI commands\n");
		return -1;
	}
	if(siptrace_init_rpc() != 0) 
	{
		LM_ERR("failed to register RPC commands\n");
		return -1;
	}

	if (trace_flag<0 || trace_flag>(int)MAX_FLAG)
	{
		LM_ERR("invalid trace flag %d\n", trace_flag);
		return -1;
	}
	trace_flag = 1<<trace_flag;

	trace_to_database_flag = (int*)shm_malloc(sizeof(int));
	if(trace_to_database_flag==NULL) {
		LM_ERR("no more shm memory left\n");
		return -1;
	}

	*trace_to_database_flag = trace_to_database;

	if(hep_version != 1 && hep_version != 2) {
	    LM_ERR("unsupported version of HEP");
	    return -1;
	}

	/* Find a database module if needed */
	if(trace_to_database_flag!=NULL && *trace_to_database_flag!=0) {
		if (db_bind_mod(&db_url, &db_funcs))
		{
			LM_ERR("unable to bind database module\n");
			return -1;
		}
		if (trace_to_database_flag && !DB_CAPABILITY(db_funcs, DB_CAP_QUERY))
		{
			LM_ERR("database modules does not provide all functions needed"
					" by module\n");
			return -1;
		}
	}

        if(hep_version != 1 && hep_version != 2) {
  
                  LM_ERR("unsupported version of HEP");
                  return -1;
        }                                          


	trace_on_flag = (int*)shm_malloc(sizeof(int));
	if(trace_on_flag==NULL) {
		LM_ERR("no more shm memory left\n");
		return -1;
	}

	*trace_on_flag = trace_on;

	xheaders_write_flag = (int*)shm_malloc(sizeof(int));
	xheaders_read_flag = (int*)shm_malloc(sizeof(int));
	if (!(xheaders_write_flag && xheaders_read_flag)) {
		LM_ERR("no more shm memory left\n");
		return -1;
	}
	*xheaders_write_flag = xheaders_write;
	*xheaders_read_flag = xheaders_read;

	/* register callbacks to TM */
	if (load_tm_api(&tmb)!=0) {
		LM_WARN("can't load tm api. Will not install tm callbacks.\n");
	} else if(tmb.register_tmcb( 0, 0, TMCB_REQUEST_IN, trace_onreq_in, 0, 0) <=0) {
		LM_ERR("can't register trace_onreq_in\n");
		return -1;
	}

	/* bind the SL API */
	if (sl_load_api(&slb)!=0) {
		LM_WARN("cannot bind to SL API. Will not install sl callbacks.\n");
	} else {
		/* register sl callbacks */
		memset(&slcb, 0, sizeof(sl_cbelem_t));

		slcb.type = SLCB_REPLY_READY;
		slcb.cbf  = trace_sl_onreply_out;
		if (slb.register_cb(&slcb) != 0) {
			LM_ERR("can't register for SLCB_REPLY_READY\n");
			return -1;
		}

		if(trace_sl_acks)
		{
			slcb.type = SLCB_ACK_FILTERED;
			slcb.cbf  = trace_sl_ack_in;
			if (slb.register_cb(&slcb) != 0) {
				LM_ERR("can't register for SLCB_ACK_FILTERED\n");
				return -1;
			}
		}
	}

	if(dup_uri_str.s!=0)
	{
		#if 0
		dup_uri = (struct sip_uri *)pkg_malloc(sizeof(struct sip_uri));
		if(dup_uri==0)
		{
			LM_ERR("no more pkg memory left\n");
			return -1;
		}
		memset(dup_uri, 0, sizeof(struct sip_uri));
		if(parse_uri(dup_uri_str.s, dup_uri_str.len, dup_uri)<0)
		{
			LM_ERR("bad dup uri\n");
			return -1;
		}
		#else
			dup_uri = NULL;
		#endif
	}

	if(force_send_sock_str.s!=0)
	{
	    force_send_sock_str.len = strlen(force_send_sock_str.s);
	    force_send_sock_uri = (struct sip_uri *)pkg_malloc(sizeof(struct sip_uri));
	    if(force_send_sock_uri==0)
	    {
	        LM_ERR("no more pkg memory left\n");
	        return -1;
	    }
	    memset(force_send_sock_uri, 0, sizeof(struct sip_uri));
	    if(parse_uri(force_send_sock_str.s, force_send_sock_str.len, force_send_sock_uri)<0)
	    {
	        LM_ERR("bad dup uri\n");
	        return -1;
	    }
	}

	if(traced_user_avp_str.s && traced_user_avp_str.len > 0)
	{
		if (pv_parse_spec(&traced_user_avp_str, &avp_spec)==0
				|| avp_spec.type!=PVT_AVP)
		{
			LM_ERR("malformed or non AVP %.*s AVP definition\n",
					traced_user_avp_str.len, traced_user_avp_str.s);
			return -1;
		}

		if(pv_get_avp_name(0, &avp_spec.pvp, &traced_user_avp,
					&traced_user_avp_type)!=0)
		{
			LM_ERR("[%.*s] - invalid AVP definition\n",
					traced_user_avp_str.len, traced_user_avp_str.s);
			return -1;
		}
	} else {
		traced_user_avp.n = 0;
		traced_user_avp_type = 0;
	}
	if(trace_table_avp_str.s && trace_table_avp_str.len > 0)
	{
		if (pv_parse_spec(&trace_table_avp_str, &avp_spec)==0
				|| avp_spec.type!=PVT_AVP)
		{
			LM_ERR("malformed or non AVP %.*s AVP definition\n",
					trace_table_avp_str.len, trace_table_avp_str.s);
			return -1;
		}

		if(pv_get_avp_name(0, &avp_spec.pvp, &trace_table_avp,
					&trace_table_avp_type)!=0)
		{
			LM_ERR("[%.*s] - invalid AVP definition\n",
					trace_table_avp_str.len, trace_table_avp_str.s);
			return -1;
		}
	} else {
		trace_table_avp.n = 0;
		trace_table_avp_type = 0;
	}

	return 0;
}


static int child_init(int rank)
{
	if (rank==PROC_INIT || rank==PROC_MAIN || rank==PROC_TCP_MAIN)
		return 0; /* do nothing for the main process */

	if(trace_to_database_flag!=NULL && *trace_to_database_flag!=0) {
		db_con = db_funcs.init(&db_url);
		if (!db_con)
		{
			LM_ERR("unable to connect to database. Please check configuration.\n");
			return -1;
		}
	}

	return 0;
}


static void destroy(void)
{
	if(trace_to_database_flag!=NULL && *trace_to_database_flag!=0) {
		if (db_con!=NULL)
			db_funcs.close(db_con);
	}

	if (trace_on_flag)
		shm_free(trace_on_flag);

}

static inline int siptrace_copy_proto(int proto, char *buf)
{
	if(buf==0)
		return -1;
	if(proto==PROTO_TCP) {
		strcpy(buf, "tcp:");
	} else if(proto==PROTO_TLS) {
		strcpy(buf, "tls:");
	} else if(proto==PROTO_SCTP) {
		strcpy(buf, "sctp:");
	} else if(proto==PROTO_WS) {
		strcpy(buf, "ws:");
	} else if(proto==PROTO_WSS) {
		strcpy(buf, "wss:");
	} else {
		strcpy(buf, "udp:");
	}
	return 0;
}

static inline str* siptrace_get_table(void)
{
	static int_str         avp_value;
	struct usr_avp *avp;

	if(trace_table_avp.n==0)
		return &siptrace_table;

	avp = NULL;
	if(trace_table_avp.n!=0)
		avp=search_first_avp(trace_table_avp_type, trace_table_avp, &avp_value,
				0);

	if(avp==NULL || !is_avp_str_val(avp) || avp_value.s.len<=0)
		return &siptrace_table;

	return &avp_value.s;
}

static int sip_trace_prepare(sip_msg_t *msg)
{
	if(parse_from_header(msg)==-1 || msg->from==NULL || get_from(msg)==NULL) {
		LM_ERR("cannot parse FROM header\n");
		goto error;
	}

	if(parse_to_header(msg)==-1 || msg->to==NULL || get_to(msg)==NULL) {
		LM_ERR("cannot parse To header\n");
		goto error;
	}

	if(parse_headers(msg, HDR_CALLID_F, 0)!=0 || msg->callid==NULL
			|| msg->callid->body.s==NULL) {
		LM_ERR("cannot parse call-id\n");
		goto error;
	}

	if(msg->cseq==NULL && ((parse_headers(msg, HDR_CSEQ_F, 0)==-1)
				|| (msg->cseq==NULL)))
	{
		LM_ERR("cannot parse cseq\n");
		goto error;
	}

	return 0;
error:
	return -1;
}

// Appends x-headers to the message in sto->body containing data from sto
static int sip_trace_xheaders_write(struct _siptrace_data *sto)
{
	char* buf = NULL;
	int bytes_written = 0;
	char* eoh = NULL;
	int eoh_offset = 0;
	char* new_eoh = NULL;

	if(xheaders_write_flag==NULL || *xheaders_write_flag==0)
		return 0;

	// Memory for the message with some additional headers.
	// It gets free()ed in sip_trace_xheaders_free().
	buf = pkg_malloc(sto->body.len + XHEADERS_BUFSIZE);
	if (buf == NULL) {
		LM_ERR("sip_trace_xheaders_write: out of memory\n");
		return -1;
	}

	// Copy the whole message to buf first; it must be \0-terminated for
	// strstr() to work. Then search for the end-of-header sequence.
	memcpy(buf, sto->body.s, sto->body.len);
	buf[sto->body.len] = '\0';
	eoh = strstr(buf, "\r\n\r\n");
	if (eoh == NULL) {
		LM_ERR("sip_trace_xheaders_write: malformed message\n");
		goto error;
	}
	eoh += 2; // the first \r\n belongs to the last header => skip it

	// Write the new headers a the end-of-header position. This overwrites
	// the \r\n terminating the old headers and the beginning of the message
	// body. Both will be recovered later.
	bytes_written = snprintf(eoh, XHEADERS_BUFSIZE,
			"X-Siptrace-Fromip: %.*s\r\n"
			"X-Siptrace-Toip: %.*s\r\n"
			"X-Siptrace-Time: %llu %llu\r\n"
			"X-Siptrace-Method: %.*s\r\n"
			"X-Siptrace-Dir: %s\r\n",
			sto->fromip.len, sto->fromip.s,
			sto->toip.len, sto->toip.s,
			(unsigned long long)sto->tv.tv_sec, (unsigned long long)sto->tv.tv_usec,
			sto->method.len, sto->method.s,
			sto->dir);
	if (bytes_written >= XHEADERS_BUFSIZE) {
		LM_ERR("sip_trace_xheaders_write: string too long\n");
		goto error;
	}

	// Copy the \r\n terminating the old headers and the message body from the
	// old buffer in sto->body.s to the new end-of-header in buf.
	eoh_offset = eoh - buf;
	new_eoh = eoh + bytes_written;
	memcpy(new_eoh, sto->body.s + eoh_offset, sto->body.len - eoh_offset);

	// Change sto to point to the new buffer.
	sto->body.s = buf;
	sto->body.len += bytes_written;
	return 0;
error:
	if(buf != NULL)
		pkg_free(buf);
	return -1;
}

// Parses x-headers, saves the data back to sto, and removes the x-headers
// from the message in sto->buf
static int sip_trace_xheaders_read(struct _siptrace_data *sto)
{
	char* searchend = NULL;
	char* eoh = NULL;
	char* xheaders = NULL;
	long long unsigned int tv_sec, tv_usec;

	if(xheaders_read_flag==NULL || *xheaders_read_flag==0)
		return 0;

	// Find the end-of-header marker \r\n\r\n
	searchend = sto->body.s + sto->body.len - 3;
	eoh = memchr(sto->body.s, '\r', searchend - eoh);
	while (eoh != NULL && eoh < searchend) {
		if (memcmp(eoh, "\r\n\r\n", 4) == 0)
			break;
		eoh = memchr(eoh + 1, '\r', searchend - eoh);
	}
	if (eoh == NULL) {
		LM_ERR("sip_trace_xheaders_read: malformed message\n");
		return -1;
	}

	// Find x-headers: eoh will be overwritten by \0 to allow the use of
	// strstr(). The byte at eoh will later be recovered, when the
	// message body is shifted towards the beginning of the message
	// to remove the x-headers.
	*eoh = '\0';
	xheaders = strstr(sto->body.s, "\r\nX-Siptrace-Fromip: ");
	if (xheaders == NULL) {
		LM_ERR("sip_trace_xheaders_read: message without x-headers "
				"from %.*s, callid %.*s\n",
				sto->fromip.len, sto->fromip.s, sto->callid.len, sto->callid.s);
		return -1;
	}

	// Allocate memory for new strings in sto
	// (gets free()ed in sip_trace_xheaders_free() )
	sto->fromip.s = pkg_malloc(51);
	sto->toip.s = pkg_malloc(51);
	sto->method.s = pkg_malloc(51);
	sto->dir = pkg_malloc(4);
	if (!(sto->fromip.s && sto->toip.s && sto->method.s && sto->dir)) {
		LM_ERR("sip_trace_xheaders_read: out of memory\n");
		goto erroraftermalloc;
	}

	// Parse the x-headers: scanf()
	if (sscanf(xheaders, "\r\n"
				"X-Siptrace-Fromip: %50s\r\n"
				"X-Siptrace-Toip: %50s\r\n"
				"X-Siptrace-Time: %llu %llu\r\n"
				"X-Siptrace-Method: %50s\r\n"
				"X-Siptrace-Dir: %3s",
				sto->fromip.s, sto->toip.s,
				&tv_sec, &tv_usec,
				sto->method.s,
				sto->dir) == EOF) {
		LM_ERR("sip_trace_xheaders_read: malformed x-headers\n");
		goto erroraftermalloc;
	}
	sto->fromip.len = strlen(sto->fromip.s);
	sto->toip.len = strlen(sto->toip.s);
	sto->tv.tv_sec = (time_t)tv_sec;
	sto->tv.tv_usec = (suseconds_t)tv_usec;
	sto->method.len = strlen(sto->method.s);

	// Remove the x-headers: the message body is shifted towards the beginning
	// of the message, overwriting the x-headers. Before that, the byte at eoh
	// is recovered.
	*eoh = '\r';
	memmove(xheaders, eoh, sto->body.len - (eoh - sto->body.s));
	sto->body.len -= eoh - xheaders;

	return 0;

erroraftermalloc:
	if (sto->fromip.s)
		pkg_free(sto->fromip.s);
	if (sto->toip.s)
		pkg_free(sto->toip.s);
	if (sto->method.s)
		pkg_free(sto->method.s);
	if (sto->dir)
		pkg_free(sto->dir);
	return -1;
}

// Frees the memory allocated by sip_trace_xheaders_{write,read}
static int sip_trace_xheaders_free(struct _siptrace_data *sto)
{
	if (xheaders_write_flag != NULL && *xheaders_write_flag != 0) {
		if(sto->body.s)
			pkg_free(sto->body.s);
	}

	if (xheaders_read_flag != NULL && *xheaders_read_flag != 0) {
		if(sto->fromip.s)
			pkg_free(sto->fromip.s);
		if(sto->toip.s)
			pkg_free(sto->toip.s);
		if(sto->dir)
			pkg_free(sto->dir);
	}

	return 0;
}

static int sip_trace_store(struct _siptrace_data *sto, struct dest_info *dst)
{
	if(sto==NULL)
	{
		LM_DBG("invalid parameter\n");
		return -1;
	}

	gettimeofday(&sto->tv, NULL);

	if (sip_trace_xheaders_read(sto) != 0)
		return -1;
	int ret = sip_trace_store_db(sto);

	if (sip_trace_xheaders_write(sto) != 0)
		return -1;

	if(hep_mode_on) trace_send_hep_duplicate(&sto->body, &sto->fromip, &sto->toip, dst);
    else trace_send_duplicate(sto->body.s, sto->body.len, dst);

	if (sip_trace_xheaders_free(sto) != 0)
		return -1;

	return ret;
}

static int sip_trace_store_db(struct _siptrace_data *sto)
{
	/*判断数据库连接是否成功*/
	if(db_con==NULL) {
		LM_DBG("database connection not initialized\n");
		return -1;
	}

	if(trace_to_database_flag==NULL || *trace_to_database_flag==0)
		goto done;

	db_key_t db_keys[NR_KEYS];
	db_val_t db_vals[NR_KEYS];

	db_keys[0] = &msg_column;
	db_vals[0].type = DB1_BLOB;
	db_vals[0].nul = 0;
	db_vals[0].val.blob_val = sto->body;

	db_keys[1] = &callid_column;
	db_vals[1].type = DB1_STR;
	db_vals[1].nul = 0;
	db_vals[1].val.str_val = sto->callid;

	db_keys[2] = &method_column;
	db_vals[2].type = DB1_STR;
	db_vals[2].nul = 0;
	db_vals[2].val.str_val = sto->method;

	db_keys[3] = &status_column;
	db_vals[3].type = DB1_STR;
	db_vals[3].nul = 0;
	db_vals[3].val.str_val = sto->status;

	db_keys[4] = &fromip_column;
	db_vals[4].type = DB1_STR;
	db_vals[4].nul = 0;
	db_vals[4].val.str_val = sto->fromip;

	db_keys[5] = &toip_column;
	db_vals[5].type = DB1_STR;
	db_vals[5].nul = 0;
	db_vals[5].val.str_val = sto->toip;

	db_keys[6] = &date_column;
	db_vals[6].type = DB1_DATETIME;
	db_vals[6].nul = 0;
	db_vals[6].val.time_val = sto->tv.tv_sec;

	db_keys[7] = &direction_column;
	db_vals[7].type = DB1_STRING;
	db_vals[7].nul = 0;
	db_vals[7].val.string_val = sto->dir;

	db_keys[8] = &fromtag_column;
	db_vals[8].type = DB1_STR;
	db_vals[8].nul = 0;
	db_vals[8].val.str_val = sto->fromtag;

	db_keys[9] = &traced_user_column;
	db_vals[9].type = DB1_STR;
	db_vals[9].nul = 0;

	db_keys[10] = &time_us_column;
	db_vals[10].type = DB1_INT;
	db_vals[10].nul = 0;
	db_vals[10].val.int_val = sto->tv.tv_usec;

	db_keys[11] = &totag_column;
	db_vals[11].type = DB1_STR;
	db_vals[11].nul = 0;
	db_vals[11].val.str_val = sto->totag;

	db_funcs.use_table(db_con, siptrace_get_table());

	if(trace_on_flag!=NULL && *trace_on_flag!=0) {
		db_vals[9].val.str_val.s   = "";
		db_vals[9].val.str_val.len = 0;

		LM_DBG("storing info...\n");
		if(trace_delayed!=0 && db_funcs.insert_delayed!=NULL)
		{
			if(db_funcs.insert_delayed(db_con, db_keys, db_vals, NR_KEYS)<0) {
				LM_ERR("error storing trace\n");
				goto error;
			}
		} else {
			if(db_funcs.insert(db_con, db_keys, db_vals, NR_KEYS) < 0) {
				LM_ERR("error storing trace\n");
				goto error;
			}
		}
#ifdef STATISTICS
		update_stat(sto->stat, 1);
#endif
	}

	if(sto->avp==NULL)
		goto done;

	db_vals[9].val.str_val = sto->avp_value.s;

	LM_DBG("storing info...\n");
	if(trace_delayed!=0 && db_funcs.insert_delayed!=NULL)
	{
		if(db_funcs.insert_delayed(db_con, db_keys, db_vals, NR_KEYS) < 0) {
			LM_ERR("error storing trace\n");
			goto error;
		}
	} else {
		if(db_funcs.insert(db_con, db_keys, db_vals, NR_KEYS) < 0) {
			LM_ERR("error storing trace\n");
			goto error;
		}
	}

	sto->avp = search_next_avp(&sto->state, &sto->avp_value);
	while(sto->avp!=NULL) {
		db_vals[9].val.str_val = sto->avp_value.s;

		LM_DBG("storing info...\n");
		if(trace_delayed!=0 && db_funcs.insert_delayed!=NULL)
		{
			if(db_funcs.insert_delayed(db_con, db_keys, db_vals, NR_KEYS) < 0) {
				LM_ERR("error storing trace\n");
				goto error;
			}
		} else {
			if(db_funcs.insert(db_con, db_keys, db_vals, NR_KEYS) < 0) {
				LM_ERR("error storing trace\n");
				goto error;
			}
		}
		sto->avp = search_next_avp(&sto->state, &sto->avp_value);
	}

done:
	return 1;
error:
	return -1;
}

static int fixup_siptrace(void** param, int param_no) {
	char *duri = (char*) *param;
	struct sip_uri dup_uri;
	struct dest_info *dst = NULL;
	struct proxy_l * p = NULL;
	str dup_uri_str = { 0, 0 };

	if (param_no != 1) {
		LM_DBG("params:%s\n", (char*)*param);
		return 0;
	}
	if (!(*duri)) {
		LM_ERR("invalid dup URI\n");
		return -1;
	}
	LM_DBG("sip_trace URI:%s\n", (char*)*param);

	dup_uri_str.s = duri;
	dup_uri_str.len = strlen(dup_uri_str.s);
	memset(&dup_uri, 0, sizeof(struct sip_uri));

	if (parse_uri(dup_uri_str.s, dup_uri_str.len, &dup_uri) < 0) {
		LM_ERR("bad dup uri\n");
		return -1;
	}

	dst = (struct dest_info *) pkg_malloc(sizeof(struct dest_info));
	if (dst == 0) {
		LM_ERR("no more pkg memory left\n");
		return -1;
	}
	init_dest_info(dst);
	/* create a temporary proxy*/
	dst->proto = PROTO_UDP;
	p = mk_proxy(&dup_uri.host, (dup_uri.port_no) ? dup_uri.port_no : SIP_PORT,
			dst->proto);
	if (p == 0) {
		LM_ERR("bad host name in uri\n");
		pkg_free(dst);
		return -1;
	}
	hostent2su(&dst->to, &p->host, p->addr_idx, (p->port) ? p->port : SIP_PORT);

	pkg_free(*param);
	/* free temporary proxy*/
	if (p) {
		free_proxy(p); /* frees only p content, not p itself */
		pkg_free(p);
	}

	*param = (void*) dst;
	return 0;
}


/*存储设备操作日志到数据库表device_log*/
#if 0 
static int Evy_device_log_store_db(struct _device_log* dev_log)
{
	LM_DBG("device_log_store_db  start ......\n");
	
	/*判断数据库连接是否成功*/
	if(unlikely(db_con==NULL)) {
		LM_DBG("database connection not initialized\n");
		return -1;
	}

	db_key_t db_keys_select[DEVICE_LOG_KEYS - 1]; /*DEVICE_LOG_KEYS-1：用于匹配的字段数*/
	db_val_t db_vals_select[DEVICE_LOG_KEYS - 1];
 	db1_res_t* db_res = NULL;

 	db_keys_select[0] = &log_id_column;
	db_vals_select[0].type = DB1_INT;
	db_vals_select[0].nul = 0;
	db_vals_select[0].val.int_val = dev_log->log_id;

	db_keys_select[1] = &userid_column;
	db_vals_select[1].type = DB1_STR;
	db_vals_select[1].nul = 0;
	db_vals_select[1].val.str_val = dev_log->user_id;

	db_keys_select[2] = &nodeid_column;
	db_vals_select[2].type = DB1_INT;
	db_vals_select[2].nul = 0;
	db_vals_select[2].val.int_val = dev_log->nodeid;

	db_keys_select[3] = &event_time_column;
	db_vals_select[3].type = DB1_DATETIME;
	db_vals_select[3].nul = 0;
	db_vals_select[3].val.time_val = dev_log->event_time.tv_sec;

	db_keys_select[4] = &cmd_code_column;
	db_vals_select[4].type = DB1_INT;
	db_vals_select[4].nul = 0;
	db_vals_select[4].val.int_val = dev_log->command_code;

	db_keys_select[5] = &gatewayid_column;
	db_vals_select[5].type = DB1_STR;
	db_vals_select[5].nul = 0;
	db_vals_select[5].val.str_val = dev_log->gateway_id;

	db_keys_select[6] = &content_column;
	db_vals_select[6].type = DB1_STR;
	db_vals_select[6].nul = 0;
	db_vals_select[6].val.str_val = dev_log->content;

	db_keys_select[7] = &device_type_column;
	db_vals_select[7].type = DB1_INT;
	db_vals_select[7].nul = 0;
	db_vals_select[7].val.int_val = dev_log->device_type;

	db_funcs.use_table(db_con, &device_log_table);     /*确定使用哪个数据库表*/

	if(db_funcs.query(db_con, db_keys_select, NULL, db_vals_select, NULL,
		DEVICE_LOG_KEYS-1/*8 keys*/, 0/*no cols*/, NULL, &db_res) != 0)
	{
		LM_ERR("failed to query database in device log!\n");
		return -1;
	}

	if(!(RES_ROW_N(db_res)<=0 || RES_ROWS(db_res)[0].values[0].nul != 0))
	{
		LM_ERR("data is existed in device_log\n");
		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("failed to freeing result of query in device_log\n");
		return 0;
	}

	db_key_t db_keys[DEVICE_LOG_KEYS];
	db_val_t db_vals[DEVICE_LOG_KEYS];   /*DEVICE_LOG_KEYS：要存储的字段个数*/

	db_keys[0] = &id_column;
	db_vals[0].type = DB1_STRING;
	db_vals[0].nul = 0;
	db_vals[0].val.string_val = dev_log->uuid;  /*id字段使用uuid*/

	int i = 1;
	for(; i<DEVICE_LOG_KEYS; i++) {
		db_keys[i] = db_keys_select[i-1];
		db_vals[i] = db_vals_select[i-1];
	}

	if(db_funcs.insert(db_con, db_keys, db_vals, DEVICE_LOG_KEYS) < 0) {
		LM_ERR("device_log: error storing trace\n");
		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("failed to freeing result of query in device_log\n");
		return -1;
	}
	if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
		LM_ERR("failed to freeing result of query in device_log\n");
	
	LM_DBG("device_log_store_db  end ......\n");
	
	return 0;
}
#endif

/*解析web发送过来的JSON格式数据*/
static int Evy_parse_webCtrl(struct json_object* json_data) {

	str webStr_userid;
	str webStr_gatewayid;
	str webStr_token;
	str webStr_cmd;

	struct json_object* webJson_userid = NULL;
	struct json_object* webJson_gatewayid = NULL;
	struct json_object* webJson_token = NULL;
	struct json_object* webJson_cmd = NULL;

	if (unlikely(json_object_object_get_ex(json_data, "userid", &webJson_userid)) == 0) {
	
		LM_ERR("Error:webJson_userid = NULL\n");
		return -1;
	}
	if (unlikely(json_object_object_get_ex(json_data, "gatewayid", &webJson_gatewayid)) == 0) {
	
		LM_ERR("Error:webJson_gatewayid = NULL\n");
		return -1;
	}
	if (unlikely(json_object_object_get_ex(json_data, "token", &webJson_token)) == 0) {
	
		LM_ERR("Error:webJson_token = NULL\n");
		return -1;
	}
	if (unlikely(json_object_object_get_ex(json_data, "cmd", &webJson_cmd)) == 0) {
	
		LM_ERR("Error:webJson_cmd = NULL\n");
		return -1;
	}
	
	webStr_userid.s = (char*)json_object_get_string(webJson_userid);
	webStr_userid.len = json_object_get_string_len(webJson_userid);

	webStr_gatewayid.s = (char*)json_object_get_string(webJson_gatewayid);
	webStr_gatewayid.len = json_object_get_string_len(webJson_gatewayid);
	
	webStr_token.s = (char*)json_object_get_string(webJson_token);
	webStr_token.len = json_object_get_string_len(webJson_token);
	
	webStr_cmd.s = (char*)json_object_get_string(webJson_cmd);
	webStr_cmd.len = json_object_get_string_len(webJson_cmd);

	LM_DBG("~~~~~~~~~~~~~~~~~~~~~~~~webStr_userid = %.*s\n",webStr_userid.len,webStr_userid.s);
	LM_DBG("~~~~~~~~~~~~~~~~~~~~~~~~webStr_gatewayid = %.*s\n",webStr_gatewayid.len,webStr_gatewayid.s);
	LM_DBG("~~~~~~~~~~~~~~~~~~~~~~~~webStr_token = %.*s\n",webStr_token.len,webStr_token.s);
	LM_DBG("~~~~~~~~~~~~~~~~~~~~~~~~webStr_cmd = %.*s\n",webStr_cmd.len,webStr_cmd.s);

	return 0;

}

/*获取web发送过来的数据，
 *格式为：{"type":"0",
           "cmd":"110011101111",
		   "userid":"sip:1234@192.168.1.19",
		   "token":"lisa",
		   "gatewayid":"sip:1000@192.168.1.19"}*/
static int Evy_webCtrl(struct sip_msg* msg, char* webJson, char*  param){

	char* webMsg = webJson;
	LM_DBG("*********webMsg = %s\n", webMsg);
	LM_DBG("66666666666____Evy_webCtrl____6666666666\n");
	
	int ret = -1;
	int webMessage_type = -1;
	struct json_object* json_type = NULL;
	struct json_object* json_body = NULL;

	json_body = json_tokener_parse(webJson);
	if (unlikely(json_body == NULL)) {
	
		LM_ERR("Error:json_body = NULL!!!\n");
		goto error;
	}
	if (unlikely(json_object_object_get_ex(json_body,"type",&json_type) == 0)) {
	
		LM_ERR("Error:not available message!!!\n");
		goto error;
	}
	webMessage_type = json_object_get_int(json_type);
	switch(webMessage_type) {
	
		case 0:
			ret = Evy_parse_webCtrl(json_body);
			break;
		case 1:
			LM_DBG("case = 1\n");
			break;
		default:
			LM_DBG("not available type!!!\n");
			break;
	}

	json_object_put(json_body);
	return 0;
error:
	json_object_put(json_body);
	return -1;
}

/*设备加网时存储设备信息到数据库表device_manage*/
#if 0
static int Evy_device_manage_add_device_store_db(struct _device_manage* dev_manage)
{

	LM_DBG("device_manage_add_device_store_db  start ......\n");
	/*判断数据库连接是否成功*/
	if(unlikely(db_con==NULL)) {
		LM_DBG("database connection not initialized\n");
		return -1;
	}

	db_key_t db_keys_select[3]; 
	db_val_t db_vals_select[3];
 	db1_res_t* db_res = NULL;

	db_keys_select[0] = &status_column;
	db_vals_select[0].type = DB1_INT;
	db_vals_select[0].nul = 0;
	db_vals_select[0].val.int_val = 1;

	db_keys_select[1] = &node_id_column;
	db_vals_select[1].type = DB1_INT;
	db_vals_select[1].nul = 0;
	db_vals_select[1].val.int_val = dev_manage->node_id;
	
	db_keys_select[2] = &gateway_id_column;
	db_vals_select[2].type = DB1_STR;
	db_vals_select[2].nul = 0;
	db_vals_select[2].val.str_val = dev_manage->gateway_id;

	db_funcs.use_table(db_con, &device_manage_table);

	if(db_funcs.query(db_con, db_keys_select, NULL, db_vals_select, NULL,
		3/*3 keys*/, 0/*no cols*/, NULL, &db_res) != 0)
	{
		LM_ERR("failed to query database in device_manage!\n");
		return -1;
	}

	if(!(RES_ROW_N(db_res)<=0 || RES_ROWS(db_res)[0].values[0].nul != 0))
	{
		LM_ERR("data is existed in device_manage\n");
		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("failed to freeing result of query in device_manage\n");
		return 0;
	}

	db_key_t db_keys[DEVICE_MANAGE_KEYS];
	db_val_t db_vals[DEVICE_MANAGE_KEYS];

	db_keys[0] = &id_column;
	db_vals[0].type = DB1_STRING;
	db_vals[0].nul = 0;
	db_vals[0].val.string_val = dev_manage->uuid;

	//db_keys[1] = &manufacture_id_column;
	//db_vals[1].type = DB1_STR;
	//db_vals[1].nul = 0;
	//db_vals[1].val.str_val = dev_manage->manufactureid;
	
	db_keys[1] = &device_type_column;
	db_vals[1].type = DB1_STR;
	db_vals[1].nul = 0;
	db_vals[1].val.str_val = dev_manage->devicetype;
	
	db_keys[2] = &product_id_column;
	db_vals[2].type = DB1_STR;
	db_vals[2].nul = 0;
	db_vals[2].val.str_val = dev_manage->productid;

	db_keys[3] = &product_type_column;
	db_vals[3].type = DB1_STR;
	db_vals[3].nul = 0;
	db_vals[3].val.str_val = dev_manage->producttype;
	
	db_keys[4] = &firmwareversion_column;
	db_vals[4].type = DB1_STR;
	db_vals[4].nul = 0;
	db_vals[4].val.str_val = dev_manage->firmwareversion;

	db_keys[5] = &inclusiontime_column;
	db_vals[5].type = DB1_DATETIME;
	db_vals[5].nul = 0;
	db_vals[5].val.time_val = dev_manage->inclusiontime.tv_sec;

	int i = 6;
	for(; i<DEVICE_MANAGE_KEYS; i++) {
		db_keys[i] = db_keys_select[i-6];
		db_vals[i] = db_vals_select[i-6];
	}

	if(db_funcs.insert(db_con, db_keys, db_vals, DEVICE_MANAGE_KEYS) < 0) {
		LM_ERR("error storing trace\n");
		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("failed to freeing result of query in device_manage\n");
		return -1;
	}
	if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
		LM_ERR("failed to freeing result of query in device_manage\n");
	
    LM_DBG("device_manage_add_device_store_db  end ......\n");
	
	return 0;
}
#endif


/*V2:设备加网时存储设备信息到数据库表device_manage*/
/***************************************************/
static int Evy_device_manage_add_device_store_db(struct _device_manage* dev_manage)
{
	LM_DBG("device_manage_add_device_store_db  start ......\n");
	/*判断数据库连接是否成功*/
	if(unlikely(db_con==NULL)) {
		LM_DBG("database connection not initialized\n");
		return -1;
	}

	db_key_t db_keys_select[2]; 
	db_val_t db_vals_select[2];
 	db1_res_t* db_res = NULL;

	db_keys_select[0] = &status_column;
	db_vals_select[0].type = DB1_INT;
	db_vals_select[0].nul = 0;
	db_vals_select[0].val.int_val = 1;

	db_keys_select[1] = &device_id_column;
	db_vals_select[1].type = DB1_INT;
	db_vals_select[1].nul = 0;
	db_vals_select[1].val.int_val = dev_manage->device_id;
	//db_vals_select[1].val.int_val = dev_manage->node_id;

	db_funcs.use_table(db_con, &device_manage_table);

	if(db_funcs.query(db_con, db_keys_select, NULL, db_vals_select, NULL,
		2/*2 keys*/, 0/*no cols*/, NULL, &db_res) != 0)
	{
		LM_ERR("failed to query database in device_manage!\n");
		return -1;
	}

	if(!(RES_ROW_N(db_res)<=0 || RES_ROWS(db_res)[0].values[0].nul != 0))
	{
		LM_ERR("data is existed in device_manage\n");
		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("failed to freeing result of query in device_manage\n");
		return 0;
	}

	db_key_t db_keys[DEVICE_MANAGE_KEYS];
	db_val_t db_vals[DEVICE_MANAGE_KEYS];

	db_keys[0] = &id_column;
	db_vals[0].type = DB1_STRING;
	db_vals[0].nul = 0;
	db_vals[0].val.string_val = dev_manage->uuid;
	
	db_keys[1] = &device_type_column;
	db_vals[1].type = DB1_STR;
	db_vals[1].nul = 0;
	db_vals[1].val.str_val = dev_manage->devicetype;
	
	db_keys[2] = &bind_id_column;
	db_vals[2].type = DB1_STR;
	db_vals[2].nul = 0;
	db_vals[2].val.str_val = dev_manage->bind_id;
	
	db_keys[3] = &version_column;
	db_vals[3].type = DB1_STR;
	db_vals[3].nul = 0;
	db_vals[3].val.str_val = dev_manage->firmwareversion;

	db_keys[4] = &init_time_column;
	db_vals[4].type = DB1_DATETIME;
	db_vals[4].nul = 0;
	db_vals[4].val.time_val = dev_manage->inclusiontime.tv_sec;

	int i = 5;
	for(; i<DEVICE_MANAGE_KEYS; i++) {
		db_keys[i] = db_keys_select[i-5];
		db_vals[i] = db_vals_select[i-5];
	}

	if(db_funcs.insert(db_con, db_keys, db_vals, DEVICE_MANAGE_KEYS) < 0) {
		LM_ERR("error storing trace\n");
		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("Evy_device_manage_add_device_store_db:failed to freeing result of query in device_manage\n");
		return -1;
	}
	if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
		LM_ERR("Evy_device_manage_add_device_store_db:failed to freeing result of query in device_manage\n");
	
    LM_DBG("device_manage_add_device_store_db  end ......\n");
	
	return 0;
}
/***************************************************/


/*设备退网时存储设备信息到数据库表device_manage*/
#if 0
static int Evy_device_manage_remove_device_store_db(struct _device_manage* dev_manage)
{
	LM_DBG("device_manage_remove_device_store_db  start ......\n");
	/*判断数据库连接是否成功*/
	if(unlikely(db_con==NULL)) {
		LM_DBG("database connection not initialized\n");
		return -1;
	}

	db_key_t db_keys_select[3]; //the key for select
	db_val_t db_vals_select[3];
 	db1_res_t* db_res = NULL;
	
	db_keys_select[0] = &status_column;
	db_vals_select[0].type = DB1_INT;
	db_vals_select[0].nul = 0;
	db_vals_select[0].val.int_val = 1;

	db_keys_select[1] = &node_id_column;
	db_vals_select[1].type = DB1_INT;
	db_vals_select[1].nul = 0;
	db_vals_select[1].val.int_val = dev_manage->node_id;
	
	db_keys_select[2] = &gateway_id_column;
	db_vals_select[2].type = DB1_STR;
	db_vals_select[2].nul = 0;
	db_vals_select[2].val.str_val = dev_manage->gateway_id;
	
	db_funcs.use_table(db_con, &device_manage_table);

	if(db_funcs.query(db_con, db_keys_select, NULL, db_vals_select, NULL,
		3/*3 keys*/, 0/*no cols*/, NULL, &db_res) != 0)
	{
		LM_ERR("failed to query database in device_manage!\n");
		return -1;
	}

	if(RES_ROW_N(db_res)<=0 || RES_ROWS(db_res)[0].values[0].nul != 0)
	{
		LM_ERR("the device has been removed before this, is not in device_manage list!\n");
		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("failed to freeing result of query in device_manage\n");
		return 0;
	}

	db_key_t db_keys[2];
	db_val_t db_vals[2];

	db_keys[0] = &status_column;
	db_vals[0].type = DB1_INT;
	db_vals[0].nul = 0;
	db_vals[0].val.int_val = 0;

	db_keys[1] = &update_time_column;
	db_vals[1].type = DB1_DATETIME;
	db_vals[1].nul = 0;
	db_vals[1].val.time_val = dev_manage->exclusiontime.tv_sec;

	if(db_funcs.update(db_con, db_keys_select, NULL, db_vals_select, db_keys, db_vals, 3, 2) < 0) {
		LM_ERR("update device_manage error storing trace!!!!\n");
		return -1;
	}

	if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
		LM_ERR("failed to freeing result of query in device_manage\n");
	
	LM_DBG("device_manage_remove_device_store_db  end ......\n");
	
	return 0;
}
#endif


/**********************************************************/
#if 0
/*V2:设备退网时存储设备信息到数据库表device_manage*/
static int Evy_device_manage_remove_device_store_db(struct _device_manage* dev_manage)
{
	LM_DBG("device_manage_remove_device_store_db  start ......\n");
	/*判断数据库连接是否成功*/
	if(unlikely(db_con==NULL)) {
		LM_DBG("database connection not initialized\n");
		return -1;
	}

	db_key_t db_keys_select[2]; //the key for select
	db_val_t db_vals_select[2];
 	db1_res_t* db_res = NULL;
	
	db_keys_select[0] = &status_column;
	db_vals_select[0].type = DB1_INT;
	db_vals_select[0].nul = 0;
	db_vals_select[0].val.int_val = 1;

	db_keys_select[1] = &node_id_column;
	db_vals_select[1].type = DB1_INT;
	db_vals_select[1].nul = 0;
	db_vals_select[1].val.int_val = dev_manage->node_id;
	
	db_funcs.use_table(db_con, &device_manage_table);

	if(db_funcs.query(db_con, db_keys_select, NULL, db_vals_select, NULL,
		2/*3 keys*/, 0/*no cols*/, NULL, &db_res) != 0)
	{
		LM_ERR("failed to query database in device_manage!\n");
		return -1;
	}

	if(RES_ROW_N(db_res)<=0 || RES_ROWS(db_res)[0].values[0].nul != 0)
	{
		LM_ERR("the device has been removed before this, is not in device_manage list!\n");
		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("failed to freeing result of query in device_manage\n");
		return 0;
	}

	db_key_t db_keys[2];
	db_val_t db_vals[2];

	db_keys[0] = &status_column;
	db_vals[0].type = DB1_INT;
	db_vals[0].nul = 0;
	db_vals[0].val.int_val = 0;

	db_keys[1] = &update_time_column;
	db_vals[1].type = DB1_DATETIME;
	db_vals[1].nul = 0;
	db_vals[1].val.time_val = dev_manage->exclusiontime.tv_sec;

	if(db_funcs.update(db_con, db_keys_select, NULL, db_vals_select, db_keys, db_vals, 2, 2) < 0) {
		LM_ERR("update device_manage error storing trace!!!!\n");
		return -1;
	}

	if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
		LM_ERR("failed to freeing result of query in device_manage\n");
	
	LM_DBG("device_manage_remove_device_store_db  end ......\n");
	
	return 0;
}
/**********************************************************/
#endif


/*设备退网时存储设备信息到数据库表device_full*/
#if 0
static int Evy_device_manage_remove_device_store_db1(struct _device_manage* dev_manage1)
{
	LM_DBG("device_manage_remove_device_store_db1  start ......\n");
	/*判断数据库连接是否成功*/
	if(unlikely(db_con==NULL)) {
		LM_DBG("database connection not initialized\n");
		return -1;
	}

	db_key_t db_keys_select[3]; //the key for select
	db_val_t db_vals_select[3];
 	db1_res_t* db_res = NULL;
	
	db_keys_select[0] = &status_column;
	db_vals_select[0].type = DB1_INT;
	db_vals_select[0].nul = 0;
	db_vals_select[0].val.int_val = 1;

	db_keys_select[1] = &node_id_column;
	db_vals_select[1].type = DB1_INT;
	db_vals_select[1].nul = 0;
	db_vals_select[1].val.int_val = dev_manage1->node_id;
	
	db_keys_select[2] = &gateway_id_column;
	db_vals_select[2].type = DB1_STR;
	db_vals_select[2].nul = 0;
	db_vals_select[2].val.str_val = dev_manage1->gateway_id;
	
	db_funcs.use_table(db_con, &device_full_table);

	if(db_funcs.query(db_con, db_keys_select, NULL, db_vals_select, NULL,
		3/*3 keys*/, 0/*no cols*/, NULL, &db_res) != 0)
	{
		LM_ERR("failed to query database in device_full!\n");
		return -1;
	}

	if(RES_ROW_N(db_res)<=0 || RES_ROWS(db_res)[0].values[0].nul != 0)
	{
		LM_ERR("the device has been removed before this, is not in device_full list!\n");
		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("failed to freeing result of query in device_full\n");
		return 0;
	}

	db_key_t db_keys[2];
	db_val_t db_vals[2];

	db_keys[0] = &status_column;
	db_vals[0].type = DB1_INT;
	db_vals[0].nul = 0;
	db_vals[0].val.int_val = 0;

	db_keys[1] = &update_time_column;
	db_vals[1].type = DB1_DATETIME;
	db_vals[1].nul = 0;
	db_vals[1].val.time_val = dev_manage1->exclusiontime.tv_sec;

	if(db_funcs.update(db_con, db_keys_select, NULL, db_vals_select, db_keys, db_vals, 3, 2) < 0) {
		LM_ERR("update device_full error storing trace!!!!\n");
		return -1;
	}

	if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
		LM_ERR("failed to freeing result of query in device manage\n");
	
    LM_DBG("device_manage_remove_device_store_db1  end ......\n");
	
	return 0;
}
#endif

/*用户有效性验证*/
static int  Evy_userAuthentication(str user_Id,str sn_Code)
{
	str id_s;
	
	str table_name;
	//table_name.s = "user_sip";
	//table_name.len = strlen("user_sip");
	//table_name.s = "bind_remark";
	//table_name.len = strlen("bind_remark");
	table_name.s = "user_gateway_bind";
	table_name.len = strlen("user_gateway_bind");
	//db_key_t db_keys[2] = {&user_id_column, &sn_code_column}; //the key for select
	db_key_t db_keys[2] = {&user_id_column, &gateway_id_column}; //the key for select
	db_val_t db_vals[2];
	db_key_t db_cols[1];
	db1_res_t* db_res = NULL;
	
	db_vals[0].type = DB1_STR;
	db_vals[0].nul = 0;
	db_vals[0].val.str_val.s = user_Id.s;
	db_vals[0].val.str_val.len = user_Id.len;
	
	db_vals[1].type = DB1_STR;
	db_vals[1].nul = 0;
	db_vals[1].val.str_val.s = sn_Code.s;
	db_vals[1].val.str_val.len = sn_Code.len;
	
	db_cols[0] = &id_column;
	
	db_funcs.use_table(db_con, &table_name);
	
	if(db_funcs.query(db_con, db_keys, NULL, db_vals, db_cols,
		2/*no keys*/, 1/*no cols*/, NULL, &db_res)!=0)
	{
		LM_ERR("failed to query database in userAuthentication\n");
		goto err_server;
	}

	if (RES_ROW_N(db_res) == 0) {
	
		LM_DBG("Evy_userAuthentication:userAuthentication failed!!!\n");
		goto err_server;
	}

	id_s.s = id_buf;
	
	switch(RES_ROWS(db_res)[0].values[0].type) {
	
		case DB1_STRING:
			strcpy(id_s.s,
					(char*)RES_ROWS(db_res)[0].values[0].val.string_val);
			id_s.len = RES_ROWS(db_res)[0].values[0].val.str_val.len;

			break;
		
		case DB1_STR:
			strncpy(id_s.s,
					(char*)RES_ROWS(db_res)[0].values[0].val.str_val.s,
					RES_ROWS(db_res)[0].values[0].val.str_val.len);
			
			id_s.len = RES_ROWS(db_res)[0].values[0].val.str_val.len;
			id_s.s[id_s.len] = '\0';

			break;
		
		case DB1_BLOB:
			strncpy(id_s.s,
					(char*)RES_ROWS(db_res)[0].values[0].val.blob_val.s,
					RES_ROWS(db_res)[0].values[0].val.blob_val.len);
		
			id_s.len = RES_ROWS(db_res)[0].values[0].val.blob_val.len;
			id_s.s[id_s.len] = '\0';
			
			break;
		
		default:
			LM_ERR("unknown type of DB id  column\n");
			if (db_res != NULL && db_funcs.free_result(db_con,db_res) < 0)
				LM_DBG("failed to free result of query in userAuthentication\n");

			break;

	}

	if (RES_ROW_N(db_res)<=0 || RES_ROWS(db_res)[0].values[0].nul != 0)
	{
		LM_ERR("no data found for user in userAuthentication\n");
		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("failed to freeing result of query in userAuthentication\n");

		goto err_server;
	}

	/**
	 * Free the DB result
	 */
	if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0) {
		LM_ERR("failed to freeing result of query in userAuthentication\n");
	}

	LM_DBG("userAuthentication success!!!\n");
	return 0;

err_server:
	return -1;
}

//十六进制字符串转换为字节流
void Evy_HexStrToByte(const char* source, unsigned char* dest, int length)
{
    short i;
    unsigned char highByte, lowByte;

    for (i = 0; i < length; i += 2)
    {
        highByte = toupper(source[i]);
        lowByte  = toupper(source[i + 1]);

        if (highByte > 0x39)
            highByte -= 0x37;
        else
            highByte -= 0x30;

        if (lowByte > 0x39)
            lowByte -= 0x37;
        else
            lowByte -= 0x30;

        // dest[i / 2] = (highByte << 4) | lowByte;
        dest[(length-2-i)/2] = (highByte << 4) | lowByte;
    }
    return;
}

/*对cmd的解析*/
#if 0 
static int Evy_sip_trace_parse_cmd(int length, char *buf)
{
	// buf = "322412345678CADD24FF1234AB12DF0123456789ABCDEF12345678CD1234567801001403";
	unsigned char header; //32
	Evy_HexStrToByte(buf, &header, 2);
	unsigned char seq_num; //24
	Evy_HexStrToByte(buf+2, &seq_num, 2);
	unsigned int client_id; //12345678
	Evy_HexStrToByte(buf+4, (unsigned char*) &client_id, 8);
	unsigned int ip_addr; //CADD24FF
	Evy_HexStrToByte(buf+12, (unsigned char*) &ip_addr, 8);
	short int port; //1234
	Evy_HexStrToByte(buf+20, (unsigned char*) &port, 4);
	unsigned char device_type; //AB
	Evy_HexStrToByte(buf+24, &device_type, 2);
	unsigned char end_point; //12
	Evy_HexStrToByte(buf+26, &end_point, 2);
	unsigned char nodeid; //DF
	Evy_HexStrToByte(buf+28, &nodeid, 2);
	unsigned long dongle_sn; //0123456789ABCDEF
	Evy_HexStrToByte(buf+30, (unsigned char*) &dongle_sn, 16);
	unsigned int reserved2; //12345678
	Evy_HexStrToByte(buf+46, (unsigned char*) &reserved2, 8);
	unsigned char command_type; //CD
	Evy_HexStrToByte(buf+54, &command_type, 2);
	short int command_code; //1234
	Evy_HexStrToByte(buf+56, (unsigned char*) &command_code, 4);
	short int reserved3; //5678
	Evy_HexStrToByte(buf+60, (unsigned char*) &reserved3, 4);
	unsigned char data_length; //01
	Evy_HexStrToByte(buf+64, &data_length, 2);
	//data content skip
	unsigned char checksum; //14
	Evy_HexStrToByte(buf+66+data_length*2, &checksum, 2);
	unsigned char tailor; //03
	Evy_HexStrToByte(buf+68+data_length*2, &tailor, 2);

	// LM_DBG("header = %x, seq_num = %x, client_id = %x, ip_addr = %x, port = %x, device_type = %x, end_point = %x, nodeid = %x, dongle_sn = %lx, reserved2 = %x, command_type = %x, command_code = %x, reserved3 = %x, data_length = %x, checksum = %x, tailor = %x\n", header, seq_num, client_id, ip_addr, port, device_type, end_point, nodeid, dongle_sn, reserved2, command_type, command_code, reserved3, data_length, checksum, tailor);
	return 0;
}
#endif


/*网关版本号检测*/
#if 0
static int Evy_gwVersionCheck(str sn_Code)
{
	int gwVersion_s;

	
	/*set table name*/
	str table_name;
	table_name.s = "sn_code";
	table_name.len = strlen("sn_code"); 
	
	db_key_t db_keys[1] = {&sn_code_column}; //the key for select
	db_val_t db_vals[1];
	db_key_t db_cols[1];
	db1_res_t* db_res = NULL;
	
	db_vals[0].type = DB1_STR;
	db_vals[0].nul = 0;
	db_vals[0].val.str_val.s = sn_Code.s;
	db_vals[0].val.str_val.len = sn_Code.len;
	
	db_cols[0] = &gw_version_column;
	
	db_funcs.use_table(db_con, &table_name);
	
	if(db_funcs.query(db_con, db_keys, NULL, db_vals, db_cols,
		1/*no keys*/, 1/*no cols*/, NULL, &db_res)!=0)
	{
		LM_ERR("failed to query database in gwVersionCheck\n");
		goto err_server;
	}

	gwVersion_s.s = gw_version_buf;

	switch(RES_ROWS(db_res)[0].values[0].type)
	{ 
		case DB1_STRING:
			strcpy(gwVersion_s.s, 
				  (char*)RES_ROWS(db_res)[0].values[0].val.string_val);
			
			gwVersion_s.len = RES_ROWS(db_res)[0].values[0].val.str_val.len;

			break;
		
		case DB1_STR:
			strncpy(gwVersion_s.s, 
				   (char*)RES_ROWS(db_res)[0].values[0].val.str_val.s,RES_ROWS(db_res)[0].values[0].val.str_val.len);
			
			gwVersion_s.len = RES_ROWS(db_res)[0].values[0].val.str_val.len;
			
			gwVersion_s.s[gwVersion_s.len] = '\0';
			
			break;

		case DB1_BLOB:
			strncpy(gwVersion_s.s, 
				(char*)RES_ROWS(db_res)[0].values[0].val.blob_val.s,RES_ROWS(db_res)[0].values[0].val.blob_val.len);
			
			gwVersion_s.len = RES_ROWS(db_res)[0].values[0].val.blob_val.len;
			
			gwVersion_s.s[gwVersion_s.len] = '\0';
			
			break;
		
		default:
			LM_ERR("unknown type of DB gwVersion column\n");
			
			if (db_res != NULL && db_funcs.free_result(db_con, db_res) < 0)
				LM_DBG("failed to free result of query in gwVersionCheck\n");

			break;
	}

#if 0

	if (gwVersion_s.len < 4 || strncmp(gwVersion_s.s, "sip", 4)) {
		memcpy(gw_version_buf, "sip", 4);
		gwVersion_s.s -= 4;
		gwVersion_s.len += 4;
	}
#endif

	if (RES_ROW_N(db_res)<=0 || RES_ROWS(db_res)[0].values[0].nul != 0) {
		
		LM_ERR("no data found for user in gwVersionCheck\n");

		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("failed to freeing result of query in gwVersionCheck\n");
		goto err_server;
	}

	/**
	 * Free the DB result
	 */
	if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
		LM_ERR("failed to freeing result of query in gwVersionCheck\n");

	LM_DBG("Evy_gwVersionCheck:gwVersionCheck success!!!\n");
	return 0;

err_server:
	return -1;

}
#endif

static int Evy_gwVersionCheck(int sn_Code) {
	
	if (sn_Code == 1) {
		return 3;
	}else {
		return -1;
	}
}


static int Evy_parse_device_action_data(struct json_object* json_data) {
	/*用户有效性验证*/

	str str_userid;
    str str_gatewayid;

	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;

	if(unlikely(json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid) == 0)) {
		LM_ERR("Error:json_gatewayid = NULL\n");
		return -1;
	}
	if(unlikely(json_object_object_get_ex(json_data, "userid", &json_userid) == 0)) {
		LM_ERR("Error:json_userid = NULL\n");
		return -1;
	}

	/*将json数据转为字符型*/

	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);

	if (unlikely(Evy_userAuthentication(str_userid,str_gatewayid) == -1)) {
		LM_ERR("userAuthentication Error!!!!!!\n");
		return -2;
	}
#if 0
	if (unlikely(Evy_gwVersionCheck(str_gatewayid) == -1)) {
		LM_ERR("gwVersionCheck  Error!!!!!!\n");
		goto error;
	}
#endif
	return 0;
}
#if 0
static int Evy_parse_device_update_data(struct json_object* json_data)
{
	/*设备操作命令*/
	str str_cmd;
	struct json_object* json_cmd = NULL;
	if(unlikely(json_object_object_get_ex(json_data, "cmd", &json_cmd) == 0)) {
		LM_ERR("Error:json_cmd = NULL\n");
		return -1;
	}
	str_cmd.s = (char*) json_object_get_string(json_cmd);
	str_cmd.len = json_object_get_string_len(json_cmd);

	if (unlikely(Evy_sip_trace_parse_cmd(str_cmd.len, str_cmd.s) == -1)) {
		LM_ERR("parse command error!\n");
		return -1;
	}
	return 0;
}
#endif


/*解析设备操作日志数据包*/
#if 0
static int Evy_parse_device_log(struct json_object* json_data)
{
	/*设备操作日志*/
	str str_content;
    const char* str_eventtime;
	int int_devicetype;
	str str_gatewayid;
	int int_cmdcode;
	int int_nodeid;

	struct json_object* json_content = NULL;
	struct json_object* json_eventtime = NULL;
	struct json_object* json_devicetype = NULL;
	struct json_object* json_gatewayid = NULL;
	struct json_object* json_cmdcode = NULL;
	struct json_object* json_nodeid = NULL;

	if(unlikely(json_object_object_get_ex(json_data, "content", &json_content) == 0)) {
		LM_ERR("Error:json_content = NULL\n");
        goto error;
	}
	if(unlikely(json_object_object_get_ex(json_data, "eventtime", &json_eventtime) == 0)) {
		LM_ERR("Error:json_eventtime = NULL\n");
		goto error;
	}
	if(unlikely(json_object_object_get_ex(json_data, "devicetype", &json_devicetype) == 0)) {
		LM_ERR("Error:json_devicetype = NULL\n");
		goto error;
	}
	if(unlikely(json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid) == 0)) {
		LM_ERR("Error:json_gatewayid = NULL\n");
		goto error;
	}
	if(unlikely(json_object_object_get_ex(json_data, "cmdcode", &json_cmdcode) == 0)) {
		LM_ERR("Error:json_cmdcode = NULL\n");
		goto error;
	}
	if(unlikely(json_object_object_get_ex(json_data, "nodeid", &json_nodeid) == 0)) {
		LM_ERR("Error:json_nodeid = NULL\n");
		goto error;
	}	
	/*将json数据转为字符型*/
	str_content.s = (char*) json_object_get_string(json_content);
	str_content.len = json_object_get_string_len(json_content);
	str_eventtime = json_object_get_string(json_eventtime);
	int_devicetype = json_object_get_int(json_devicetype);
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	int_cmdcode = json_object_get_int(json_cmdcode);
	int_nodeid = json_object_get_int(json_nodeid);

	LM_DBG("/***********json data print start************/\n");
	LM_DBG("str_content = <%.*s>, str_eventtime = <%s>, int_devicetype = <%d>, str_gatewayid = <%.*s>, int_cmdcode = <%d>, int_nodeid = <%d>\n",
		str_content.len,str_content.s,
		str_eventtime,
		int_devicetype,
		str_gatewayid.len,str_gatewayid.s,
		int_cmdcode, int_nodeid);
	LM_DBG("/***********json data print end**************/\n");
#if 0
	if (unlikely(Evy_gwVersionCheck(str_sn_code) == -1)) {
		LM_ERR("gwVersionCheck Error!!!!!!\n");
		goto error;
	}	
#endif
	//store log message
	struct _device_log* device_log;
	device_log = pkg_malloc(sizeof(struct _device_log));
	if(unlikely(device_log == 0)) {
		LM_ERR("no mem for _device_log\n");
		goto error;
	}
	memset(device_log, 0, sizeof(struct _device_log));

	//获取UUID
	uuid_t uuid;
	uuid_generate(uuid);
	uuid_unparse(uuid, device_log->uuid);
	LM_DBG("uuid = %s \n", device_log->uuid);
	//解析出的时间字符串转换成timeval时间格式
	struct tm tm_eventtime;
	tm_eventtime.tm_isdst = -1;
	if(strptime(str_eventtime, "%Y/%m/%d %H:%M:%S", &tm_eventtime) == NULL) {
		LM_ERR("translate time error!!!\n");
	}
	LM_DBG("translated time = %04d/%02d/%02d %02d:%02d:%02d\n",
		tm_eventtime.tm_year+1900, tm_eventtime.tm_mon+1, tm_eventtime.tm_mday, tm_eventtime.tm_hour, tm_eventtime.tm_min, tm_eventtime.tm_sec);

	device_log->event_time.tv_sec = mktime(&tm_eventtime);	/*04*/
	device_log->event_time.tv_usec = 0;
	device_log->log_id                  = 0x01;          /*01*/
	device_log->user_id.s               = "a";           /*02*/
	device_log->user_id.len             = 1;
	device_log->nodeid                  = int_nodeid;    /*03*/
	device_log->command_code            = int_cmdcode;   /*05*/
	device_log->gateway_id              = str_gatewayid; /*06*/
	device_log->content                 = str_content;   /*07*/
	device_log->device_type             = int_devicetype;/*08*/
	if(Evy_device_log_store_db(device_log) < 0) {
		LM_ERR("device log store db Error!\n");
		pkg_free(device_log);
		goto error;
	}
	pkg_free(device_log);
	return 1;
error:
	return -1;
}
#endif
/**********************************/

/*查询设备的device_type*/
str  Evy_select_devicetype(str manufacturer_id,str product_id, str product_type, str err_value)
{
	str device_type_s;
	str table_name;
	
	/*确定使用哪个数据库表*/
	table_name.s = "device_attributes";
	table_name.len = strlen("device_attributes");
	
	/*用于查询的关键字段*/
	db_key_t db_keys[3] = {&manufacture_id_column, &product_id_column, &product_type_column};
	db_val_t db_vals[3];
	db_key_t db_cols[1];
	db1_res_t* db_res = NULL;
	
	db_vals[0].type = DB1_STR;
	db_vals[0].nul = 0;
	db_vals[0].val.str_val.s = manufacturer_id.s;
	db_vals[0].val.str_val.len = manufacturer_id.len;
	
	db_vals[1].type = DB1_STR;
	db_vals[1].nul = 0;
	db_vals[1].val.str_val.s = product_id.s;
	db_vals[1].val.str_val.len = product_id.len;
	
	db_vals[2].type = DB1_STR;
	db_vals[2].nul = 0;
	db_vals[2].val.str_val.s = product_type.s;
	db_vals[2].val.str_val.len = product_type.len;
	
	db_cols[0] = &device_type_column;
	
	db_funcs.use_table(db_con, &table_name);//使用数据库表
	
	/*查询数据*/
	if(db_funcs.query(db_con, db_keys, NULL, db_vals, db_cols,
		3/*no keys*/, 1/*no cols*/, NULL, &db_res)!=0)
	{
		LM_ERR("failed to query database in Evy_select_devicetype\n");
		goto err_server;
	}

	if (RES_ROW_N(db_res) == 0) {
	
		LM_DBG("Evy_select_devicetype:select devicetype failed!!!\n");
		goto err_server;
	}

	device_type_s.s = device_type_buf;
	
	switch(RES_ROWS(db_res)[0].values[0].type) {
	
		case DB1_STRING:
			strcpy(device_type_s.s,
					(char*)RES_ROWS(db_res)[0].values[0].val.string_val);
			device_type_s.len = RES_ROWS(db_res)[0].values[0].val.str_val.len;
			LM_DBG("device_type_s = %s\n",device_type_s.s);
			
			break;
		
		case DB1_STR:
			strncpy(device_type_s.s,
					(char*)RES_ROWS(db_res)[0].values[0].val.str_val.s,
					RES_ROWS(db_res)[0].values[0].val.str_val.len);
			
			device_type_s.len = RES_ROWS(db_res)[0].values[0].val.str_val.len;
			
			LM_DBG("device_type_s = %s\n",device_type_s.s);
			device_type_s.s[device_type_s.len] = '\0';
			
			break;
		
		case DB1_BLOB:
			strncpy(device_type_s.s,
					(char*)RES_ROWS(db_res)[0].values[0].val.blob_val.s,
					RES_ROWS(db_res)[0].values[0].val.blob_val.len);
		
			device_type_s.len = RES_ROWS(db_res)[0].values[0].val.blob_val.len;
			
			LM_DBG("device_type_s = %s\n",device_type_s.s);
			device_type_s.s[device_type_s.len] = '\0';
			
			break;
		
		default:
			LM_ERR("unknown type of DB id  column\n");
			if (db_res != NULL && db_funcs.free_result(db_con,db_res) < 0)
				LM_DBG("failed to free result of query in Evy_select_devicetype\n");

			break;
	}

	if (RES_ROW_N(db_res)<=0 || RES_ROWS(db_res)[0].values[0].nul != 0)
	{
		LM_ERR("no data found for user in Evy_select_devicetype\n");
		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("failed to freeing result of query in Evy_select_devicetype\n");
		goto err_server;
	}

	/**
	 * Free the DB result
	 */
	if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0) {
		LM_ERR("failed to freeing result of query in Evy_select_devicetype\n");
	}

	LM_DBG("Evy_select_devicetype success!!!\n");
	return device_type_s;

err_server:
	LM_DBG("select device_type failed!!!\n");
	return err_value;
}
/**********************************/

/**********************************/
/*查询设备的绑定id*/
str  Evy_select_bindId(str gateway_id, str err_value)
{
	str bind_id_s;
	str table_name;
	
	/*确定使用哪个数据库表*/
	//table_name.s = "bind_remark";
	//table_name.len = strlen("bind_remark");
	table_name.s = "user_gateway_bind";
	table_name.len = strlen("user_gateway_bind");
	
	/*用于查询的关键字段*/
	db_key_t db_keys[1] = {&gateway_id_column};
	db_val_t db_vals[1];
	db_key_t db_cols[1];
	db1_res_t* db_res = NULL;
	
	db_vals[0].type = DB1_STR;
	db_vals[0].nul = 0;
	db_vals[0].val.str_val.s = gateway_id.s;
	db_vals[0].val.str_val.len = gateway_id.len;
	
	db_cols[0] = &id_column;
	
	db_funcs.use_table(db_con, &table_name);//使用数据库表
	
	/*查询数据*/
	if(db_funcs.query(db_con, db_keys, NULL, db_vals, db_cols,
		1/*no keys*/, 1/*no cols*/, NULL, &db_res)!=0)
	{
		LM_ERR("failed to query database in Evy_select_bindId\n");
		goto err_server;
	}

	if (RES_ROW_N(db_res) == 0) {
	
		LM_DBG("Evy_select_bindId:select id failed!!!\n");
		goto err_server;
	}

	bind_id_s.s = bind_id_buf;
	bind_id_s.len = strlen(bind_id_buf);
	
	switch(RES_ROWS(db_res)[0].values[0].type) {
	
		case DB1_STRING:
			strcpy(bind_id_s.s,
					(char*)RES_ROWS(db_res)[0].values[0].val.string_val);
			bind_id_s.len = RES_ROWS(db_res)[0].values[0].val.str_val.len;
			LM_DBG("bind_id_s = %s\n",bind_id_s.s);
	
			break;
		
		case DB1_STR:
			strncpy(bind_id_s.s,
					(char*)RES_ROWS(db_res)[0].values[0].val.str_val.s,
					RES_ROWS(db_res)[0].values[0].val.str_val.len);
			
			bind_id_s.len = RES_ROWS(db_res)[0].values[0].val.str_val.len;
			
			LM_DBG("bind_id_s = %s\n",bind_id_s.s);
			bind_id_s.s[bind_id_s.len] = '\0';
			
			break;
		
		case DB1_BLOB:
			strncpy(bind_id_s.s,
					(char*)RES_ROWS(db_res)[0].values[0].val.blob_val.s,
					RES_ROWS(db_res)[0].values[0].val.blob_val.len);
		
			bind_id_s.len = RES_ROWS(db_res)[0].values[0].val.blob_val.len;
			
			LM_DBG("bind_id_s = %s\n",bind_id_s.s);
			bind_id_s.s[bind_id_s.len] = '\0';
			
			break;
		
		default:
			LM_ERR("unknown type of DB id  column\n");
			if (db_res != NULL && db_funcs.free_result(db_con,db_res) < 0)
				LM_DBG("failed to free result of query in Evy_select_bindId\n");

			break;
	}

	if (RES_ROW_N(db_res)<=0 || RES_ROWS(db_res)[0].values[0].nul != 0)
	{
		LM_ERR("no data found for user in Evy_select_bindId\n");
		if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0)
			LM_ERR("failed to freeing result of query in Evy_select_bindId\n");
		goto err_server;
	}

	/**
	 * Free the DB result
	 */
	if (db_res!=NULL && db_funcs.free_result(db_con, db_res) < 0) {
		LM_ERR("failed to freeing result of query in Evy_select_bindId\n");
	}

	LM_DBG("Evy_select_bindId success!!!\n");
	return bind_id_s;

err_server:
	LM_DBG("Evy_select_bindId failed!!!\n");
	return err_value;
}
/**********************************/


/*解析设备入网时发送过来的数据包*/
static int Evy_parse_device_add_message(struct json_object* json_data)
{
	str err_msg;
	err_msg.s = 0;
	//err_msg.len = strlen(err_msg.s);
	/*设备信息*/
	str str_manufactureid;
	str str_firmwareversion;
	str str_productid;
	str str_producttype;
	str str_gateway_id;
	str str_devicetype;
	str str_bind_id;
	str devicetype1;
	str bindId;
	//str str_nodeID;
	int int_deviceID;
	const char* str_inclusiontime;

	struct json_object* json_manufactureid = NULL;
	struct json_object* json_firmwareversion = NULL;
	struct json_object* json_deviceID = NULL;
	struct json_object* json_productid = NULL;
	struct json_object* json_producttype = NULL;
	struct json_object* json_inclusiontime = NULL;
	struct json_object* json_gateway_id = NULL;

	struct _device_manage* device_manage;
	device_manage = pkg_malloc(sizeof(struct _device_manage));
	if(unlikely(device_manage == 0)) {
		LM_ERR("no mem for _device_manage\n");
		pkg_free(device_manage);
		goto error;
	}
	memset(device_manage, 0, sizeof(struct _device_manage));

	if(unlikely(json_object_object_get_ex(json_data, "manufacturerid", &json_manufactureid) == 0)) {
		LM_ERR("Error:json_manufactureid = NULL\n");
		pkg_free(device_manage);
		goto error;	
	}
	if(unlikely(json_object_object_get_ex(json_data, "firmwareversion", &json_firmwareversion) == 0)) {
		LM_ERR("Error:json_firmwareversion = NULL\n");
		pkg_free(device_manage);
		goto error;
	}
	if(unlikely(json_object_object_get_ex(json_data, "deviceid", &json_deviceID) == 0)) {
		LM_ERR("Error:json_nodeID = NULL\n");
		pkg_free(device_manage);
		goto error;
	}
	if(unlikely(json_object_object_get_ex(json_data, "productid", &json_productid) == 0)) {
		LM_ERR("Error:json_productid = NULL\n");
		pkg_free(device_manage);
		goto error;
	}
	if(unlikely(json_object_object_get_ex(json_data, "producttype", &json_producttype) == 0)) {
		LM_ERR("Error:json_producttype = NULL\n");
		pkg_free(device_manage);
		goto error;
	}
	if(unlikely(json_object_object_get_ex(json_data, "inclusiontime", &json_inclusiontime) == 0)) {
		LM_ERR("Error:json_inclusiontime = NULL\n");
		pkg_free(device_manage);
		goto error;
	}
	if(unlikely(json_object_object_get_ex(json_data, "gateway_id", &json_gateway_id) == 0)) {
		LM_ERR("Error:json_gateway_id = NULL\n");
		pkg_free(device_manage);
		goto error;
	}
	str_manufactureid.s = (char*) json_object_get_string(json_manufactureid);
	str_manufactureid.len = json_object_get_string_len(json_manufactureid);
	str_firmwareversion.s = (char*) json_object_get_string(json_firmwareversion);
	str_firmwareversion.len = json_object_get_string_len(json_firmwareversion);
	//str_nodeID.s = (char*)json_object_get_string(json_nodeID);
	//str_nodeID.len = json_object_get_string_len(json_nodeID);
	
	int_deviceID = json_object_get_int(json_deviceID);
	
	str_productid.s = (char*) json_object_get_string(json_productid);
	str_productid.len = json_object_get_string_len(json_productid);
	str_producttype.s = (char*) json_object_get_string(json_producttype);
	str_producttype.len = json_object_get_string_len(json_producttype);
	str_inclusiontime = json_object_get_string(json_inclusiontime);
	str_gateway_id.s = (char*) json_object_get_string(json_gateway_id);
	str_gateway_id.len = json_object_get_string_len(json_gateway_id);
	

	LM_DBG("/***********json data print start************/\n");
    LM_DBG("str_manufactureid = <%.*s>, str_firmwareversion = <%.*s>,str_productid = <%.*s>,int_deviceID = <%d>,str_producttype = <%.*s>, str_inclusiontime = <%s>, str_gateway_id = <%.*s>\n", 
			str_manufactureid.len,str_manufactureid.s,
			str_firmwareversion.len,str_firmwareversion.s,
			str_productid.len,str_productid.s,
			int_deviceID,
			str_producttype.len,str_producttype.s,
			str_inclusiontime,
			str_gateway_id.len,str_gateway_id.s);
	LM_DBG("/***********json data print end**************/\n");

	/*调用查询device_type的函数*/
	devicetype1 = Evy_select_devicetype(str_manufactureid, str_productid, str_producttype, err_msg);
	
	/*调用查询绑定id的函数*/
	bindId = Evy_select_bindId(str_gateway_id, err_msg);

	LM_DBG("............devicetype1 = %s\n",devicetype1.s);
	LM_DBG("............bindId = %s\n",bindId.s);
	//LM_DBG("............str_nodeID.s = %s\n",str_nodeID.s);
	//LM_DBG("............str_nodeID.len = %d\n",str_nodeID.len);
    LM_DBG("............int_deviceID = %d\n",int_deviceID);	
	if (devicetype1.s == 0) {
		LM_ERR("Error:Evy_select_devicetype is null!!!\n");
		return -1;
	}	
	if (bindId.s == 0) {
		LM_ERR("Error:Evy_select_bindId is null!!!\n");
		return -1;
	}
	if (int_deviceID == 0) {
		LM_ERR("Error:Evy_select_deviceID is null!!!\n");
	}
	
	str_devicetype.s = devicetype1.s;
	str_bind_id.s = bindId.s;
	
	//获取UUID
	uuid_t uuid;
	uuid_generate(uuid);
	uuid_unparse(uuid, device_manage->uuid);
	LM_DBG("uuid = %s \n", device_manage->uuid);
	
	//解析出的时间字符串转换成timeval时间格式
	struct tm tm_inclusiontime;
	tm_inclusiontime.tm_isdst = -1;
	if(strptime(str_inclusiontime, "%Y/%m/%d %H:%M:%S", &tm_inclusiontime) == NULL) {
		LM_ERR("translate time error!!!\n");
	}
	LM_DBG("translated time = %04d/%02d/%02d %02d:%02d:%02d\n",
		tm_inclusiontime.tm_year+1900, tm_inclusiontime.tm_mon+1, tm_inclusiontime.tm_mday, tm_inclusiontime.tm_hour, tm_inclusiontime.tm_min, tm_inclusiontime.tm_sec);

	device_manage->inclusiontime.tv_sec = mktime(&tm_inclusiontime);	 
	device_manage->inclusiontime.tv_usec = 0;
	device_manage->manufactureid.s           = str_manufactureid.s;      
	device_manage->manufactureid.len         = str_manufactureid.len;
	device_manage->devicetype.s              = str_devicetype.s;
	device_manage->devicetype.len            = strlen(str_devicetype.s);
	device_manage->firmwareversion.s         = str_firmwareversion.s;    
    device_manage->firmwareversion.len       = str_firmwareversion.len;
	device_manage->device_id                 = int_deviceID;      		 
	//device_manage->device_id.s               = str_nodeID.s;
	//device_manage->device_id.len             = str_nodeID.len;
	LM_DBG("111111111111111111111111111111111111\n");
	LM_DBG("***********************device_id = %d\n",device_manage->device_id);
	LM_DBG("111111111111111111111111111111111111\n");
	
	device_manage->productid.s               = str_productid.s;  			 
	device_manage->productid.len             = str_productid.len;
	device_manage->producttype.s             = str_producttype.s; 			 
	device_manage->producttype.len           = str_producttype.len;
	device_manage->gateway_id.s              = str_gateway_id.s; 		
	device_manage->gateway_id.len            = str_gateway_id.len;
	device_manage->bind_id.s                 = str_bind_id.s;
	device_manage->bind_id.len               = strlen(str_bind_id.s);

	if(Evy_device_manage_add_device_store_db(device_manage) < 0) {
		LM_ERR("device manage store db Error!\n");
		pkg_free(device_manage);
		goto error;
	}
	pkg_free(device_manage);
	return 1;

error:
	return -1;
}


 
/*设备退网*/
#if 0
static int Evy_parse_device_remove_message(struct json_object* json_data)
{
	/*设备信息*/
	int int_nodeID;
	str str_gateway_id;
	const char* str_exclusiontime;

	struct json_object* json_nodeID = NULL;
	struct json_object* json_exclusiontime = NULL;
	struct json_object* json_gateway_id = NULL;

	struct _device_manage* device_manage;
	device_manage = pkg_malloc(sizeof(struct _device_manage));
	if(unlikely(device_manage == 0)) {
		LM_ERR("no mem for _device_manage\n");
		pkg_free(device_manage);
		goto error;
	}
	memset(device_manage, 0, sizeof(struct _device_manage));

	if(unlikely(json_object_object_get_ex(json_data, "nodeid", &json_nodeID) == 0)) {
		LM_ERR("Error:json_nodeID = NULL\n");
		pkg_free(device_manage);
		goto error;
	}
	if(unlikely(json_object_object_get_ex(json_data, "exclusiontime", &json_exclusiontime) == 0)) {
		LM_ERR("Error:json_exclusiontime = NULL\n");
		pkg_free(device_manage);
		goto error;
	}
	if(unlikely(json_object_object_get_ex(json_data, "gateway_id", &json_gateway_id) == 0)) {
		LM_ERR("Error:json_gateway_id = NULL\n");
		pkg_free(device_manage);
		goto error;
	}
	int_nodeID = json_object_get_int(json_nodeID);
	str_exclusiontime = json_object_get_string(json_exclusiontime);
	str_gateway_id.s = (char*) json_object_get_string(json_gateway_id);
	str_gateway_id.len = json_object_get_string_len(json_gateway_id);
	LM_DBG("/***********json data print start************/\n");
    LM_DBG("int_nodeID = <%d>, str_exclusiontime = <%s>, str_gateway_id = <%.*s>\n", 
			int_nodeID,
			str_exclusiontime,
			str_gateway_id.len,str_gateway_id.s);
	LM_DBG("/***********json data print end**************/\n");
	//解析出的时间字符串转换成timeval时间格式
	struct tm tm_exclusiontime;
	tm_exclusiontime.tm_isdst = -1;
	if(strptime(str_exclusiontime, "%Y/%m/%d %H:%M:%S", &tm_exclusiontime) == NULL) {
		LM_ERR("translate time error!!!\n");
	}
	LM_DBG("translated time = %04d/%02d/%02d %02d:%02d:%02d\n",
		tm_exclusiontime.tm_year+1900, tm_exclusiontime.tm_mon+1, tm_exclusiontime.tm_mday, tm_exclusiontime.tm_hour, tm_exclusiontime.tm_min, tm_exclusiontime.tm_sec);
	device_manage->exclusiontime.tv_sec = mktime(&tm_exclusiontime);	 /*04*/
	device_manage->exclusiontime.tv_usec = 0;
	device_manage->node_id                   = int_nodeID;      		 /*03*/
	device_manage->gateway_id.s              = str_gateway_id.s; 		 /*07*/
	device_manage->gateway_id.len            = str_gateway_id.len;
	if(Evy_device_manage_remove_device_store_db(device_manage) < 0) {
		LM_ERR("Evy_device_manage_remove_device_store_db:device manage store db Error!\n");
		pkg_free(device_manage);
		goto error;
	}
	#if 0
	if(Evy_device_manage_remove_device_store_db1(device_manage) < 0) {
		LM_ERR("Evy_device_manage_remove_device_store_db1:device manage store db Error!\n");
		pkg_free(device_manage);
		goto error;
	}
	#endif
	pkg_free(device_manage);
	return 1;

error:
	return -1;
}

#endif


/******************************项目二期_start********************************/

/*控制类，type=1*/
static int Evy_control(struct json_object* json_data){
	
	str str_userid;
        str str_gatewayid;
	str str_msg;

	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(unlikely(json_object_object_get_ex(json_data, "userid", &json_userid) == 0)) {
		LM_ERR("Error:json_userid = NULL\n");
		return -1;
	}	
	if(unlikely(json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid) == 0)) {
		LM_ERR("Error:json_gatewayid = NULL\n");
		return -1;
	}
	if(unlikely(json_object_object_get_ex(json_data, "msg", &json_msg) == 0)) {
		LM_ERR("Error:json_msg = NULL\n");
		return -1;
	}
	/*将json数据转为字符型*/
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	
	if (unlikely(Evy_userAuthentication(str_userid,str_gatewayid) == -1)) {
		LM_ERR("userAuthentication Error!!!!!!\n");
		return -2;
	}
	
	return 0;
}

/*场景类，type=2*/
static int Evy_scene(struct json_object* json_data){
	
	str str_userid;
    str str_gatewayid;
	str str_msg;

	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(unlikely(json_object_object_get_ex(json_data, "userid", &json_userid) == 0)) {
		LM_ERR("Error:json_userid = NULL\n");
		return -1;
	}	
	if(unlikely(json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid) == 0)) {
		LM_ERR("Error:json_gatewayid = NULL\n");
		return -1;
	}
	if(unlikely(json_object_object_get_ex(json_data, "msg", &json_msg) == 0)) {
		LM_ERR("Error:json_msg = NULL\n");
		return -1;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (unlikely(Evy_userAuthentication(str_userid,str_gatewayid) == -1)) {
		LM_ERR("userAuthentication Error!!!!!!\n");
		return -2;
	}

	return 0;
}

/*联动类，type=3*/
static int Evy_linkage(struct json_object* json_data){
	
	str str_userid;
        str str_gatewayid;
	str str_msg;

	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(unlikely(json_object_object_get_ex(json_data, "userid", &json_userid) == 0)) {
		LM_ERR("Error:json_userid = NULL\n");
		return -1;
	}	
	if(unlikely(json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid) == 0)) {
		LM_ERR("Error:json_gatewayid = NULL\n");
		return -1;
	}
	if(unlikely(json_object_object_get_ex(json_data, "msg", &json_msg) == 0)) {
		LM_ERR("Error:json_msg = NULL\n");
		return -1;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (unlikely(Evy_userAuthentication(str_userid,str_gatewayid) == -1)) {
		LM_ERR("userAuthentication Error!!!!!!\n");
		return -2;
	}

	return 0;
}

/*定时类，type=4*/
static int Evy_timing(struct json_object* json_data){
	
	str str_userid;
    str str_gatewayid;
	str str_msg;

	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(unlikely(json_object_object_get_ex(json_data, "userid", &json_userid) == 0)) {
		LM_ERR("Error:json_userid = NULL\n");
		return -1;
	}	
	if(unlikely(json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid) == 0)) {
		LM_ERR("Error:json_gatewayid = NULL\n");
		return -1;
	}
	if(unlikely(json_object_object_get_ex(json_data, "msg", &json_msg) == 0)) {
		LM_ERR("Error:json_msg = NULL\n");
		return -1;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (unlikely(Evy_userAuthentication(str_userid,str_gatewayid) == -1)) {
		LM_ERR("userAuthentication Error!!!!!!\n");
		return -2;
	}

	return 0;
}

/*自动上报类，type=5*/
static int Evy_autoReport(struct json_object* json_data){
	
	str str_userid;
    str str_gatewayid;
	str str_msg;
	int int_version_code;

	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;
	struct json_object* json_version_code = NULL;

	if(unlikely(json_object_object_get_ex(json_data, "userid", &json_userid) == 0)) {
		LM_ERR("Error:json_userid = NULL\n");
		return -1;
	}	
	if(unlikely(json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid) == 0)) {
		LM_ERR("Error:json_gatewayid = NULL\n");
		return -1;
	}
	if(unlikely(json_object_object_get_ex(json_data, "msg", &json_msg) == 0)) {
		LM_ERR("Error:json_msg = NULL\n");
		return -1;
	}
	if(unlikely(json_object_object_get_ex(json_data, "version_code", &json_version_code) == 0)) {
		LM_ERR("Error:json_version_code = NULL\n");
		return -1;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	int_version_code = json_object_get_int(json_version_code);
	
	if (unlikely(Evy_userAuthentication(str_userid,str_gatewayid) == -1)) {
		LM_ERR("userAuthentication Error!!!!!!\n");
		return -2;
	}
	if (unlikely(Evy_gwVersionCheck(int_version_code) == -1)) {
		LM_ERR("version_code is old, you need upgrade the version\n");
		return -3;
	}else {
		return 4;
	}

	return 0;
}

/*设备信息类，type=6*/
static int Evy_deviceManage(struct json_object* json_data){
	
	str str_userid;
    str str_gatewayid;
	str str_msg;

	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(unlikely(json_object_object_get_ex(json_data, "userid", &json_userid) == 0)) {
		LM_ERR("Error:json_userid = NULL\n");
		return -1;
	}	
	if(unlikely(json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid) == 0)) {
		LM_ERR("Error:json_gatewayid = NULL\n");
		return -1;
	}
	if(unlikely(json_object_object_get_ex(json_data, "msg", &json_msg) == 0)) {
		LM_ERR("Error:json_msg = NULL\n");
		return -1;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (unlikely(Evy_userAuthentication(str_userid,str_gatewayid) == -1)) {
		LM_ERR("userAuthentication Error!!!!!!\n");
		return -2;
	}

	return 0;
}

/*加网，type=7*/
static int Evy_add_Internet(struct json_object* json_data){
	
	str str_userid;
    str str_gatewayid;
	str str_msg;

	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(unlikely(json_object_object_get_ex(json_data, "userid", &json_userid) == 0)) {
		LM_ERR("Error:json_userid = NULL\n");
		return -1;
	}	
	if(unlikely(json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid) == 0)) {
		LM_ERR("Error:json_gatewayid = NULL\n");
		return -1;
	}
	if(unlikely(json_object_object_get_ex(json_data, "msg", &json_msg) == 0)) {
		LM_ERR("Error:json_msg = NULL\n");
		return -1;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (unlikely(Evy_userAuthentication(str_userid,str_gatewayid) == -1)) {
		LM_ERR("userAuthentication Error!!!!!!\n");
		return -2;
	}

	return 0;
}

/*退网，type=8*/
static int Evy_remove_Internet(struct json_object* json_data){
	
	str str_userid;
    str str_gatewayid;
	str str_msg;

	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(unlikely(json_object_object_get_ex(json_data, "userid", &json_userid) == 0)) {
		LM_ERR("Error:json_userid = NULL\n");
		return -1;
	}	
	if(unlikely(json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid) == 0)) {
		LM_ERR("Error:json_gatewayid = NULL\n");
		return -1;
	}
	if(unlikely(json_object_object_get_ex(json_data, "msg", &json_msg) == 0)) {
		LM_ERR("Error:json_msg = NULL\n");
		return -1;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (unlikely(Evy_userAuthentication(str_userid,str_gatewayid) == -1)) {
		LM_ERR("userAuthentication Error!!!!!!\n");
		return -2;
	}

	return 0;
}

/*设备状态，type=9*/
static int Evy_deviceStatus(struct json_object* json_data){
	
	str str_userid;
    str str_gatewayid;
	str str_msg;

	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(unlikely(json_object_object_get_ex(json_data, "userid", &json_userid) == 0)) {
		LM_ERR("Error:json_userid = NULL\n");
		return -1;
	}	
	if(unlikely(json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid) == 0)) {
		LM_ERR("Error:json_gatewayid = NULL\n");
		return -1;
	}
	if(unlikely(json_object_object_get_ex(json_data, "msg", &json_msg) == 0)) {
		LM_ERR("Error:json_msg = NULL\n");
		return -1;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (unlikely(Evy_userAuthentication(str_userid,str_gatewayid) == -1)) {
		LM_ERR("userAuthentication Error!!!!!!\n");
		return -2;
	}

	return 0;
}

/******************************项目二期_end**********************************/

/*解析json数据*/
static int Evy_sip_trace_parse_body(int length, char *buf)
{
	int ret = -1;
	int message_type = -1;
	struct json_object* json_body = NULL;
	struct json_object* json_type = NULL;

	json_body = json_tokener_parse(buf);
	if(unlikely(json_body == NULL)) {
		LM_ERR("Error:json_body = NULL\n");
		goto error;
	}

	if(unlikely(json_object_object_get_ex(json_body, "type", &json_type) == 0)) {
		LM_ERR("Error: not available message!\n");
		goto error;
	}

	message_type = json_object_get_int(json_type);
	switch(message_type) {
	
	case 0:
		ret = Evy_parse_device_action_data(json_body);
		break;
	case 1:
		//ret = Evy_parse_device_update_data(json_body);
		ret = Evy_control(json_body);
		break;
	case 2:
		//ret = Evy_parse_device_log(json_body);
		ret = Evy_scene(json_body);
		break;
	case 3:
		ret = Evy_linkage(json_body);
		break;
	case 4:
		ret = Evy_timing(json_body);
		break;
	case 5:
		ret = Evy_autoReport(json_body);
		break;
	case 6:
	    ret = Evy_deviceManage(json_body);
		break;
	case 7:
	    ret = Evy_add_Internet(json_body);
		break;
	case 8:
	    ret = Evy_remove_Internet(json_body);
		break;
	case 9:
	    ret = Evy_deviceStatus(json_body);
		break;
	case 10:
		ret = Evy_parse_device_add_message(json_body);
		break;
	default:
		LM_DBG("Not available type!\n");
		break;	
	}

	json_object_put(json_body);
	return ret;

error:
	json_object_put(json_body);
	return -1;
}
#if 0
static int Evy_Gw_Ip(char *str) {

	LM_DBG("6666666666666666666\n");
	LM_DBG("%s\n",str);
	return 0;

}
#endif

static int sip_trace(struct sip_msg *msg, struct dest_info * dst, char *dir)
{
	struct _siptrace_data sto;
	struct onsend_info *snd_inf = NULL;

	str msg_body;
	msg_body.s = get_body(msg);//get the content of message_body
	if (msg_body.s==0) {
	
		LM_ERR("failed to extract body from msg!\n");
		return -1;
	}
	msg_body.len = get_content_length(msg);//get the length of message_body
	LM_DBG("%s, %d\n",msg_body.s, msg_body.len);

    /*transfer the func of sip_parse*/
	 int ret = Evy_sip_trace_parse_body(msg_body.len, msg_body.s);
	 if (ret == -1) {
		LM_ERR("can't get available commands\n");
		return -1;
	}else if (ret == -2) {
		LM_DBG("authentication failed!\n");
		return 2;
	}else if (ret == 1) {
	
		LM_DBG("store db ok!!!\n");
		return 0;
	}else if (ret == -3) {
		LM_ERR("gateway version is old,you need upgrade the version\n" );
		return -3;
	}else if (ret == 4) {
		LM_DBG("gateway version is the newest\n");
		return 4;
	}

	if (dst){
	    if (dst->send_sock == 0){
	        dst->send_sock=get_send_socket(0, &dst->to, dst->proto);
	        if (dst->send_sock==0){
	            LM_ERR("can't forward to af %d, proto %d no corresponding"
	                    " listening socket\n", dst->to.s.sa_family, dst->proto);
	            return -1;
	        }
	    }
	}

	if(msg==NULL) {
		LM_DBG("nothing to trace\n");
		return -1;
	}
	memset(&sto, 0, sizeof(struct _siptrace_data));

	if(traced_user_avp.n!=0)
		sto.avp=search_first_avp(traced_user_avp_type, traced_user_avp,
				&sto.avp_value, &sto.state);

	if((sto.avp==NULL) && (trace_on_flag==NULL || *trace_on_flag==0)) {
		LM_DBG("trace off...\n");
		return -1;
	}
	if(sip_trace_prepare(msg)<0)
		return -1;

	sto.callid = msg->callid->body;

	if(msg->first_line.type==SIP_REQUEST) {
		sto.method = msg->first_line.u.request.method;
	} else {
		if(parse_headers(msg, HDR_CSEQ_F, 0) != 0 || msg->cseq==NULL
				|| msg->cseq->parsed==NULL) {
			LM_ERR("cannot parse cseq header\n");
			return -1;
		}
		sto.method = get_cseq(msg)->method;
	}

	if(msg->first_line.type==SIP_REPLY) {
		sto.status = msg->first_line.u.reply.status;
	} else {
		sto.status.s = "";
		sto.status.len = 0;
	}

	snd_inf=get_onsend_info();
	if(snd_inf==NULL) {
		sto.body.s = msg->buf;
		sto.body.len = msg->len;

		siptrace_copy_proto(msg->rcv.proto, sto.fromip_buff);
		strcat(sto.fromip_buff, ip_addr2a(&msg->rcv.src_ip));
		strcat(sto.fromip_buff,":");
		strcat(sto.fromip_buff, int2str(msg->rcv.src_port, NULL));
		sto.fromip.s = sto.fromip_buff;
		sto.fromip.len = strlen(sto.fromip_buff);

		siptrace_copy_proto(msg->rcv.proto, sto.toip_buff);
		strcat(sto.toip_buff, ip_addr2a(&msg->rcv.dst_ip));
		strcat(sto.toip_buff,":");
		strcat(sto.toip_buff, int2str(msg->rcv.dst_port, NULL));
		sto.toip.s = sto.toip_buff;
		sto.toip.len = strlen(sto.toip_buff);

		sto.dir = (dir)?dir:"in";
	} else {
		sto.body.s   = snd_inf->buf;
		sto.body.len = snd_inf->len;

		strncpy(sto.fromip_buff, snd_inf->send_sock->sock_str.s,
				snd_inf->send_sock->sock_str.len);
		sto.fromip.s = sto.fromip_buff;
		sto.fromip.len = strlen(sto.fromip_buff);

		siptrace_copy_proto(snd_inf->send_sock->proto, sto.toip_buff);
		strcat(sto.toip_buff, suip2a(snd_inf->to, sizeof(*snd_inf->to)));
		strcat(sto.toip_buff,":");
		strcat(sto.toip_buff, int2str((int)su_getport(snd_inf->to), NULL));
		sto.toip.s = sto.toip_buff;
		sto.toip.len = strlen(sto.toip_buff);

		sto.dir = "out";
	}

	sto.fromtag = get_from(msg)->tag_value;
	sto.totag = get_to(msg)->tag_value;

#ifdef STATISTICS
	if(msg->first_line.type==SIP_REPLY) {
		sto.stat = siptrace_rpl;
	} else {
		sto.stat = siptrace_req;
	}
#endif
	return sip_trace_store(&sto, dst);
}

#define trace_is_off(_msg) \
	(trace_on_flag==NULL || *trace_on_flag==0 || \
	 ((_msg)->flags&trace_flag)==0)

static void trace_onreq_in(struct cell* t, int type, struct tmcb_params *ps)
{
	struct sip_msg* msg;
	int_str         avp_value;
	struct usr_avp* avp;

	if(t==NULL || ps==NULL)
	{
		LM_DBG("no uas request, local transaction\n");
		return;
	}

	msg = ps->req;
	if(msg==NULL)
	{
		LM_DBG("no uas request, local transaction\n");
		return;
	}

	avp = NULL;
	if(traced_user_avp.n!=0)
		avp=search_first_avp(traced_user_avp_type, traced_user_avp, &avp_value,
				0);

	if((avp==NULL) && trace_is_off(msg))
	{
		LM_DBG("trace off...\n");
		return;
	}

	if(parse_from_header(msg)==-1 || msg->from==NULL || get_from(msg)==NULL)
	{
		LM_ERR("cannot parse FROM header\n");
		return;
	}

	if(parse_headers(msg, HDR_CALLID_F, 0)!=0)
	{
		LM_ERR("cannot parse call-id\n");
		return;
	}

	if(tmb.register_tmcb(0, t, TMCB_REQUEST_SENT, trace_onreq_out, 0, 0) <=0)
	{
		LM_ERR("can't register trace_onreq_out\n");
		return;
	}
	if(tmb.register_tmcb(0, t, TMCB_RESPONSE_IN, trace_onreply_in, 0, 0) <=0)
	{
		LM_ERR("can't register trace_onreply_in\n");
		return;
	}

	if(tmb.register_tmcb(0, t, TMCB_RESPONSE_SENT, trace_onreply_out, 0, 0)<=0)
	{
		LM_ERR("can't register trace_onreply_out\n");
		return;
	}
}

static void trace_onreq_out(struct cell* t, int type, struct tmcb_params *ps)
{
	struct _siptrace_data sto;
	sip_msg_t *msg;
	struct ip_addr to_ip;
	int len;
	struct dest_info *dst;

	if(t==NULL || ps==NULL) {
		LM_DBG("very weird\n");
		return;
	}

	if(ps->flags&TMCB_RETR_F) {
		LM_DBG("retransmission\n");
		return;
	}
	msg=ps->req;
	if(msg==NULL) {
		/* check if it is outgoing cancel, t is INVITE
		 * and send_buf starts with CANCEL */
		if(is_invite(t) && ps->send_buf.len>7
				&& strncmp(ps->send_buf.s, "CANCEL ", 7)==0) {
			msg = t->uas.request;
			if(msg==NULL) {
				LM_DBG("no uas msg for INVITE transaction\n");
				return;
			} else {
				LM_DBG("recording CANCEL based on INVITE transaction\n");
			}
		} else {
			LM_DBG("no uas msg, local transaction\n");
			return;
		}
	}
	memset(&sto, 0, sizeof(struct _siptrace_data));

	if(traced_user_avp.n!=0)
		sto.avp=search_first_avp(traced_user_avp_type, traced_user_avp,
				&sto.avp_value, &sto.state);

	if((sto.avp==NULL) && trace_is_off(msg) ) {
		LM_DBG("trace off...\n");
		return;
	}

	if(sip_trace_prepare(msg)<0)
		return;

	if(ps->send_buf.len>0) {
		sto.body = ps->send_buf;
	} else {
		sto.body.s   = "No request buffer";
		sto.body.len = sizeof("No request buffer")-1;
	}

	sto.callid = msg->callid->body;

	if(ps->send_buf.len>10) {
		sto.method.s = ps->send_buf.s;
		sto.method.len = 0;
		while(sto.method.len<ps->send_buf.len) {
			if(ps->send_buf.s[sto.method.len]==' ')
				break;
			sto.method.len++;
		}
		if(sto.method.len==ps->send_buf.len)
			sto.method.len = 10;
	} else {
		sto.method = t->method;
	}

	sto.status.s = "";
	sto.status.len = 0;

	memset(&to_ip, 0, sizeof(struct ip_addr));
	dst = ps->dst;

	if (trace_local_ip.s && trace_local_ip.len > 0) {
		sto.fromip = trace_local_ip;
	} else {
		if(dst==0 || dst->send_sock==0 || dst->send_sock->sock_str.s==0) {
			siptrace_copy_proto(msg->rcv.proto, sto.fromip_buff);
			strcat(sto.fromip_buff, ip_addr2a(&msg->rcv.dst_ip));
			strcat(sto.fromip_buff,":");
			strcat(sto.fromip_buff, int2str(msg->rcv.dst_port, NULL));
			sto.fromip.s = sto.fromip_buff;
			sto.fromip.len = strlen(sto.fromip_buff);
		} else {
			sto.fromip = dst->send_sock->sock_str;
		}
	}

	if(dst==0) {
		sto.toip.s = "any:255.255.255.255";
		sto.toip.len = 19;
	} else {
		su2ip_addr(&to_ip, &dst->to);
		siptrace_copy_proto(dst->proto, sto.toip_buff);
		strcat(sto.toip_buff, ip_addr2a(&to_ip));
		strcat(sto.toip_buff, ":");
		strcat(sto.toip_buff,
				int2str((unsigned long)su_getport(&dst->to), &len));
		sto.toip.s = sto.toip_buff;
		sto.toip.len = strlen(sto.toip_buff);
	}

	sto.dir = "out";

	sto.fromtag = get_from(msg)->tag_value;
	sto.totag = get_to(msg)->tag_value;

#ifdef STATISTICS
	sto.stat = siptrace_req;
#endif

	sip_trace_store(&sto, NULL);
	return;
}

static void trace_onreply_in(struct cell* t, int type, struct tmcb_params *ps)
{
	struct _siptrace_data sto;
	sip_msg_t *msg;
	sip_msg_t *req;
	char statusbuf[8];

	if(t==NULL || t->uas.request==0 || ps==NULL) {
		LM_DBG("no uas request, local transaction\n");
		return;
	}

	req = ps->req;
	msg = ps->rpl;
	if(msg==NULL || req==NULL) {
		LM_DBG("no reply\n");
		return;
	}
	memset(&sto, 0, sizeof(struct _siptrace_data));

	if(traced_user_avp.n!=0)
		sto.avp=search_first_avp(traced_user_avp_type, traced_user_avp,
				&sto.avp_value, &sto.state);

	if((sto.avp==NULL) &&  trace_is_off(req)) {
		LM_DBG("trace off...\n");
		return;
	}

	if(sip_trace_prepare(msg)<0)
		return;

	sto.body.s = msg->buf;
	sto.body.len = msg->len;

	sto.callid = msg->callid->body;

	sto.method = get_cseq(msg)->method;

	strcpy(statusbuf, int2str(ps->code, &sto.status.len));
	sto.status.s = statusbuf;

	siptrace_copy_proto(msg->rcv.proto, sto.fromip_buff);
	strcat(sto.fromip_buff, ip_addr2a(&msg->rcv.src_ip));
	strcat(sto.fromip_buff,":");
	strcat(sto.fromip_buff, int2str(msg->rcv.src_port, NULL));
	sto.fromip.s = sto.fromip_buff;
	sto.fromip.len = strlen(sto.fromip_buff);

	if(trace_local_ip.s && trace_local_ip.len > 0) {
		sto.toip = trace_local_ip;
	} else {
		siptrace_copy_proto(msg->rcv.proto, sto.toip_buff);
		strcat(sto.toip_buff, ip_addr2a(&msg->rcv.dst_ip));
		strcat(sto.toip_buff,":");
		strcat(sto.toip_buff, int2str(msg->rcv.dst_port, NULL));
		sto.toip.s = sto.toip_buff;
		sto.toip.len = strlen(sto.toip_buff);
	}

	sto.dir = "in";

	sto.fromtag = get_from(msg)->tag_value;
	sto.totag = get_to(msg)->tag_value;
#ifdef STATISTICS
	sto.stat = siptrace_rpl;
#endif

	sip_trace_store(&sto, NULL);
	return;
}

static void trace_onreply_out(struct cell* t, int type, struct tmcb_params *ps)
{
	struct _siptrace_data sto;
	int faked = 0;
	struct sip_msg* msg;
	struct sip_msg* req;
	struct ip_addr to_ip;
	int len;
	char statusbuf[8];
	struct dest_info *dst;

	if (t==NULL || t->uas.request==0 || ps==NULL) {
		LM_DBG("no uas request, local transaction\n");
		return;
	}

	if(ps->flags&TMCB_RETR_F) {
		LM_DBG("retransmission\n");
		return;
	}
	memset(&sto, 0, sizeof(struct _siptrace_data));
	if(traced_user_avp.n!=0)
		sto.avp=search_first_avp(traced_user_avp_type, traced_user_avp,
				&sto.avp_value, &sto.state);

	if((sto.avp==NULL) &&  trace_is_off(t->uas.request)) {
		LM_DBG("trace off...\n");
		return;
	}

	req = ps->req;
	msg = ps->rpl;
	if(msg==NULL || msg==FAKED_REPLY) {
		msg = t->uas.request;
		faked = 1;
	}

	if(sip_trace_prepare(msg)<0)
		return;

	if(faked==0) {
		if(ps->send_buf.len>0) {
			sto.body = ps->send_buf;
		} else if(t->uas.response.buffer!=NULL) {
			sto.body.s = t->uas.response.buffer;
			sto.body.len = t->uas.response.buffer_len;
		} else if(msg->len>0) {
			sto.body.s = msg->buf;
			sto.body.len = msg->len;
		} else {
			sto.body.s = "No reply buffer";
			sto.body.len = sizeof("No reply buffer")-1;
		}
	} else {
		if(ps->send_buf.len>0) {
			sto.body = ps->send_buf;
		} else if(t->uas.response.buffer!=NULL) {
			sto.body.s = t->uas.response.buffer;
			sto.body.len = t->uas.response.buffer_len;
		} else {
			sto.body.s = "No reply buffer";
			sto.body.len = sizeof("No reply buffer")-1;
		}
	}

	sto.callid = msg->callid->body;
	sto.method = get_cseq(msg)->method;

	if(trace_local_ip.s && trace_local_ip.len > 0) {
		sto.fromip = trace_local_ip;
	} else {
		siptrace_copy_proto(msg->rcv.proto, sto.fromip_buff);
		strcat(sto.fromip_buff, ip_addr2a(&req->rcv.dst_ip));
		strcat(sto.fromip_buff,":");
		strcat(sto.fromip_buff, int2str(req->rcv.dst_port, NULL));
		sto.fromip.s = sto.fromip_buff;
		sto.fromip.len = strlen(sto.fromip_buff);
	}

	strcpy(statusbuf, int2str(ps->code, &sto.status.len));
	sto.status.s = statusbuf;

	memset(&to_ip, 0, sizeof(struct ip_addr));
	dst = ps->dst;
	if(dst==0) {
		sto.toip.s = "any:255.255.255.255";
		sto.toip.len = 19;
	} else {
		su2ip_addr(&to_ip, &dst->to);
		siptrace_copy_proto(dst->proto, sto.toip_buff);
		strcat(sto.toip_buff, ip_addr2a(&to_ip));
		strcat(sto.toip_buff, ":");
		strcat(sto.toip_buff,
				int2str((unsigned long)su_getport(&dst->to), &len));
		sto.toip.s = sto.toip_buff;
		sto.toip.len = strlen(sto.toip_buff);
	}

	sto.dir = "out";
	sto.fromtag = get_from(msg)->tag_value;
	sto.totag = get_to(msg)->tag_value;

#ifdef STATISTICS
	sto.stat = siptrace_rpl;
#endif

	sip_trace_store(&sto, NULL);
	return;
}

static void trace_sl_ack_in(sl_cbp_t *slcbp)
{
	sip_msg_t *req;
	LM_DBG("storing ack...\n");
	req = slcbp->req;
	sip_trace(req, 0, 0);
}

static void trace_sl_onreply_out(sl_cbp_t *slcbp)
{
	sip_msg_t *req;
	struct _siptrace_data sto;
	struct sip_msg* msg;
	struct ip_addr to_ip;
	int len;
	char statusbuf[5];

	if(slcbp==NULL || slcbp->req==NULL)
	{
		LM_ERR("bad parameters\n");
		return;
	}
	req = slcbp->req;

	memset(&sto, 0, sizeof(struct _siptrace_data));
	if(traced_user_avp.n!=0)
		sto.avp=search_first_avp(traced_user_avp_type, traced_user_avp,
				&sto.avp_value, &sto.state);

	if((sto.avp==NULL) && trace_is_off(req)) {
		LM_DBG("trace off...\n");
		return;
	}

	msg = req;

	if(sip_trace_prepare(msg)<0)
		return;

	sto.body.s = (slcbp->reply)?slcbp->reply->s:"";
	sto.body.len = (slcbp->reply)?slcbp->reply->len:0;

	sto.callid = msg->callid->body;
	sto.method = msg->first_line.u.request.method;

	if(trace_local_ip.len > 0) {
		sto.fromip = trace_local_ip;
	} else {
		siptrace_copy_proto(msg->rcv.proto, sto.fromip_buff);
		strcat(sto.fromip_buff, ip_addr2a(&req->rcv.dst_ip));
		strcat(sto.fromip_buff,":");
		strcat(sto.fromip_buff, int2str(req->rcv.dst_port, NULL));
		sto.fromip.s = sto.fromip_buff;
		sto.fromip.len = strlen(sto.fromip_buff);
	}

	strcpy(statusbuf, int2str(slcbp->code, &sto.status.len));
	sto.status.s = statusbuf;

	memset(&to_ip, 0, sizeof(struct ip_addr));
	if(slcbp->dst==0)
	{
		sto.toip.s = "any:255.255.255.255";
		sto.toip.len = 19;
	} else {
		su2ip_addr(&to_ip, &slcbp->dst->to);
		siptrace_copy_proto(req->rcv.proto, sto.toip_buff);
		strcat(sto.toip_buff, ip_addr2a(&to_ip));
		strcat(sto.toip_buff, ":");
		strcat(sto.toip_buff,
				int2str((unsigned long)su_getport(&slcbp->dst->to), &len));
		sto.toip.s = sto.toip_buff;
		sto.toip.len = strlen(sto.toip_buff);
	}

	sto.dir = "out";
	sto.fromtag = get_from(msg)->tag_value;
	sto.totag = get_to(msg)->tag_value;

#ifdef STATISTICS
	sto.stat = siptrace_rpl;
#endif

	sip_trace_store(&sto, NULL);
	return;
}


/*! \brief
 * MI Sip_trace command
 *
 * MI command format:
 * name: sip_trace
 * attribute: name=none, value=[on|off]
 */
static struct mi_root* sip_trace_mi(struct mi_root* cmd_tree, void* param )
{
	struct mi_node* node;

	struct mi_node *rpl; 
	struct mi_root *rpl_tree ; 

	node = cmd_tree->node.kids;
	if(node == NULL) {
		rpl_tree = init_mi_tree( 200, MI_SSTR(MI_OK));
		if (rpl_tree == 0)
			return 0;
		rpl = &rpl_tree->node;

		if (*trace_on_flag == 0 ) {
			node = add_mi_node_child(rpl,0,0,0,MI_SSTR("off"));
		} else if (*trace_on_flag == 1) {
			node = add_mi_node_child(rpl,0,0,0,MI_SSTR("on"));
		}
		return rpl_tree ;
	}
	if(trace_on_flag==NULL)
		return init_mi_tree( 500, MI_SSTR(MI_INTERNAL_ERR));

	if ( node->value.len==2 && (node->value.s[0]=='o'
				|| node->value.s[0]=='O') &&
			(node->value.s[1]=='n'|| node->value.s[1]=='N')) {
		*trace_on_flag = 1;
		return init_mi_tree( 200, MI_SSTR(MI_OK));
	} else if ( node->value.len==3 && (node->value.s[0]=='o'
				|| node->value.s[0]=='O')
			&& (node->value.s[1]=='f'|| node->value.s[1]=='F')
			&& (node->value.s[2]=='f'|| node->value.s[2]=='F')) {
		*trace_on_flag = 0;
		return init_mi_tree( 200, MI_SSTR(MI_OK));
	} else {
		return init_mi_tree( 400, MI_SSTR(MI_BAD_PARM));
	}
}

static size_t write_data_func(void *ptr, size_t size, size_t nmemb, void *stream)
{
	int s = size * nmemb;
	if (s != 0) {
		if (dstr_append((dstring_t*)stream, ptr, s) != 0) {
			LM_ERR("can't append %d bytes into data buffer\n", s);
			return 0;
		}
	}
	return s;
}

int curl_post(const char* url,char* buf,int size,dstring_t* resultdata)
{

	CURLcode res = -1;
	static CURL *handle = NULL;
	
	char *auth = NULL;
	//char* resultstr = NULL;
	int i;
	long auth_methods;
	
	if (!url) {
		LM_ERR("BUG: no uri given\n");
		return -1;
	}
	if (!buf) {
		LM_ERR("BUG: no buf given\n");
		return -1;
	}

	i = 0;
#ifdef HTTP_AUTH
	i +=strlen("test");
	i += strlen("test");
#endif
	if (i > 0) {
		/* do authentication */
		auth = (char *)cds_malloc(i + 2);
		if (!auth) return -1;
		sprintf(auth, "%s:%s", "wrt","wrt12");
	}

	auth_methods = CURLAUTH_BASIC | CURLAUTH_DIGEST;
	if (!handle) handle = curl_easy_init(); 
	if (handle) {
		struct curl_slist *chunk = NULL;
		//dstr_init(&data,size);
		//dstr_append(&data,buf,size);
		//curl_easy_setopt(handle, CURLOPT_READFUNCTION, read_callback);

		/* enable uploading */
	//	curl_easy_setopt(handle, CURLOPT_UPLOAD, 1L);

		/* HTTP PUT please */
		curl_easy_setopt(handle, CURLOPT_WRITEFUNCTION, write_data_func);
		curl_easy_setopt(handle, CURLOPT_WRITEDATA, resultdata);

		curl_easy_setopt(handle, CURLOPT_POSTFIELDS, buf);
		curl_easy_setopt(handle,CURLOPT_POSTFIELDSIZE,size);

		/* specify target URL, and note that this URL should include a file
		name, not only a directory */
		curl_easy_setopt(handle, CURLOPT_URL, url);

		/* now specify which file to upload */
		//curl_easy_setopt(handle, CURLOPT_READDATA, &data);

		
		
		chunk = curl_slist_append(chunk, "content-Type: json/sip");

		chunk = curl_slist_append(chunk, "content-Encoding: UTF-8");
		curl_easy_setopt(handle,CURLOPT_HTTPHEADER,chunk);
#ifdef HTTP_AUTH
		/* auth */
		curl_easy_setopt(handle, CURLOPT_HTTPAUTH, auth_methods); /* TODO possibility of selection */
		curl_easy_setopt(handle, CURLOPT_NETRC, CURL_NETRC_IGNORED);
		curl_easy_setopt(handle, CURLOPT_USERPWD, auth);
#endif
		/* SSL */
		//if (params) {
		//	if (params->enable_unverified_ssl_peer) {
		//		curl_easy_setopt(handle, CURLOPT_SSL_VERIFYPEER, 0);
		//		curl_easy_setopt(handle, CURLOPT_SSL_VERIFYHOST, 0);
			//}
		//}

		/* provide the size of the upload, we specicially typecast the value
		to curl_off_t since we must be sure to use the correct data size */
		//curl_easy_setopt(handle, CURLOPT_INFILESIZE_LARGE,
		//	(curl_off_t)size);

		/* Now run off and do what you've been told! */
		res = curl_easy_perform(handle);

		/* always cleanup */
		/*curl_easy_cleanup(handle);*/
	}
	

	
	if (auth) cds_free(auth);
	return res;
}


static int trace_send_duplicate(char *buf, int len,struct dest_info *dst2)
{

	char uri[256];
	str_t res;
	dstring_t result;
	dstr_init(&result,4096);
	memset(uri,0,256);
	memcpy(uri,dup_uri_str.s,dup_uri_str.len);
	LM_DBG("HTTP = %.*s\n",dup_uri_str.len,dup_uri_str.s);
	curl_post(uri,buf,len,&result);
	dstr_get_str(&result,&res);
	
	//LM_MSG("result:%.*s \n",res.len,res.s);
	LM_INFO("trace_send_duplicate......result = %.*s\n",res.len,res.s);
	struct dest_info dst;
	struct proxy_l * p = NULL;

	if(buf==NULL || len <= 0)
		return -1;

	if(dup_uri_str.s==0 || dup_uri==NULL)
		return 0;

	init_dest_info(&dst);
	/* create a temporary proxy*/
	dst.proto = PROTO_UDP;
	p=mk_proxy(&dup_uri->host, (dup_uri->port_no)?dup_uri->port_no:SIP_PORT,
			dst.proto);
	if (p==0)
	{
		LM_ERR("bad host name in uri\n");
		return -1;
	}

	if (!dst2){
	    init_dest_info(&dst);
	    /* create a temporary proxy*/
	    dst.proto = PROTO_UDP;
	    p=mk_proxy(&dup_uri->host, (dup_uri->port_no)?dup_uri->port_no:SIP_PORT,
	             dst.proto);
	    if (p==0){
	        LM_ERR("bad host name in uri\n");
	        return -1;
	    }
	    hostent2su(&dst.to, &p->host, p->addr_idx, (p->port)?p->port:SIP_PORT);

	    dst.send_sock=get_send_socket(0, &dst.to, dst.proto);
	    if (dst.send_sock==0){
	        LM_ERR("can't forward to af %d, proto %d no corresponding"
	                " listening socket\n", dst.to.s.sa_family, dst.proto);
	        goto error;
	    }
	}

	if (msg_send((dst2)?dst2:&dst, buf, len)<0)
	{
		LM_ERR("cannot send duplicate message\n");
		goto error;
	}

	if (p){
	    free_proxy(p); /* frees only p content, not p itself */
	    pkg_free(p);
	}
	return 0;
error:
    if (p){
	free_proxy(p); /* frees only p content, not p itself */
	pkg_free(p);
    }
	return -1;
}

static int trace_send_hep_duplicate(str *body, str *from, str *to, struct dest_info * dst2)
{
	struct dest_info dst;
	struct socket_info *si;
	struct dest_info* dst_fin = NULL;
	struct proxy_l * p=NULL /* make gcc happy */;
	void* buffer = NULL;
	union sockaddr_union from_su;
	union sockaddr_union to_su;
	unsigned int len, buflen, proto;
	struct hep_hdr hdr;
	struct hep_iphdr hep_ipheader;
	struct hep_timehdr hep_time;
	struct timeval tvb;
	struct timezone tz;
	                 
	struct hep_ip6hdr hep_ip6header;

	if(body->s==NULL || body->len <= 0)
		return -1;

	if(dup_uri_str.s==0 || dup_uri==NULL)
		return 0;


        gettimeofday( &tvb, &tz );
        

	/* message length */
	len = body->len 
		+ sizeof(struct hep_ip6hdr)
		+ sizeof(struct hep_hdr) + sizeof(struct hep_timehdr);;


	/* The packet is too big for us */
	if (unlikely(len>BUF_SIZE)){
		goto error;
	}

	/* Convert proto:ip:port to sockaddress union SRC IP */
	if (pipport2su(from->s, &from_su, &proto)==-1 || (pipport2su(to->s, &to_su, &proto)==-1))
		goto error;

	/* check if from and to are in the same family*/
	if(from_su.s.sa_family != to_su.s.sa_family) {
		LOG(L_ERR, "ERROR: trace_send_hep_duplicate: interworking detected ?\n");
		goto error;
	}

    if (!dst2){
	init_dest_info(&dst);
	/* create a temporary proxy*/
	dst.proto = PROTO_UDP;
	p=mk_proxy(&dup_uri->host, (dup_uri->port_no)?dup_uri->port_no:SIP_PORT,
			dst.proto);
	if (p==0)
	{
		LM_ERR("bad host name in uri\n");
		goto error;
	}

	hostent2su(&dst.to, &p->host, p->addr_idx, (p->port)?p->port:SIP_PORT);
	LM_DBG("setting up the socket_info\n");
	dst_fin = &dst;
    } else {
        dst_fin = dst2;
    }

    if (force_send_sock_str.s) {
        LM_DBG("force_send_sock activated, grep for the sock_info\n");
        si = grep_sock_info(&force_send_sock_uri->host,
                (force_send_sock_uri->port_no)?force_send_sock_uri->port_no:SIP_PORT,
                PROTO_UDP);
        if (!si) {
             LM_WARN("cannot grep socket info\n");
        } else {
            LM_DBG("found socket while grep: [%.*s] [%.*s]\n", si->name.len, si->name.s, si->address_str.len, si->address_str.s);
            dst_fin->send_sock = si;
        }
    }

    if (dst_fin->send_sock == 0) {
        dst_fin->send_sock=get_send_socket(0, &dst_fin->to, dst_fin->proto);
        if (dst_fin->send_sock == 0) {
            LM_ERR("can't forward to af %d, proto %d no corresponding"
                    " listening socket\n", dst_fin->to.s.sa_family, dst_fin->proto);
            goto error;
        }
    }

	/* Version && proto && length */
	hdr.hp_l = sizeof(struct hep_hdr);
	hdr.hp_v = hep_version;
	hdr.hp_p = proto;

	/* AND the last */
	if (from_su.s.sa_family==AF_INET){
		/* prepare the hep headers */

		hdr.hp_f = AF_INET;
		hdr.hp_sport = htons(from_su.sin.sin_port);
		hdr.hp_dport = htons(to_su.sin.sin_port);

		hep_ipheader.hp_src = from_su.sin.sin_addr;
		hep_ipheader.hp_dst = to_su.sin.sin_addr;

		len = sizeof(struct hep_iphdr);
	}
	else if (from_su.s.sa_family==AF_INET6){
		/* prepare the hep6 headers */

		hdr.hp_f = AF_INET6;

		hdr.hp_sport = htons(from_su.sin6.sin6_port);
		hdr.hp_dport = htons(to_su.sin6.sin6_port);

		hep_ip6header.hp6_src = from_su.sin6.sin6_addr;
		hep_ip6header.hp6_dst = to_su.sin6.sin6_addr;

		len = sizeof(struct hep_ip6hdr);
	}
	else {
		LOG(L_ERR, "ERROR: trace_send_hep_duplicate: Unsupported protocol family\n");
		goto error;;
	}

	hdr.hp_l +=len;
	if (hep_version == 2){
		len += sizeof(struct hep_timehdr);
	}
	len += sizeof(struct hep_hdr) + body->len;
	buffer = (void *)pkg_malloc(len+1);
	if (buffer==0){
		LOG(L_ERR, "ERROR: trace_send_hep_duplicate: out of memory\n");
		goto error;
	}

	/* Copy job */
	memset(buffer, '\0', len+1);

	/* copy hep_hdr */
	memcpy((void*)buffer, &hdr, sizeof(struct hep_hdr));
	buflen = sizeof(struct hep_hdr);

	/* hep_ip_hdr */
	if(from_su.s.sa_family==AF_INET) {
		memcpy((void*)buffer + buflen, &hep_ipheader, sizeof(struct hep_iphdr));
		buflen += sizeof(struct hep_iphdr);
	}
	else {
		memcpy((void*)buffer+buflen, &hep_ip6header, sizeof(struct hep_ip6hdr));
		buflen += sizeof(struct hep_ip6hdr);
	}

	if(hep_version == 2) {

                hep_time.tv_sec = tvb.tv_sec;
                hep_time.tv_usec = tvb.tv_usec;
                hep_time.captid = hep_capture_id;

                memcpy((void*)buffer+buflen, &hep_time, sizeof(struct hep_timehdr));
                buflen += sizeof(struct hep_timehdr);
        }

	/* PAYLOAD */
	memcpy((void*)(buffer + buflen) , (void*)body->s, body->len);
	buflen +=body->len;

	if (msg_send(dst_fin, buffer, buflen)<0)
	{
		LM_ERR("cannot send hep duplicate message\n");
		goto error;
	}

    if (p) {
	free_proxy(p); /* frees only p content, not p itself */
	pkg_free(p);
    }
	pkg_free(buffer);
	return 0;
error:
	if(p)
	{
		free_proxy(p); /* frees only p content, not p itself */
		pkg_free(p);
	}
	if(buffer) pkg_free(buffer);
	return -1;
}

/*!
 * \brief Convert a STR [proto:]ip[:port] into socket address.
 * [proto:]ip[:port]
 * \param pipport (udp:127.0.0.1:5060 or tcp:2001:0DB8:AC10:FE01:5060)
 * \param tmp_su target structure
 * \param proto uint protocol type
 * \return success / unsuccess
 */
static int pipport2su (char *pipport, union sockaddr_union *tmp_su, unsigned int *proto)
{
	unsigned int port_no, cutlen = 4;
	struct ip_addr *ip;
	char *p, *host_s;
	str port_str, host_uri;
	unsigned len = 0;
	char tmp_piport[256];

	/*parse protocol */
	if(strncmp(pipport, "udp:",4) == 0) *proto = IPPROTO_UDP;
	else if(strncmp(pipport, "tcp:",4) == 0) *proto = IPPROTO_TCP;
	else if(strncmp(pipport, "tls:",4) == 0) *proto = IPPROTO_IDP; /* fake proto type */
	else if(strncmp(pipport, "ws:",3) == 0) *proto = IPPROTO_IDP; /* fake proto type */
	else if(strncmp(pipport, "wss:",4) == 0) *proto = IPPROTO_IDP; /* fake proto type */
#ifdef USE_SCTP
	else if(strncmp(pipport, "sctp:",5) == 0) cutlen = 5, *proto = IPPROTO_SCTP;
#endif
	else if(strncmp(pipport, "any:",4) == 0) *proto = IPPROTO_UDP;
	else {
		LM_ERR("bad protocol %s\n", pipport);
		return -1;
	}
	
	if((len = strlen(pipport)) >= 256) {
		LM_ERR("too big pipport\n");
		goto error;
	}

	/* our tmp string */
        strncpy(tmp_piport, pipport, len+1);

	len = 0;

	/*separate proto and host */
	p = tmp_piport+cutlen;
	if( (*(p)) == '\0') {
		LM_ERR("malformed ip address\n");
		goto error;
	}
	host_s=p;

	if( (p = strrchr(p+1, ':')) == 0 ) {
		LM_DBG("no port specified\n");
		port_no = 0;
	}
	else {
        	/*the address contains a port number*/
        	*p = '\0';
        	p++;
        	port_str.s = p;
        	port_str.len = strlen(p);
        	LM_DBG("the port string is %s\n", p);
        	if(str2int(&port_str, &port_no) != 0 ) {
	        	LM_ERR("there is not a valid number port\n");
	        	goto error;
        	}
        	*p = '\0';
        }
        
	/* now IPv6 address has no brakets. It should be fixed! */
	if (host_s[0] == '[') {
		len = strlen(host_s + 1) - 1;
		if(host_s[len+1] != ']') {
			LM_ERR("bracket not closed\n");
			goto error;
		}
		memmove(host_s, host_s + 1, len);
		host_s[len] = '\0';
	}

	host_uri.s = host_s;
	host_uri.len = strlen(host_s);

	/* check if it's an ip address */
	if (((ip=str2ip(&host_uri))!=0)
			|| ((ip=str2ip6(&host_uri))!=0)
	   ) {
		ip_addr2su(tmp_su, ip, ntohs(port_no));
		return 0;	

	}

error:
	return -1;
}

static void siptrace_rpc_status (rpc_t* rpc, void* c) {
	str status = {0, 0};

	if (rpc->scan(c, "S", &status) < 1) {
		rpc->fault(c, 500, "Not enough parameters (on, off or check)");
		return;
	}

	if(trace_on_flag==NULL) {
		rpc->fault(c, 500, "Internal error");
		return;
	}

	if (strncasecmp(status.s, "on", strlen("on")) == 0) {
		*trace_on_flag = 1;
		rpc->rpl_printf(c, "Enabled");
		return;
	}
	if (strncasecmp(status.s, "off", strlen("off")) == 0) {
		*trace_on_flag = 0;
		rpc->rpl_printf(c, "Disabled");
		return;
	}
	if (strncasecmp(status.s, "check", strlen("check")) == 0) {
		rpc->rpl_printf(c, *trace_on_flag ? "Enabled" : "Disabled");
		return;
	} 
	rpc->fault(c, 500, "Bad parameter (on, off or check)");
	return;
}

static const char* siptrace_status_doc[2] = {
        "Get status or turn on/off siptrace. Parameters: on, off or check.",
        0
};

rpc_export_t siptrace_rpc[] = {
	{"siptrace.status", siptrace_rpc_status, siptrace_status_doc, 0},
	{0, 0, 0, 0}
};

static int siptrace_init_rpc(void)
{
	if (rpc_register_array(siptrace_rpc)!=0)
	{
		LM_ERR("failed to register RPC commands\n");
		return -1;
	}
	return 0;
}

