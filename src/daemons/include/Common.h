/********************************************************************************
  Copyright (C), 2016-2017, Everyoo Co., Ltd.
  File name:      Common.h
  Author: wangfei      Version: 0.1.0       Date: 2016/02/26
  Description:  SIP daemon project common config header file
  History:
    1. Date:
       Author:
       Modification:
    2. ...
********************************************************************************/
#ifndef _COMMON_H_
#define _COMMON_H_

#include <stdint.h>
#include <cstdlib>
#include <iostream>
#include <fstream>

#include <boost/program_options.hpp>
#include <boost/filesystem.hpp>
#include <boost/ptr_container/ptr_list.hpp>
#include <boost/format.hpp>
#include <boost/algorithm/string.hpp>
#include <boost/dynamic_bitset.hpp>

//boost time headers
#include <boost/date_time/local_time/local_time.hpp>  
#include <boost/date_time/gregorian/gregorian.hpp>
#include <boost/date_time/posix_time/posix_time.hpp>  

//boost thread headers
#include <boost/thread/thread.hpp>
#include <boost/thread/mutex.hpp>
#include <boost/bind.hpp>
#include <boost/thread/once.hpp>
#include <boost/thread.hpp>

//signal headers
 #include <signal.h>

// mysql cppconn headers
#include <cppconn/driver.h>
#include <cppconn/exception.h>
#include <cppconn/prepared_statement.h>
#include <cppconn/resultset.h>
#include <cppconn/statement.h>
#include <cppconn/warning.h>


namespace po = boost::program_options;

#define	PROJECT_VERSION	0.1.0	/***********PROJECT VERSION MACRO**********/


/****************Return Value Defination********************************************/
#define	RET_SUCCESS	0						    /*******Success return value*****/
#define RET_XML_GENERIC_ERROR   3
#define RET_DB_GENERIC_ERROR    10
#define RET_DB_WRONG_RECORD_COUNT   11
#define RET_DB_INSERT_ERROR   12
#define RET_DUPLICATE_ERROR     20
#define RET_FS_GENERIC_ERROR    40
#define RET_FS_NOT_DIR_ERROR    41
#define	RET_UNKNOWN_ERROR	    255
/****************Return Value Defination End****************************************/


/****************Confi Defination****************************************/
#define   MD_DEBUG
#define   MD_DEFAULT_CONFIG_PATH   "../etc"
#define   MD_DEFAULT_CONFIG_FILE   "sip-daemon.conf"

#endif		//	_COMMON_H_




