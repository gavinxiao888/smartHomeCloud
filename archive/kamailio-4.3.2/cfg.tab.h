/* A Bison parser, made by GNU Bison 2.7.  */

/* Bison interface for Yacc-like parsers in C
   
      Copyright (C) 1984, 1989-1990, 2000-2012 Free Software Foundation, Inc.
   
   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.
   
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.  */

/* As a special exception, you may create a larger work that contains
   part or all of the Bison parser skeleton and distribute that work
   under terms of your choice, so long as that work isn't itself a
   parser generator using the skeleton or a modified version thereof
   as a parser skeleton.  Alternatively, if you modify or redistribute
   the parser skeleton itself, you may (at your option) remove this
   special exception, which will cause the skeleton and the resulting
   Bison output files to be licensed under the GNU General Public
   License without this special exception.
   
   This special exception was added by the Free Software Foundation in
   version 2.2 of Bison.  */

#ifndef YY_YY_CFG_TAB_H_INCLUDED
# define YY_YY_CFG_TAB_H_INCLUDED
/* Enabling traces.  */
#ifndef YYDEBUG
# define YYDEBUG 0
#endif
#if YYDEBUG
extern int yydebug;
#endif

/* Tokens.  */
#ifndef YYTOKENTYPE
# define YYTOKENTYPE
   /* Put the tokens into the symbol table, so that GDB and other debuggers
      know about them.  */
   enum yytokentype {
     FORWARD = 258,
     FORWARD_TCP = 259,
     FORWARD_TLS = 260,
     FORWARD_SCTP = 261,
     FORWARD_UDP = 262,
     EXIT = 263,
     DROP = 264,
     RETURN = 265,
     BREAK = 266,
     LOG_TOK = 267,
     ERROR = 268,
     ROUTE = 269,
     ROUTE_REQUEST = 270,
     ROUTE_FAILURE = 271,
     ROUTE_ONREPLY = 272,
     ROUTE_REPLY = 273,
     ROUTE_BRANCH = 274,
     ROUTE_SEND = 275,
     ROUTE_EVENT = 276,
     EXEC = 277,
     SET_HOST = 278,
     SET_HOSTPORT = 279,
     SET_HOSTPORTTRANS = 280,
     PREFIX = 281,
     STRIP = 282,
     STRIP_TAIL = 283,
     SET_USERPHONE = 284,
     APPEND_BRANCH = 285,
     REMOVE_BRANCH = 286,
     CLEAR_BRANCHES = 287,
     SET_USER = 288,
     SET_USERPASS = 289,
     SET_PORT = 290,
     SET_URI = 291,
     REVERT_URI = 292,
     FORCE_RPORT = 293,
     ADD_LOCAL_RPORT = 294,
     FORCE_TCP_ALIAS = 295,
     UDP_MTU = 296,
     UDP_MTU_TRY_PROTO = 297,
     UDP4_RAW = 298,
     UDP4_RAW_MTU = 299,
     UDP4_RAW_TTL = 300,
     IF = 301,
     ELSE = 302,
     SET_ADV_ADDRESS = 303,
     SET_ADV_PORT = 304,
     FORCE_SEND_SOCKET = 305,
     SET_FWD_NO_CONNECT = 306,
     SET_RPL_NO_CONNECT = 307,
     SET_FWD_CLOSE = 308,
     SET_RPL_CLOSE = 309,
     SWITCH = 310,
     CASE = 311,
     DEFAULT = 312,
     WHILE = 313,
     CFG_SELECT = 314,
     CFG_RESET = 315,
     URIHOST = 316,
     URIPORT = 317,
     MAX_LEN = 318,
     SETFLAG = 319,
     RESETFLAG = 320,
     ISFLAGSET = 321,
     SETAVPFLAG = 322,
     RESETAVPFLAG = 323,
     ISAVPFLAGSET = 324,
     METHOD = 325,
     URI = 326,
     FROM_URI = 327,
     TO_URI = 328,
     SRCIP = 329,
     SRCPORT = 330,
     DSTIP = 331,
     DSTPORT = 332,
     TOIP = 333,
     TOPORT = 334,
     SNDIP = 335,
     SNDPORT = 336,
     SNDPROTO = 337,
     SNDAF = 338,
     PROTO = 339,
     AF = 340,
     MYSELF = 341,
     MSGLEN = 342,
     UDP = 343,
     TCP = 344,
     TLS = 345,
     SCTP = 346,
     WS = 347,
     WSS = 348,
     DEBUG_V = 349,
     FORK = 350,
     FORK_DELAY = 351,
     MODINIT_DELAY = 352,
     LOGSTDERROR = 353,
     LOGFACILITY = 354,
     LOGNAME = 355,
     LOGCOLOR = 356,
     LOGPREFIX = 357,
     LISTEN = 358,
     ADVERTISE = 359,
     ALIAS = 360,
     SR_AUTO_ALIASES = 361,
     DNS = 362,
     REV_DNS = 363,
     DNS_TRY_IPV6 = 364,
     DNS_TRY_NAPTR = 365,
     DNS_SRV_LB = 366,
     DNS_UDP_PREF = 367,
     DNS_TCP_PREF = 368,
     DNS_TLS_PREF = 369,
     DNS_SCTP_PREF = 370,
     DNS_RETR_TIME = 371,
     DNS_RETR_NO = 372,
     DNS_SERVERS_NO = 373,
     DNS_USE_SEARCH = 374,
     DNS_SEARCH_FMATCH = 375,
     DNS_NAPTR_IGNORE_RFC = 376,
     DNS_CACHE_INIT = 377,
     DNS_USE_CACHE = 378,
     DNS_USE_FAILOVER = 379,
     DNS_CACHE_FLAGS = 380,
     DNS_CACHE_NEG_TTL = 381,
     DNS_CACHE_MIN_TTL = 382,
     DNS_CACHE_MAX_TTL = 383,
     DNS_CACHE_MEM = 384,
     DNS_CACHE_GC_INT = 385,
     DNS_CACHE_DEL_NONEXP = 386,
     DNS_CACHE_REC_PREF = 387,
     AUTO_BIND_IPV6 = 388,
     DST_BLST_INIT = 389,
     USE_DST_BLST = 390,
     DST_BLST_MEM = 391,
     DST_BLST_TTL = 392,
     DST_BLST_GC_INT = 393,
     DST_BLST_UDP_IMASK = 394,
     DST_BLST_TCP_IMASK = 395,
     DST_BLST_TLS_IMASK = 396,
     DST_BLST_SCTP_IMASK = 397,
     PORT = 398,
     STAT = 399,
     CHILDREN = 400,
     SOCKET_WORKERS = 401,
     ASYNC_WORKERS = 402,
     CHECK_VIA = 403,
     PHONE2TEL = 404,
     MEMLOG = 405,
     MEMDBG = 406,
     MEMSUM = 407,
     MEMSAFETY = 408,
     MEMJOIN = 409,
     CORELOG = 410,
     SIP_WARNING = 411,
     SERVER_SIGNATURE = 412,
     SERVER_HEADER = 413,
     USER_AGENT_HEADER = 414,
     REPLY_TO_VIA = 415,
     LOADMODULE = 416,
     LOADPATH = 417,
     MODPARAM = 418,
     MAXBUFFER = 419,
     SQL_BUFFER_SIZE = 420,
     USER = 421,
     GROUP = 422,
     CHROOT = 423,
     WDIR = 424,
     RUNDIR = 425,
     MHOMED = 426,
     DISABLE_TCP = 427,
     TCP_ACCEPT_ALIASES = 428,
     TCP_CHILDREN = 429,
     TCP_CONNECT_TIMEOUT = 430,
     TCP_SEND_TIMEOUT = 431,
     TCP_CON_LIFETIME = 432,
     TCP_POLL_METHOD = 433,
     TCP_MAX_CONNECTIONS = 434,
     TLS_MAX_CONNECTIONS = 435,
     TCP_NO_CONNECT = 436,
     TCP_SOURCE_IPV4 = 437,
     TCP_SOURCE_IPV6 = 438,
     TCP_OPT_FD_CACHE = 439,
     TCP_OPT_BUF_WRITE = 440,
     TCP_OPT_CONN_WQ_MAX = 441,
     TCP_OPT_WQ_MAX = 442,
     TCP_OPT_RD_BUF = 443,
     TCP_OPT_WQ_BLK = 444,
     TCP_OPT_DEFER_ACCEPT = 445,
     TCP_OPT_DELAYED_ACK = 446,
     TCP_OPT_SYNCNT = 447,
     TCP_OPT_LINGER2 = 448,
     TCP_OPT_KEEPALIVE = 449,
     TCP_OPT_KEEPIDLE = 450,
     TCP_OPT_KEEPINTVL = 451,
     TCP_OPT_KEEPCNT = 452,
     TCP_OPT_CRLF_PING = 453,
     TCP_OPT_ACCEPT_NO_CL = 454,
     TCP_CLONE_RCVBUF = 455,
     DISABLE_TLS = 456,
     ENABLE_TLS = 457,
     TLSLOG = 458,
     TLS_PORT_NO = 459,
     TLS_METHOD = 460,
     TLS_HANDSHAKE_TIMEOUT = 461,
     TLS_SEND_TIMEOUT = 462,
     SSLv23 = 463,
     SSLv2 = 464,
     SSLv3 = 465,
     TLSv1 = 466,
     TLS_VERIFY = 467,
     TLS_REQUIRE_CERTIFICATE = 468,
     TLS_CERTIFICATE = 469,
     TLS_PRIVATE_KEY = 470,
     TLS_CA_LIST = 471,
     DISABLE_SCTP = 472,
     ENABLE_SCTP = 473,
     SCTP_CHILDREN = 474,
     ADVERTISED_ADDRESS = 475,
     ADVERTISED_PORT = 476,
     DISABLE_CORE = 477,
     OPEN_FD_LIMIT = 478,
     SHM_MEM_SZ = 479,
     SHM_FORCE_ALLOC = 480,
     MLOCK_PAGES = 481,
     REAL_TIME = 482,
     RT_PRIO = 483,
     RT_POLICY = 484,
     RT_TIMER1_PRIO = 485,
     RT_TIMER1_POLICY = 486,
     RT_TIMER2_PRIO = 487,
     RT_TIMER2_POLICY = 488,
     MCAST_LOOPBACK = 489,
     MCAST_TTL = 490,
     TOS = 491,
     PMTU_DISCOVERY = 492,
     KILL_TIMEOUT = 493,
     MAX_WLOOPS = 494,
     PVBUFSIZE = 495,
     PVBUFSLOTS = 496,
     HTTP_REPLY_PARSE = 497,
     VERSION_TABLE_CFG = 498,
     CFG_DESCRIPTION = 499,
     SERVER_ID = 500,
     MAX_RECURSIVE_LEVEL = 501,
     MAX_BRANCHES_PARAM = 502,
     LATENCY_LOG = 503,
     LATENCY_LIMIT_DB = 504,
     LATENCY_LIMIT_ACTION = 505,
     MSG_TIME = 506,
     ONSEND_RT_REPLY = 507,
     FLAGS_DECL = 508,
     AVPFLAGS_DECL = 509,
     ATTR_MARK = 510,
     SELECT_MARK = 511,
     ATTR_FROM = 512,
     ATTR_TO = 513,
     ATTR_FROMURI = 514,
     ATTR_TOURI = 515,
     ATTR_FROMUSER = 516,
     ATTR_TOUSER = 517,
     ATTR_FROMDOMAIN = 518,
     ATTR_TODOMAIN = 519,
     ATTR_GLOBAL = 520,
     ADDEQ = 521,
     SUBST = 522,
     SUBSTDEF = 523,
     SUBSTDEFS = 524,
     EQUAL = 525,
     LOG_OR = 526,
     LOG_AND = 527,
     BIN_OR = 528,
     BIN_AND = 529,
     BIN_XOR = 530,
     BIN_LSHIFT = 531,
     BIN_RSHIFT = 532,
     STRDIFF = 533,
     STREQ = 534,
     INTDIFF = 535,
     INTEQ = 536,
     MATCH = 537,
     DIFF = 538,
     EQUAL_T = 539,
     LTE = 540,
     GTE = 541,
     LT = 542,
     GT = 543,
     MINUS = 544,
     PLUS = 545,
     MODULO = 546,
     SLASH = 547,
     STAR = 548,
     BIN_NOT = 549,
     UNARY = 550,
     NOT = 551,
     DEFINED = 552,
     STRCAST = 553,
     INTCAST = 554,
     DOT = 555,
     STRLEN = 556,
     STREMPTY = 557,
     NUMBER = 558,
     ID = 559,
     NUM_ID = 560,
     STRING = 561,
     IPV6ADDR = 562,
     PVAR = 563,
     AVP_OR_PVAR = 564,
     EVENT_RT_NAME = 565,
     COMMA = 566,
     SEMICOLON = 567,
     RPAREN = 568,
     LPAREN = 569,
     LBRACE = 570,
     RBRACE = 571,
     LBRACK = 572,
     RBRACK = 573,
     CR = 574,
     COLON = 575
   };
