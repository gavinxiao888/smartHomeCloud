#
# siptrace module makefile
#
# 
# WARNING: do not run this directly, it should be run by the master Makefile

include ../../Makefile.defs
auto_gen=
NAME=siptrace.so
LIBS=
JLIB=json

BUILDER = $(shell which pkg-config)
ifeq ($(BUILDER),)
	JSONC=$(shell ls $(SYSBASE)/include/lib/libjson*.so $(LOCALBASE)/lib/libjson*.so 2>/dev/null | grep json-c)
else
	JSONC=$(shell pkg-config --libs json-c 2>/dev/null | grep json-c)
endif

ifneq ($(JSONC),)
	JLIB=json-c
endif

ifeq ($(BUILDER),)
	DEFS+=-I/usr/include/$(JLIB) -I$(LOCALBASE)/include/$(JLIB) \
       -I$(LOCALBASE)/include
	LIBS+=-L$(SYSBASE)/include/lib -L$(LOCALBASE)/lib -levent -l$(JLIB) -luuid -lcurl
else
    DEFS+= $(shell pkg-config --cflags $(JLIB))
	LIBS+= $(shell pkg-config --libs $(JLIB) uuid)
	LIBS+= $(shell pkg-config --libs  libcurl)
#DEFS+= $(shell pkg-config --cflags libevent)
#LIBS+= $(shell pkg-config --libs libevent)
endif

DEFS+=-DKAMAILIO_MOD_INTERFACE

SERLIBPATH=../../lib
SER_LIBS+=$(SERLIBPATH)/kmi/kmi
SER_LIBS+=$(SERLIBPATH)/srdb1/srdb1
SER_LIBS+=$(SERLIBPATH)/kcore/kcore
SER_LIBS+=$(SERLIBPATH)/cds/ser_cds
include ../../Makefile.modules
