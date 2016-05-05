/*************************************************************************
  Copyright (C), 2016-2017, Everyoo Co., Ltd.
  FileName: Server.cpp
  Author: cuishiyang       Version : 0.1.0          Date: 2016/02/26
  Description:    SIP daemon main cpp file      
  Version:         0.1.0
  History:         
      <author>           <time>           <version >    <desc>
      cuishiyang          2016/02/26              0.1.0        create this file 
*************************************************************************/
#include <thrift/concurrency/ThreadManager.h>
#include <thrift/concurrency/PlatformThreadFactory.h>
#include <thrift/protocol/TBinaryProtocol.h>
#include <thrift/server/TSimpleServer.h>
#include <thrift/server/TThreadPoolServer.h>
#include <thrift/server/TThreadedServer.h>
#include <thrift/transport/TServerSocket.h>
#include <thrift/transport/TSocket.h>
#include <thrift/transport/TTransportUtils.h>
#include <thrift/TToString.h>

#include <boost/make_shared.hpp>
#include <boost/thread/tss.hpp>

#include <iostream>
#include <stdexcept>
#include <sstream>
#include <mysql/mysql.h>

#include "CommunicateInterface.h"
#include "Common.h"
#include "SDConfig.h"
#include "Utils.h"
#include "SipService.h"

using namespace std;
using namespace apache::thrift;
using namespace apache::thrift::concurrency;
using namespace apache::thrift::protocol;
using namespace apache::thrift::transport;
using namespace apache::thrift::server;

using namespace service;
using namespace shared;
using namespace std;

namespace po = boost::program_options;

extern int  Evy_sip_trace_parse_body(int,const char*);

SipService sipservice;

class sendMessageHandler : public sendMessageIf {
	public:
		sendMessageHandler() {}
		
		void ping() { cout << "ping()" << endl; }
#if 0 
		int32_t add(const int32_t n1, const int32_t n2) {
			cout << "add(" << n1 << ", " << n2 << ")" << endl;
			return n1 + n2;
		}
#endif
		int32_t message(const std::string& msg) {

			const char *msg_body = msg.c_str();
	  		//char *message = msg.c_str();
			int msg_len = strlen(msg_body);
			cout << "message = , msg_len = " << msg  << msg_len << endl;
			cout << "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~" << endl;
			int ret_msg = sipservice.Evy_sip_trace_parse_body(msg_len,msg_body);
			cout << "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~" << endl;
			return ret_msg;
		}
#if 0
		int32_t calculate(const int32_t logid, const Work& work) {
			cout << "calculate(" << logid << ", " << work << ")" << endl;
			int32_t val;
			
			switch (work.op) {
				case Operation::ADD:
					val = work.num1 + work.num2;
					break;
				case Operation::SUBTRACT:
					val = work.num1 - work.num2;
					break;
				case Operation::MULTIPLY:
					val = work.num1 * work.num2;
					break;
				case Operation::DIVIDE:
					if (work.num2 == 0) {
						InvalidOperation io;
						io.whatOp = work.op;
						io.why = "Cannot divide by 0";
						throw io;
					}
					val = work.num1 / work.num2;
					break;
				default:
					InvalidOperation io;
					io.whatOp = work.op;
					io.why = "Invalid Operation";
					throw io;
			}
    
			SharedStruct ss;
			ss.key = logid;
			ss.value = to_string(val);
			log[logid] = ss;
			return val;
		}
#endif
		void getStruct(SharedStruct& ret, const int32_t logid) {
			cout << "getStruct(" << logid << ")" << endl;
			ret = log[logid];
		}

		//void zip() { cout << "zip()" << endl; }
	protected:
		map<int32_t, SharedStruct> log;
};

/*
  sendMessageIfFactory is code generated.
  sendMessageCloneFactory is useful for getting access to the server side of the
  transport.  It is also useful for making per-connection state.  Without this
  CloneFactory, all connections will end up sharing the same handler instance.
*/
class sendMessageCloneFactory : virtual public sendMessageIfFactory {
	public:
		virtual ~sendMessageCloneFactory() {}
  
