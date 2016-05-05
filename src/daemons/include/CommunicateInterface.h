/********************************************************************************
  Copyright (C), 2016-2017, Everyoo Co., Ltd.
  File name:      CommunicateInterface.h
  Author: cuishiyang      Version: 0.1.0       Date: 2016/02/26
  Description:  thrift communicate interface header file
  History:
    1. Date:
       Author:
       Modification:
    2. ...
********************************************************************************/
#ifndef _SEND_MESSAGE_H_
#define _SEND_MESSAGE_H_

#include <thrift/TDispatchProcessor.h>
#include <thrift/async/TConcurrentClientSyncInfo.h>

#include "ServiceTypes.h"
#include "SharedService.h"

namespace service {

#ifdef _WIN32
  #pragma warning( push )
  #pragma warning (disable : 4250 ) //inheriting methods via dominance 
#endif

class sendMessageIf : virtual public  ::shared::SharedServiceIf {
	public:
		virtual ~sendMessageIf() {}

  /**
   * A method definition looks like C code. It has a return type, arguments,
   * and optionally a list of exceptions that it may throw. Note that argument
   * lists and exception lists are specified using the exact same syntax as
   * field lists in struct or exception definitions.
   */
  	virtual void ping() = 0;
  	virtual int32_t message(const std::string& body) = 0;
};

class sendMessageIfFactory : virtual public  ::shared::SharedServiceIfFactory {
	public:
		typedef sendMessageIf Handler;

  		virtual ~sendMessageIfFactory() {}

  		virtual sendMessageIf* getHandler(const ::apache::thrift::TConnectionInfo& connInfo) = 0;
  		virtual void releaseHandler( ::shared::SharedServiceIf* /* handler */) = 0;
};

class sendMessageIfSingletonFactory : virtual public sendMessageIfFactory {
	public:
		sendMessageIfSingletonFactory(const boost::shared_ptr<sendMessageIf>& iface) : iface_(iface) {}

		virtual ~sendMessageIfSingletonFactory() {}
		virtual sendMessageIf* getHandler(const ::apache::thrift::TConnectionInfo&) {
			return iface_.get();
		}
		virtual void releaseHandler( ::shared::SharedServiceIf* /* handler */) {}

	protected:
		boost::shared_ptr<sendMessageIf> iface_;
};

class sendMessageNull : virtual public sendMessageIf , virtual public  ::shared::SharedServiceNull {
	public:
		virtual ~sendMessageNull() {}

		void ping() {
			return;
		}
 
		int32_t message(const std::string& /* body */) {
			int32_t _return = 0;
			return _return;
		}
};


class sendMessage_ping_args {
	public:
		sendMessage_ping_args(const sendMessage_ping_args&);
		sendMessage_ping_args& operator=(const sendMessage_ping_args&);
		sendMessage_ping_args() {
 
		}

		virtual ~sendMessage_ping_args() throw();

		bool operator == (const sendMessage_ping_args & /* rhs */) const{
			return true;
		}

		bool operator != (const sendMessage_ping_args &rhs) const {
			return !(*this == rhs);
		}

		bool operator < (const sendMessage_ping_args & ) const;
		uint32_t read(::apache::thrift::protocol::TProtocol* iprot);
		uint32_t write(::apache::thrift::protocol::TProtocol* oprot) const;
};

class sendMessage_ping_pargs {
	public:
		virtual ~sendMessage_ping_pargs() throw();
		uint32_t write(::apache::thrift::protocol::TProtocol* oprot) const;
};


class sendMessage_ping_result {
	public:
		sendMessage_ping_result(const sendMessage_ping_result&);
		sendMessage_ping_result& operator=(const sendMessage_ping_result&);
		sendMessage_ping_result() {}
  
		virtual ~sendMessage_ping_result() throw();
		bool operator == (const sendMessage_ping_result & /* rhs */) const{
			return true;
		}

		bool operator != (const sendMessage_ping_result &rhs) const {
			return !(*this == rhs);
		}

