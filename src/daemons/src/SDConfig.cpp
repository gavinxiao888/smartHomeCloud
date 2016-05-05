/*************************************************************************
  Copyright (C), 2016-2017, Everyoo Co., Ltd.
  FileName: SDConfig.cpp
  Author: wangfei       Version : 0.1.0          Date: 2016/02/26
  Description:     SIP daemon config cpp file      
  Version:         0.1.0
  History:         
      <author>  	<time>   		    <version >   	<desc>
      wangfei            2015/09/01    	    		        create this file 
*************************************************************************/
#include "SDConfig.h"

/*****************************************************************************
  Function:       SDConfig
  Description:    class SDConfig's construct funtion
  Input:          none
  Output:         none
  Return:         none
  Others:
******************************************************************************/
SDConfig::SDConfig() {
    dbIp.clear();
    dbUser.clear();
    dbPasswd.clear();
    dbSchema.clear();

    debug = false;
    verbose = false;
}


/*****************************************************************************
  Function:       ~SDConfig
  Description:    class SDConfig's destroy funtion
  Input:          none
  Output:         none
  Return:         none
  Others:
******************************************************************************/
SDConfig::~SDConfig() {

}


/*****************************************************************************
  Function:       init
  Description:    init the class SDConfig's members
  Input:          vm: variables_map
  Output:         none
  Return:         return flag code, return 0 if it has no error occurs
  Others:
******************************************************************************/
uint32_t SDConfig::init(po::variables_map &vm) {
    dbIp = vm["database_ip"].as<std::string>();
    dbUser= vm["database_user"].as<std::string>();
    dbPasswd= vm["database_passwd"].as<std::string>();
    dbSchema = vm["database_schema"].as<std::string>();
    verbose = vm["verbose"].as<bool>();
    debug = vm["debug"].as<bool>();
    
    return RET_SUCCESS;
}



















