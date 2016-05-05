cmake_minimum_required(VERSION 2.6)
project(thrift_server)

if (NOT CMAKE_BUILD_TYPE)
    set(CMAKE_BUILD_TYPE Release)
    message(STATUS "No build type specified, assume Release")
endif (NOT CMAKE_BUILD_TYPE)

set(CMAKE_CXX_FLAGS "-Wall")
set(CMAKE_C_FLAGS "-Wall")


include_directories(include)

find_library(LIB_BOOST_PROGRAM_OPTIONS NAMES boost_program_options)
find_library(LIB_BOOST_SYSTEM NAMES boost_system)
find_library(LIB_BOOST_FILESYSTEM NAMES boost_filesystem)
find_library(LIB_BOOST_THREAD NAMES boost_thread)
find_library(LIB_BOOST_DATE_TIME NAMES boost_date_time)
find_library(LIB_MYSQL_CPPCONN NAMES mysqlcppconn)
find_library(LIB_MYSQL_PP NAMES mysqlpp)
find_library(LIB_THRIFT NAMES thrift)
find_library(LIB_THRIFTNB NAMES thriftnb)
find_library(LIB_THRIFZ NAMES thriftz)
find_library(LIB_THRIFT_C_GLIB NAMES thrift_c_glib)
find_library(LIB_GLIB NAMES glib-2.0)
find_library(LIB_GOBJ NAMES gobject-2.0)
find_library(LIB_JSON NAMES json-c)


add_subdirectory(src)

