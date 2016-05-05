/*************************************************************************
  Copyright (C), 2016-2017, Everyoo Co., Ltd.
  FileName: SipService.cpp
  Author: cuishiyang       Version : 0.1.0          Date: 2016/02/26
  Description:   process sip logical function cpp file      
  Version:         0.1.0
  History:         
      <author>           <time>                <version >          <desc>
      cuishiyang          2016/02/26             0.1.0        create this file 
*************************************************************************/
#include "SipService.h"
      
using namespace std;
using namespace sql;

string query_sql;
str str_userid;
str str_gatewayid;
str str_msg;
extern Connection *conn;

/*Function:        Evy_OperateMysql
  Description:     operate mysql
  Input:           sql:  sql statement
  Output:          none
  Return:          0:  Success
  				   -1: Failed
  Others:          
*/
int SipService::Evy_OperateMysql(string sql){
	int ret_val = 0;

	Statement* stat = conn->createStatement();
	stat->execute("set names 'gbk'");
	
	ResultSet *res;
	res = stat->executeQuery(sql);
	if (res == NULL) {
		cout << "query failed" << endl;
		ret_val = -1;
	} else {
		cout << "query success" << endl;
	}
	return ret_val;
}


/*Function:        Evy_userAuthentication
  Description:     user authentication
  Input:           user_id:the unique identifier of the use
				   gateway_Id:the unique identifier of the gateway
  Output:          none
  Return:          0:  normal
				   -2: userAuthentication failed
  Others:          
*/
int SipService::Evy_userAuthentication(str user_Id,str gateway_Id) {
	int retAuth;
	char query[200];
	
    /*user  authentication*/	
	sprintf(query,"SELECT id FROM user_sip WHERE user_id = %s and sn_code = %s",user_Id.s,gateway_Id.s);
	query_sql = query;
	printf("query_sql = %s\n",query_sql.c_str());

	retAuth = SipService::Evy_OperateMysql(query_sql);
	printf("retAuth = %d\n",retAuth);
	if (retAuth == 0) {
		printf("Evy_userAuthentication:userAuthentication Success!!!\n");
	}else{
		printf("Evy_userAuthentication:userAuthentication Failed!!!\n");
		goto err_server;
	}
	
	return 0;

err_server:
	return -2;
}


/*Function:        Evy_control
  Description:     parse control cmd
  Input:           struct json_object* json_data
  Output:          none
  Return:          0:normal
				   -1:a field value is empty
				   -2:userAuthentication failed
  Others:          type = 1
*/
int SipService::Evy_control(struct json_object* json_data){
	
	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(!json_object_object_get_ex(json_data, "userid", &json_userid)) {
		printf("Error:json_userid = NULL\n");
		return -3;
	}	
	if(!json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid)) {
		printf("Error:json_gatewayid = NULL\n");
		return -3;
	}
	if(!json_object_object_get_ex(json_data, "msg", &json_msg)) {
		printf("Error:json_msg = NULL\n");
		return -3;
	}
	/*将json数据转为字符型*/
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	if (SipService::Evy_userAuthentication(str_userid,str_gatewayid) == -2) {
		printf("Evy_control：userAuthentication Error!!!!!!\n");
		return -2;
	}else {
		printf("Evy_control:userAuthentication Success!!!\n");
	}
	
	return 1;
}


/*Function:        Evy_scene
  Description:     parse scene cmd
  Input:           struct json_object* json_data
  Output:          none
  Return:          0:normal
				   -1:a field value is empty
				   -2:userAuthentication failed
  Others:          type = 2
*/
int SipService::Evy_scene(struct json_object* json_data){
	
	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(!json_object_object_get_ex(json_data, "userid", &json_userid)) {
		printf("Error:json_userid = NULL\n");
		return -3;
	}	
	if(!json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid)) {
		printf("Error:json_gatewayid = NULL\n");
		return -3;
	}
	if(!json_object_object_get_ex(json_data, "msg", &json_msg)) {
		printf("Error:json_msg = NULL\n");
		return -3;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (SipService::Evy_userAuthentication(str_userid,str_gatewayid) == -2) {
		printf("Evy_scene：userAuthentication Error!!!!!!\n");
		return -2;
	}else {
		printf("Evy_scene:userAuthentication Success!!!\n");
	}

	return 1;
}


/*Function:        Evy_linkage
  Description:     parse linkage cmd
  Input:           struct json_object* json_data
  Output:          none
  Return:          0:normal
				   -1:a field value is empty
				   -2:userAuthentication failed
  Others:          type = 3
*/
int SipService::Evy_linkage(struct json_object* json_data){
	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(!json_object_object_get_ex(json_data, "userid", &json_userid)) {
		printf("Error:json_userid = NULL\n");
		return -3;
	}	
	if(!json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid)) {
		printf("Error:json_gateway_id = NULL\n");
		return -3;
	}

	if(!json_object_object_get_ex(json_data, "msg", &json_msg)) {
		printf("Error:json_msg = NULL\n");
		return -3;
	}
	
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	printf("Evy_linkage:%s, %d\n",str_gatewayid.s,str_gatewayid.len);
	
	if (SipService::Evy_userAuthentication(str_userid,str_gatewayid) == -2) {
		printf("Evy_linkage:userAuthentication Failed!!!!!!\n");
		return -2;
	}else {
		printf("Evy_linkage:userAuthentication Success!!!\n");
	}
    
	return 1;
}


