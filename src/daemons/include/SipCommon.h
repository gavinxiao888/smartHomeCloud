/********************************************************************************
  Copyright (C), 2016-2017, Everyoo Co., Ltd.
  File name:      SipCommon.h
  Author: cuishiyang      Version: 0.1.0       Date: 2016/02/26
  Description: sip daemon common configure header file
  History:
    1. Date:
       Author:
       Modification:
    2. ...
********************************************************************************/
#ifndef  __SIP_COMMON_H__
#define  __SIP_COMMON_H__

#include <signal.h>
#include <stdio.h>
#include <string.h>
#include <json-c/json.h>

struct _str {
	char* s;
	int len;
};
typedef struct _str str;

#endif  //__SIP_COMMON_H__