		virtual sendMessageIf* getHandler(const ::apache::thrift::TConnectionInfo& connInfo){
			boost::shared_ptr<TSocket> sock = boost::dynamic_pointer_cast<TSocket>(connInfo.transport);
    
			cout << "Incoming connection\n";
    		cout << "\tSocketInfo: "  << sock->getSocketInfo() << "\n";
    		cout << "\tPeerHost: "    << sock->getPeerHost() << "\n";
    		cout << "\tPeerAddress: " << sock->getPeerAddress() << "\n";
    		cout << "\tPeerPort: "    << sock->getPeerPort() << "\n";
    
			return new sendMessageHandler;
		}
  
		virtual void releaseHandler( ::shared::SharedServiceIf* handler) {
			delete handler;
		}
};

/***************Global variants declare**************************************/
SDConfig gConfig;        //program config options
boost::thread_specific_ptr<sql::Connection> gDbConn; 
/***************Global variants declare end**********************************/

/***************Static functions*********************************************/

/*****************************************************************************
  Function:       showBanner
  Description:    show the program's banner info
  Input:          none
  Output:         none
  Return:         void
  Others:
******************************************************************************/
static void showBanner() {
    cout<<"Everyoo smartHomeCloud sip daemon"<<endl;
}


/*****************************************************************************
  Function:       showUsage
  Description:    show the program's right use method
  Input:          none
  Output:         none
  Return:         void
  Others:
******************************************************************************/
static void showUsage() {
    cout<<"Usage:   ";
    cout<<"sip-daemon [options]"<<endl<<endl;
    
    cout<<"Options:"<<endl;
    cout<<" --database_ip "<<"database server ipv4 address, eg. 127.0.0.1:3306"<<endl;
    cout<<" --database_user    "<< "username of the database"<<endl;
    cout<<" --database_passwd "<<"password of the user"<<endl;
    cout<<" --database_schema  "<<"database name"<<endl;
    cout<<" --verbose   "<<"verbose swtich"<<endl;
    cout<<" --debug "<<"debug swtich"<<endl;
}


/*****************************************************************************
  Function:       showProgramOptions
  Description:    show the valid program's options
  Input:          vm: variables_map
  Output:         none
  Return:         void
  Others:
******************************************************************************/
static void showProgramOptions(po::variables_map &vm) {
    cout<<"************************Program Options*******************************"<<endl;
    
    cout<<"database_ip = "<<vm["database_ip"].as<std::string>()<<endl;
    cout<<"database_user = "<<vm["database_user"].as<std::string>()<<endl;
    cout<<"database_passwd = "<<vm["database_passwd"].as<std::string>()<<endl;
    cout<<"database_schema = "<<vm["database_schema"].as<std::string>()<<endl;
    cout<<"verbose = "<<vm["verbose"].as<bool>()<<endl;
    cout<<"debug = "<<vm["debug"].as<bool>()<<endl;

    cout<<"************************Program Options End***************************"<<endl;
}


/*****************************************************************************
  Function:       parseProgramOptions
  Description:    parse the programs options from command line and 
  Input:          argc: arg count from the command line
                  argv: args from the command line
  Output:         vm: variables_map
  Return:         return flag code, return 0 if it has no error occurs
  Others:
******************************************************************************/
static uint32_t parseProgramOptions(int argc, char **argv, po::variables_map &vm) {
    po::options_description genericOptions("Generic options");
    genericOptions.add_options()
        ("help", "show help message")
        ("verbose", "verbose")
        ("config-folder", po::value<string>()->default_value(MD_DEFAULT_CONFIG_PATH), "config")
        ;
    
    po::options_description configOptions("Configuration");
    configOptions.add_options()
        ("database_ip", po::value<string>()->required(), "database server ipv4 address, eg. 127.0.0.1:3306")
        ("database_user", po::value<string>()->required(), "username of the database")
        ("database_passwd", po::value<string>()->required(), "password of the user")        
        ("database_schema", po::value<string>()->required(), "database name")
        ("verbose", po::value<bool>()->required(), "verbose swtich")
        ("debug", po::value<bool>()->required(), "debug swtich")
        ;
    
    po::options_description cmdlineOptions("Allowed options");
    cmdlineOptions.add(genericOptions).add(configOptions);
    
    po::store(po::parse_command_line(argc, argv, cmdlineOptions), vm);

    if (vm.count("help")) {
        showUsage();
        exit(EXIT_SUCCESS);
    }

    string configFile = vm["config-folder"].as<string>() + "/" + MD_DEFAULT_CONFIG_FILE;
    ifstream ifs(configFile.c_str());

    if (ifs) {
        po::store(po::parse_config_file(ifs, configOptions), vm);
    } else {
        cout << "Warning: can not open " << configFile << endl;
    }
    po::notify(vm);

    gConfig.init(vm);

#ifdef  MD_DEBUG
    showProgramOptions(vm);
#endif

    return RET_SUCCESS;
}