		bool operator < (const sendMessage_ping_result & ) const;
		uint32_t read(::apache::thrift::protocol::TProtocol* iprot);
		uint32_t write(::apache::thrift::protocol::TProtocol* oprot) const;
};


class sendMessage_ping_presult {
	public:
		virtual ~sendMessage_ping_presult() throw();
		uint32_t read(::apache::thrift::protocol::TProtocol* iprot);
};

typedef struct _sendMessage_message_args__isset { 
	_sendMessage_message_args__isset() : body(false) {}
  	bool body :1;

} _sendMessage_message_args__isset;

class sendMessage_message_args {
	public:
		sendMessage_message_args(const sendMessage_message_args&);
		sendMessage_message_args& operator=(const sendMessage_message_args&);
		sendMessage_message_args() : body() {}

		virtual ~sendMessage_message_args() throw();
		std::string body;
		_sendMessage_message_args__isset __isset;

		void __set_body(const std::string& val);
		bool operator == (const sendMessage_message_args & rhs) const{
    
			if (!(body == rhs.body))
				return false;
			return true;
		}
  
		bool operator != (const sendMessage_message_args &rhs) const {
			return !(*this == rhs);
		}
  
		bool operator < (const sendMessage_message_args & ) const;
		uint32_t read(::apache::thrift::protocol::TProtocol* iprot);
		uint32_t write(::apache::thrift::protocol::TProtocol* oprot) const;

};


class sendMessage_message_pargs {
	public:
		virtual ~sendMessage_message_pargs() throw();
		const std::string* body;
		uint32_t write(::apache::thrift::protocol::TProtocol* oprot) const;

};

typedef struct _sendMessage_message_result__isset {
  	_sendMessage_message_result__isset() : success(false) {}
  	bool success :1;
} _sendMessage_message_result__isset;

class sendMessage_message_result {
 	public:
		sendMessage_message_result(const sendMessage_message_result&);
		sendMessage_message_result& operator=(const sendMessage_message_result&);
		sendMessage_message_result() : success(0) {
		}
  
		virtual ~sendMessage_message_result() throw();
		int32_t success;

		_sendMessage_message_result__isset __isset;
		void __set_success(const int32_t val);
		bool operator == (const sendMessage_message_result & rhs) const{
    
			if (!(success == rhs.success))
				return false;
			return true;
		}
 
		bool operator != (const sendMessage_message_result &rhs) const {
			return !(*this == rhs);
		}
 
		bool operator < (const sendMessage_message_result & ) const;
		uint32_t read(::apache::thrift::protocol::TProtocol* iprot);
		uint32_t write(::apache::thrift::protocol::TProtocol* oprot) const;
};

typedef struct _sendMessage_message_presult__isset {
	_sendMessage_message_presult__isset() : success(false) {}
  	bool success :1;
} _sendMessage_message_presult__isset;

class sendMessage_message_presult {
	public:
		virtual ~sendMessage_message_presult() throw();
		int32_t* success;
		_sendMessage_message_presult__isset __isset;
		uint32_t read(::apache::thrift::protocol::TProtocol* iprot);

};

class sendMessageClient : virtual public sendMessageIf, public  ::shared::SharedServiceClient {
	public:
		sendMessageClient(boost::shared_ptr< ::apache::thrift::protocol::TProtocol> prot) :
     	::shared::SharedServiceClient(prot, prot) {}
		sendMessageClient(boost::shared_ptr< ::apache::thrift::protocol::TProtocol> iprot, boost::shared_ptr< ::apache::thrift::protocol::TProtocol> oprot) :     ::shared::SharedServiceClient(iprot, oprot) {}
		boost::shared_ptr< ::apache::thrift::protocol::TProtocol> getInputProtocol() {
			return piprot_;
		}
  
		boost::shared_ptr< ::apache::thrift::protocol::TProtocol> getOutputProtocol() {
			return poprot_;
		}
  
		void ping();
		void send_ping();
		void recv_ping();
		int32_t message(const std::string& body);
		void send_message(const std::string& body);
		int32_t recv_message();
};

class sendMessageProcessor : public  ::shared::SharedServiceProcessor {
	protected:
		boost::shared_ptr<sendMessageIf> iface_;
		virtual bool dispatchCall(::apache::thrift::protocol::TProtocol* iprot, ::apache::thrift::protocol::TProtocol* oprot, const std::string& fname, int32_t seqid, void* callContext);
 