#endif


#if ! defined YYSTYPE && ! defined YYSTYPE_IS_DECLARED
typedef union YYSTYPE
{
/* Line 2058 of yacc.c  */
#line 274 "cfg.y"

	long intval;
	unsigned long uval;
	char* strval;
	struct expr* expr;
	struct action* action;
	struct case_stms* case_stms;
	struct net* ipnet;
	struct ip_addr* ipaddr;
	struct socket_id* sockid;
	struct name_lst* name_l;
	struct avp_spec* attr;
	struct _pv_spec* pvar;
	struct lvalue* lval;
	struct rvalue* rval;
	struct rval_expr* rv_expr;
	select_t* select;


/* Line 2058 of yacc.c  */
#line 397 "cfg.tab.h"
} YYSTYPE;
# define YYSTYPE_IS_TRIVIAL 1
# define yystype YYSTYPE /* obsolescent; will be withdrawn */
# define YYSTYPE_IS_DECLARED 1
#endif

extern YYSTYPE yylval;

#ifdef YYPARSE_PARAM
#if defined __STDC__ || defined __cplusplus
int yyparse (void *YYPARSE_PARAM);
#else
int yyparse ();
#endif
#else /* ! YYPARSE_PARAM */
#if defined __STDC__ || defined __cplusplus
int yyparse (void);
#else
int yyparse ();
#endif
#endif /* ! YYPARSE_PARAM */

#endif /* !YY_YY_CFG_TAB_H_INCLUDED  */