/*Function:        Evy_timing
  Description:     parse timing cmd
  Input:           struct json_object* json_data
  Output:          none
  Return:          0:normal
				   -1:a field value is empty
				   -2:userAuthentication failed
  Others:          type = 4
*/
int SipService::Evy_timing(struct json_object* json_data){
	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(!json_object_object_get_ex(json_data, "userid", &json_userid)) {
		printf("Error:json_userid = NULL\n");
		return -3;
	}	
	if(!json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid)) {
		printf("Error:json_gatewayid = NULL\n");
		return -3;
	}
	if(!json_object_object_get_ex(json_data, "msg", &json_msg)) {
		printf("Error:json_msg = NULL\n");
		return -3;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (SipService::Evy_userAuthentication(str_userid,str_gatewayid) == -2) {
		printf("Evy_timing：userAuthentication Error!!!!!!\n");
		return -2;
	}else {
		printf("Evy_timing:userAuthentication Success!!!\n");
	}

	return 1;
}


/*Function:        Evy_autoReport
  Description:     parse autoReport cmd
  Input:           struct json_object* json_data
  Output:          none
  Return:          0:normal
				   -1:a field value is empty
				   -2:userAuthentication failed
  Others:          type = 5
*/
int SipService::Evy_autoReport(struct json_object* json_data){
	//int int_version_code;
	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;
	//struct json_object* json_version_code = NULL;

	if(!json_object_object_get_ex(json_data, "userid", &json_userid)) {
		printf("Error:json_userid = NULL\n");
		return -3;
	}	
	if(!json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid)) {
		printf("Error:json_gatewayid = NULL\n");
		return -3;
	}
	if(!json_object_object_get_ex(json_data, "msg", &json_msg)) {
		printf("Error:json_msg = NULL\n");
		return -3;
	}
	#if 0
	if(！json_object_object_get_ex(json_data, "version_code", &json_version_code)) {
		printf("Error:json_version_code = NULL\n");
		return -1;
	}
	#endif
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	//int_version_code = json_object_get_int(json_version_code);
	
	if (SipService::Evy_userAuthentication(str_userid,str_gatewayid) == -2) {
		printf("Evy_autoReport：userAuthentication Error!!!!!!\n");
		return -2;
	}else {
		printf("Evy_autoReport:userAuthentication Success!!!\n");
	}
	#if 0
	if (!Evy_gwVersionCheck(int_version_code)) {
		printf("version_code is old, you need upgrade the version\n");
		return -3;
	}else {
		return 4;
	}
    #endif
	return 1;
}


/*Function:        Evy_deviceManage
  Description:     parse deviceManage cmd
  Input:           struct json_object* json_data
  Output:          none
  Return:          0:normal
				   -1:a field value is empty
				   -2:userAuthentication failed
  Others:          type = 6
*/
int SipService::Evy_deviceManage(struct json_object* json_data){
	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(!json_object_object_get_ex(json_data, "userid", &json_userid)) {
		printf("Error:json_userid = NULL\n");
		return -3;
	}	
	if(!json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid)) {
		printf("Error:json_gatewayid = NULL\n");
		return -3;
	}
	if(!json_object_object_get_ex(json_data, "msg", &json_msg)) {
		printf("Error:json_msg = NULL\n");
		return -3;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (SipService::Evy_userAuthentication(str_userid,str_gatewayid) == -2) {
		printf("Evy_deviceManage：userAuthentication Error!!!!!!\n");
		return -2;
	}else {
		printf("Evy_deviceManage:userAuthentication Success!!!\n");
	}

	return 1;
}


/*Function:        Evy_add_Internet
  Description:     parse add_Internet cmd
  Input:           struct json_object* json_data
  Output:          none
  Return:          0:normal
				   -1:a field value is empty
				   -2:userAuthentication failed
  Others:          type = 7
*/
int SipService::Evy_add_Internet(struct json_object* json_data){
	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(!json_object_object_get_ex(json_data, "userid", &json_userid)) {
		printf("Error:json_userid = NULL\n");
		return -3;
	}	
	if(!json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid)) {
		printf("Error:json_gatewayid = NULL\n");
		return -3;
	}
	if(!json_object_object_get_ex(json_data, "msg", &json_msg)) {
		printf("Error:json_msg = NULL\n");
		return -3;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (SipService::Evy_userAuthentication(str_userid,str_gatewayid) == -2) {
		printf("Evy_add_Internet：userAuthentication Error!!!!!!\n");
		return -2;
	}else {
		printf("Evy_add_Internet:userAuthentication Success!!!\n");
	}

	return 1;
}


