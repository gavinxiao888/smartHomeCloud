/**************************************************************
  Copyright (C), 2016-2017, Everyoo. Co., Ltd.
  File name:      SDConfig.h
  Author: cuishiyang      Version: 0.1.0       Date: 2016/02/26
  Description:  SIP daemon config class header file  
  History:       
    1. Date:
       Author:
       Modification:
    2. ...
**************************************************************/
#ifndef _SD_CONFIG_H_
#define _SD_CONFIG_H_

#include <string>
#include <boost/program_options.hpp>

#include "Common.h"

class SDConfig {
	public:
		
		uint32_t    dbPort;                       /******db service port******/
		std::string dbIp;                         /******db ipv4 address******/
		std::string dbUser;                       /******db access user*******/
		std::string dbPasswd;                     /******db user's pwd********/
		std::string dbSchema;                     /******middle db's name*****/
    
		bool          debug;                      /******debug swtich*********/  
		bool          verbose;                    /******verbose swtich*******/

	public:
    
		SDConfig();
		~SDConfig();
		
		uint32_t init(po::variables_map &vm);
};

#endif		//	_SD_CONFIG_H_
