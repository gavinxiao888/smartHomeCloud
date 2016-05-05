/*************************************************************************
  Copyright (C), 2016-2017, Everyoo Co., Ltd.
  FileName: CommunicateInterface.cpp
  Author: cuishiyang       Version : 0.1.0          Date: 2016/02/26
  Description:    communicate interface cpp file      
  Version:         0.1.0
  History:         
      <author>           <time>           <version >    <desc>
      cuishiyang          2016/02/26              0.1.0        create this file 
*************************************************************************/
#include "CommunicateInterface.h"

namespace service {

sendMessage_ping_args::~sendMessage_ping_args() throw() {}

uint32_t sendMessage_ping_args::read(::apache::thrift::protocol::TProtocol* iprot) {
	apache::thrift::protocol::TInputRecursionTracker tracker(*iprot);
	uint32_t xfer = 0;
	std::string fname;
	::apache::thrift::protocol::TType ftype;
  	int16_t fid;
  	xfer += iprot->readStructBegin(fname);

  	using ::apache::thrift::protocol::TProtocolException;
  
	while (true) {
    
		xfer += iprot->readFieldBegin(fname, ftype, fid);
		if (ftype == ::apache::thrift::protocol::T_STOP) {
			break;
		}
		xfer += iprot->skip(ftype);
		xfer += iprot->readFieldEnd();
	}
  
	xfer += iprot->readStructEnd();
  
	return xfer;
}

uint32_t sendMessage_ping_args::write(::apache::thrift::protocol::TProtocol* oprot) const {
  	uint32_t xfer = 0;
  	apache::thrift::protocol::TOutputRecursionTracker tracker(*oprot);
  	xfer += oprot->writeStructBegin("sendMessage_ping_args");

  	xfer += oprot->writeFieldStop();
  	xfer += oprot->writeStructEnd();
  	return xfer;
}

sendMessage_ping_pargs::~sendMessage_ping_pargs() throw() {}

uint32_t sendMessage_ping_pargs::write(::apache::thrift::protocol::TProtocol* oprot) const {
  	uint32_t xfer = 0;
  	apache::thrift::protocol::TOutputRecursionTracker tracker(*oprot);
  	xfer += oprot->writeStructBegin("sendMessage_ping_pargs");

  	xfer += oprot->writeFieldStop();
  	xfer += oprot->writeStructEnd();
  	return xfer;
}

sendMessage_ping_result::~sendMessage_ping_result() throw() { }

uint32_t sendMessage_ping_result::read(::apache::thrift::protocol::TProtocol* iprot) {
  	apache::thrift::protocol::TInputRecursionTracker tracker(*iprot);
  	uint32_t xfer = 0;
  	std::string fname;
  	::apache::thrift::protocol::TType ftype;
  	int16_t fid;

  	xfer += iprot->readStructBegin(fname);

  	using ::apache::thrift::protocol::TProtocolException;

	  while (true){
    	xfer += iprot->readFieldBegin(fname, ftype, fid);
    	if (ftype == ::apache::thrift::protocol::T_STOP) {
      	break;
    	}
    	xfer += iprot->skip(ftype);
    	xfer += iprot->readFieldEnd();
  	}

  	xfer += iprot->readStructEnd();

  	return xfer;
}

uint32_t sendMessage_ping_result::write(::apache::thrift::protocol::TProtocol* oprot) const {
  	uint32_t xfer = 0;

  	xfer += oprot->writeStructBegin("sendMessage_ping_result");

  	xfer += oprot->writeFieldStop();
  	xfer += oprot->writeStructEnd();
  	return xfer;
}

sendMessage_ping_presult::~sendMessage_ping_presult() throw() {}

uint32_t sendMessage_ping_presult::read(::apache::thrift::protocol::TProtocol* iprot) {
  	apache::thrift::protocol::TInputRecursionTracker tracker(*iprot);
  	uint32_t xfer = 0;
  	std::string fname;
  	::apache::thrift::protocol::TType ftype;
  	int16_t fid;

  	xfer += iprot->readStructBegin(fname);

  	using ::apache::thrift::protocol::TProtocolException;

  	while (true){
    	xfer += iprot->readFieldBegin(fname, ftype, fid);
    	if (ftype == ::apache::thrift::protocol::T_STOP) {
      	break;
    	}
    	xfer += iprot->skip(ftype);
    	xfer += iprot->readFieldEnd();
  	}

  	xfer += iprot->readStructEnd();

  	return xfer;
}

sendMessage_message_args::~sendMessage_message_args() throw() { }

uint32_t sendMessage_message_args::read(::apache::thrift::protocol::TProtocol* iprot) {
  	apache::thrift::protocol::TInputRecursionTracker tracker(*iprot);
  	uint32_t xfer = 0;
  	std::string fname;
  	::apache::thrift::protocol::TType ftype;
  	int16_t fid;

  	xfer += iprot->readStructBegin(fname);

  	using ::apache::thrift::protocol::TProtocolException;

  	while (true){
    	xfer += iprot->readFieldBegin(fname, ftype, fid);
    	if (ftype == ::apache::thrift::protocol::T_STOP) {
      	break;
    	}
    	switch (fid){
      	case 1:
        	if (ftype == ::apache::thrift::protocol::T_STRING) {
          	xfer += iprot->readString(this->body);
          	this->__isset.body = true;
        	} else {
          	xfer += iprot->skip(ftype);
        	}
        	break;
      	default:
        	xfer += iprot->skip(ftype);
        	break;
    	}
    	xfer += iprot->readFieldEnd();
  	}

  	xfer += iprot->readStructEnd();

  	return xfer;
}

uint32_t sendMessage_message_args::write(::apache::thrift::protocol::TProtocol* oprot) const {
  	uint32_t xfer = 0;
  	apache::thrift::protocol::TOutputRecursionTracker tracker(*oprot);
  	xfer += oprot->writeStructBegin("sendMessage_message_args");

  	xfer += oprot->writeFieldBegin("body", ::apache::thrift::protocol::T_STRING, 1);
  	xfer += oprot->writeString(this->body);
  	xfer += oprot->writeFieldEnd();

  	xfer += oprot->writeFieldStop();
  	xfer += oprot->writeStructEnd();
  	return xfer;
}

sendMessage_message_pargs::~sendMessage_message_pargs() throw() {}

uint32_t sendMessage_message_pargs::write(::apache::thrift::protocol::TProtocol* oprot) const {
  	uint32_t xfer = 0;
  	apache::thrift::protocol::TOutputRecursionTracker tracker(*oprot);
  	xfer += oprot->writeStructBegin("sendMessage_message_pargs");

  	xfer += oprot->writeFieldBegin("body", ::apache::thrift::protocol::T_STRING, 1);
  	xfer += oprot->writeString((*(this->body)));
  	xfer += oprot->writeFieldEnd();

  	xfer += oprot->writeFieldStop();
  	xfer += oprot->writeStructEnd();
  	return xfer;
}

sendMessage_message_result::~sendMessage_message_result() throw() {}

uint32_t sendMessage_message_result::read(::apache::thrift::protocol::TProtocol* iprot) {
  	apache::thrift::protocol::TInputRecursionTracker tracker(*iprot);
  	uint32_t xfer = 0;
  	std::string fname;
  	::apache::thrift::protocol::TType ftype;
  	int16_t fid;

  	xfer += iprot->readStructBegin(fname);

  	using ::apache::thrift::protocol::TProtocolException;

  while (true){
    	xfer += iprot->readFieldBegin(fname, ftype, fid);
    	if (ftype == ::apache::thrift::protocol::T_STOP) {
      	break;
    	}
    
		switch (fid){
      	
			case 0:
				if (ftype == ::apache::thrift::protocol::T_I32) {
					xfer += iprot->readI32(this->success);
					this->__isset.success = true;
				} else {
					xfer += iprot->skip(ftype);
				}
				break;
   
			default:
				xfer += iprot->skip(ftype);
				break;
		}
		xfer += iprot->readFieldEnd();
  }
  
  xfer += iprot->readStructEnd();
 
  return xfer;
}

uint32_t sendMessage_message_result::write(::apache::thrift::protocol::TProtocol* oprot) const {
	uint32_t xfer = 0;

  	xfer += oprot->writeStructBegin("sendMessage_message_result");
  
	if (this->__isset.success) {
		xfer += oprot->writeFieldBegin("success", ::apache::thrift::protocol::T_I32, 0);
		xfer += oprot->writeI32(this->success);
		xfer += oprot->writeFieldEnd();
	}
	xfer += oprot->writeFieldStop();
	xfer += oprot->writeStructEnd();
	return xfer;
}


sendMessage_message_presult::~sendMessage_message_presult() throw() {}

uint32_t sendMessage_message_presult::read(::apache::thrift::protocol::TProtocol* iprot) {
  	apache::thrift::protocol::TInputRecursionTracker tracker(*iprot);
  	uint32_t xfer = 0;
  	std::string fname;
  	::apache::thrift::protocol::TType ftype;
  	int16_t fid;

  	xfer += iprot->readStructBegin(fname);

  	using ::apache::thrift::protocol::TProtocolException;

  while (true){
    	xfer += iprot->readFieldBegin(fname, ftype, fid);
    
		if (ftype == ::apache::thrift::protocol::T_STOP) {
			break;
		}
		switch (fid){
    
			case 0:
    
				if (ftype == ::apache::thrift::protocol::T_I32) {
					xfer += iprot->readI32((*(this->success)));
					this->__isset.success = true;
				} else {
					xfer += iprot->skip(ftype);
				}
				break;

			default:
				xfer += iprot->skip(ftype);
				break;
		}
		xfer += iprot->readFieldEnd();
  }
 
  xfer += iprot->readStructEnd();
  
  return xfer;
}

void sendMessageClient::ping(){
  	send_ping();
  	recv_ping();
}

void sendMessageClient::send_ping(){
  	int32_t cseqid = 0;
  	oprot_->writeMessageBegin("ping", ::apache::thrift::protocol::T_CALL, cseqid);

  	sendMessage_ping_pargs args;
  	args.write(oprot_);

  	oprot_->writeMessageEnd();
  	oprot_->getTransport()->writeEnd();
  	oprot_->getTransport()->flush();
}

void sendMessageClient::recv_ping(){
  	int32_t rseqid = 0;
  	std::string fname;
  	::apache::thrift::protocol::TMessageType mtype;

  	iprot_->readMessageBegin(fname, mtype, rseqid);
  	if (mtype == ::apache::thrift::protocol::T_EXCEPTION) {
    	::apache::thrift::TApplicationException x;
    	x.read(iprot_);
    	iprot_->readMessageEnd();
    	iprot_->getTransport()->readEnd();
    	throw x;
  	}

  	if (mtype != ::apache::thrift::protocol::T_REPLY) {
    	iprot_->skip(::apache::thrift::protocol::T_STRUCT);
    	iprot_->readMessageEnd();
    	iprot_->getTransport()->readEnd();
  	}
 
	if (fname.compare("ping") != 0) {
    	iprot_->skip(::apache::thrift::protocol::T_STRUCT);
  	    iprot_->readMessageEnd();
    	iprot_->getTransport()->readEnd();
  	}
  
	sendMessage_ping_presult result;
	result.read(iprot_);
	iprot_->readMessageEnd();
	iprot_->getTransport()->readEnd();

	return;
}

int32_t sendMessageClient::message(const std::string& body){
  	send_message(body);
  	return recv_message();
}

void sendMessageClient::send_message(const std::string& body){
  	int32_t cseqid = 0;
  	oprot_->writeMessageBegin("message", ::apache::thrift::protocol::T_CALL, cseqid);

  	sendMessage_message_pargs args;
  	args.body = &body;
  	args.write(oprot_);

  	oprot_->writeMessageEnd();
  	oprot_->getTransport()->writeEnd();
  	oprot_->getTransport()->flush();
}

int32_t sendMessageClient::recv_message(){
  	int32_t rseqid = 0;
  	std::string fname;
  	::apache::thrift::protocol::TMessageType mtype;

  	iprot_->readMessageBegin(fname, mtype, rseqid);
  	if (mtype == ::apache::thrift::protocol::T_EXCEPTION) {
    	::apache::thrift::TApplicationException x;
    	x.read(iprot_);
    	iprot_->readMessageEnd();
    	iprot_->getTransport()->readEnd();
    	throw x;
  	}
  	if (mtype != ::apache::thrift::protocol::T_REPLY) {
    	iprot_->skip(::apache::thrift::protocol::T_STRUCT);
    	iprot_->readMessageEnd();
    	iprot_->getTransport()->readEnd();
  	}
  	if (fname.compare("message") != 0) {
   		iprot_->skip(::apache::thrift::protocol::T_STRUCT);
    	iprot_->readMessageEnd();
    	iprot_->getTransport()->readEnd();
  	}
  	int32_t _return;
  	sendMessage_message_presult result;
  	result.success = &_return;
  	result.read(iprot_);
  	iprot_->readMessageEnd();
  	iprot_->getTransport()->readEnd();

  	if (result.__isset.success) {
    	return _return;
  	}
  	throw ::apache::thrift::TApplicationException(::apache::thrift::TApplicationException::MISSING_RESULT, "message failed: unknown result");
}

bool sendMessageProcessor::dispatchCall(::apache::thrift::protocol::TProtocol* iprot, ::apache::thrift::protocol::TProtocol* oprot, const std::string& fname, int32_t seqid, void* callContext) {
  	ProcessMap::iterator pfn;
  	pfn = processMap_.find(fname);
  	if (pfn == processMap_.end()) {
    	return  ::shared::SharedServiceProcessor::dispatchCall(iprot, oprot, fname, seqid, callContext);
  	}
  	(this->*(pfn->second))(seqid, iprot, oprot, callContext);
  	return true;
}

void sendMessageProcessor::process_ping(int32_t seqid, ::apache::thrift::protocol::TProtocol* iprot, ::apache::thrift::protocol::TProtocol* oprot, void* callContext){
  	void* ctx = NULL;
  	if (this->eventHandler_.get() != NULL) {
    	ctx = this->eventHandler_->getContext("sendMessage.ping", callContext);
  	}
  	::apache::thrift::TProcessorContextFreer freer(this->eventHandler_.get(), ctx, "sendMessage.ping");

  	if (this->eventHandler_.get() != NULL) {
    	this->eventHandler_->preRead(ctx, "sendMessage.ping");
  	}

  	sendMessage_ping_args args;
  	args.read(iprot);
  	iprot->readMessageEnd();
  	uint32_t bytes = iprot->getTransport()->readEnd();

  	if (this->eventHandler_.get() != NULL) {
    	this->eventHandler_->postRead(ctx, "sendMessage.ping", bytes);
  	}

  	sendMessage_ping_result result;
  	try {
    	iface_->ping();
  	} catch (const std::exception& e) {
    	if (this->eventHandler_.get() != NULL) {
      	this->eventHandler_->handlerError(ctx, "sendMessage.ping");
		}

		::apache::thrift::TApplicationException x(e.what());
    	oprot->writeMessageBegin("ping", ::apache::thrift::protocol::T_EXCEPTION, seqid);
    	x.write(oprot);
    	oprot->writeMessageEnd();
    	oprot->getTransport()->writeEnd();
    	oprot->getTransport()->flush();
    	return;
  	}

  	if (this->eventHandler_.get() != NULL) {
    	this->eventHandler_->preWrite(ctx, "sendMessage.ping");
  	}

  	oprot->writeMessageBegin("ping", ::apache::thrift::protocol::T_REPLY, seqid);
  	result.write(oprot);
  	oprot->writeMessageEnd();
  	bytes = oprot->getTransport()->writeEnd();
  	oprot->getTransport()->flush();

  	if (this->eventHandler_.get() != NULL) {
    	this->eventHandler_->postWrite(ctx, "sendMessage.ping", bytes);
  	}	
}

void sendMessageProcessor::process_message(int32_t seqid, ::apache::thrift::protocol::TProtocol* iprot, ::apache::thrift::protocol::TProtocol* oprot, void* callContext){
  	void* ctx = NULL;
  	if (this->eventHandler_.get() != NULL) {
    	ctx = this->eventHandler_->getContext("sendMessage.message", callContext);
  	}
  	::apache::thrift::TProcessorContextFreer freer(this->eventHandler_.get(), ctx, "sendMessage.message");

  	if (this->eventHandler_.get() != NULL) {
    	this->eventHandler_->preRead(ctx, "sendMessage.message");
  	}

  	sendMessage_message_args args;
  	args.read(iprot);
  	iprot->readMessageEnd();
  	uint32_t bytes = iprot->getTransport()->readEnd();

  	if (this->eventHandler_.get() != NULL) {
    	this->eventHandler_->postRead(ctx, "sendMessage.message", bytes);
  	}

  	sendMessage_message_result result;
  	try {
    	result.success = iface_->message(args.body);
    	result.__isset.success = true;
  	} catch (const std::exception& e) {
    	if (this->eventHandler_.get() != NULL) {
      	this->eventHandler_->handlerError(ctx, "sendMessage.message");
		}
    	::apache::thrift::TApplicationException x(e.what());
    	oprot->writeMessageBegin("message", ::apache::thrift::protocol::T_EXCEPTION, seqid);
    	x.write(oprot);
    	oprot->writeMessageEnd();
    	oprot->getTransport()->writeEnd();
    	oprot->getTransport()->flush();
   
		return;
  
	}

  
	if (this->eventHandler_.get() != NULL) {
    	this->eventHandler_->preWrite(ctx, "sendMessage.message");
  	}

  	oprot->writeMessageBegin("message", ::apache::thrift::protocol::T_REPLY, seqid);
  	result.write(oprot);
  	oprot->writeMessageEnd();
  	bytes = oprot->getTransport()->writeEnd();
  	oprot->getTransport()->flush();

  	if (this->eventHandler_.get() != NULL) {
    	this->eventHandler_->postWrite(ctx, "sendMessage.message", bytes);
  	}	
}

::boost::shared_ptr< ::apache::thrift::TProcessor > sendMessageProcessorFactory::getProcessor(const ::apache::thrift::TConnectionInfo& connInfo) {
	::apache::thrift::ReleaseHandler< sendMessageIfFactory > cleanup(handlerFactory_);
  	::boost::shared_ptr< sendMessageIf > handler(handlerFactory_->getHandler(connInfo), cleanup);
  	::boost::shared_ptr< ::apache::thrift::TProcessor > processor(new sendMessageProcessor(handler));
  	return processor;
}

void sendMessageConcurrentClient::ping(){
  	int32_t seqid = send_ping();
  	recv_ping(seqid);
}

int32_t sendMessageConcurrentClient::send_ping(){
  	int32_t cseqid = this->sync_.generateSeqId();
  	::apache::thrift::async::TConcurrentSendSentry sentry(&this->sync_);
  	oprot_->writeMessageBegin("ping", ::apache::thrift::protocol::T_CALL, cseqid);

  	sendMessage_ping_pargs args;
  	args.write(oprot_);

  	oprot_->writeMessageEnd();
  	oprot_->getTransport()->writeEnd();
  	oprot_->getTransport()->flush();

  	sentry.commit();
  	return cseqid;
}

void sendMessageConcurrentClient::recv_ping(const int32_t seqid){
  	int32_t rseqid = 0;
  	std::string fname;
  	::apache::thrift::protocol::TMessageType mtype;

  	// the read mutex gets dropped and reacquired as part of waitForWork()
  	// The destructor of this sentry wakes up other clients
  	::apache::thrift::async::TConcurrentRecvSentry sentry(&this->sync_, seqid);

  	while(true) {
		if(!this->sync_.getPending(fname, mtype, rseqid)) {
      	iprot_->readMessageBegin(fname, mtype, rseqid);
		}
    
		if(seqid == rseqid) {
      
			if (mtype == ::apache::thrift::protocol::T_EXCEPTION) {
				::apache::thrift::TApplicationException x;
				x.read(iprot_);
				iprot_->readMessageEnd();
				iprot_->getTransport()->readEnd();
				sentry.commit();
				throw x;
      
			}
 
			if (mtype != ::apache::thrift::protocol::T_REPLY) {
				iprot_->skip(::apache::thrift::protocol::T_STRUCT);
				iprot_->readMessageEnd();
				iprot_->getTransport()->readEnd();
			}
      
			if (fname.compare("ping") != 0) {
				iprot_->skip(::apache::thrift::protocol::T_STRUCT);
				iprot_->readMessageEnd();
				iprot_->getTransport()->readEnd();
       
				// in a bad state, don't commit
				using ::apache::thrift::protocol::TProtocolException;
				throw TProtocolException(TProtocolException::INVALID_DATA);
			}

			sendMessage_ping_presult result;
			result.read(iprot_);
			iprot_->readMessageEnd();
			iprot_->getTransport()->readEnd();

			sentry.commit();
			return;
    
		}
		// seqid != rseqid
		this->sync_.updatePending(fname, mtype, rseqid);
    
		// this will temporarily unlock the readMutex, and let other clients get work done
		this->sync_.waitForWork(seqid);
	} // end while(true)
}

int32_t sendMessageConcurrentClient::message(const std::string& body){
  	int32_t seqid = send_message(body);
  	return recv_message(seqid);
}

int32_t sendMessageConcurrentClient::send_message(const std::string& body){
  	int32_t cseqid = this->sync_.generateSeqId();
  	::apache::thrift::async::TConcurrentSendSentry sentry(&this->sync_);
  	oprot_->writeMessageBegin("message", ::apache::thrift::protocol::T_CALL, cseqid);

  	sendMessage_message_pargs args;
  	args.body = &body;
  	args.write(oprot_);

  	oprot_->writeMessageEnd();
  	oprot_->getTransport()->writeEnd();
  	oprot_->getTransport()->flush();

  	sentry.commit();
  	return cseqid;
}

int32_t sendMessageConcurrentClient::recv_message(const int32_t seqid){
  	int32_t rseqid = 0;
  	std::string fname;
  	::apache::thrift::protocol::TMessageType mtype;

  	// the read mutex gets dropped and reacquired as part of waitForWork()
  	// The destructor of this sentry wakes up other clients
  	::apache::thrift::async::TConcurrentRecvSentry sentry(&this->sync_, seqid);

  	while(true) {
		if(!this->sync_.getPending(fname, mtype, rseqid)) {
			iprot_->readMessageBegin(fname, mtype, rseqid);
		}
    
		if(seqid == rseqid) {
      
			if (mtype == ::apache::thrift::protocol::T_EXCEPTION) {
				::apache::thrift::TApplicationException x;
				x.read(iprot_);
				iprot_->readMessageEnd();
				iprot_->getTransport()->readEnd();
				sentry.commit();
				throw x;
			}
  
			if (mtype != ::apache::thrift::protocol::T_REPLY) {
				iprot_->skip(::apache::thrift::protocol::T_STRUCT);
				iprot_->readMessageEnd();
				iprot_->getTransport()->readEnd();
			}
 
			if (fname.compare("message") != 0) {
				iprot_->skip(::apache::thrift::protocol::T_STRUCT);
				iprot_->readMessageEnd();
				iprot_->getTransport()->readEnd();
     
				// in a bad state, don't commit
				using ::apache::thrift::protocol::TProtocolException;
				throw TProtocolException(TProtocolException::INVALID_DATA);
			}
    
			int32_t _return;
			sendMessage_message_presult result;
			result.success = &_return;
      		result.read(iprot_);
			iprot_->readMessageEnd();
			iprot_->getTransport()->readEnd();

			if (result.__isset.success) {
				sentry.commit();
				return _return;
			}
			// in a bad state, don't commit
			throw ::apache::thrift::TApplicationException(::apache::thrift::TApplicationException::MISSING_RESULT, "message failed: unknown result");
		}
		// seqid != rseqid
		this->sync_.updatePending(fname, mtype, rseqid);
    
		// this will temporarily unlock the readMutex, and let other clients get work done
		this->sync_.waitForWork(seqid);
	} // end while(true)
}
} // namespace

