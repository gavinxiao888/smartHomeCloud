set(EXECUTABLE_OUTPUT_PATH ${PROJECT_BINARY_DIR})

add_executable(sip_daemon
		
		CommunicateInterface.cpp
		SDConfig.cpp
		Server.cpp
		SipService.cpp
		SharedService.cpp
		SharedTypes.cpp
		SharedConstants.cpp
		ServiceConstants.cpp
		ServiceTypes.cpp
		Utils.cpp)

target_link_libraries(sip_daemon
    ${LIB_BOOST_PROGRAM_OPTIONS}
    ${LIB_BOOST_THREAD}
    ${LIB_BOOST_SYSTEM}
    ${LIB_MYSQL_CPPCONN}
    ${LIB_THRIFT}
    ${LIB_THRIFTNB}
    ${LIB_THRIFTZ}
    ${LIB_THRIFT_C_GLIB}
    ${LIB_GLIB}
    ${LIB_GOBJ}
    ${LIB_JSON}
)

install(TARGETS sip_daemon DESTINATION bin)
