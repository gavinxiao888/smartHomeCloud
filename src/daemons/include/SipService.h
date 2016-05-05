/**************************************************************
  Copyright (C), 2016-2017, Everyoo Co., Ltd.
  File name:      SipService.h
  Author: cuishiyang      Version: 0.1.0       Date: 2016/02/26
  Description:  sip service header file
  History:
    1. Date:
       Author:
       Modification:
    2. ...
**************************************************************/
#include "Common.h"
#include "SipCommon.h"
#include "Utils.h"
#include "SDConfig.h"

using namespace std;

class SipService{

	public:
		int Evy_sip_trace_parse_body(int,const char*);
		static int Evy_userAuthentication(str,str);
		static int Evy_OperateMysql(string);

		static int Evy_control(struct json_object*);
		static int Evy_scene(struct json_object*);
		static int Evy_linkage(struct json_object*);
		static int Evy_timing(struct json_object*);
		static int Evy_autoReport(struct json_object*);
		static int Evy_deviceManage(struct json_object*);
		static int Evy_add_Internet(struct json_object*);
		static int Evy_remove_Internet(struct json_object*);
		static int Evy_deviceStatus(struct json_object*);

	public:
		SipService();
		~SipService();
};