/*Function:        Evy_remove_Internet
  Description:     parse remove_Internet cmd
  Input:           struct json_object* json_data
  Output:          none
  Return:          0:normal
				   -1:a field value is empty
				   -2:userAuthentication failed
  Others:          type = 8
*/
int SipService::Evy_remove_Internet(struct json_object* json_data){
	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(!json_object_object_get_ex(json_data, "userid", &json_userid)) {
		printf("Error:json_userid = NULL\n");
		return -3;
	}	
	if(!json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid)) {
		printf("Error:json_gatewayid = NULL\n");
		return -3;
	}
	if(!json_object_object_get_ex(json_data, "msg", &json_msg)) {
		printf("Error:json_msg = NULL\n");
		return -3;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (SipService::Evy_userAuthentication(str_userid,str_gatewayid) == -2) {
		printf("Evy_remove_Internet：userAuthentication Error!!!!!!\n");
		return -2;
	}else {
		printf("Evy_remove_Internet:userAuthentication Success!!!\n");
	}

	return 1;
}


/*Function:        Evy_deviceStatus
  Description:     parse deviceStatus cmd
  Input:           struct json_object* json_data
  Output:          none
  Return:          0:normal
				   -1:a field value is empty
				   -2:userAuthentication failed
  Others:          type = 9
*/
int SipService::Evy_deviceStatus(struct json_object* json_data){
	struct json_object* json_gatewayid = NULL;
	struct json_object* json_userid = NULL;
	struct json_object* json_msg = NULL;

	if(!json_object_object_get_ex(json_data, "userid", &json_userid)) {
		printf("Error:json_userid = NULL\n");
		return -3;
	}	
	if(!json_object_object_get_ex(json_data, "gatewayid", &json_gatewayid)) {
		printf("Error:json_gatewayid = NULL\n");
		return -3;
	}
	if(!json_object_object_get_ex(json_data, "msg", &json_msg)) {
		printf("Error:json_msg = NULL\n");
		return -3;
	}
	/*将json数据转为字符型*/
	str_userid.s = (char*) json_object_get_string(json_userid);
	str_userid.len = json_object_get_string_len(json_userid);
	
	str_gatewayid.s = (char*) json_object_get_string(json_gatewayid);
	str_gatewayid.len = json_object_get_string_len(json_gatewayid);
	
	str_msg.s = (char*) json_object_get_string(json_msg);
	str_msg.len = json_object_get_string_len(json_msg);
	
	if (SipService::Evy_userAuthentication(str_userid,str_gatewayid) == -2) {
		printf("Evy_deviceStatus：userAuthentication Error!!!!!!\n");
		return -2;
	}else {
		printf("Evy_deviceStatus:userAuthentication Success!!!\n");
	}

	return 1;
}


/*Function:        Evy_sip_trace_parse_body
  Description:     parse sip cmd
  Input:           length: message length
  				   buf: message content
  Output:          none
  Return:          0:normal
				   -1:a field value is empty
				   -2:userAuthentication failed
  Others:          
*/
int SipService::Evy_sip_trace_parse_body(int length, const char *buf) {
	int ret = 1;
	int message_type = -1;
	struct json_object* json_body = NULL;
	struct json_object* json_type = NULL;

	json_body = json_tokener_parse(buf);
	if(json_body == NULL) {
		cout << "Error:json_body = NULL\n" << endl;
		goto error;
	}

	if(!json_object_object_get_ex(json_body, "type", &json_type)) {
		cout << "Error: not available message!\n" << endl;
		goto error;
	}

	message_type = json_object_get_int(json_type);
	switch(message_type) {
	
	case 0:
		cout << "This is type=3 func" << endl;
		break;
	case 1:
		ret = SipService::Evy_control(json_body);
		break;
	case 2:
		ret = SipService::Evy_scene(json_body);
		break;
	case 3:
		ret = SipService::Evy_linkage(json_body);
		break;
	case 4:
		ret = SipService::Evy_timing(json_body);
		break;
	case 5:
		ret = SipService::Evy_autoReport(json_body);
		break;
	case 6:
	    ret = SipService::Evy_deviceManage(json_body);
		break;
	case 7:
	    ret = SipService::Evy_add_Internet(json_body);
		break;
	case 8:
	    ret = SipService::Evy_remove_Internet(json_body);
		break;
	case 9:
	    ret = SipService::Evy_deviceStatus(json_body);
		break;
	default:
		cout << "Not available type!\n" << endl;
		break;	
	}

	json_object_put(json_body);
	return ret;

error:
	json_object_put(json_body);
	return -3;
}

SipService::SipService() {

}

SipService::~SipService() {

}

