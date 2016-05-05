/*************************************************************************
  Copyright (C), 2016-2017, Everyoo Co., Ltd.
  FileName: Utils.cpp
  Author: wangfei       Version : 0.1.0          Date: 2016/02/26
  Description:     SIP daemon utilities cpp file      
  Version:         0.1.0
  History:         
      <author>          <time>                      <version >          <desc>
      wangfei            2015/09/14                             create this file 
*************************************************************************/
#include "Utils.h"
#include "Common.h"
#include "SDConfig.h"

using namespace std;
using namespace sql;
using boost::scoped_ptr;

Connection *conn;

/*****************************************************************************
    Function:       create_db_connection
    Description:    创建数据库连接，并设置连接超时参数
    Input:          host，数据库ip
 		                username，用户名
                    passwd，密码
                    db_name，数据库名称
    Output:
    Return:         返回sql::Connection *，发生错误时返回0
    Others:
******************************************************************************/
Connection* createDbConnection(const string &host, const string &username, const string &passwd, const string &db_name) {
    try {
        Driver *driver = get_driver_instance();
        conn = driver->connect(host.c_str(), username.c_str(), passwd.c_str());
        conn->setSchema(db_name.c_str());
        conn->setAutoCommit(0);
        boost::scoped_ptr<Statement> stmt(conn->createStatement());
        stmt->execute("SET wait_timeout=8640000"); // 100days
        stmt->execute("SET interactive_timeout=8640000");
        stmt->execute("SET FOREIGN_KEY_CHECKS=0");
        return conn;
    }
    catch (const SQLException &e) {
        cerr << "Error: Create database connection failed: " << e.what() << endl;
    }

    return 0;
}