/*****************************************************************************
  Function:       signal_handler
  Description:    signals handler function
  Input:          signo: signal
  Output:         none
  Return:         void
  Others:
******************************************************************************/
static void signalHandler(int signo) {
    switch(signo) {
        case SIGTERM: {  //signal for ctrl+c 
            verbose_cout(gConfig.verbose, "Receive signal SIGTERM");
            exit(EXIT_SUCCESS);
            break;
        }

        case SIGINT:  {  //signal for kill
            verbose_cout(gConfig.verbose, "Receive signal SIGINT");
            exit(EXIT_SUCCESS);
            break;
        }

        default:
            break;
    }    
}


/*****************************************************************************
  Function:       initSignalHanders
  Description:    init program's signal processor
  Input:          none
  Output:         none
  Return:         return flag code, return 0 if it has no error occurs
  Others:
******************************************************************************/
static uint32_t    initSignalHanders() {
    struct sigaction sigAct;
    
    sigAct.sa_handler = signalHandler;
    sigemptyset(&sigAct.sa_mask);
    sigAct.sa_flags = 0; 
    sigaction(SIGTERM, &sigAct, NULL);
    sigaction(SIGINT, &sigAct, NULL);

    return RET_SUCCESS;
}


/*****************************************************************************
 *   Function:       initDatabases
     Description:    Init database
     Input:
     Output:
     Return:         RET_SUCCESS: success
                     RET_DB_GENERIC_ERROR: fail
                     Others:
 ******************************************************************************/
static uint32_t initDatabases() {
    if (gDbConn.get() == 0) {
        gDbConn.reset(createDbConnection(
                    gConfig.dbIp, gConfig.dbUser, gConfig.dbPasswd, gConfig.dbSchema));
    }

    if (gDbConn.get() == 0) {
        return RET_DB_GENERIC_ERROR;
    }
    
    verbose_cout(gConfig.verbose, "Database init complete");
    
    return RET_SUCCESS;
}


/*****************************************************************************
 *   Function:       main
     Description:    start service
     Input:
     Output:
     Return:         RET_SUCCESS: success
     Others:
 ******************************************************************************/
int main(int argc, char* argv[]) {
    TThreadedServer server(
    boost::make_shared<sendMessageProcessorFactory>(boost::make_shared<sendMessageCloneFactory>()),
    boost::make_shared<TServerSocket>(9090), //port
    boost::make_shared<TBufferedTransportFactory>(),
    boost::make_shared<TBinaryProtocolFactory>());
	
    po::variables_map vm;

    if (RET_SUCCESS != parseProgramOptions(argc, argv, vm)) {
        showUsage();
        exit(EXIT_FAILURE);
    }

    showBanner();

    initSignalHanders();  //for system signals
	
    if (RET_SUCCESS != initDatabases()) {
        cout<<"Fail to init databases"<<endl;
        exit(EXIT_FAILURE);
    }

    cout << "Starting the server..." << endl;
    server.serve();
    cout << "Done." << endl;

    return RET_SUCCESS;
}