	private:
		typedef  void (sendMessageProcessor::*ProcessFunction)(int32_t, ::apache::thrift::protocol::TProtocol*, ::apache::thrift::protocol::TProtocol*, void*);
		typedef std::map<std::string, ProcessFunction> ProcessMap;
		ProcessMap processMap_;
		void process_ping(int32_t seqid, ::apache::thrift::protocol::TProtocol* iprot, ::apache::thrift::protocol::TProtocol* oprot, void* callContext);
		void process_message(int32_t seqid, ::apache::thrift::protocol::TProtocol* iprot, ::apache::thrift::protocol::TProtocol* oprot, void* callContext);

	public:
		sendMessageProcessor(boost::shared_ptr<sendMessageIf> iface) :
			::shared::SharedServiceProcessor(iface),
  
			iface_(iface) {
				processMap_["ping"] = &sendMessageProcessor::process_ping;
				processMap_["message"] = &sendMessageProcessor::process_message;
			}
		virtual ~sendMessageProcessor() {}
};

class sendMessageProcessorFactory : public ::apache::thrift::TProcessorFactory {
	public:
		sendMessageProcessorFactory(const ::boost::shared_ptr< sendMessageIfFactory >& handlerFactory) :
			handlerFactory_(handlerFactory) {}
		::boost::shared_ptr< ::apache::thrift::TProcessor > getProcessor(const ::apache::thrift::TConnectionInfo& connInfo);

	protected:
		::boost::shared_ptr< sendMessageIfFactory > handlerFactory_;
};

class sendMessageMultiface : virtual public sendMessageIf, public  ::shared::SharedServiceMultiface {
 
	public:
		sendMessageMultiface(std::vector<boost::shared_ptr<sendMessageIf> >& ifaces) : ifaces_(ifaces) {
			std::vector<boost::shared_ptr<sendMessageIf> >::iterator iter;
			for (iter = ifaces.begin(); iter != ifaces.end(); ++iter) {
				::shared::SharedServiceMultiface::add(*iter);
			}
		}
		virtual ~sendMessageMultiface() {}

	protected:
		std::vector<boost::shared_ptr<sendMessageIf> > ifaces_;
		sendMessageMultiface() {}

		void add(boost::shared_ptr<sendMessageIf> iface) {
			::shared::SharedServiceMultiface::add(iface);
			ifaces_.push_back(iface);
		}

	public:
  
		void ping() {
			size_t sz = ifaces_.size();
			size_t i = 0;
    
			for (; i < (sz - 1); ++i) {
				ifaces_[i]->ping();
			}
			ifaces_[i]->ping();
		}
  
		int32_t message(const std::string& body) {
			size_t sz = ifaces_.size();
			size_t i = 0;
  
			for (; i < (sz - 1); ++i) {
				ifaces_[i]->message(body);
			}
			return ifaces_[i]->message(body);
		}
};

// The 'concurrent' client is a thread safe client that correctly handles
// out of order responses.  It is slower than the regular client, so should
// only be used when you need to share a connection among multiple threads
class sendMessageConcurrentClient : virtual public sendMessageIf, public  ::shared::SharedServiceConcurrentClient {
	public:
		sendMessageConcurrentClient(boost::shared_ptr< ::apache::thrift::protocol::TProtocol> prot) :
			::shared::SharedServiceConcurrentClient(prot, prot) {}
		sendMessageConcurrentClient(boost::shared_ptr< ::apache::thrift::protocol::TProtocol> iprot, boost::shared_ptr< ::apache::thrift::protocol::TProtocol> oprot) :     ::shared::SharedServiceConcurrentClient(iprot, oprot) {}

		boost::shared_ptr< ::apache::thrift::protocol::TProtocol> getInputProtocol() {
			return piprot_;
		}
  
		boost::shared_ptr< ::apache::thrift::protocol::TProtocol> getOutputProtocol() {
			return poprot_;
		}
  
		void ping();
		int32_t send_ping();
		void recv_ping(const int32_t seqid);
		int32_t message(const std::string& body);
		int32_t send_message(const std::string& body);
		int32_t recv_message(const int32_t seqid);
};

#ifdef _WIN32
  #pragma warning( pop )
#endif

} // namespace

#endif  //_SEND_MESSAGE_H_
