/**************************************************************
  Copyright (C), 2016-2017, Everyoo Co., Ltd.
  File name:      Utils.h
  Author: wangfei      Version: 0.1.0       Date: 2016/02/26
  Description:  sip daemon utilities header file
  History:
    1. Date:
       Author:
       Modification:
    2. ...
**************************************************************/
#ifndef _UTILS_H_
#define _UTILS_H_

#include <string>
#include <vector>

#include <boost/algorithm/string/join.hpp>
#include <boost/filesystem.hpp>
#include <boost/thread/tss.hpp>
#include <boost/unordered_set.hpp>
#include <boost/unordered_map.hpp>
#include <boost/scoped_ptr.hpp>

#include "Common.h"
#include "SDConfig.h"

#define difftimespec__(start, end)      ((end.tv_sec - start.tv_sec) * 1000 + (double)(end.tv_nsec - start.tv_nsec) / 1000000)
#define WALL_TIMER_INIT(id)             struct timespec start__##id##__, end__##id##__
#define WALL_TIMER_START(id)            clock_gettime(CLOCK_MONOTONIC, &start__##id##__)
//#define WALL_TIMER_END(n)               clock_gettime(CLOCK_REALTIME, &end__##id##__)
#define WALL_TIMER_ELAPSED(id)          (clock_gettime(CLOCK_MONOTONIC, &end__##id##__), difftimespec__(start__##id##__, end__##id##__))


/*****************************************************************************
  Function:       verbose_cout
  Description:    display verbose information when program's verbose flag is enbale
  Input:          msg: message need to be displayed
  Output:         none
  Return:         void
  Others:
******************************************************************************/
inline void verbose_cout(const bool flag, const std::string &msg) {   
    if (flag) {
        std::cout << msg << std::endl;
    }
}

/*****************************************************************************
  Function:       debug_cout
  Description:    display debug information when program's debug flag is enbale
  Input:          msg: message need to be displayed
  Output:         none
  Return:         void
  Others:
******************************************************************************/
inline void debug_cout(const bool flag, const std::string &msg) {   
    if (flag) {
        std::cout << msg << std::endl;
    }
}


sql::Connection *createDbConnection(const std::string &host, const std::string &username,
        const std::string &passwd, const std::string &db_name);


#endif    //_UTILS_H_

