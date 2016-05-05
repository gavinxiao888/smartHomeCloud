/* A Bison parser, made by GNU Bison 2.7.  */

/* Bison implementation for Yacc-like parsers in C
   
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

/* C LALR(1) parser skeleton written by Richard Stallman, by
   simplifying the original so-called "semantic" parser.  */

/* All symbols defined below should begin with yy or YY, to avoid
   infringing on user name space.  This should be done even for local
   variables, as they might otherwise be expanded by user macros.
   There are some unavoidable exceptions within include files to
   define necessary library symbols; they are noted "INFRINGES ON
   USER NAME SPACE" below.  */

/* Identify Bison output.  */
#define YYBISON 1

/* Bison version.  */
#define YYBISON_VERSION "2.7"

/* Skeleton name.  */
#define YYSKELETON_NAME "yacc.c"

/* Pure parsers.  */
#define YYPURE 0

/* Push parsers.  */
#define YYPUSH 0

/* Pull parsers.  */
#define YYPULL 1




/* Copy the first part of user declarations.  */
/* Line 371 of yacc.c  */
#line 106 "cfg.y"


#include <stdlib.h>
#include <stdio.h>
#include <stdarg.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <netinet/ip.h>
#include <arpa/inet.h>
#include <string.h>
#include <errno.h>
#include "route_struct.h"
#include "globals.h"
#ifdef SHM_MEM
#include "shm_init.h"
#endif /* SHM_MEM */
#include "route.h"
#include "switch.h"
#include "dprint.h"
#include "sr_module.h"
#include "modparam.h"
#include "ip_addr.h"
#include "resolve.h"
#include "socket_info.h"
#include "name_alias.h"
#include "ut.h"
#include "dset.h"
#include "select.h"
#include "flags.h"
#include "tcp_init.h"
#include "tcp_options.h"
#include "sctp_core.h"
#include "pvar.h"
#include "lvalue.h"
#include "rvalue.h"
#include "sr_compat.h"
#include "msg_translator.h"
#include "async_task.h"

#include "ppcfg.h"
#include "pvapi.h"
#include "config.h"
#include "cfg_core.h"
#include "cfg/cfg.h"
#ifdef CORE_TLS
#include "tls/tls_config.h"
#endif
#include "timer_ticks.h"

#ifdef DEBUG_DMALLOC
#include <dmalloc.h>
#endif

/* hack to avoid alloca usage in the generated C file (needed for compiler
 with no built in alloca, like icc*/
#undef _ALLOCA_H

#define onsend_check(s) \
	do{\
		if (rt!=ONSEND_ROUTE) yyerror( s " allowed only in onsend_routes");\
	}while(0)

	#define IF_AUTO_BIND_IPV6(x) x

#ifdef USE_DNS_CACHE
	#define IF_DNS_CACHE(x) x
#else
	#define IF_DNS_CACHE(x) warn("dns cache support not compiled in")
#endif

#ifdef USE_DNS_FAILOVER
	#define IF_DNS_FAILOVER(x) x
#else
	#define IF_DNS_FAILOVER(x) warn("dns failover support not compiled in")
#endif

#ifdef USE_NAPTR
	#define IF_NAPTR(x) x
#else
	#define IF_NAPTR(x) warn("dns naptr support not compiled in")
#endif

#ifdef USE_DST_BLACKLIST
	#define IF_DST_BLACKLIST(x) x
#else
	#define IF_DST_BLACKLIST(x) warn("dst blacklist support not compiled in")
#endif

#ifdef USE_SCTP
	#define IF_SCTP(x) x
#else
	#define IF_SCTP(x) warn("sctp support not compiled in")
#endif

#ifdef USE_RAW_SOCKS
	#define IF_RAW_SOCKS(x) x
#else
	#define IF_RAW_SOCKS(x) warn("raw socket support not compiled in")
#endif


extern int yylex();
/* safer then using yytext which can be array or pointer */
extern char* yy_number_str;

static void yyerror(char* s, ...);
static void yyerror_at(struct cfg_pos* pos, char* s, ...);
static char* tmp;
static int i_tmp;
static unsigned u_tmp;
static struct socket_id* lst_tmp;
static struct name_lst*  nl_tmp;
static int rt;  /* Type of route block for find_export */
static str* str_tmp;
static str s_tmp;
static struct ip_addr* ip_tmp;
static struct avp_spec* s_attr;
static select_t sel;
static select_t* sel_ptr;
static pv_spec_t* pv_spec;
static struct action *mod_func_action;
static struct lvalue* lval_tmp;
static struct rvalue* rval_tmp;

static void warn(char* s, ...);
static void warn_at(struct cfg_pos* pos, char* s, ...);
static void get_cpos(struct cfg_pos* pos);
static struct rval_expr* mk_rve_rval(enum rval_type, void* v);
static struct rval_expr* mk_rve1(enum rval_expr_op op, struct rval_expr* rve1);
static struct rval_expr* mk_rve2(enum rval_expr_op op, struct rval_expr* rve1,
									struct rval_expr* rve2);
static int rval_expr_int_check(struct rval_expr *rve);
static int warn_ct_rve(struct rval_expr *rve, char* name);
static struct socket_id* mk_listen_id(char*, int, int);
static struct name_lst* mk_name_lst(char* name, int flags);
static struct socket_id* mk_listen_id2(struct name_lst*, int, int);
static void free_name_lst(struct name_lst* lst);
static void free_socket_id_lst(struct socket_id* i);

static struct case_stms* mk_case_stm(struct rval_expr* ct, int is_re, 
									struct action* a, int* err);
static int case_check_type(struct case_stms* stms);
static int case_check_default(struct case_stms* stms);
static int mod_f_params_pre_fixup(struct action* a);
static void free_mod_func_action(struct action* a);


extern int line;
extern int column;
extern int startcolumn;
extern int startline;
extern char *finame;
extern char *routename;
extern char *default_routename;

#define set_cfg_pos(x) \
	do{\
		if(x) {\
		(x)->cline = line;\
		(x)->cfile = (finame!=0)?finame:((cfg_file!=0)?cfg_file:"default");\
		(x)->rname = (routename!=0)?routename:((default_routename!=0)?default_routename:"DEFAULT");\
		}\
	}while(0)



/* Line 371 of yacc.c  */
#line 236 "cfg.tab.c"

# ifndef YY_NULL
#  if defined __cplusplus && 201103L <= __cplusplus
#   define YY_NULL nullptr
#  else
#   define YY_NULL 0
#  endif
# endif

/* Enabling verbose error messages.  */
#ifdef YYERROR_VERBOSE
# undef YYERROR_VERBOSE
# define YYERROR_VERBOSE 1
#else
# define YYERROR_VERBOSE 0
#endif

/* In a future release of Bison, this section will be replaced
   by #include "cfg.tab.h".  */
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
/* Line 387 of yacc.c  */
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


/* Line 387 of yacc.c  */
#line 619 "cfg.tab.c"
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

/* Copy the second part of user declarations.  */

/* Line 390 of yacc.c  */
#line 647 "cfg.tab.c"

#ifdef short
# undef short
#endif

#ifdef YYTYPE_UINT8
typedef YYTYPE_UINT8 yytype_uint8;
#else
typedef unsigned char yytype_uint8;
#endif

#ifdef YYTYPE_INT8
typedef YYTYPE_INT8 yytype_int8;
#elif (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
typedef signed char yytype_int8;
#else
typedef short int yytype_int8;
#endif

#ifdef YYTYPE_UINT16
typedef YYTYPE_UINT16 yytype_uint16;
#else
typedef unsigned short int yytype_uint16;
#endif

#ifdef YYTYPE_INT16
typedef YYTYPE_INT16 yytype_int16;
#else
typedef short int yytype_int16;
#endif

#ifndef YYSIZE_T
# ifdef __SIZE_TYPE__
#  define YYSIZE_T __SIZE_TYPE__
# elif defined size_t
#  define YYSIZE_T size_t
# elif ! defined YYSIZE_T && (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
#  include <stddef.h> /* INFRINGES ON USER NAME SPACE */
#  define YYSIZE_T size_t
# else
#  define YYSIZE_T unsigned int
# endif
#endif

#define YYSIZE_MAXIMUM ((YYSIZE_T) -1)

#ifndef YY_
# if defined YYENABLE_NLS && YYENABLE_NLS
#  if ENABLE_NLS
#   include <libintl.h> /* INFRINGES ON USER NAME SPACE */
#   define YY_(Msgid) dgettext ("bison-runtime", Msgid)
#  endif
# endif
# ifndef YY_
#  define YY_(Msgid) Msgid
# endif
#endif

/* Suppress unused-variable warnings by "using" E.  */
#if ! defined lint || defined __GNUC__
# define YYUSE(E) ((void) (E))
#else
# define YYUSE(E) /* empty */
#endif

/* Identity function, used to suppress warnings about constant conditions.  */
#ifndef lint
# define YYID(N) (N)
#else
#if (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
static int
YYID (int yyi)
#else
static int
YYID (yyi)
    int yyi;
#endif
{
  return yyi;
}
#endif

#if ! defined yyoverflow || YYERROR_VERBOSE

/* The parser invokes alloca or malloc; define the necessary symbols.  */

# ifdef YYSTACK_USE_ALLOCA
#  if YYSTACK_USE_ALLOCA
#   ifdef __GNUC__
#    define YYSTACK_ALLOC __builtin_alloca
#   elif defined __BUILTIN_VA_ARG_INCR
#    include <alloca.h> /* INFRINGES ON USER NAME SPACE */
#   elif defined _AIX
#    define YYSTACK_ALLOC __alloca
#   elif defined _MSC_VER
#    include <malloc.h> /* INFRINGES ON USER NAME SPACE */
#    define alloca _alloca
#   else
#    define YYSTACK_ALLOC alloca
#    if ! defined _ALLOCA_H && ! defined EXIT_SUCCESS && (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
#     include <stdlib.h> /* INFRINGES ON USER NAME SPACE */
      /* Use EXIT_SUCCESS as a witness for stdlib.h.  */
#     ifndef EXIT_SUCCESS
#      define EXIT_SUCCESS 0
#     endif
#    endif
#   endif
#  endif
# endif

# ifdef YYSTACK_ALLOC
   /* Pacify GCC's `empty if-body' warning.  */
#  define YYSTACK_FREE(Ptr) do { /* empty */; } while (YYID (0))
#  ifndef YYSTACK_ALLOC_MAXIMUM
    /* The OS might guarantee only one guard page at the bottom of the stack,
       and a page size can be as small as 4096 bytes.  So we cannot safely
       invoke alloca (N) if N exceeds 4096.  Use a slightly smaller number
       to allow for a few compiler-allocated temporary stack slots.  */
#   define YYSTACK_ALLOC_MAXIMUM 4032 /* reasonable circa 2006 */
#  endif
# else
#  define YYSTACK_ALLOC YYMALLOC
#  define YYSTACK_FREE YYFREE
#  ifndef YYSTACK_ALLOC_MAXIMUM
#   define YYSTACK_ALLOC_MAXIMUM YYSIZE_MAXIMUM
#  endif
#  if (defined __cplusplus && ! defined EXIT_SUCCESS \
       && ! ((defined YYMALLOC || defined malloc) \
	     && (defined YYFREE || defined free)))
#   include <stdlib.h> /* INFRINGES ON USER NAME SPACE */
#   ifndef EXIT_SUCCESS
#    define EXIT_SUCCESS 0
#   endif
#  endif
#  ifndef YYMALLOC
#   define YYMALLOC malloc
#   if ! defined malloc && ! defined EXIT_SUCCESS && (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
void *malloc (YYSIZE_T); /* INFRINGES ON USER NAME SPACE */
#   endif
#  endif
#  ifndef YYFREE
#   define YYFREE free
#   if ! defined free && ! defined EXIT_SUCCESS && (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
void free (void *); /* INFRINGES ON USER NAME SPACE */
#   endif
#  endif
# endif
#endif /* ! defined yyoverflow || YYERROR_VERBOSE */


#if (! defined yyoverflow \
     && (! defined __cplusplus \
	 || (defined YYSTYPE_IS_TRIVIAL && YYSTYPE_IS_TRIVIAL)))

/* A type that is properly aligned for any stack member.  */
union yyalloc
{
  yytype_int16 yyss_alloc;
  YYSTYPE yyvs_alloc;
};

/* The size of the maximum gap between one aligned stack and the next.  */
# define YYSTACK_GAP_MAXIMUM (sizeof (union yyalloc) - 1)

/* The size of an array large to enough to hold all stacks, each with
   N elements.  */
# define YYSTACK_BYTES(N) \
     ((N) * (sizeof (yytype_int16) + sizeof (YYSTYPE)) \
      + YYSTACK_GAP_MAXIMUM)

# define YYCOPY_NEEDED 1

/* Relocate STACK from its old location to the new one.  The
   local variables YYSIZE and YYSTACKSIZE give the old and new number of
   elements in the stack, and YYPTR gives the new location of the
   stack.  Advance YYPTR to a properly aligned location for the next
   stack.  */
# define YYSTACK_RELOCATE(Stack_alloc, Stack)				\
    do									\
      {									\
	YYSIZE_T yynewbytes;						\
	YYCOPY (&yyptr->Stack_alloc, Stack, yysize);			\
	Stack = &yyptr->Stack_alloc;					\
	yynewbytes = yystacksize * sizeof (*Stack) + YYSTACK_GAP_MAXIMUM; \
	yyptr += yynewbytes / sizeof (*yyptr);				\
      }									\
    while (YYID (0))

#endif

#if defined YYCOPY_NEEDED && YYCOPY_NEEDED
/* Copy COUNT objects from SRC to DST.  The source and destination do
   not overlap.  */
# ifndef YYCOPY
#  if defined __GNUC__ && 1 < __GNUC__
#   define YYCOPY(Dst, Src, Count) \
      __builtin_memcpy (Dst, Src, (Count) * sizeof (*(Src)))
#  else
#   define YYCOPY(Dst, Src, Count)              \
      do                                        \
        {                                       \
          YYSIZE_T yyi;                         \
          for (yyi = 0; yyi < (Count); yyi++)   \
            (Dst)[yyi] = (Src)[yyi];            \
        }                                       \
      while (YYID (0))
#  endif
# endif
#endif /* !YYCOPY_NEEDED */

/* YYFINAL -- State number of the termination state.  */
#define YYFINAL  411
/* YYLAST -- Last index in YYTABLE.  */
#define YYLAST   11944

/* YYNTOKENS -- Number of terminals.  */
#define YYNTOKENS  321
/* YYNNTS -- Number of nonterminals.  */
#define YYNNTS  99
/* YYNRULES -- Number of rules.  */
#define YYNRULES  836
/* YYNRULES -- Number of states.  */
#define YYNSTATES  1563

/* YYTRANSLATE(YYLEX) -- Bison symbol number corresponding to YYLEX.  */
#define YYUNDEFTOK  2
#define YYMAXUTOK   575

#define YYTRANSLATE(YYX)						\
  ((unsigned int) (YYX) <= YYMAXUTOK ? yytranslate[YYX] : YYUNDEFTOK)

/* YYTRANSLATE[YYLEX] -- Bison symbol number corresponding to YYLEX.  */
static const yytype_uint16 yytranslate[] =
{
       0,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     1,     2,     3,     4,
       5,     6,     7,     8,     9,    10,    11,    12,    13,    14,
      15,    16,    17,    18,    19,    20,    21,    22,    23,    24,
      25,    26,    27,    28,    29,    30,    31,    32,    33,    34,
      35,    36,    37,    38,    39,    40,    41,    42,    43,    44,
      45,    46,    47,    48,    49,    50,    51,    52,    53,    54,
      55,    56,    57,    58,    59,    60,    61,    62,    63,    64,
      65,    66,    67,    68,    69,    70,    71,    72,    73,    74,
      75,    76,    77,    78,    79,    80,    81,    82,    83,    84,
      85,    86,    87,    88,    89,    90,    91,    92,    93,    94,
      95,    96,    97,    98,    99,   100,   101,   102,   103,   104,
     105,   106,   107,   108,   109,   110,   111,   112,   113,   114,
     115,   116,   117,   118,   119,   120,   121,   122,   123,   124,
     125,   126,   127,   128,   129,   130,   131,   132,   133,   134,
     135,   136,   137,   138,   139,   140,   141,   142,   143,   144,
     145,   146,   147,   148,   149,   150,   151,   152,   153,   154,
     155,   156,   157,   158,   159,   160,   161,   162,   163,   164,
     165,   166,   167,   168,   169,   170,   171,   172,   173,   174,
     175,   176,   177,   178,   179,   180,   181,   182,   183,   184,
     185,   186,   187,   188,   189,   190,   191,   192,   193,   194,
     195,   196,   197,   198,   199,   200,   201,   202,   203,   204,
     205,   206,   207,   208,   209,   210,   211,   212,   213,   214,
     215,   216,   217,   218,   219,   220,   221,   222,   223,   224,
     225,   226,   227,   228,   229,   230,   231,   232,   233,   234,
     235,   236,   237,   238,   239,   240,   241,   242,   243,   244,
     245,   246,   247,   248,   249,   250,   251,   252,   253,   254,
     255,   256,   257,   258,   259,   260,   261,   262,   263,   264,
     265,   266,   267,   268,   269,   270,   271,   272,   273,   274,
     275,   276,   277,   278,   279,   280,   281,   282,   283,   284,
     285,   286,   287,   288,   289,   290,   291,   292,   293,   294,
     295,   296,   297,   298,   299,   300,   301,   302,   303,   304,
     305,   306,   307,   308,   309,   310,   311,   312,   313,   314,
     315,   316,   317,   318,   319,   320
};

#if YYDEBUG
/* YYPRHS[YYN] -- Index of the first RHS symbol of rule number YYN in
   YYRHS.  */
static const yytype_uint16 yyprhs[] =
{
       0,     0,     3,     5,     8,    10,    13,    15,    17,    19,
      21,    23,    24,    27,    28,    31,    33,    34,    37,    38,
      41,    42,    45,    47,    49,    51,    53,    55,    57,    61,
      65,    67,    69,    71,    73,    75,    77,    79,    81,    83,
      85,    87,    89,    91,    93,    95,    97,   101,   105,   111,
     115,   117,   121,   125,   131,   135,   137,   140,   142,   145,
     148,   151,   153,   157,   159,   163,   165,   167,   170,   173,
     175,   179,   181,   185,   189,   193,   197,   201,   205,   209,
     213,   217,   221,   225,   229,   233,   237,   241,   245,   249,
     253,   257,   261,   265,   269,   273,   276,   280,   283,   287,
     290,   294,   297,   301,   304,   308,   311,   315,   318,   322,
     325,   329,   332,   336,   339,   343,   346,   350,   353,   357,
     360,   364,   367,   371,   374,   378,   381,   385,   388,   392,
     395,   399,   402,   406,   409,   413,   416,   420,   423,   427,
     430,   434,   437,   441,   444,   448,   451,   455,   458,   462,
     465,   469,   472,   476,   479,   483,   486,   490,   493,   497,
     500,   504,   507,   511,   515,   519,   523,   527,   531,   535,
     539,   543,   547,   551,   555,   559,   563,   567,   571,   575,
     579,   583,   587,   591,   595,   599,   603,   607,   611,   615,
     619,   623,   627,   631,   635,   639,   643,   647,   651,   655,
     659,   663,   667,   671,   675,   679,   683,   687,   691,   695,
     699,   703,   707,   711,   715,   719,   723,   727,   731,   735,
     739,   743,   747,   751,   755,   759,   763,   767,   771,   775,
     779,   783,   787,   791,   795,   799,   803,   807,   811,   815,
     819,   823,   827,   830,   834,   837,   841,   844,   848,   851,
     855,   859,   863,   867,   871,   875,   879,   883,   887,   891,
     895,   899,   903,   907,   911,   915,   919,   923,   927,   931,
     935,   939,   943,   947,   951,   955,   959,   963,   967,   971,
     975,   979,   983,   987,   991,   995,   999,  1003,  1007,  1011,
    1015,  1019,  1023,  1027,  1031,  1035,  1039,  1043,  1047,  1051,
    1055,  1059,  1063,  1067,  1071,  1075,  1079,  1083,  1087,  1091,
    1095,  1099,  1103,  1107,  1115,  1119,  1123,  1127,  1131,  1135,
    1139,  1143,  1147,  1151,  1155,  1159,  1163,  1167,  1171,  1175,
    1179,  1183,  1187,  1191,  1195,  1199,  1203,  1207,  1211,  1215,
    1219,  1223,  1227,  1231,  1235,  1239,  1243,  1247,  1251,  1255,
    1259,  1263,  1267,  1271,  1275,  1279,  1282,  1286,  1290,  1294,
    1298,  1302,  1306,  1310,  1314,  1318,  1322,  1326,  1330,  1334,
    1338,  1342,  1346,  1350,  1354,  1358,  1362,  1366,  1370,  1374,
    1378,  1382,  1386,  1390,  1394,  1398,  1402,  1406,  1410,  1414,
    1418,  1422,  1424,  1427,  1429,  1431,  1437,  1443,  1451,  1459,
    1465,  1474,  1483,  1486,  1489,  1492,  1495,  1499,  1503,  1512,
    1521,  1524,  1526,  1528,  1536,  1538,  1540,  1544,  1546,  1548,
    1550,  1552,  1554,  1559,  1567,  1570,  1573,  1578,  1586,  1589,
    1591,  1593,  1594,  1600,  1603,  1606,  1607,  1616,  1622,  1627,
    1635,  1638,  1643,  1651,  1654,  1662,  1665,  1668,  1671,  1674,
    1677,  1680,  1683,  1685,  1687,  1689,  1691,  1693,  1695,  1697,
    1699,  1701,  1703,  1705,  1707,  1709,  1711,  1713,  1715,  1717,
    1719,  1721,  1723,  1725,  1727,  1729,  1731,  1733,  1735,  1737,
    1739,  1741,  1743,  1745,  1747,  1749,  1751,  1753,  1755,  1757,
    1761,  1765,  1769,  1772,  1776,  1780,  1784,  1787,  1791,  1795,
    1799,  1803,  1806,  1810,  1814,  1818,  1822,  1826,  1830,  1834,
    1838,  1842,  1846,  1850,  1853,  1857,  1861,  1865,  1868,  1872,
    1876,  1878,  1882,  1884,  1888,  1892,  1896,  1900,  1902,  1904,
    1906,  1908,  1912,  1916,  1920,  1924,  1926,  1928,  1932,  1935,
    1937,  1940,  1943,  1945,  1947,  1949,  1952,  1955,  1957,  1960,
    1964,  1970,  1972,  1977,  1983,  1987,  1992,  1996,  1999,  2004,
    2010,  2014,  2019,  2024,  2027,  2029,  2035,  2040,  2043,  2049,
    2053,  2055,  2060,  2065,  2069,  2071,  2072,  2076,  2078,  2080,
    2082,  2084,  2086,  2088,  2090,  2092,  2094,  2096,  2098,  2102,
    2104,  2107,  2113,  2118,  2120,  2122,  2124,  2126,  2128,  2130,
    2132,  2134,  2136,  2138,  2140,  2142,  2144,  2146,  2148,  2150,
    2152,  2154,  2156,  2158,  2160,  2162,  2166,  2170,  2174,  2178,
    2180,  2182,  2184,  2186,  2189,  2192,  2195,  2199,  2203,  2207,
    2211,  2215,  2219,  2223,  2227,  2231,  2235,  2239,  2243,  2247,
    2251,  2255,  2260,  2265,  2268,  2271,  2274,  2277,  2281,  2285,
    2289,  2293,  2297,  2301,  2305,  2309,  2313,  2317,  2321,  2326,
    2331,  2334,  2338,  2340,  2342,  2344,  2348,  2353,  2358,  2363,
    2370,  2377,  2384,  2391,  2398,  2403,  2406,  2411,  2416,  2421,
    2426,  2433,  2440,  2447,  2454,  2461,  2466,  2469,  2474,  2479,
    2484,  2489,  2496,  2503,  2510,  2517,  2524,  2529,  2532,  2537,
    2542,  2547,  2552,  2559,  2566,  2573,  2580,  2587,  2592,  2595,
    2600,  2605,  2610,  2615,  2622,  2629,  2636,  2643,  2650,  2655,
    2658,  2663,  2668,  2675,  2678,  2683,  2688,  2693,  2696,  2701,
    2706,  2709,  2714,  2719,  2722,  2729,  2736,  2743,  2748,  2751,
    2758,  2761,  2766,  2771,  2776,  2779,  2784,  2789,  2794,  2797,
    2802,  2807,  2810,  2815,  2820,  2823,  2828,  2833,  2836,  2841,
    2845,  2848,  2853,  2857,  2860,  2865,  2869,  2874,  2877,  2882,
    2887,  2890,  2895,  2900,  2903,  2908,  2913,  2916,  2921,  2926,
    2929,  2934,  2939,  2942,  2947,  2951,  2953,  2957,  2959,  2963,
    2965,  2970,  2974,  2976,  2981,  2986,  2991,  2996,  3001,  3004,
    3009,  3014,  3017,  3022,  3027,  3030,  3034,  3036,  3040,  3042,
    3046,  3048,  3052,  3054,  3061,  3068,  3071,  3076,  3081,  3084,
    3089,  3090,  3096,  3099,  3100,  3104,  3106,  3108,  3112,  3115,
    3117,  3121,  3124,  3126,  3128,  3132,  3135
};

/* YYRHS -- A `-1'-separated list of the rules' RHS.  */
static const yytype_int16 yyrhs[] =
{
     322,     0,    -1,   323,    -1,   323,   324,    -1,   324,    -1,
     323,     1,    -1,   347,    -1,   366,    -1,   340,    -1,   344,
      -1,   350,    -1,    -1,   325,   357,    -1,    -1,   326,   358,
      -1,   360,    -1,    -1,   327,   363,    -1,    -1,   328,   364,
      -1,    -1,   329,   365,    -1,   312,    -1,   319,    -1,   351,
      -1,   306,    -1,   381,    -1,   330,    -1,   330,   311,   331,
      -1,   314,   331,   313,    -1,   330,    -1,    88,    -1,    89,
      -1,    90,    -1,    91,    -1,   293,    -1,    88,    -1,    89,
      -1,    90,    -1,    91,    -1,    92,    -1,    93,    -1,   293,
      -1,   303,    -1,   293,    -1,   330,    -1,   330,   320,   335,
      -1,   333,   320,   330,    -1,   333,   320,   330,   320,   335,
      -1,   330,   320,     1,    -1,   332,    -1,   332,   320,   335,
      -1,   333,   320,   332,    -1,   333,   320,   332,   320,   335,
      -1,   332,   320,     1,    -1,   337,    -1,   337,   338,    -1,
     303,    -1,   289,   303,    -1,   253,   341,    -1,   253,     1,
      -1,   342,    -1,   342,   311,   341,    -1,   343,    -1,   343,
     320,   303,    -1,   306,    -1,   304,    -1,   254,   345,    -1,
     254,     1,    -1,   346,    -1,   346,   311,   345,    -1,   343,
      -1,    94,   270,   339,    -1,    94,   270,     1,    -1,    95,
     270,   303,    -1,    95,   270,     1,    -1,    96,   270,   303,
      -1,    96,   270,     1,    -1,    97,   270,   303,    -1,    97,
     270,     1,    -1,    98,   270,   303,    -1,    98,   270,     1,
      -1,    99,   270,   304,    -1,    99,   270,     1,    -1,   100,
     270,   306,    -1,   100,   270,     1,    -1,   101,   270,   303,
      -1,   101,   270,     1,    -1,   102,   270,   306,    -1,   102,
     270,     1,    -1,   107,   270,   303,    -1,   107,   270,     1,
      -1,   108,   270,   303,    -1,   108,   270,     1,    -1,   109,
     270,   303,    -1,   109,     1,    -1,   110,   270,   303,    -1,
     110,     1,    -1,   111,   270,   303,    -1,   111,     1,    -1,
     112,   270,   339,    -1,   112,     1,    -1,   113,   270,   339,
      -1,   113,     1,    -1,   114,   270,   339,    -1,   114,     1,
      -1,   115,   270,   339,    -1,   115,     1,    -1,   116,   270,
     303,    -1,   116,     1,    -1,   117,   270,   303,    -1,   117,
       1,    -1,   118,   270,   303,    -1,   118,     1,    -1,   119,
     270,   303,    -1,   119,     1,    -1,   120,   270,   303,    -1,
     120,     1,    -1,   121,   270,   303,    -1,   121,     1,    -1,
     122,   270,   303,    -1,   122,     1,    -1,   123,   270,   303,
      -1,   123,     1,    -1,   124,   270,   303,    -1,   124,     1,
      -1,   125,   270,   303,    -1,   125,     1,    -1,   126,   270,
     303,    -1,   126,     1,    -1,   128,   270,   303,    -1,   128,
       1,    -1,   127,   270,   303,    -1,   127,     1,    -1,   129,
     270,   303,    -1,   129,     1,    -1,   130,   270,   303,    -1,
     130,     1,    -1,   131,   270,   303,    -1,   131,     1,    -1,
     132,   270,   303,    -1,   132,     1,    -1,   133,   270,   303,
      -1,   133,     1,    -1,   134,   270,   303,    -1,   134,     1,
      -1,   135,   270,   303,    -1,   135,     1,    -1,   136,   270,
     303,    -1,   136,     1,    -1,   137,   270,   303,    -1,   137,
       1,    -1,   138,   270,   303,    -1,   138,     1,    -1,   139,
     270,   303,    -1,   139,     1,    -1,   140,   270,   303,    -1,
     140,     1,    -1,   141,   270,   303,    -1,   141,     1,    -1,
     142,   270,   303,    -1,   142,     1,    -1,   143,   270,   303,
      -1,   144,   270,   306,    -1,   164,   270,   303,    -1,   164,
     270,     1,    -1,   165,   270,   303,    -1,   165,   270,     1,
      -1,   143,   270,     1,    -1,   145,   270,   303,    -1,   145,
     270,     1,    -1,   146,   270,   303,    -1,   146,   270,     1,
      -1,   147,   270,   303,    -1,   147,   270,     1,    -1,   148,
     270,   303,    -1,   148,   270,     1,    -1,   149,   270,   303,
      -1,   149,   270,     1,    -1,   150,   270,   339,    -1,   150,
     270,     1,    -1,   151,   270,   339,    -1,   151,   270,     1,
      -1,   152,   270,   339,    -1,   152,   270,     1,    -1,   153,
     270,   339,    -1,   153,   270,     1,    -1,   154,   270,   339,
      -1,   154,   270,     1,    -1,   155,   270,   339,    -1,   155,
     270,     1,    -1,   156,   270,   303,    -1,   156,   270,     1,
      -1,   243,   270,   306,    -1,   243,   270,     1,    -1,   166,
     270,   306,    -1,   166,   270,   304,    -1,   166,   270,     1,
      -1,   167,   270,   306,    -1,   167,   270,   304,    -1,   167,
     270,     1,    -1,   168,   270,   306,    -1,   168,   270,   304,
      -1,   168,   270,     1,    -1,   169,   270,   306,    -1,   169,
     270,   304,    -1,   169,   270,     1,    -1,   170,   270,   306,
      -1,   170,   270,   304,    -1,   170,   270,     1,    -1,   171,
     270,   303,    -1,   171,   270,     1,    -1,   172,   270,   303,
      -1,   172,   270,     1,    -1,   173,   270,   303,    -1,   173,
     270,     1,    -1,   174,   270,   303,    -1,   174,   270,     1,
      -1,   175,   270,   339,    -1,   175,   270,     1,    -1,   176,
     270,   339,    -1,   176,   270,     1,    -1,   177,   270,   339,
      -1,   177,   270,     1,    -1,   178,   270,   304,    -1,   178,
     270,   306,    -1,   178,   270,     1,    -1,   179,   270,   303,
      -1,   179,   270,     1,    -1,   180,   270,   303,    -1,   180,
     270,     1,    -1,   181,   270,   303,    -1,   181,   270,     1,
      -1,   182,   270,   352,    -1,   182,   270,     1,    -1,   183,
     270,   354,    -1,   183,   270,     1,    -1,   184,   270,   303,
      -1,   184,   270,     1,    -1,   185,   270,   303,    -1,   185,
     270,     1,    -1,   186,   270,   303,    -1,   186,     1,    -1,
     187,   270,   303,    -1,   187,     1,    -1,   188,   270,   303,
      -1,   188,     1,    -1,   189,   270,   303,    -1,   189,     1,
      -1,   190,   270,   303,    -1,   190,   270,     1,    -1,   191,
     270,   303,    -1,   191,   270,     1,    -1,   192,   270,   303,
      -1,   192,   270,     1,    -1,   193,   270,   303,    -1,   193,
     270,     1,    -1,   194,   270,   303,    -1,   194,   270,     1,
      -1,   195,   270,   303,    -1,   195,   270,     1,    -1,   196,
     270,   303,    -1,   196,   270,     1,    -1,   197,   270,   303,
      -1,   197,   270,     1,    -1,   198,   270,   303,    -1,   198,
     270,     1,    -1,   199,   270,   303,    -1,   199,   270,     1,
      -1,   200,   270,   303,    -1,   200,   270,     1,    -1,   201,
     270,   303,    -1,   201,   270,     1,    -1,   202,   270,   303,
      -1,   202,   270,     1,    -1,   203,   270,   303,    -1,   203,
     270,     1,    -1,   204,   270,   303,    -1,   204,   270,     1,
      -1,   205,   270,   208,    -1,   205,   270,   209,    -1,   205,
     270,   210,    -1,   205,   270,   211,    -1,   205,   270,     1,
      -1,   212,   270,   303,    -1,   212,   270,     1,    -1,   213,
     270,   303,    -1,   213,   270,     1,    -1,   214,   270,   306,
      -1,   214,   270,     1,    -1,   215,   270,   306,    -1,   215,
     270,     1,    -1,   216,   270,   306,    -1,   216,   270,     1,
      -1,   206,   270,   303,    -1,   206,   270,     1,    -1,   207,
     270,   303,    -1,   207,   270,     1,    -1,   217,   270,   303,
      -1,   217,   270,     1,    -1,   218,   270,   303,    -1,   218,
     270,     1,    -1,   219,   270,   303,    -1,   219,   270,     1,
      -1,   157,   270,   303,    -1,   157,   270,     1,    -1,   158,
     270,   306,    -1,   158,   270,     1,    -1,   159,   270,   306,
      -1,   159,   270,     1,    -1,   160,   270,   303,    -1,   160,
     270,     1,    -1,   103,   270,   338,    -1,   103,   270,   338,
     104,   330,   320,   303,    -1,   103,   270,     1,    -1,   105,
     270,   338,    -1,   105,   270,     1,    -1,   106,   270,   303,
      -1,   106,   270,     1,    -1,   220,   270,   330,    -1,   220,
     270,     1,    -1,   221,   270,   303,    -1,   221,   270,     1,
      -1,   222,   270,   303,    -1,   222,   270,     1,    -1,   223,
     270,   303,    -1,   223,   270,     1,    -1,   224,   270,   303,
      -1,   224,   270,     1,    -1,   225,   270,   303,    -1,   225,
     270,     1,    -1,   226,   270,   303,    -1,   226,   270,     1,
      -1,   227,   270,   303,    -1,   227,   270,     1,    -1,   228,
     270,   303,    -1,   228,   270,     1,    -1,   229,   270,   303,
      -1,   229,   270,     1,    -1,   230,   270,   303,    -1,   230,
     270,     1,    -1,   231,   270,   303,    -1,   231,   270,     1,
      -1,   232,   270,   303,    -1,   232,   270,     1,    -1,   233,
     270,   303,    -1,   233,   270,     1,    -1,   234,   270,   303,
      -1,   234,   270,     1,    -1,   235,   270,   303,    -1,   235,
     270,     1,    -1,   236,   270,   303,    -1,   236,   270,   304,
      -1,   236,   270,     1,    -1,   237,   270,   303,    -1,   237,
       1,    -1,   238,   270,   303,    -1,   238,   270,     1,    -1,
     239,   270,   303,    -1,   239,   270,     1,    -1,   240,   270,
     303,    -1,   240,   270,     1,    -1,   241,   270,   303,    -1,
     241,   270,     1,    -1,   242,   270,   303,    -1,   242,   270,
       1,    -1,   245,   270,   303,    -1,   246,   270,   303,    -1,
     247,   270,   303,    -1,   248,   270,   303,    -1,   248,   270,
       1,    -1,   249,   270,   303,    -1,   249,   270,     1,    -1,
     250,   270,   303,    -1,   250,   270,     1,    -1,   251,   270,
     303,    -1,   251,   270,     1,    -1,   252,   270,   303,    -1,
     252,   270,     1,    -1,    41,   270,   303,    -1,    41,   270,
       1,    -1,    38,   270,   303,    -1,    38,   270,     1,    -1,
      42,   270,   333,    -1,    42,   270,     1,    -1,    43,   270,
     339,    -1,    43,   270,     1,    -1,    44,   270,   303,    -1,
      44,   270,     1,    -1,    45,   270,   303,    -1,    45,   270,
       1,    -1,   349,    -1,     1,   270,    -1,   304,    -1,    57,
      -1,   348,   300,   348,   270,   303,    -1,   348,   300,   348,
     270,   306,    -1,   348,   300,   348,   270,   303,   244,   306,
      -1,   348,   300,   348,   270,   306,   244,   306,    -1,   348,
     300,   348,   270,     1,    -1,   348,   317,   303,   318,   300,
     348,   270,   303,    -1,   348,   317,   303,   318,   300,   348,
     270,   306,    -1,   161,   306,    -1,   161,     1,    -1,   162,
     306,    -1,   162,     1,    -1,   162,   270,   306,    -1,   162,
     270,     1,    -1,   163,   314,   306,   311,   306,   311,   306,
     313,    -1,   163,   314,   306,   311,   306,   311,   339,   313,
      -1,   163,     1,    -1,   352,    -1,   354,    -1,   303,   300,
     303,   300,   303,   300,   303,    -1,   307,    -1,   353,    -1,
     317,   353,   318,    -1,   303,    -1,   304,    -1,   306,    -1,
      14,    -1,    15,    -1,   356,   315,   384,   316,    -1,    14,
     317,   355,   318,   315,   384,   316,    -1,    14,     1,    -1,
      15,     1,    -1,    16,   315,   384,   316,    -1,    16,   317,
     355,   318,   315,   384,   316,    -1,    16,     1,    -1,    17,
      -1,    18,    -1,    -1,   359,   315,   361,   384,   316,    -1,
      17,     1,    -1,    18,     1,    -1,    -1,    17,   317,   355,
     318,   362,   315,   384,   316,    -1,    17,   317,   355,   318,
       1,    -1,    19,   315,   384,   316,    -1,    19,   317,   355,
     318,   315,   384,   316,    -1,    19,     1,    -1,    20,   315,
     384,   316,    -1,    20,   317,   355,   318,   315,   384,   316,
      -1,    20,     1,    -1,    21,   317,   310,   318,   315,   384,
     316,    -1,    21,     1,    -1,   267,   306,    -1,   267,     1,
      -1,   268,   306,    -1,   268,     1,    -1,   269,   306,    -1,
     269,     1,    -1,   284,    -1,   283,    -1,   279,    -1,   278,
      -1,   288,    -1,   287,    -1,   286,    -1,   285,    -1,   367,
      -1,   282,    -1,   284,    -1,   283,    -1,   281,    -1,   280,
      -1,   279,    -1,   278,    -1,   282,    -1,   288,    -1,   287,
      -1,   286,    -1,   285,    -1,    71,    -1,    72,    -1,    73,
      -1,    81,    -1,    79,    -1,    83,    -1,    75,    -1,    77,
      -1,    85,    -1,    87,    -1,   373,    -1,    80,    -1,    78,
      -1,    74,    -1,    76,    -1,   375,    -1,    70,   369,   412,
      -1,    70,   369,   304,    -1,    70,   369,     1,    -1,    70,
       1,    -1,   372,   369,   412,    -1,   372,   369,    86,    -1,
     372,   369,     1,    -1,   372,     1,    -1,   374,   368,   412,
      -1,   374,   367,   412,    -1,   374,   368,     1,    -1,   374,
     367,     1,    -1,   374,     1,    -1,    84,   367,   334,    -1,
      84,   367,   412,    -1,    84,   367,     1,    -1,    82,   367,
     334,    -1,    82,   367,   412,    -1,    82,   367,     1,    -1,
     376,   369,   378,    -1,   376,   369,   412,    -1,   376,   369,
     379,    -1,   376,   369,    86,    -1,   376,   369,     1,    -1,
     376,     1,    -1,    86,   367,   372,    -1,    86,   367,   376,
      -1,    86,   367,     1,    -1,    86,     1,    -1,   351,   292,
     351,    -1,   351,   292,   303,    -1,   351,    -1,   351,   292,
       1,    -1,   304,    -1,   379,   300,   304,    -1,   379,   289,
     304,    -1,   379,   300,     1,    -1,   379,   289,     1,    -1,
     304,    -1,   305,    -1,   303,    -1,   380,    -1,   381,   300,
     380,    -1,   381,   289,   380,    -1,   381,   300,     1,    -1,
     381,   289,     1,    -1,   415,    -1,   385,    -1,   315,   384,
     316,    -1,   384,   385,    -1,   385,    -1,   384,     1,    -1,
     382,   312,    -1,   386,    -1,   390,    -1,   391,    -1,   419,
     312,    -1,   413,   312,    -1,   312,    -1,   382,     1,    -1,
      46,   412,   383,    -1,    46,   412,   383,    47,   383,    -1,
     412,    -1,    56,   387,   320,   384,    -1,    56,   292,   387,
     320,   384,    -1,    56,   387,   320,    -1,    56,   292,   387,
     320,    -1,    57,   320,   384,    -1,    57,   320,    -1,    56,
       1,   320,   384,    -1,    56,   292,     1,   320,   384,    -1,
      56,     1,   320,    -1,    56,   292,     1,   320,    -1,    56,
     387,   320,     1,    -1,   389,   388,    -1,   388,    -1,    55,
     412,   315,   389,   316,    -1,    55,   412,   315,   316,    -1,
      55,     1,    -1,    55,   412,   315,     1,   316,    -1,    58,
     412,   383,    -1,   304,    -1,   304,   317,   339,   318,    -1,
     304,   317,   306,   318,    -1,   393,   300,   392,    -1,   392,
      -1,    -1,   256,   395,   393,    -1,   257,    -1,   258,    -1,
     259,    -1,   260,    -1,   261,    -1,   262,    -1,   263,    -1,
     264,    -1,   265,    -1,   304,    -1,   397,    -1,   396,   300,
     397,    -1,   255,    -1,   399,   398,    -1,   399,   398,   317,
     339,   318,    -1,   399,   398,   317,   318,    -1,   400,    -1,
     402,    -1,   400,    -1,   402,    -1,   401,    -1,   400,    -1,
     407,    -1,   306,    -1,   308,    -1,   309,    -1,   270,    -1,
     403,    -1,   406,    -1,   407,    -1,   339,    -1,   306,    -1,
     404,    -1,   406,    -1,   407,    -1,   394,    -1,   382,    -1,
     377,    -1,   315,   384,   316,    -1,   315,     1,   316,    -1,
     314,   413,   313,    -1,   314,     1,   313,    -1,   296,    -1,
     294,    -1,   289,    -1,   410,    -1,   411,   412,    -1,   299,
     412,    -1,   298,   412,    -1,   412,   290,   412,    -1,   412,
     289,   412,    -1,   412,   293,   412,    -1,   412,   292,   412,
      -1,   412,   291,   412,    -1,   412,   273,   412,    -1,   412,
     274,   412,    -1,   412,   275,   412,    -1,   412,   276,   412,
      -1,   412,   277,   412,    -1,   412,   371,   412,    -1,   412,
     370,   412,    -1,   412,   272,   412,    -1,   412,   271,   412,
      -1,   314,   412,   313,    -1,   301,   314,   412,   313,    -1,
     302,   314,   412,   313,    -1,   297,   412,    -1,   411,     1,
      -1,   299,     1,    -1,   298,     1,    -1,   412,   290,     1,
      -1,   412,   289,     1,    -1,   412,   293,     1,    -1,   412,
     292,     1,    -1,   412,   291,     1,    -1,   412,   273,     1,
      -1,   412,   274,     1,    -1,   412,   371,     1,    -1,   412,
     370,     1,    -1,   412,   272,     1,    -1,   412,   271,     1,
      -1,   301,   314,     1,   313,    -1,   302,   314,     1,   313,
      -1,   297,     1,    -1,   409,   408,   412,    -1,    67,    -1,
      68,    -1,    69,    -1,     3,   314,   313,    -1,     3,   314,
     379,   313,    -1,     3,   314,   306,   313,    -1,     3,   314,
     351,   313,    -1,     3,   314,   379,   311,   303,   313,    -1,
       3,   314,   306,   311,   303,   313,    -1,     3,   314,   351,
     311,   303,   313,    -1,     3,   314,    61,   311,    62,   313,
      -1,     3,   314,    61,   311,   303,   313,    -1,     3,   314,
      61,   313,    -1,     3,     1,    -1,     3,   314,     1,   313,
      -1,     7,   314,   379,   313,    -1,     7,   314,   306,   313,
      -1,     7,   314,   351,   313,    -1,     7,   314,   379,   311,
     303,   313,    -1,     7,   314,   306,   311,   303,   313,    -1,
       7,   314,   351,   311,   303,   313,    -1,     7,   314,    61,
     311,    62,   313,    -1,     7,   314,    61,   311,   303,   313,
      -1,     7,   314,    61,   313,    -1,     7,     1,    -1,     7,
     314,     1,   313,    -1,     4,   314,   379,   313,    -1,     4,
     314,   306,   313,    -1,     4,   314,   351,   313,    -1,     4,
     314,   379,   311,   303,   313,    -1,     4,   314,   306,   311,
     303,   313,    -1,     4,   314,   351,   311,   303,   313,    -1,
       4,   314,    61,   311,    62,   313,    -1,     4,   314,    61,
     311,   303,   313,    -1,     4,   314,    61,   313,    -1,     4,
       1,    -1,     4,   314,     1,   313,    -1,     5,   314,   379,
     313,    -1,     5,   314,   306,   313,    -1,     5,   314,   351,
     313,    -1,     5,   314,   379,   311,   303,   313,    -1,     5,
     314,   306,   311,   303,   313,    -1,     5,   314,   351,   311,
     303,   313,    -1,     5,   314,    61,   311,    62,   313,    -1,
       5,   314,    61,   311,   303,   313,    -1,     5,   314,    61,
     313,    -1,     5,     1,    -1,     5,   314,     1,   313,    -1,
       6,   314,   379,   313,    -1,     6,   314,   306,   313,    -1,
       6,   314,   351,   313,    -1,     6,   314,   379,   311,   303,
     313,    -1,     6,   314,   306,   311,   303,   313,    -1,     6,
     314,   351,   311,   303,   313,    -1,     6,   314,    61,   311,
      62,   313,    -1,     6,   314,    61,   311,   303,   313,    -1,
       6,   314,    61,   313,    -1,     6,     1,    -1,     6,   314,
       1,   313,    -1,    12,   314,   306,   313,    -1,    12,   314,
     303,   311,   306,   313,    -1,    12,     1,    -1,    12,   314,
       1,   313,    -1,    64,   314,   303,   313,    -1,    64,   314,
     343,   313,    -1,    64,     1,    -1,    65,   314,   303,   313,
      -1,    65,   314,   343,   313,    -1,    65,     1,    -1,    66,
     314,   303,   313,    -1,    66,   314,   343,   313,    -1,    66,
       1,    -1,   414,   314,   405,   311,   343,   313,    -1,   414,
     314,   405,   311,     1,   313,    -1,   414,   314,     1,   311,
     343,   313,    -1,   414,   314,     1,   313,    -1,   414,     1,
      -1,    13,   314,   306,   311,   306,   313,    -1,    13,     1,
      -1,    13,   314,     1,   313,    -1,    14,   314,   412,   313,
      -1,    14,   314,   304,   313,    -1,    14,     1,    -1,    14,
     314,     1,   313,    -1,    22,   314,   306,   313,    -1,    23,
     314,   306,   313,    -1,    23,     1,    -1,    23,   314,     1,
     313,    -1,    26,   314,   306,   313,    -1,    26,     1,    -1,
      26,   314,     1,   313,    -1,    28,   314,   303,   313,    -1,
      28,     1,    -1,    28,   314,     1,   313,    -1,    27,   314,
     303,   313,    -1,    27,     1,    -1,    27,   314,     1,   313,
      -1,    29,   314,   313,    -1,    29,     1,    -1,    31,   314,
     339,   313,    -1,    31,   314,   313,    -1,    31,     1,    -1,
      31,   314,     1,   313,    -1,    32,   314,   313,    -1,    24,
     314,   306,   313,    -1,    24,     1,    -1,    24,   314,     1,
     313,    -1,    25,   314,   306,   313,    -1,    25,     1,    -1,
      25,   314,     1,   313,    -1,    35,   314,   306,   313,    -1,
      35,     1,    -1,    35,   314,     1,   313,    -1,    33,   314,
     306,   313,    -1,    33,     1,    -1,    33,   314,     1,   313,
      -1,    34,   314,   306,   313,    -1,    34,     1,    -1,    34,
     314,     1,   313,    -1,    36,   314,   306,   313,    -1,    36,
       1,    -1,    36,   314,     1,   313,    -1,    37,   314,   313,
      -1,    37,    -1,    38,   314,   313,    -1,    38,    -1,    39,
     314,   313,    -1,    39,    -1,    40,   314,   303,   313,    -1,
      40,   314,   313,    -1,    40,    -1,    40,   314,     1,   313,
      -1,    42,   314,   333,   313,    -1,    42,   314,     1,   313,
      -1,    48,   314,   330,   313,    -1,    48,   314,     1,   313,
      -1,    48,     1,    -1,    49,   314,   303,   313,    -1,    49,
     314,     1,   313,    -1,    49,     1,    -1,    50,   314,   336,
     313,    -1,    50,   314,     1,   313,    -1,    50,     1,    -1,
      51,   314,   313,    -1,    51,    -1,    52,   314,   313,    -1,
      52,    -1,    53,   314,   313,    -1,    53,    -1,    54,   314,
     313,    -1,    54,    -1,    59,   314,   306,   311,   303,   313,
      -1,    59,   314,   306,   311,   412,   313,    -1,    59,     1,
      -1,    59,   314,     1,   313,    -1,    60,   314,   306,   313,
      -1,    60,     1,    -1,    60,   314,     1,   313,    -1,    -1,
     304,   416,   314,   417,   313,    -1,   304,     1,    -1,    -1,
     417,   311,   418,    -1,   418,    -1,   412,    -1,     9,   314,
     313,    -1,     9,   412,    -1,     9,    -1,     8,   314,   313,
      -1,     8,   412,    -1,     8,    -1,    10,    -1,    10,   314,
     313,    -1,    10,   412,    -1,    11,    -1
};

/* YYRLINE[YYN] -- source line where rule number YYN was defined.  */
static const yytype_uint16 yyrline[] =
{
       0,   672,   672,   675,   676,   677,   680,   681,   682,   683,
     684,   685,   685,   686,   686,   687,   688,   688,   689,   689,
     690,   690,   691,   692,   695,   713,   722,   737,   738,   744,
     745,   749,   750,   751,   752,   753,   756,   757,   758,   759,
     760,   761,   762,   765,   766,   769,   770,   771,   772,   773,
     777,   778,   779,   780,   781,   785,   786,   789,   790,   793,
     794,   796,   797,   800,   803,   809,   810,   814,   815,   818,
     819,   822,   828,   829,   830,   831,   832,   833,   834,   835,
     836,   839,   840,   846,   847,   848,   849,   850,   851,   852,
     853,   854,   855,   856,   857,   858,   859,   860,   861,   862,
     863,   864,   865,   866,   867,   868,   869,   871,   872,   873,
     874,   875,   876,   877,   878,   879,   880,   881,   882,   883,
     884,   885,   886,   887,   888,   889,   890,   891,   892,   893,
     894,   895,   896,   897,   898,   899,   900,   901,   902,   903,
     904,   905,   906,   907,   908,   909,   910,   913,   914,   917,
     918,   921,   922,   923,   924,   927,   928,   931,   932,   935,
     936,   939,   940,   941,   946,   947,   948,   949,   950,   951,
     952,   953,   954,   955,   956,   957,   958,   959,   960,   961,
     962,   963,   964,   965,   966,   967,   968,   969,   970,   971,
     972,   973,   974,   975,   978,   979,   986,   993,   994,   995,
     996,   997,   998,   999,  1000,  1001,  1002,  1003,  1004,  1005,
    1006,  1007,  1008,  1015,  1016,  1023,  1024,  1031,  1032,  1039,
    1040,  1047,  1048,  1058,  1059,  1073,  1087,  1088,  1095,  1096,
    1103,  1104,  1111,  1112,  1121,  1122,  1131,  1132,  1139,  1140,
    1147,  1148,  1155,  1156,  1163,  1164,  1171,  1172,  1179,  1180,
    1187,  1188,  1195,  1196,  1203,  1204,  1211,  1212,  1219,  1220,
    1227,  1228,  1235,  1236,  1243,  1244,  1251,  1252,  1259,  1260,
    1267,  1268,  1275,  1276,  1283,  1284,  1291,  1292,  1299,  1300,
    1307,  1314,  1321,  1328,  1335,  1342,  1343,  1350,  1351,  1358,
    1359,  1366,  1367,  1374,  1375,  1382,  1383,  1390,  1391,  1398,
    1399,  1406,  1407,  1414,  1415,  1416,  1417,  1420,  1421,  1424,
    1425,  1426,  1427,  1440,  1454,  1456,  1467,  1468,  1469,  1470,
    1476,  1477,  1487,  1488,  1489,  1490,  1491,  1492,  1499,  1500,
    1507,  1508,  1509,  1510,  1511,  1512,  1513,  1514,  1515,  1516,
    1517,  1518,  1519,  1520,  1521,  1522,  1523,  1524,  1531,  1532,
    1539,  1540,  1541,  1568,  1569,  1570,  1571,  1572,  1573,  1574,
    1575,  1576,  1577,  1578,  1579,  1580,  1581,  1582,  1583,  1584,
    1585,  1586,  1587,  1588,  1589,  1590,  1591,  1592,  1593,  1594,
    1595,  1596,  1598,  1599,  1601,  1603,  1604,  1605,  1608,  1609,
    1612,  1613,  1614,  1617,  1618,  1622,  1627,  1632,  1637,  1642,
    1645,  1650,  1658,  1664,  1665,  1675,  1676,  1686,  1687,  1698,
    1709,  1712,  1713,  1716,  1745,  1760,  1761,  1765,  1776,  1777,
    1781,  1782,  1786,  1795,  1813,  1814,  1817,  1826,  1844,  1848,
    1849,  1854,  1854,  1863,  1864,  1866,  1865,  1890,  1895,  1904,
    1922,  1924,  1933,  1951,  1953,  1972,  1975,  1976,  1977,  1978,
    1979,  1980,  2002,  2003,  2004,  2005,  2008,  2009,  2010,  2011,
    2014,  2015,  2021,  2022,  2023,  2024,  2025,  2026,  2027,  2030,
    2031,  2032,  2033,  2040,  2041,  2042,  2049,  2050,  2051,  2055,
    2056,  2057,  2058,  2059,  2064,  2065,  2068,  2069,  2070,  2076,
    2078,  2080,  2081,  2083,  2085,  2087,  2089,  2091,  2092,  2094,
    2095,  2096,  2097,  2099,  2101,  2103,  2105,  2107,  2109,  2110,
    2146,  2148,  2150,  2152,  2155,  2157,  2159,  2161,  2165,  2166,
    2177,  2178,  2182,  2183,  2199,  2215,  2216,  2219,  2220,  2221,
    2230,  2231,  2247,  2263,  2265,  2272,  2314,  2315,  2318,  2319,
    2320,  2323,  2324,  2325,  2326,  2327,  2328,  2329,  2330,  2333,
    2341,  2351,  2368,  2375,  2382,  2389,  2396,  2401,  2406,  2407,
    2408,  2409,  2410,  2413,  2422,  2429,  2454,  2470,  2471,  2476,
    2495,  2504,  2516,  2531,  2532,  2535,  2535,  2545,  2546,  2547,
    2548,  2549,  2550,  2551,  2552,  2553,  2556,  2559,  2560,  2563,
    2570,  2573,  2580,  2586,  2587,  2596,  2597,  2598,  2601,  2602,
    2614,  2638,  2649,  2680,  2684,  2694,  2708,  2720,  2721,  2723,
    2724,  2725,  2739,  2740,  2741,  2742,  2743,  2744,  2745,  2749,
    2750,  2751,  2764,  2770,  2771,  2772,  2773,  2774,  2775,  2776,
    2777,  2778,  2779,  2780,  2781,  2782,  2783,  2784,  2802,  2803,
    2804,  2805,  2806,  2807,  2808,  2809,  2810,  2811,  2812,  2813,
    2814,  2815,  2816,  2817,  2818,  2820,  2822,  2823,  2824,  2825,
    2826,  2829,  2847,  2848,  2849,  2852,  2853,  2854,  2855,  2856,
    2857,  2858,  2859,  2860,  2861,  2862,  2863,  2864,  2865,  2866,
    2867,  2868,  2869,  2870,  2871,  2872,  2873,  2874,  2875,  2876,
    2877,  2878,  2879,  2880,  2881,  2882,  2883,  2884,  2885,  2886,
    2894,  2902,  2910,  2918,  2926,  2934,  2942,  2950,  2958,  2959,
    2961,  2969,  2977,  2985,  2994,  3003,  3012,  3020,  3029,  3037,
    3038,  3040,  3043,  3044,  3045,  3046,  3053,  3060,  3061,  3067,
    3074,  3075,  3081,  3088,  3089,  3095,  3098,  3101,  3102,  3103,
    3106,  3107,  3108,  3117,  3126,  3127,  3128,  3129,  3130,  3131,
    3132,  3133,  3134,  3135,  3136,  3137,  3138,  3139,  3140,  3141,
    3142,  3143,  3147,  3151,  3152,  3153,  3154,  3155,  3156,  3157,
    3158,  3159,  3160,  3161,  3162,  3163,  3164,  3165,  3166,  3167,
    3168,  3169,  3170,  3171,  3172,  3173,  3174,  3175,  3176,  3177,
    3178,  3186,  3194,  3202,  3203,  3205,  3207,  3218,  3219,  3220,
    3236,  3237,  3238,  3242,  3245,  3246,  3249,  3252,  3255,  3258,
    3261,  3264,  3267,  3270,  3273,  3276,  3277,  3278,  3281,  3282,
    3283,  3283,  3311,  3313,  3315,  3316,  3319,  3336,  3340,  3344,
    3348,  3353,  3357,  3362,  3366,  3370,  3374
};
#endif

#if YYDEBUG || YYERROR_VERBOSE || 0
/* YYTNAME[SYMBOL-NUM] -- String name of the symbol SYMBOL-NUM.
   First, the terminals, then, starting at YYNTOKENS, nonterminals.  */
static const char *const yytname[] =
{
  "$end", "error", "$undefined", "FORWARD", "FORWARD_TCP", "FORWARD_TLS",
  "FORWARD_SCTP", "FORWARD_UDP", "EXIT", "DROP", "RETURN", "BREAK",
  "LOG_TOK", "ERROR", "ROUTE", "ROUTE_REQUEST", "ROUTE_FAILURE",
  "ROUTE_ONREPLY", "ROUTE_REPLY", "ROUTE_BRANCH", "ROUTE_SEND",
  "ROUTE_EVENT", "EXEC", "SET_HOST", "SET_HOSTPORT", "SET_HOSTPORTTRANS",
  "PREFIX", "STRIP", "STRIP_TAIL", "SET_USERPHONE", "APPEND_BRANCH",
  "REMOVE_BRANCH", "CLEAR_BRANCHES", "SET_USER", "SET_USERPASS",
  "SET_PORT", "SET_URI", "REVERT_URI", "FORCE_RPORT", "ADD_LOCAL_RPORT",
  "FORCE_TCP_ALIAS", "UDP_MTU", "UDP_MTU_TRY_PROTO", "UDP4_RAW",
  "UDP4_RAW_MTU", "UDP4_RAW_TTL", "IF", "ELSE", "SET_ADV_ADDRESS",
  "SET_ADV_PORT", "FORCE_SEND_SOCKET", "SET_FWD_NO_CONNECT",
  "SET_RPL_NO_CONNECT", "SET_FWD_CLOSE", "SET_RPL_CLOSE", "SWITCH", "CASE",
  "DEFAULT", "WHILE", "CFG_SELECT", "CFG_RESET", "URIHOST", "URIPORT",
  "MAX_LEN", "SETFLAG", "RESETFLAG", "ISFLAGSET", "SETAVPFLAG",
  "RESETAVPFLAG", "ISAVPFLAGSET", "METHOD", "URI", "FROM_URI", "TO_URI",
  "SRCIP", "SRCPORT", "DSTIP", "DSTPORT", "TOIP", "TOPORT", "SNDIP",
  "SNDPORT", "SNDPROTO", "SNDAF", "PROTO", "AF", "MYSELF", "MSGLEN", "UDP",
  "TCP", "TLS", "SCTP", "WS", "WSS", "DEBUG_V", "FORK", "FORK_DELAY",
  "MODINIT_DELAY", "LOGSTDERROR", "LOGFACILITY", "LOGNAME", "LOGCOLOR",
  "LOGPREFIX", "LISTEN", "ADVERTISE", "ALIAS", "SR_AUTO_ALIASES", "DNS",
  "REV_DNS", "DNS_TRY_IPV6", "DNS_TRY_NAPTR", "DNS_SRV_LB", "DNS_UDP_PREF",
  "DNS_TCP_PREF", "DNS_TLS_PREF", "DNS_SCTP_PREF", "DNS_RETR_TIME",
  "DNS_RETR_NO", "DNS_SERVERS_NO", "DNS_USE_SEARCH", "DNS_SEARCH_FMATCH",
  "DNS_NAPTR_IGNORE_RFC", "DNS_CACHE_INIT", "DNS_USE_CACHE",
  "DNS_USE_FAILOVER", "DNS_CACHE_FLAGS", "DNS_CACHE_NEG_TTL",
  "DNS_CACHE_MIN_TTL", "DNS_CACHE_MAX_TTL", "DNS_CACHE_MEM",
  "DNS_CACHE_GC_INT", "DNS_CACHE_DEL_NONEXP", "DNS_CACHE_REC_PREF",
  "AUTO_BIND_IPV6", "DST_BLST_INIT", "USE_DST_BLST", "DST_BLST_MEM",
  "DST_BLST_TTL", "DST_BLST_GC_INT", "DST_BLST_UDP_IMASK",
  "DST_BLST_TCP_IMASK", "DST_BLST_TLS_IMASK", "DST_BLST_SCTP_IMASK",
  "PORT", "STAT", "CHILDREN", "SOCKET_WORKERS", "ASYNC_WORKERS",
  "CHECK_VIA", "PHONE2TEL", "MEMLOG", "MEMDBG", "MEMSUM", "MEMSAFETY",
  "MEMJOIN", "CORELOG", "SIP_WARNING", "SERVER_SIGNATURE", "SERVER_HEADER",
  "USER_AGENT_HEADER", "REPLY_TO_VIA", "LOADMODULE", "LOADPATH",
  "MODPARAM", "MAXBUFFER", "SQL_BUFFER_SIZE", "USER", "GROUP", "CHROOT",
  "WDIR", "RUNDIR", "MHOMED", "DISABLE_TCP", "TCP_ACCEPT_ALIASES",
  "TCP_CHILDREN", "TCP_CONNECT_TIMEOUT", "TCP_SEND_TIMEOUT",
  "TCP_CON_LIFETIME", "TCP_POLL_METHOD", "TCP_MAX_CONNECTIONS",
  "TLS_MAX_CONNECTIONS", "TCP_NO_CONNECT", "TCP_SOURCE_IPV4",
  "TCP_SOURCE_IPV6", "TCP_OPT_FD_CACHE", "TCP_OPT_BUF_WRITE",
  "TCP_OPT_CONN_WQ_MAX", "TCP_OPT_WQ_MAX", "TCP_OPT_RD_BUF",
  "TCP_OPT_WQ_BLK", "TCP_OPT_DEFER_ACCEPT", "TCP_OPT_DELAYED_ACK",
  "TCP_OPT_SYNCNT", "TCP_OPT_LINGER2", "TCP_OPT_KEEPALIVE",
  "TCP_OPT_KEEPIDLE", "TCP_OPT_KEEPINTVL", "TCP_OPT_KEEPCNT",
  "TCP_OPT_CRLF_PING", "TCP_OPT_ACCEPT_NO_CL", "TCP_CLONE_RCVBUF",
  "DISABLE_TLS", "ENABLE_TLS", "TLSLOG", "TLS_PORT_NO", "TLS_METHOD",
  "TLS_HANDSHAKE_TIMEOUT", "TLS_SEND_TIMEOUT", "SSLv23", "SSLv2", "SSLv3",
  "TLSv1", "TLS_VERIFY", "TLS_REQUIRE_CERTIFICATE", "TLS_CERTIFICATE",
  "TLS_PRIVATE_KEY", "TLS_CA_LIST", "DISABLE_SCTP", "ENABLE_SCTP",
  "SCTP_CHILDREN", "ADVERTISED_ADDRESS", "ADVERTISED_PORT", "DISABLE_CORE",
  "OPEN_FD_LIMIT", "SHM_MEM_SZ", "SHM_FORCE_ALLOC", "MLOCK_PAGES",
  "REAL_TIME", "RT_PRIO", "RT_POLICY", "RT_TIMER1_PRIO",
  "RT_TIMER1_POLICY", "RT_TIMER2_PRIO", "RT_TIMER2_POLICY",
  "MCAST_LOOPBACK", "MCAST_TTL", "TOS", "PMTU_DISCOVERY", "KILL_TIMEOUT",
  "MAX_WLOOPS", "PVBUFSIZE", "PVBUFSLOTS", "HTTP_REPLY_PARSE",
  "VERSION_TABLE_CFG", "CFG_DESCRIPTION", "SERVER_ID",
  "MAX_RECURSIVE_LEVEL", "MAX_BRANCHES_PARAM", "LATENCY_LOG",
  "LATENCY_LIMIT_DB", "LATENCY_LIMIT_ACTION", "MSG_TIME",
  "ONSEND_RT_REPLY", "FLAGS_DECL", "AVPFLAGS_DECL", "ATTR_MARK",
  "SELECT_MARK", "ATTR_FROM", "ATTR_TO", "ATTR_FROMURI", "ATTR_TOURI",
  "ATTR_FROMUSER", "ATTR_TOUSER", "ATTR_FROMDOMAIN", "ATTR_TODOMAIN",
  "ATTR_GLOBAL", "ADDEQ", "SUBST", "SUBSTDEF", "SUBSTDEFS", "EQUAL",
  "LOG_OR", "LOG_AND", "BIN_OR", "BIN_AND", "BIN_XOR", "BIN_LSHIFT",
  "BIN_RSHIFT", "STRDIFF", "STREQ", "INTDIFF", "INTEQ", "MATCH", "DIFF",
  "EQUAL_T", "LTE", "GTE", "LT", "GT", "MINUS", "PLUS", "MODULO", "SLASH",
  "STAR", "BIN_NOT", "UNARY", "NOT", "DEFINED", "STRCAST", "INTCAST",
  "DOT", "STRLEN", "STREMPTY", "NUMBER", "ID", "NUM_ID", "STRING",
  "IPV6ADDR", "PVAR", "AVP_OR_PVAR", "EVENT_RT_NAME", "COMMA", "SEMICOLON",
  "RPAREN", "LPAREN", "LBRACE", "RBRACE", "LBRACK", "RBRACK", "CR",
  "COLON", "$accept", "cfg", "statements", "statement", "$@1", "$@2",
  "$@3", "$@4", "$@5", "listen_id", "listen_id_lst", "listen_id2", "proto",
  "eqproto", "port", "phostport", "listen_phostport", "id_lst", "intno",
  "flags_decl", "flag_list", "flag_spec", "flag_name", "avpflags_decl",
  "avpflag_list", "avpflag_spec", "assign_stm", "cfg_var_id", "cfg_var",
  "module_stm", "ip", "ipv4", "ipv6addr", "ipv6", "route_name",
  "route_main", "route_stm", "failure_route_stm", "route_reply_main",
  "onreply_route_stm", "$@6", "$@7", "branch_route_stm", "send_route_stm",
  "event_route_stm", "preprocess_stm", "equalop", "cmpop", "strop",
  "rve_equalop", "rve_cmpop", "uri_type", "eint_op_onsend", "eint_op",
  "eip_op_onsend", "eip_op", "exp_elem", "ipnet", "host", "host_if_id",
  "host_or_if", "fcmd", "stm", "actions", "action", "if_cmd", "ct_rval",
  "single_case", "case_stms", "switch_cmd", "while_cmd", "select_param",
  "select_params", "select_id", "$@8", "attr_class_spec", "attr_name_spec",
  "attr_spec", "attr_mark", "attr_id", "attr_id_num_idx", "attr_id_no_idx",
  "attr_id_ass", "attr_id_any", "attr_id_any_str", "pvar", "avp_pvar",
  "assign_op", "lval", "rval", "rve_un_op", "rval_expr", "assign_action",
  "avpflag_oper", "cmd", "$@9", "func_params", "func_param", "ret_cmd", YY_NULL
};
#endif

# ifdef YYPRINT
/* YYTOKNUM[YYLEX-NUM] -- Internal token number corresponding to
   token YYLEX-NUM.  */
static const yytype_uint16 yytoknum[] =
{
       0,   256,   257,   258,   259,   260,   261,   262,   263,   264,
     265,   266,   267,   268,   269,   270,   271,   272,   273,   274,
     275,   276,   277,   278,   279,   280,   281,   282,   283,   284,
     285,   286,   287,   288,   289,   290,   291,   292,   293,   294,
     295,   296,   297,   298,   299,   300,   301,   302,   303,   304,
     305,   306,   307,   308,   309,   310,   311,   312,   313,   314,
     315,   316,   317,   318,   319,   320,   321,   322,   323,   324,
     325,   326,   327,   328,   329,   330,   331,   332,   333,   334,
     335,   336,   337,   338,   339,   340,   341,   342,   343,   344,
     345,   346,   347,   348,   349,   350,   351,   352,   353,   354,
     355,   356,   357,   358,   359,   360,   361,   362,   363,   364,
     365,   366,   367,   368,   369,   370,   371,   372,   373,   374,
     375,   376,   377,   378,   379,   380,   381,   382,   383,   384,
     385,   386,   387,   388,   389,   390,   391,   392,   393,   394,
     395,   396,   397,   398,   399,   400,   401,   402,   403,   404,
     405,   406,   407,   408,   409,   410,   411,   412,   413,   414,
     415,   416,   417,   418,   419,   420,   421,   422,   423,   424,
     425,   426,   427,   428,   429,   430,   431,   432,   433,   434,
     435,   436,   437,   438,   439,   440,   441,   442,   443,   444,
     445,   446,   447,   448,   449,   450,   451,   452,   453,   454,
     455,   456,   457,   458,   459,   460,   461,   462,   463,   464,
     465,   466,   467,   468,   469,   470,   471,   472,   473,   474,
     475,   476,   477,   478,   479,   480,   481,   482,   483,   484,
     485,   486,   487,   488,   489,   490,   491,   492,   493,   494,
     495,   496,   497,   498,   499,   500,   501,   502,   503,   504,
     505,   506,   507,   508,   509,   510,   511,   512,   513,   514,
     515,   516,   517,   518,   519,   520,   521,   522,   523,   524,
     525,   526,   527,   528,   529,   530,   531,   532,   533,   534,
     535,   536,   537,   538,   539,   540,   541,   542,   543,   544,
     545,   546,   547,   548,   549,   550,   551,   552,   553,   554,
     555,   556,   557,   558,   559,   560,   561,   562,   563,   564,
     565,   566,   567,   568,   569,   570,   571,   572,   573,   574,
     575
};
# endif

/* YYR1[YYN] -- Symbol number of symbol that rule YYN derives.  */
static const yytype_uint16 yyr1[] =
{
       0,   321,   322,   323,   323,   323,   324,   324,   324,   324,
     324,   325,   324,   326,   324,   324,   327,   324,   328,   324,
     329,   324,   324,   324,   330,   330,   330,   331,   331,   332,
     332,   333,   333,   333,   333,   333,   334,   334,   334,   334,
     334,   334,   334,   335,   335,   336,   336,   336,   336,   336,
     337,   337,   337,   337,   337,   338,   338,   339,   339,   340,
     340,   341,   341,   342,   342,   343,   343,   344,   344,   345,
     345,   346,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   347,   347,   347,   347,   347,   347,   347,
     347,   347,   347,   348,   348,   349,   349,   349,   349,   349,
     349,   349,   350,   350,   350,   350,   350,   350,   350,   350,
     350,   351,   351,   352,   353,   354,   354,   355,   355,   355,
     356,   356,   357,   357,   357,   357,   358,   358,   358,   359,
     359,   361,   360,   360,   360,   362,   360,   360,   363,   363,
     363,   364,   364,   364,   365,   365,   366,   366,   366,   366,
     366,   366,   367,   367,   367,   367,   368,   368,   368,   368,
     369,   369,   370,   370,   370,   370,   370,   370,   370,   371,
     371,   371,   371,   372,   372,   372,   373,   373,   373,   374,
     374,   374,   374,   374,   375,   375,   376,   376,   376,   377,
     377,   377,   377,   377,   377,   377,   377,   377,   377,   377,
     377,   377,   377,   377,   377,   377,   377,   377,   377,   377,
     377,   377,   377,   377,   377,   377,   377,   377,   378,   378,
     378,   378,   379,   379,   379,   379,   379,   380,   380,   380,
     381,   381,   381,   381,   381,   382,   383,   383,   384,   384,
     384,   385,   385,   385,   385,   385,   385,   385,   385,   386,
     386,   387,   388,   388,   388,   388,   388,   388,   388,   388,
     388,   388,   388,   389,   389,   390,   390,   390,   390,   391,
     392,   392,   392,   393,   393,   395,   394,   396,   396,   396,
     396,   396,   396,   396,   396,   396,   397,   398,   398,   399,
     400,   401,   402,   403,   403,   404,   404,   404,   405,   405,
     405,   406,   407,   408,   409,   409,   409,   410,   410,   410,
     410,   410,   410,   410,   410,   410,   410,   410,   410,   411,
     411,   411,   412,   412,   412,   412,   412,   412,   412,   412,
     412,   412,   412,   412,   412,   412,   412,   412,   412,   412,
     412,   412,   412,   412,   412,   412,   412,   412,   412,   412,
     412,   412,   412,   412,   412,   412,   412,   412,   412,   412,
     412,   413,   414,   414,   414,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     415,   415,   415,   415,   415,   415,   415,   415,   415,   415,
     416,   415,   415,   417,   417,   417,   418,   419,   419,   419,
     419,   419,   419,   419,   419,   419,   419
};

/* YYR2[YYN] -- Number of symbols composing right hand side of rule YYN.  */
static const yytype_uint8 yyr2[] =
{
       0,     2,     1,     2,     1,     2,     1,     1,     1,     1,
       1,     0,     2,     0,     2,     1,     0,     2,     0,     2,
       0,     2,     1,     1,     1,     1,     1,     1,     3,     3,
       1,     1,     1,     1,     1,     1,     1,     1,     1,     1,
       1,     1,     1,     1,     1,     1,     3,     3,     5,     3,
       1,     3,     3,     5,     3,     1,     2,     1,     2,     2,
       2,     1,     3,     1,     3,     1,     1,     2,     2,     1,
       3,     1,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     2,     3,     2,     3,     2,
       3,     2,     3,     2,     3,     2,     3,     2,     3,     2,
       3,     2,     3,     2,     3,     2,     3,     2,     3,     2,
       3,     2,     3,     2,     3,     2,     3,     2,     3,     2,
       3,     2,     3,     2,     3,     2,     3,     2,     3,     2,
       3,     2,     3,     2,     3,     2,     3,     2,     3,     2,
       3,     2,     3,     2,     3,     2,     3,     2,     3,     2,
       3,     2,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     2,     3,     2,     3,     2,     3,     2,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     7,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     2,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     1,     2,     1,     1,     5,     5,     7,     7,     5,
       8,     8,     2,     2,     2,     2,     3,     3,     8,     8,
       2,     1,     1,     7,     1,     1,     3,     1,     1,     1,
       1,     1,     4,     7,     2,     2,     4,     7,     2,     1,
       1,     0,     5,     2,     2,     0,     8,     5,     4,     7,
       2,     4,     7,     2,     7,     2,     2,     2,     2,     2,
       2,     2,     1,     1,     1,     1,     1,     1,     1,     1,
       1,     1,     1,     1,     1,     1,     1,     1,     1,     1,
       1,     1,     1,     1,     1,     1,     1,     1,     1,     1,
       1,     1,     1,     1,     1,     1,     1,     1,     1,     3,
       3,     3,     2,     3,     3,     3,     2,     3,     3,     3,
       3,     2,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     3,     3,     2,     3,     3,     3,     2,     3,     3,
       1,     3,     1,     3,     3,     3,     3,     1,     1,     1,
       1,     3,     3,     3,     3,     1,     1,     3,     2,     1,
       2,     2,     1,     1,     1,     2,     2,     1,     2,     3,
       5,     1,     4,     5,     3,     4,     3,     2,     4,     5,
       3,     4,     4,     2,     1,     5,     4,     2,     5,     3,
       1,     4,     4,     3,     1,     0,     3,     1,     1,     1,
       1,     1,     1,     1,     1,     1,     1,     1,     3,     1,
       2,     5,     4,     1,     1,     1,     1,     1,     1,     1,
       1,     1,     1,     1,     1,     1,     1,     1,     1,     1,
       1,     1,     1,     1,     1,     3,     3,     3,     3,     1,
       1,     1,     1,     2,     2,     2,     3,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
       3,     4,     4,     2,     2,     2,     2,     3,     3,     3,
       3,     3,     3,     3,     3,     3,     3,     3,     4,     4,
       2,     3,     1,     1,     1,     3,     4,     4,     4,     6,
       6,     6,     6,     6,     4,     2,     4,     4,     4,     4,
       6,     6,     6,     6,     6,     4,     2,     4,     4,     4,
       4,     6,     6,     6,     6,     6,     4,     2,     4,     4,
       4,     4,     6,     6,     6,     6,     6,     4,     2,     4,
       4,     4,     4,     6,     6,     6,     6,     6,     4,     2,
       4,     4,     6,     2,     4,     4,     4,     2,     4,     4,
       2,     4,     4,     2,     6,     6,     6,     4,     2,     6,
       2,     4,     4,     4,     2,     4,     4,     4,     2,     4,
       4,     2,     4,     4,     2,     4,     4,     2,     4,     3,
       2,     4,     3,     2,     4,     3,     4,     2,     4,     4,
       2,     4,     4,     2,     4,     4,     2,     4,     4,     2,
       4,     4,     2,     4,     3,     1,     3,     1,     3,     1,
       4,     3,     1,     4,     4,     4,     4,     4,     2,     4,
       4,     2,     4,     4,     2,     3,     1,     3,     1,     3,
       1,     3,     1,     6,     6,     2,     4,     4,     2,     4,
       0,     5,     2,     0,     3,     1,     1,     3,     2,     1,
       3,     2,     1,     1,     3,     2,     1
};

/* YYDEFACT[STATE-NAME] -- Default reduction number in state STATE-NUM.
   Performed when YYTABLE doesn't specify something else to do.  Zero
   means the default is an error.  */
static const yytype_uint16 yydefact[] =
{
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
     394,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,   393,
      22,    23,     0,     0,     4,     0,     0,     0,     0,     0,
       8,     9,     6,     0,   391,    10,     0,    15,     7,   392,
     433,     0,   434,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,    95,     0,    97,     0,    99,     0,   101,
       0,   103,     0,   105,     0,   107,     0,   109,     0,   111,
       0,   113,     0,   115,     0,   117,     0,   119,     0,   121,
       0,   123,     0,   125,     0,   127,     0,   129,     0,   133,
       0,   131,     0,   135,     0,   137,     0,   139,     0,   141,
       0,   143,     0,   145,     0,   147,     0,   149,     0,   151,
       0,   153,     0,   155,     0,   157,     0,   159,     0,   161,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,   403,
     402,   405,     0,   404,   410,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,   242,     0,
     244,     0,   246,     0,   248,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,   355,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,    60,    66,    65,    59,    61,
      63,    68,    71,    67,    69,   447,   446,   449,   448,   451,
     450,     1,     5,     3,     0,     0,     0,    12,     0,    14,
       0,    17,     0,    19,     0,    21,     0,     0,   431,   417,
     418,   419,     0,   382,   381,   380,   379,   384,    31,    32,
      33,    34,    35,   383,   386,     0,    57,   385,   388,   387,
     390,   389,    73,    72,    75,    74,    77,    76,    79,    78,
      81,    80,    83,    82,    85,    84,    87,    86,    89,    88,
     314,   529,   527,   528,    25,   414,     0,     0,    30,    50,
       0,    55,   312,    24,   411,   415,   412,   530,    26,   316,
     315,   318,   317,    91,    90,    93,    92,    94,    96,    98,
     100,   102,   104,   106,   108,   110,   112,   114,   116,   118,
     120,   122,   124,   126,   128,   132,   130,   134,   136,   138,
     140,   142,   144,   146,   148,   150,   152,   154,   156,   158,
     160,   168,   162,   163,   170,   169,   172,   171,   174,   173,
     176,   175,   178,   177,   180,   179,   182,   181,   184,   183,
     186,   185,   188,   187,   190,   189,   192,   191,   305,   304,
     307,   306,   309,   308,   311,   310,   407,   406,     0,   165,
     164,   167,   166,   197,   196,   195,   200,   199,   198,   203,
     202,   201,   206,   205,   204,   209,   208,   207,   211,   210,
     213,   212,   215,   214,   217,   216,   219,   218,   221,   220,
     223,   222,   226,   224,   225,   228,   227,   230,   229,   232,
     231,   234,     0,   233,   236,   235,   238,   237,   240,   239,
     241,   243,   245,   247,   250,   249,   252,   251,   254,   253,
     256,   255,   258,   257,   260,   259,   262,   261,   264,   263,
     266,   265,   268,   267,   270,   269,   272,   271,   274,   273,
     276,   275,   278,   277,   283,   279,   280,   281,   282,   295,
     294,   297,   296,   285,   284,   287,   286,   289,   288,   291,
     290,   293,   292,   299,   298,   301,   300,   303,   302,   320,
     319,   322,   321,   324,   323,   326,   325,   328,   327,   330,
     329,   332,   331,   334,   333,   336,   335,   338,   337,   340,
     339,   342,   341,   344,   343,   346,   345,   348,   347,   350,
     349,   353,   351,   352,   354,   357,   356,   359,   358,   361,
     360,   363,   362,   365,   364,   194,   193,   366,   367,   368,
     370,   369,   372,   371,   374,   373,   376,   375,   378,   377,
       0,     0,     0,   424,     0,   425,     0,   428,     0,     0,
     440,     0,     0,   443,     0,     0,   445,     0,     0,     0,
       0,     0,    58,     0,    27,     0,     0,     0,     0,    56,
       0,     0,     0,     0,    62,    64,    70,     0,     0,     0,
       0,     0,     0,   832,   829,   833,   836,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,   785,   787,   789,   792,     0,     0,
       0,     0,     0,   806,   808,   810,   812,     0,     0,     0,
       0,     0,     0,     0,   662,   663,   664,   589,     0,   601,
     602,   547,     0,     0,   539,   542,   543,   544,     0,   593,
     594,   604,   605,   606,     0,     0,     0,   535,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,   437,
       0,     0,     0,    29,   416,    54,    44,    43,    51,    52,
       0,   534,   529,   532,   533,   531,     0,     0,   675,     0,
     697,     0,   708,     0,   719,     0,   686,     0,     0,   473,
     474,   475,   486,   479,   487,   480,   485,   477,   484,   476,
       0,   478,     0,   481,     0,   482,   575,   621,   620,   619,
       0,     0,     0,     0,     0,   608,     0,     0,   607,     0,
     483,     0,   488,     0,   614,   613,   612,     0,   595,   597,
     596,   609,   610,   611,   622,     0,   831,     0,   828,     0,
     835,   723,     0,   740,     0,   744,     0,     0,   748,     0,
     767,     0,   770,     0,   751,     0,   757,     0,   754,     0,
     760,     0,   763,     0,     0,   776,     0,   779,     0,   773,
       0,   782,     0,     0,     0,     0,     0,     0,     0,     0,
     798,     0,   801,     0,   804,     0,     0,     0,     0,     0,
     567,     0,     0,   815,     0,   818,     0,   727,     0,   730,
       0,   733,     0,   822,     0,   548,   541,   540,   422,   538,
     577,   578,   579,   580,   581,   582,   583,   584,   585,   586,
       0,   587,   590,   603,     0,   546,   738,     0,   545,   426,
       0,   438,     0,   441,     0,     0,   399,   395,   396,     0,
     432,     0,     0,    28,     0,     0,     0,     0,     0,     0,
     522,     0,   665,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,   492,   455,   454,   461,   453,
     452,   460,     0,     0,     0,   517,     0,     0,   660,   643,
     646,   625,   645,   624,     0,     0,     0,   830,   595,   596,
     610,   611,     0,     0,     0,     0,   496,     0,   501,   459,
     458,   457,   456,     0,     0,   513,     0,   590,   644,   623,
       0,     0,     0,     0,     0,     0,     0,   467,   466,   465,
     464,   468,   463,   462,   472,   471,   470,   469,     0,     0,
       0,     0,     0,     0,     0,   827,   834,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,   759,     0,
     762,     0,   765,     0,     0,     0,     0,     0,     0,     0,
       0,   784,   786,   788,     0,     0,   791,     0,     0,     0,
     549,   536,     0,     0,     0,     0,     0,    45,     0,     0,
     805,   807,   809,   811,     0,   569,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   823,     0,     0,   661,
       0,   600,     0,   598,     0,   599,     0,     0,     0,     0,
       0,     0,     0,     0,     0,    53,   313,     0,     0,     0,
     676,     0,   674,     0,   667,     0,   668,     0,     0,     0,
     666,   698,     0,   696,     0,   689,     0,   690,     0,   688,
     709,     0,   707,     0,   700,     0,   701,     0,   699,   720,
       0,   718,     0,   711,     0,   712,     0,   710,   687,     0,
     685,     0,   678,     0,   679,     0,   677,   491,     0,   489,
     507,    36,    37,    38,    39,    40,    41,    42,   505,   506,
     504,   502,   503,   516,   514,   515,   570,   574,   576,     0,
       0,     0,     0,   618,   640,   617,   616,   615,   495,     0,
     493,   500,   498,   499,   497,   512,     0,    57,     0,   520,
     508,   510,   509,     0,   657,   639,   656,   638,   652,   631,
     653,   632,   633,   634,   635,   648,   627,   647,   626,   651,
     630,   650,   629,   649,   628,   655,   637,   654,   636,   724,
       0,   721,   741,     0,   745,   743,   742,   746,   749,   747,
     768,   766,   771,   769,   752,   750,   758,   756,   755,   753,
     764,   761,   777,   775,   780,   778,   774,   772,   783,   781,
     793,   790,   795,   794,     0,     0,   797,   796,   800,   799,
     803,     0,     0,   802,     0,     0,     0,   566,   564,     0,
     816,     0,   819,   817,   725,   726,   728,   729,   731,   732,
     826,     0,   825,   588,   592,     0,   737,   590,     0,     0,
       0,     0,     0,   397,   398,     0,   436,     0,   408,   409,
     423,     0,     0,     0,     0,   526,   524,   525,   523,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,   658,   641,   659,   642,     0,     0,     0,     0,
     537,   550,    49,    46,    47,   568,     0,     0,     0,   551,
     557,   565,   563,    57,     0,     0,   821,     0,     0,     0,
     427,   439,   442,   444,   400,   401,   413,   672,   673,   670,
     671,   669,   694,   695,   692,   693,   691,   705,   706,   703,
     704,   702,   716,   717,   714,   715,   713,   683,   684,   681,
     682,   680,     0,     0,   573,   521,   519,   518,   591,   722,
     739,     0,   560,     0,     0,     0,     0,   813,   814,   824,
     736,   735,   734,   572,   571,    48,     0,   561,   555,   562,
       0,     0,     0
};

/* YYDEFGOTO[NTERM-NUM].  */
static const yytype_int16 yydefgoto[] =
{
      -1,   172,   173,   174,   175,   176,   177,   178,   179,   478,
     765,   479,   480,  1298,   868,  1199,   481,   482,   918,   180,
     398,   399,   400,   181,   403,   404,   182,   183,   184,   185,
     483,   484,   485,   486,   432,   416,   417,   419,   186,   187,
     760,   860,   421,   423,   425,   188,  1081,  1114,  1082,  1143,
    1144,   919,   920,   921,   922,   923,   924,  1330,  1054,   487,
     488,   925,  1190,   833,   834,   835,  1488,  1408,  1409,   836,
     837,  1307,  1308,   926,  1087,  1020,  1021,  1022,   927,   928,
     929,   930,   841,   931,  1224,   932,   933,  1024,   844,   934,
     935,  1102,   845,   846,   847,  1004,  1421,  1422,   848
};

/* YYPACT[STATE-NUM] -- Index in YYTABLE of the portion describing
   STATE-NUM.  */
#define YYPACT_NINF -1039
static const yytype_int16 yypact[] =
{
    2596,  -198,    18,    19,  -172,  -169,  -160,  -157,  -120,  -101,
   -1039,   -98,   -90,   -80,   -72,   -57,   -51,   -33,   -17,     5,
       6,    11,    12,    16,    35,    69,   283,   527,   528,   618,
     637,   656,   688,   690,   704,   711,   712,   783,   799,   819,
     823,   824,   825,   836,   847,   848,   855,   857,   858,   859,
     860,   861,   872,   877,   878,   879,   880,   881,   882,   357,
     358,   395,   396,   400,   405,   406,   415,   439,   451,   475,
     515,   518,   523,   531,   534,   535,   552,   103,   132,    39,
     553,   558,   562,   582,   587,   598,   624,   657,   663,   679,
     694,   716,   729,   730,   734,   740,   747,   766,   780,   794,
     798,   807,   883,   885,   886,   888,   813,   840,   846,   853,
     865,   887,   893,   896,   902,   903,   938,   939,   945,   947,
     950,   952,   960,   961,   969,   970,   971,   972,   973,   975,
     979,   989,   990,   992,   993,   995,   996,  1002,  1003,  1005,
    1006,  1007,  1009,  1010,  1012,  1013,  1016,  1020,  1022,   889,
    1023,  1024,  1056,  1057,  1059,  1065,  1066,  1067,  1068,  1069,
    1071,  1072,  1075,  1076,    41,    70,   123,   134,   160, -1039,
   -1039, -1039,   173,  1514, -1039,    92,   536,   247,   894,   915,
   -1039, -1039, -1039,  -249, -1039, -1039,   -11, -1039, -1039, -1039,
   -1039,   -36, -1039,   193,   237,   723,    28,   268,   288,   284,
     308,   328,   478,   488,    42,   164,   526,   209,    37,    66,
     550,   599,   612, -1039,   453, -1039,   603, -1039,   620, -1039,
     -97, -1039,   -97, -1039,   -97, -1039,   -97, -1039,   643, -1039,
     652, -1039,   660, -1039,   673, -1039,   715, -1039,   725, -1039,
     737, -1039,   785, -1039,   821, -1039,   842, -1039,   874, -1039,
     924, -1039,  1044, -1039,  1045, -1039,  1046, -1039,  1047, -1039,
    1048, -1039,  1049, -1039,  1055, -1039,  1058, -1039,  1060, -1039,
    1062, -1039,  1063, -1039,  1074, -1039,  1077, -1039,  1078, -1039,
    1079,   613,   713,   619,   621,   628,   629,   634,   290,   291,
     292,   307,   309,   312,   635,   636,   220,   238,   639, -1039,
   -1039, -1039,   240, -1039, -1039,   726,   641,   642,   129,   163,
     167,   226,   227,   644,   645,   647,   648,   313,   320,   459,
     235,   649,   650,   651,   653,    21,   654,   658, -1039,  1126,
   -1039,  1127, -1039,  1128, -1039,  1129,   659,   666,   667,   668,
     676,   680,   681,   682,   685,   686,   691,   692,   693,   705,
     706,   904,   709,   710,   718,   731,   241,   243,   248,   732,
     736,   742,    61,   746,   748,   749,   751,   752,   753,   754,
     756,   757,   758,   759,   760,   762,   763,   764,   282, -1039,
    1130,   767,   768,   769,   770,   771,   250,  1131,  1133,  1137,
     772,   773,   775,   776,   777, -1039, -1039, -1039, -1039,   937,
     677, -1039, -1039, -1039,   987, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039,  -198, -1039,    40,    34,   722, -1039,    43, -1039,
      60, -1039,    72, -1039,    20, -1039,    -9,  1138, -1039, -1039,
   -1039, -1039,  1041, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039,  1139, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039,   743, -1039, -1039, -1039, -1039,  1027,  1053, -1039,  1042,
    1117,  1050,  1274, -1039, -1039, -1039, -1039, -1039,  -189, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,  1132, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039,   743, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
    -252,  1141,  -252, -1039,   -36, -1039, 11632, -1039, 11632,   -36,
   -1039, 11632,   -36, -1039, 11632,   -36, -1039,  1135,  1109,  1181,
   11632,    73, -1039,  1143,  1136,  1187,  1183,   296,   907, -1039,
    1027,   272,   279,  1142, -1039, -1039, -1039,  1184,    82,    83,
      84,    85,    86, 10602, 10915, 11000, -1039,    87,    89,    90,
    1189,    98,   101,   104,   107,   108,   111,   114,   130,  1190,
     145,   148,   150,   158,  1191,  1192,  1193,  1194,  1195, 11108,
     169,   170,   180,  1196,  1197,  1198,  1199,  6225, 11108,   181,
     183,   186,   187,   203, -1039, -1039, -1039, -1039,   207, -1039,
   -1039, -1039,    36,  3573, -1039, -1039, -1039, -1039,   940, -1039,
   -1039, -1039, -1039, -1039,  1182,  1206,   208, -1039,  1207,  3643,
    1202,  3957,  1203,  4026,  1208,  1209,   128,  1216,  4095, -1039,
    1210,  1222,  1027, -1039, -1039, -1039, -1039, -1039, -1039,  1233,
    1234, -1039, -1039, -1039, -1039, -1039,  1213,  1245, -1039,   113,
   -1039,   139, -1039,   146, -1039,   151, -1039,   174,   524, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
    -203, -1039,  -203, -1039,   820, -1039, -1039,  1139, -1039, -1039,
    6540,  6627,  6733,  1247,  1248, -1039,  5617, 11511, -1039,   808,
   -1039,   625, -1039,   818, -1039, -1039, -1039,   940, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039,  7048,  2223,  5717,  2223,  6032,
    2223, -1039,   217, -1039,   251, -1039,  7135,  1257, -1039,   253,
   -1039,   254, -1039,   255, -1039,   256, -1039,   778, -1039,   781,
   -1039,  1251, -1039,   138,  1252, -1039,   257, -1039,   258, -1039,
     259, -1039,   260,  1253,  1254,  1255,   162,   727,  7241, 10500,
   -1039,   202, -1039,   782, -1039,    88,  1256,  1259,  1260,  1261,
   -1039,  1113, 10500, -1039,   261, -1039,   264, -1039,   -32, -1039,
     419, -1039,   486, -1039,  1286, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
    1270, -1039,  1284, -1039, 11108, -1039, -1039,   249, -1039, -1039,
    1287, -1039,  1288, -1039,  1289,  1290, -1039,  1331,  1362,    -9,
   -1039, 11632,  1419, -1039,  -247,  1420,   -92, 11632,  1411,  -234,
   -1039,   -79, -1039,   -68,   854,  1412,   377,   416,   483,   875,
    1445,   522,   580,   606,   957,  1456,   607,   630,   662,   958,
    1457,   735,   833,   849,   978, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039,  7556,  5209,  5524, -1039,  1107,  1467, -1039, -1039,
   -1039, -1039, -1039, -1039,  7643,  7749,  1459, -1039,  1503,  1504,
    1505,  1506,  1185,  1464,  1462,  4165, -1039,  8064, -1039, -1039,
   -1039, -1039, -1039,  8151,  8257, -1039,  2363,  1463, -1039, -1039,
    8572,  8659,  8765,  9080, 11108, 11108, 11108, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,  9167,  9273,
    9588,  9675,  9781, 10096, 10183, -1039, -1039,  1466,  1473,  1472,
    1496,  1499,  1498,    10,  1305,  1500,  1501,  1502,  1507,  1508,
    1509,  1510,  1511,  1512,  1516,  1517,  1518,  1533, -1039,  1534,
   -1039,  1535, -1039,  1536,  1537,  1538,  1539,  1548,  1559,  1561,
    1562, -1039, -1039, -1039,  1563,  1565, -1039,  1574,  1575, 11632,
    1765, -1039,  1579,  1580,  1581,  1589,  1602,  1497,  1596,  1604,
   -1039, -1039, -1039, -1039,    63, -1039,  1605,  1608,  1607,  1617,
    1628,  1630,  1631,  1632,  1634,  1643, 11108,  1523,  1603,  2223,
     863, -1039,   940, -1039,  1646, -1039, 11632, 11632, 11632, 11632,
    1513,  1655,  1546,  4479,  1662, -1039, -1039,  1650,  1658,  4548,
   -1039,   -13, -1039,  1681, -1039,  1682, -1039,   184,   230,  1683,
   -1039, -1039,    -4, -1039,  1684, -1039,  1685, -1039,  1686, -1039,
   -1039,    -3, -1039,  1687, -1039,  1696, -1039,  1707, -1039, -1039,
       3, -1039,  1709, -1039,  1710, -1039,  1711, -1039, -1039,     4,
   -1039,  1713, -1039,  1722, -1039,  1723, -1039, -1039,  1900,  1083,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,  1083,
   -1039, -1039,  1083, -1039, -1039, -1039,  1714, -1039,  1730,  1719,
    1515,  1767,  1768, -1039, -1039, -1039, -1039, -1039, -1039,  1969,
    1083, -1039,  1083, -1039,   900, -1039,  2287,   743,  1831,  1790,
   -1039,  -152,  1083,  -186, -1039,  2245, -1039,  1134, -1039,  1028,
   -1039,  1204,  1786,  2197,  1258, -1039,   408, -1039,   408, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039,  1083, -1039,   900, -1039,
    1777, -1039, -1039,  1778, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039,  4617, 11319, -1039, -1039, -1039, -1039,
   -1039,   315,  1027, -1039,  1769,  6119,  1770, -1039, -1039,   576,
   -1039, 11421, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
    2223,   905, -1039, -1039, -1039,  -252, -1039, -1039,   239,  4687,
    5001,  5070,  5139, -1039, -1039,  -253, -1039,  1784, -1039, -1039,
   -1039,  1775,  1776,  1779,  1780, -1039, -1039, -1039, -1039,  1781,
    1782,  1783,  1785,  1787,  1788,  1812,  1813,  1814,  1815,  1816,
    1817,  1819,  1820,  1821,  1823,  1824,  1825,  1828,  1834,  1835,
     -73,  1467, -1039, -1039, -1039, -1039,     8,  1773,  1836,  1837,
   -1039, -1039, -1039, -1039,  1832, -1039,  1833, 10289,  1838,  2223,
   11632, -1039, -1039,  1841,  2180, 11108, -1039,  1843,  1844,  1846,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039,  1842,  1845, -1039, -1039,   743, -1039, -1039, -1039,
   -1039,  -247, 11632,  1847,  1848,  2913,  2982, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039,  3051, 11632, 11632, -1039,
    3121,  3435,  3504
};

/* YYPGOTO[NTERM-NUM].  */
static const yytype_int16 yypgoto[] =
{
   -1039, -1039, -1039,  1924, -1039, -1039, -1039, -1039, -1039,  -360,
    1237,  1393,  -190,  1080, -1038, -1039, -1039,  -162,  -196, -1039,
    1422, -1039,  -164, -1039,  1423, -1039, -1039,  -422, -1039, -1039,
    -869,  1870,  1689,  1871,   289, -1039, -1039, -1039, -1039, -1039,
   -1039, -1039, -1039, -1039, -1039, -1039,  -887, -1039,  -841, -1039,
   -1039,  1084, -1039, -1039, -1039,  1111, -1039, -1039,   224,  -627,
   -1039,  -107,  -984,  -565,  -826, -1039,   708,   789, -1039, -1039,
   -1039,   698, -1039, -1039, -1039, -1039,   982,  -786,  -261,  -715,
   -1039,  -555, -1039, -1039, -1039,  -531,  -691, -1039, -1039, -1039,
   -1039,   112,   245, -1039, -1039, -1039, -1039,   707, -1039
};

/* YYTABLE[YYPACT[STATE-NUM]].  What to do in state STATE-NUM.  If
   positive, shift that token.  If negative, reduce the rule which
   number is the opposite.  If YYTABLE_NINF, syntax error.  */
#define YYTABLE_NINF -821
static const yytype_int16 yytable[] =
{
     447,   402,   680,   453,   758,   443,  1235,  1009,  1205,  1535,
    1053,  1003,  1058,  1083,  1063,  1084,  1068,  1086,  1073,   190,
     192,   756,   614,  1009,   500,  1009,   501,  1009,   502,   444,
     503,   839,  1009,   839,  1113,   745,   839,  1005,   470,   839,
     304,   743,   395,   462,   747,   839,   866,   490,    10,  1441,
    1504,   426,   396,  1505,   397,   843,   867,   843,  1450,  1455,
     843,   750,   679,   843,  1404,  1460,  1465,   489,   427,   843,
     213,   401,   189,   753,   859,  1076,  1077,  1241,  1107,  1242,
    1079,  1080,  1116,   878,   880,   882,   884,   886,   941,  1196,
     943,   945,   545,   547,   549,   551,   553,   555,   193,   948,
     771,   194,   950,   445,   299,   952,   414,   415,   954,   956,
     195,   772,   958,   196,  1048,   960,   764,   446,   839,  1405,
    1406,   597,   599,   601,   405,   438,   439,   440,   441,  1036,
     573,   962,  1424,   301,   839,   407,   839,  1247,   839,  1169,
    1055,  1117,   843,   839,   873,   875,   965,  1060,  1248,   967,
     197,   969,  1065,  1191,   438,   439,   440,   441,   843,   971,
     843,   409,   843,  1184,   576,   464,  1191,   843,   579,   198,
     980,   982,   199,   411,  1049,  1070,   438,   439,   440,   441,
     200,   984,   993,   849,   995,  1445,   851,   997,   999,   853,
     201,   840,   445,   840,   433,   858,   840,   445,   202,   840,
    1056,  1098,   839,  1192,  1001,   840,   446,  1061,  1003,  1026,
     468,   446,  1066,   203,  1237,   842,   445,   842,  1147,   204,
     842,   560,  1098,   842,  1098,  1101,   843,   582,   585,   842,
     446,  1447,  1243,  1532,  1244,  1071,   602,   205,   435,   562,
    1498,   566,   667,  1245,   669,  1246,  1101,  1329,  1101,   671,
    1220,   725,  1150,   206,  1156,  1158,  1160,  1162,  1173,  1175,
    1177,  1179,  1206,  1098,   839,  1208,   420,   429,   430,   448,
     431,  1210,   396,   871,   397,   207,   208,   839,   840,  1009,
     874,   209,   210,   711,   215,   452,   211,  1101,   843,   450,
    1442,   544,   546,   548,   840,   169,   840,   865,   840,  1451,
    1456,   843,   842,   840,   428,   212,  1461,  1466,   550,   454,
     552,  1536,  1223,   554,   596,   475,  1482,   445,   842,   769,
     842,   598,   842,  1365,  -820,   477,   839,   842,   475,   456,
     442,   446,   839,  -429,  -430,   191,  1225,   757,   477,   214,
     471,   472,   473,   474,   475,   396,   463,   397,  1006,  -421,
     843,   476,  1105,   305,   477,  -420,   843,   744,   748,   442,
     749,  1099,   840,  1483,   471,   472,   473,   474,   475,   471,
     472,   473,   474,   475,   396,   751,   397,   752,   477,  1407,
     476,   442,  1099,   477,  1099,  1100,   842,   754,  -435,   755,
     839,   471,   472,   473,   474,   475,   879,   881,   883,   885,
     887,   942,   302,   944,   946,   477,  1100,  1009,  1100,   300,
     870,  1481,   949,  1009,   843,   951,   612,  1050,   953,  1051,
     475,   955,   957,  1099,   840,   959,  1052,   445,   961,   406,
     477,  1037,  1086,   574,  1038,   575,  1427,   840,   303,  1086,
     408,   446,   612,  1050,   963,  1057,   475,  1100,   842,   612,
    1050,  1170,  1062,   475,   612,  1050,   477,  1067,   475,   966,
     600,   842,   968,   477,   970,  1185,   410,   577,   477,   578,
     465,   580,   972,   581,   839,  1186,  1233,   612,  1050,   458,
    1072,   475,  1239,   981,   983,   838,   840,   838,  1446,   460,
     838,   477,   840,   838,   985,   994,   434,   996,   843,   838,
     998,  1000,   764,  1555,   827,   471,   472,   473,   474,   475,
     842,   839,   839,   839,   839,   469,   842,  1002,   839,   477,
    1148,  -820,  1027,  1149,   839,  1075,   561,   466,   217,   219,
     583,   586,   584,   587,  1448,   843,   843,   843,   843,   603,
     436,   604,   843,   396,   563,   397,   567,   668,   843,   670,
     840,   491,   418,   216,   672,  1221,   726,  1151,   830,  1157,
    1159,  1161,  1163,  1174,  1176,  1178,  1180,  1207,  1009,  1191,
    1209,   449,   838,   445,   842,   872,   472,   473,   402,   445,
     445,   445,   872,   472,   473,   712,   713,   446,   838,   866,
     838,   451,   838,   446,   446,   446,   445,   838,   445,   867,
     493,   445,   445,  1009,  1009,  1009,  1009,  1537,   866,   445,
     446,   455,   446,   495,   531,   446,   446,  1232,   867,   221,
     534,  1193,   536,   446,  1394,  1197,  1108,   281,   282,   538,
     540,   457,  1405,  1406,   840,   542,   556,   558,   223,   832,
     564,   832,   569,   571,   832,   588,   590,   832,   592,   594,
     605,   607,   609,   832,   611,   616,   838,   225,   842,   618,
     624,  1429,  1430,  1431,  1432,   283,   284,   626,   628,   630,
     285,   840,   840,   840,   840,   286,   287,   632,   840,   839,
     839,   634,   636,   638,   840,   288,   640,   642,  1252,   227,
    1253,   229,   644,   646,   648,   842,   842,   842,   842,  1140,
    1141,  1142,   842,   843,   843,   231,   650,   652,   842,   289,
     659,   661,   233,   235,   839,   839,   839,   839,   838,   663,
    1009,   290,  1212,   396,   437,   397,   832,  1254,  1187,  1255,
    1009,   838,   665,   673,  1009,  1009,  1009,   675,   843,   843,
     843,   843,   832,   677,   832,   291,   832,   681,   445,   683,
     685,   832,   687,   689,   691,   693,   497,   695,   697,   699,
     701,   703,   446,   705,   707,   709,  1222,  1171,   715,   717,
     719,   721,   723,   730,   732,   839,   734,   736,   738,  1164,
     838,   459,  1166,  1194,   237,   292,   838,  1188,   293,  1214,
     396,   461,   397,   294,  1256,  1198,  1257,   218,   220,   843,
     239,   295,  1076,  1077,   296,   297,  1078,  1079,  1080,  1106,
     832,   438,   439,   440,   441,   438,   439,   440,   441,  1115,
     241,  1085,   298,   306,   243,   245,   247,   839,   307,   467,
     839,   839,   308,  1261,  1211,  1262,  1213,   249,  1215,   840,
     840,   839,   839,   839,   838,   839,   839,   839,   251,   253,
    1238,   843,   309,   492,   843,   843,   255,   310,   257,   259,
     261,   263,   265,   842,   842,   843,   843,   843,   311,   843,
     843,   843,   832,   267,   840,   840,   840,   840,   269,   271,
     273,   275,   277,   279,   328,   832,   330,   332,   222,   334,
     379,  1263,  1491,  1264,   312,   936,   938,   940,   842,   842,
     842,   842,   494,  1076,  1077,   654,   498,   224,  1079,  1080,
    1109,  1110,  1111,  1112,   422,   496,   532,  1265,  1270,  1266,
    1271,   979,   535,   499,   537,  1546,   226,   313,   838,   991,
     992,   539,   541,   314,   832,   840,   424,   543,   557,   559,
     832,  1272,   565,  1273,   570,   572,   504,   589,   591,   315,
     593,   595,   606,   608,   610,   505,   612,   617,   228,   842,
     230,   619,   625,   506,   316,   838,   838,   838,   838,   627,
     629,   631,   838,  1274,   232,  1275,   507,  1556,   838,   633,
    1560,   234,   236,   635,   637,   639,   317,   840,   641,   643,
     840,   840,  1561,  1562,   645,   647,   649,   741,   832,   318,
     319,   840,   840,   840,   320,   840,   840,   840,   651,   653,
     321,   842,   660,   662,   842,   842,   442,   322,   508,   533,
     442,   664,  1089,  1091,  1093,   842,   842,   842,   509,   842,
     842,   842,   568,   777,   666,   674,   323,   746,   850,   676,
     510,   852,  1484,   763,   854,   678,  1279,  1119,  1280,   682,
     324,   684,   686,   238,   688,   690,   692,   694,  1154,   696,
     698,   700,   702,   704,   325,   706,   708,   710,   326,   240,
     716,   718,   720,   722,   724,   731,   733,   327,   735,   737,
     739,  1165,   832,   336,  1167,  1195,  1076,  1077,   511,   242,
    1078,  1079,  1080,   244,   246,   248,  1076,  1077,  1076,  1077,
    1078,  1079,  1080,  1079,  1080,  1059,   250,  1064,  1303,  1069,
     337,  1074,   655,   656,   657,   658,   338,   252,   254,   832,
     832,   832,   832,   339,   512,   256,   832,   258,   260,   262,
     264,   266,   832,   838,   838,   340,  1219,  1477,   438,   439,
     440,   441,   268,  1247,  1281,   513,  1282,   270,   272,   274,
     276,   278,   280,   329,  1248,   331,   333,   341,   335,   380,
    1283,  1103,  1284,   342,  1247,  1249,   343,  1250,   838,   838,
     838,   838,   344,   345,  1425,  1248,  1426,   514,   889,   890,
     891,   892,  1103,   894,  1103,   896,  1258,   898,  1259,  1138,
    1139,  1140,  1141,  1142,  1289,  1299,  1302,  1010,  1011,  1012,
    1013,  1014,  1015,  1016,  1017,  1018,  1310,  1312,   346,   347,
     471,   472,   473,   474,   475,   348,  1495,   349,  1496,  1320,
     350,   476,   351,  1103,   477,  1322,  1324,   515,  1332,   838,
     352,   353,  1335,  1337,  1339,  1341,  1342,  1343,  1344,   354,
     355,   356,   357,   358,  1019,   359,  1247,  1247,   740,   360,
    1346,  1348,  1350,  1352,  1354,  1356,  1358,  1248,  1248,   361,
     362,  1497,   363,   364,  1499,   365,   366,  1247,  1267,  1276,
    1268,  1277,   367,   368,  1533,   369,   370,   371,  1248,   372,
     373,   838,   374,   375,   838,   838,   376,   832,   832,  1285,
     377,  1286,   378,   381,   382,   838,   838,   838,   742,   838,
     838,   838,  1123,  1124,  1125,  1126,  1127,  1128,  1129,  1130,
    1131,  1132,  1133,  1134,  1135,  1136,  1137,  1138,  1139,  1140,
    1141,  1142,   832,   832,   832,   832,   383,   384,  1420,   385,
     471,   472,   473,   474,   475,   386,   387,   388,   389,   390,
    1331,   391,   392,   442,   477,   393,   394,   516,   517,   518,
     519,   520,   521,   471,   472,   473,   474,   475,   522,   761,
     475,   523,   767,   524,   476,   525,   526,   477,  1134,  1135,
    1136,  1137,  1138,  1139,  1140,  1141,  1142,   527,   770,   856,
     528,   529,   530,   832,  1120,  1121,  1122,  1123,  1124,  1125,
    1126,  1127,  1128,  1129,  1130,  1131,  1132,  1133,  1134,  1135,
    1136,  1137,  1138,  1139,  1140,  1141,  1142,  1122,  1123,  1124,
    1125,  1126,  1127,  1128,  1129,  1130,  1131,  1132,  1133,  1134,
    1135,  1136,  1137,  1138,  1139,  1140,  1141,  1142,  1204,   620,
     621,   622,   623,   714,   727,   832,   728,   768,   832,   832,
     729,   759,   762,   773,   775,   855,   861,   862,   876,   832,
     832,   832,  1023,   832,   832,   832,  1120,  1121,  1122,  1123,
    1124,  1125,  1126,  1127,  1128,  1129,  1130,  1131,  1132,  1133,
    1134,  1135,  1136,  1137,  1138,  1139,  1140,  1141,  1142,  1124,
    1125,  1126,  1127,  1128,  1129,  1130,  1131,  1132,  1133,  1134,
    1135,  1136,  1137,  1138,  1139,  1140,  1141,  1142,  1314,   857,
     863,   864,   877,   947,   964,   973,   974,   975,   976,   977,
     986,   987,   988,   989,    -2,   412,  1039,  1489,  1025,  1028,
    1030,  1032,  1042,  1494,  1046,  1041,  1034,  1035,   -11,   -11,
     -13,     2,     3,   -16,   -18,   -20,  1127,  1128,  1129,  1130,
    1131,  1132,  1133,  1134,  1135,  1136,  1137,  1138,  1139,  1140,
    1141,  1142,     4,  1044,  1045,     5,     6,     7,     8,     9,
    1047,  1094,  1095,  1155,  1168,  1172,  1181,  1182,  1183,  1200,
    1217,    10,  1201,  1202,  1203,  1230,  1120,  1121,  1122,  1123,
    1124,  1125,  1126,  1127,  1128,  1129,  1130,  1131,  1132,  1133,
    1134,  1135,  1136,  1137,  1138,  1139,  1140,  1141,  1142,  1489,
    1216,  1218,  1226,  1227,  1228,  1229,  1231,  1420,    11,    12,
      13,    14,    15,    16,    17,    18,    19,    20,  1366,    21,
      22,    23,    24,    25,    26,    27,    28,    29,    30,    31,
      32,    33,    34,    35,    36,    37,    38,    39,    40,    41,
      42,    43,    44,    45,    46,    47,    48,    49,    50,    51,
      52,    53,    54,    55,    56,    57,    58,    59,    60,    61,
      62,    63,    64,    65,    66,    67,    68,    69,    70,    71,
      72,    73,    74,    75,    76,    77,    78,    79,    80,    81,
      82,    83,    84,    85,    86,    87,    88,    89,    90,    91,
      92,    93,    94,    95,    96,    97,    98,    99,   100,   101,
     102,   103,   104,   105,   106,   107,   108,   109,   110,   111,
     112,   113,   114,   115,   116,   117,   118,   119,   120,   121,
     122,   123,  1234,  1236,  1240,  1251,   124,   125,   126,   127,
     128,   129,   130,   131,   132,   133,   134,   135,   136,   137,
     138,   139,   140,   141,   142,   143,   144,   145,   146,   147,
     148,   149,   150,   151,   152,   153,   154,   155,  1260,   156,
     157,   158,   159,   160,   161,   162,   163,   164,   165,  1269,
    1278,  1306,  1313,  -593,  -594,  -605,  -606,  1315,  1316,  1359,
    1333,   166,   167,   168,  1360,  1361,  1120,  1121,  1122,  1123,
    1124,  1125,  1126,  1127,  1128,  1129,  1130,  1131,  1132,  1133,
    1134,  1135,  1136,  1137,  1138,  1139,  1140,  1141,  1142,  1362,
    1363,  1364,  1395,  1367,  1368,  1369,  1435,  1401,   169,  1433,
    1370,  1371,  1372,  1373,  1374,  1375,   170,  1019,  1473,  1376,
    1377,  1378,  1003,   171,  -522,  -522,  -522,  -522,  -522,  -522,
    -522,  -522,  -522,  -522,  -522,  -522,  1379,  1380,  1381,  1382,
    1383,  1384,  1385,  -522,  -522,  -522,  -522,  -522,  -522,  -522,
    -522,  1386,  -522,  -522,  -522,  -522,  -522,  -522,  -522,  -522,
    -522,  -522,  1387,  -522,  1388,  1389,  1390,  -522,  1391,  -522,
    -522,  -522,  -522,  -522,  -522,  -522,  -522,  1392,  1393,  -522,
    -522,  -522,  1396,  1397,  1398,  -522,  -522,  -522,  -522,  -522,
    -522,  1003,  1399,  -490,  -490,  -490,  -490,  -490,  -490,  -490,
    -490,  -490,  -490,  -490,  -490,  1400,  1402,  1403,  1410,  1411,
    1412,  1424,  -490,  -490,  -490,  -490,  -490,  -490,  -490,  -490,
    1413,  -490,  -490,  -490,  -490,  -490,  -490,  -490,  -490,  -490,
    -490,  1414,  -490,  1415,  1416,  1417,  -490,  1418,  -490,  -490,
    -490,  -490,  -490,  -490,  -490,  -490,  1419,  1428,  -490,  -490,
    -490,  1434,  1437,  1438,  -490,  -490,  -490,  -490,  -490,  -490,
    1085,  1439,  -494,  -494,  -494,  -494,  -494,  -494,  -494,  -494,
    -494,  -494,  -494,  -494,  1443,  1444,  1449,  1452,  1453,  1454,
    1457,  -494,  -494,  -494,  -494,  -494,  -494,  -494,  -494,  1458,
    -494,  -494,  -494,  -494,  -494,  -494,  -494,  -494,  -494,  -494,
    1459,  -494,  1462,  1463,  1464,  -494,  1467,  -494,  -494,  -494,
    -494,  -494,  -494,  -494,  -494,  1468,  1469,  -494,  -494,  -494,
    1471,  1470,  1472,  -494,  -494,  -494,  -494,  -494,  -494,  1120,
    1121,  1122,  1123,  1124,  1125,  1126,  1127,  1128,  1129,  1130,
    1131,  1132,  1133,  1134,  1135,  1136,  1137,  1138,  1139,  1140,
    1141,  1142,  1125,  1126,  1127,  1128,  1129,  1130,  1131,  1132,
    1133,  1134,  1135,  1136,  1137,  1138,  1139,  1140,  1141,  1142,
    1474,  1475,  1476,  1478,  1479,  1485,  -522,  1506,  1507,  1508,
    1490,  1538,  1509,  1510,  1511,  1512,  1513,   413,  1514,  1043,
    1515,  1516,  -522,  -522,  -522,  -522,  -522,  -522,  -522,  -522,
    -522,  -522,  -522,  -522,  -522,  -522,  -522,  -522,  -522,  -522,
    -522,  -522,  -522,  -522,  -522,  1517,  1518,  1519,  1520,  1521,
    1522,  -522,  1523,  1524,  1525,  -522,  1526,  1527,  1528,  -522,
    -522,  1529,  -522,  -522,  -522,  -820,  -522,  1530,  1531,  1539,
    1540,  -522,  1541,  1542,  1547,  -490,  1550,  1551,  1545,  1552,
    1553,   869,   774,  1554,  1301,   776,   766,  1557,  1558,  1534,
    1304,  -490,  -490,  -490,  -490,  -490,  -490,  -490,  -490,  -490,
    -490,  -490,  -490,  -490,  -490,  -490,  -490,  -490,  -490,  -490,
    -490,  -490,  -490,  -490,   613,  1544,   615,  1305,  1492,  1423,
       0,     0,  1549,     0,  -490,     0,     0,     0,  -490,  -490,
       0,  -490,  -490,  -490,  -820,  -490,     0,     0,     0,     0,
    -490,     0,     0,     0,  -494,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
    -494,  -494,  -494,  -494,  -494,  -494,  -494,  -494,  -494,  -494,
    -494,  -494,  -494,  -494,  -494,  -494,  -494,  -494,  -494,  -494,
    -494,  -494,  -494,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,  -494,     0,     0,     0,  -494,  -494,     0,
    -494,  -494,  -494,     0,  -494,     0,     0,     0,  1085,  -494,
    -511,  -511,  -511,  -511,  -511,  -511,  -511,  -511,  -511,  -511,
    -511,  -511,     0,     0,     0,     0,     0,     0,     0,  -511,
    -511,  -511,  -511,  -511,  -511,  -511,  -511,     0,  -511,  -511,
    -511,  -511,  -511,  -511,  -511,  -511,  -511,  -511,     0,  -511,
       0,     0,     0,  -511,     0,  -511,  -511,  -511,  -511,  -511,
    -511,  -511,  -511,     0,     0,  -511,  -511,  -511,     0,     0,
       0,  -511,  -511,  -511,  -511,  -511,  -511,     0,     0,     0,
       0,     0,     0,     0,  1325,     0,   778,   779,   780,   781,
     782,     0,     0,     0,     0,   787,   788,   789,     0,     0,
       0,     0,     0,     0,     0,   790,   791,   792,   793,   794,
     795,   796,   797,     0,   798,   799,   800,   801,   802,   803,
     804,   805,   806,   807,     0,   808,     0,     0,     0,     0,
       0,   810,   811,   812,   813,   814,   815,   816,     0,     0,
       0,     0,   819,   820,     0,     0,     0,   821,   822,   823,
     824,   825,   826,   888,   889,   890,   891,   892,   893,   894,
     895,   896,   897,   898,   899,   900,   901,   902,   903,  1326,
     905,  1120,  1121,  1122,  1123,  1124,  1125,  1126,  1127,  1128,
    1129,  1130,  1131,  1132,  1133,  1134,  1135,  1136,  1137,  1138,
    1139,  1140,  1141,  1142,  1126,  1127,  1128,  1129,  1130,  1131,
    1132,  1133,  1134,  1135,  1136,  1137,  1138,  1139,  1140,  1141,
    1142,     0,     0,  1548,  1120,  1121,  1122,  1123,  1124,  1125,
    1126,  1127,  1128,  1129,  1130,  1131,  1132,  1133,  1134,  1135,
    1136,  1137,  1138,  1139,  1140,  1141,  1142,  1121,  1122,  1123,
    1124,  1125,  1126,  1127,  1128,  1129,  1130,  1131,  1132,  1133,
    1134,  1135,  1136,  1137,  1138,  1139,  1140,  1141,  1142,     0,
       0,     0,  -511,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,  -511,  -511,
    -511,  -511,  -511,  -511,  -511,  -511,  -511,  -511,  -511,  -511,
    -511,  -511,  -511,  -511,  -511,  -511,  -511,  -511,  -511,  -511,
    -511,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,  -511,     0,     0,     0,  -511,  -511,     1,  -511,  -511,
    -511,     0,  -511,     0,     0,     0,     0,  -511,     0,     0,
     -11,   -11,   -13,     2,     3,   -16,   -18,   -20,   827,   906,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     4,     0,     0,     5,     6,     7,
       8,     9,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,   907,    10,     0,     0,     0,   908,     0,   909,
     910,   911,   912,     0,   913,   914,  1327,  1328,     0,   915,
     475,   829,   830,     0,     0,     0,     0,   978,   917,     0,
     477,     0,     0,     0,     0,     0,     0,     0,     0,     0,
      11,    12,    13,    14,    15,    16,    17,    18,    19,    20,
       0,    21,    22,    23,    24,    25,    26,    27,    28,    29,
      30,    31,    32,    33,    34,    35,    36,    37,    38,    39,
      40,    41,    42,    43,    44,    45,    46,    47,    48,    49,
      50,    51,    52,    53,    54,    55,    56,    57,    58,    59,
      60,    61,    62,    63,    64,    65,    66,    67,    68,    69,
      70,    71,    72,    73,    74,    75,    76,    77,    78,    79,
      80,    81,    82,    83,    84,    85,    86,    87,    88,    89,
      90,    91,    92,    93,    94,    95,    96,    97,    98,    99,
     100,   101,   102,   103,   104,   105,   106,   107,   108,   109,
     110,   111,   112,   113,   114,   115,   116,   117,   118,   119,
     120,   121,   122,   123,     0,     0,     0,     0,   124,   125,
     126,   127,   128,   129,   130,   131,   132,   133,   134,   135,
     136,   137,   138,   139,   140,   141,   142,   143,   144,   145,
     146,   147,   148,   149,   150,   151,   152,   153,   154,   155,
       0,   156,   157,   158,   159,   160,   161,   162,   163,   164,
     165,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,   166,   167,   168,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
     169,     0,     0,     0,     0,     0,     0,     0,   170,     0,
       0,     0,     0,     0,  1559,   171,   778,   779,   780,   781,
     782,   783,   784,   785,   786,   787,   788,   789,     0,     0,
       0,     0,     0,     0,     0,   790,   791,   792,   793,   794,
     795,   796,   797,     0,   798,   799,   800,   801,   802,   803,
     804,   805,   806,   807,     0,   808,     0,     0,     0,   809,
       0,   810,   811,   812,   813,   814,   815,   816,   817,  -554,
    -554,   818,   819,   820,     0,     0,     0,   821,   822,   823,
     824,   825,   826,  1007,     0,   778,   779,   780,   781,   782,
     783,   784,   785,   786,   787,   788,   789,     0,     0,     0,
       0,     0,     0,     0,   790,   791,   792,   793,   794,   795,
     796,   797,     0,   798,   799,   800,   801,   802,   803,   804,
     805,   806,   807,     0,   808,     0,     0,     0,   809,     0,
     810,   811,   812,   813,   814,   815,   816,   817,  -556,  -556,
     818,   819,   820,     0,     0,     0,   821,   822,   823,   824,
     825,   826,  1007,     0,   778,   779,   780,   781,   782,   783,
     784,   785,   786,   787,   788,   789,     0,     0,     0,     0,
       0,     0,     0,   790,   791,   792,   793,   794,   795,   796,
     797,     0,   798,   799,   800,   801,   802,   803,   804,   805,
     806,   807,     0,   808,     0,     0,     0,   809,     0,   810,
     811,   812,   813,   814,   815,   816,   817,  -558,  -558,   818,
     819,   820,     0,     0,     0,   821,   822,   823,   824,   825,
     826,     0,  1007,     0,   778,   779,   780,   781,   782,   783,
     784,   785,   786,   787,   788,   789,     0,     0,     0,     0,
       0,     0,     0,   790,   791,   792,   793,   794,   795,   796,
     797,     0,   798,   799,   800,   801,   802,   803,   804,   805,
     806,   807,     0,   808,     0,     0,     0,   809,   827,   810,
     811,   812,   813,   814,   815,   816,   817,  -552,  -552,   818,
     819,   820,     0,     0,     0,   821,   822,   823,   824,   825,
     826,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,   828,     0,     0,
       0,   829,   830,     0,     0,   831,     0,     0,     0,  -554,
       0,     0,     0,     0,     0,     0,     0,   827,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   828,     0,     0,     0,
     829,   830,     0,     0,   831,     0,     0,     0,  -556,     0,
       0,     0,     0,     0,     0,     0,   827,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,   828,     0,     0,     0,   829,
     830,     0,     0,   831,     0,     0,     0,  -558,     0,     0,
       0,     0,     0,     0,     0,     0,   827,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,   828,     0,     0,     0,   829,
     830,     0,     0,   831,     0,     0,  1007,  -552,   778,   779,
     780,   781,   782,   783,   784,   785,   786,   787,   788,   789,
       0,     0,     0,     0,     0,     0,     0,   790,   791,   792,
     793,   794,   795,   796,   797,     0,   798,   799,   800,   801,
     802,   803,   804,   805,   806,   807,     0,   808,     0,     0,
       0,   809,     0,   810,   811,   812,   813,   814,   815,   816,
     817,  -559,  -559,   818,   819,   820,     0,     0,     0,   821,
     822,   823,   824,   825,   826,  1007,     0,   778,   779,   780,
     781,   782,   783,   784,   785,   786,   787,   788,   789,     0,
       0,     0,     0,     0,     0,     0,   790,   791,   792,   793,
     794,   795,   796,   797,     0,   798,   799,   800,   801,   802,
     803,   804,   805,   806,   807,     0,   808,     0,     0,     0,
     809,     0,   810,   811,   812,   813,   814,   815,   816,   817,
    -553,  -553,   818,   819,   820,     0,     0,     0,   821,   822,
     823,   824,   825,   826,  1007,     0,   778,   779,   780,   781,
     782,   783,   784,   785,   786,   787,   788,   789,     0,     0,
       0,     0,     0,     0,     0,   790,   791,   792,   793,   794,
     795,   796,   797,     0,   798,   799,   800,   801,   802,   803,
     804,   805,   806,   807,     0,   808,     0,     0,     0,   809,
       0,   810,   811,   812,   813,   814,   815,   816,   817,     0,
       0,   818,   819,   820,     0,     0,     0,   821,   822,   823,
     824,   825,   826,     0,  1007,     0,   778,   779,   780,   781,
     782,   783,   784,   785,   786,   787,   788,   789,     0,     0,
       0,     0,     0,     0,     0,   790,   791,   792,   793,   794,
     795,   796,   797,     0,   798,   799,   800,   801,   802,   803,
     804,   805,   806,   807,     0,   808,     0,     0,     0,   809,
     827,   810,   811,   812,   813,   814,   815,   816,   817,     0,
       0,   818,   819,   820,     0,     0,     0,   821,   822,   823,
     824,   825,   826,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,   828,
       0,     0,     0,   829,   830,     0,     0,   831,     0,     0,
       0,  -559,     0,     0,     0,     0,     0,     0,     0,   827,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,   828,     0,
       0,     0,   829,   830,     0,     0,   831,     0,     0,     0,
    -553,     0,     0,     0,     0,     0,     0,     0,   827,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,   828,     0,     0,
       0,   829,   830,     0,     0,   831,     0,     0,     0,  1008,
       0,     0,     0,     0,     0,     0,     0,     0,   827,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,   828,     0,     0,
       0,   829,   830,     0,     0,   831,     0,     0,  1007,  1029,
     778,   779,   780,   781,   782,   783,   784,   785,   786,   787,
     788,   789,     0,     0,     0,     0,     0,     0,     0,   790,
     791,   792,   793,   794,   795,   796,   797,     0,   798,   799,
     800,   801,   802,   803,   804,   805,   806,   807,     0,   808,
       0,     0,     0,   809,     0,   810,   811,   812,   813,   814,
     815,   816,   817,     0,     0,   818,   819,   820,     0,     0,
       0,   821,   822,   823,   824,   825,   826,  1007,     0,   778,
     779,   780,   781,   782,   783,   784,   785,   786,   787,   788,
     789,     0,     0,     0,     0,     0,     0,     0,   790,   791,
     792,   793,   794,   795,   796,   797,     0,   798,   799,   800,
     801,   802,   803,   804,   805,   806,   807,     0,   808,     0,
       0,     0,   809,     0,   810,   811,   812,   813,   814,   815,
     816,   817,     0,     0,   818,   819,   820,     0,     0,     0,
     821,   822,   823,   824,   825,   826,  1007,     0,   778,   779,
     780,   781,   782,   783,   784,   785,   786,   787,   788,   789,
       0,     0,     0,     0,     0,     0,     0,   790,   791,   792,
     793,   794,   795,   796,   797,     0,   798,   799,   800,   801,
     802,   803,   804,   805,   806,   807,     0,   808,     0,     0,
       0,   809,     0,   810,   811,   812,   813,   814,   815,   816,
     817,     0,     0,   818,   819,   820,     0,     0,     0,   821,
     822,   823,   824,   825,   826,     0,  1007,     0,   778,   779,
     780,   781,   782,   783,   784,   785,   786,   787,   788,   789,
       0,     0,     0,     0,     0,     0,     0,   790,   791,   792,
     793,   794,   795,   796,   797,     0,   798,   799,   800,   801,
     802,   803,   804,   805,   806,   807,     0,   808,     0,     0,
       0,   809,   827,   810,   811,   812,   813,   814,   815,   816,
     817,     0,     0,   818,   819,   820,     0,     0,     0,   821,
     822,   823,   824,   825,   826,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,   828,     0,     0,     0,   829,   830,     0,     0,   831,
       0,     0,     0,  1031,     0,     0,     0,     0,     0,     0,
       0,   827,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
     828,     0,     0,     0,   829,   830,     0,     0,   831,     0,
       0,     0,  1033,     0,     0,     0,     0,     0,     0,     0,
     827,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,   828,
       0,     0,     0,   829,   830,     0,     0,   831,     0,     0,
       0,  1040,     0,     0,     0,     0,     0,     0,     0,     0,
     827,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,   828,
       0,     0,     0,   829,   830,     0,     0,   831,     0,     0,
    1007,  1317,   778,   779,   780,   781,   782,   783,   784,   785,
     786,   787,   788,   789,     0,     0,     0,     0,     0,     0,
       0,   790,   791,   792,   793,   794,   795,   796,   797,     0,
     798,   799,   800,   801,   802,   803,   804,   805,   806,   807,
       0,   808,     0,     0,     0,   809,     0,   810,   811,   812,
     813,   814,   815,   816,   817,     0,     0,   818,   819,   820,
       0,     0,     0,   821,   822,   823,   824,   825,   826,  1007,
       0,   778,   779,   780,   781,   782,   783,   784,   785,   786,
     787,   788,   789,     0,     0,     0,     0,     0,     0,     0,
     790,   791,   792,   793,   794,   795,   796,   797,     0,   798,
     799,   800,   801,   802,   803,   804,   805,   806,   807,     0,
     808,     0,     0,     0,   809,     0,   810,   811,   812,   813,
     814,   815,   816,   817,     0,     0,   818,   819,   820,     0,
       0,     0,   821,   822,   823,   824,   825,   826,  1007,     0,
     778,   779,   780,   781,   782,   783,   784,   785,   786,   787,
     788,   789,     0,     0,     0,     0,     0,     0,     0,   790,
     791,   792,   793,   794,   795,   796,   797,     0,   798,   799,
     800,   801,   802,   803,   804,   805,   806,   807,     0,   808,
       0,     0,     0,   809,     0,   810,   811,   812,   813,   814,
     815,   816,   817,     0,     0,   818,   819,   820,     0,     0,
       0,   821,   822,   823,   824,   825,   826,     0,  1007,     0,
     778,   779,   780,   781,   782,   783,   784,   785,   786,   787,
     788,   789,     0,     0,     0,     0,     0,     0,     0,   790,
     791,   792,   793,   794,   795,   796,   797,     0,   798,   799,
     800,   801,   802,   803,   804,   805,   806,   807,     0,   808,
       0,     0,     0,   809,   827,   810,   811,   812,   813,   814,
     815,   816,   817,     0,     0,   818,   819,   820,     0,     0,
       0,   821,   822,   823,   824,   825,   826,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,   828,     0,     0,     0,   829,   830,     0,
       0,   831,     0,     0,     0,  1436,     0,     0,     0,     0,
       0,     0,     0,   827,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,   828,     0,     0,     0,   829,   830,     0,     0,
     831,     0,     0,     0,  1440,     0,     0,     0,     0,     0,
       0,     0,   827,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,   828,     0,     0,     0,   829,   830,     0,     0,   831,
       0,     0,     0,  1480,     0,     0,     0,     0,     0,     0,
       0,     0,   827,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,   828,     0,     0,     0,   829,   830,     0,     0,   831,
       0,     0,  1007,  1500,   778,   779,   780,   781,   782,   783,
     784,   785,   786,   787,   788,   789,     0,     0,     0,     0,
       0,     0,     0,   790,   791,   792,   793,   794,   795,   796,
     797,     0,   798,   799,   800,   801,   802,   803,   804,   805,
     806,   807,     0,   808,     0,     0,     0,   809,     0,   810,
     811,   812,   813,   814,   815,   816,   817,     0,     0,   818,
     819,   820,     0,     0,     0,   821,   822,   823,   824,   825,
     826,  1007,     0,   778,   779,   780,   781,   782,   783,   784,
     785,   786,   787,   788,   789,     0,     0,     0,     0,     0,
       0,     0,   790,   791,   792,   793,   794,   795,   796,   797,
       0,   798,   799,   800,   801,   802,   803,   804,   805,   806,
     807,     0,   808,     0,     0,     0,   809,     0,   810,   811,
     812,   813,   814,   815,   816,   817,     0,     0,   818,   819,
     820,     0,     0,     0,   821,   822,   823,   824,   825,   826,
    1007,     0,   778,   779,   780,   781,   782,   783,   784,   785,
     786,   787,   788,   789,     0,     0,     0,     0,     0,     0,
       0,   790,   791,   792,   793,   794,   795,   796,   797,     0,
     798,   799,   800,   801,   802,   803,   804,   805,   806,   807,
       0,   808,     0,     0,     0,   809,     0,   810,   811,   812,
     813,   814,   815,   816,   817,     0,     0,   818,   819,   820,
       0,     0,     0,   821,   822,   823,   824,   825,   826,     0,
    1290,     0,   778,   779,   780,   781,   782,     0,     0,     0,
       0,   787,   788,   789,     0,     0,     0,     0,     0,     0,
       0,   790,   791,   792,   793,   794,   795,   796,   797,     0,
     798,   799,   800,   801,   802,   803,   804,   805,   806,   807,
       0,   808,     0,     0,     0,     0,   827,   810,   811,   812,
     813,   814,   815,   816,     0,     0,     0,     0,   819,   820,
       0,     0,     0,   821,   822,   823,   824,   825,   826,   888,
     889,   890,   891,   892,   893,   894,   895,   896,   897,   898,
     899,   900,   901,   902,   903,   904,   905,  1291,  1292,  1293,
    1294,  1295,  1296,     0,     0,   828,     0,     0,     0,   829,
     830,     0,     0,   831,     0,     0,     0,  1501,     0,     0,
       0,     0,     0,     0,     0,   827,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,   828,     0,     0,     0,   829,   830,
       0,     0,   831,     0,     0,     0,  1502,     0,     0,     0,
       0,     0,     0,     0,   827,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,   828,     0,     0,     0,   829,   830,     0,
       0,   831,     0,     0,     0,  1503,     0,     0,     0,     0,
       0,     0,     0,     0,   827,   906,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,   907,     0,
       0,     0,  1297,   908,     0,   909,   910,   911,   912,     0,
     913,   914,   446,   828,     0,   915,     0,   829,   830,     0,
       0,     0,     0,   978,   917,  1300,     0,   778,   779,   780,
     781,   782,     0,     0,     0,     0,   787,   788,   789,     0,
       0,     0,     0,     0,     0,     0,   790,   791,   792,   793,
     794,   795,   796,   797,     0,   798,   799,   800,   801,   802,
     803,   804,   805,   806,   807,     0,   808,     0,     0,     0,
       0,     0,   810,   811,   812,   813,   814,   815,   816,     0,
       0,     0,     0,   819,   820,     0,     0,     0,   821,   822,
     823,   824,   825,   826,   888,   889,   890,   891,   892,   893,
     894,   895,   896,   897,   898,   899,   900,   901,   902,   903,
     904,   905,  1291,  1292,  1293,  1294,  1295,  1296,  1096,     0,
     778,   779,   780,   781,   782,     0,     0,     0,     0,   787,
     788,   789,     0,     0,     0,     0,     0,     0,     0,   790,
     791,   792,   793,   794,   795,   796,   797,     0,   798,   799,
     800,   801,   802,   803,   804,   805,   806,   807,     0,   808,
       0,     0,     0,     0,     0,   810,   811,   812,   813,   814,
     815,   816,     0,     0,     0,     0,   819,   820,     0,     0,
       0,   821,   822,   823,   824,   825,   826,   888,   889,   890,
     891,   892,   893,   894,   895,   896,   897,   898,   899,   900,
     901,   902,   903,   904,   905,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,  1096,     0,
     778,   779,   780,   781,   782,     0,     0,     0,     0,   787,
     788,   789,     0,     0,     0,     0,     0,     0,     0,   790,
     791,   792,   793,   794,   795,   796,   797,     0,   798,   799,
     800,   801,   802,   803,   804,   805,   806,   807,     0,   808,
       0,     0,     0,     0,     0,   810,   811,   812,   813,   814,
     815,   816,     0,     0,     0,     0,   819,   820,     0,   827,
     906,   821,   822,   823,   824,   825,   826,   888,   889,   890,
     891,   892,   893,   894,   895,   896,   897,   898,   899,   900,
     901,   902,   903,   904,   905,     0,     0,     0,     0,     0,
       0,     0,     0,   907,     0,     0,     0,  1297,   908,     0,
     909,   910,   911,   912,     0,   913,   914,   446,   828,     0,
     915,     0,   829,   830,     0,     0,     0,     0,   978,   917,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,   827,   906,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   907,     0,     0,     0,
       0,   908,     0,   909,   910,   911,   912,     0,   913,   914,
     446,   828,     0,   915,     0,   829,   830,     0,     0,     0,
    1097,   978,   917,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,   827,   906,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   907,     0,     0,     0,
       0,   908,     0,   909,   910,   911,   912,     0,   913,   914,
     446,   828,     0,   915,     0,   829,   830,     0,     0,     0,
    1145,   978,   917,  1096,     0,   778,   779,   780,   781,   782,
       0,     0,     0,     0,   787,   788,   789,     0,     0,     0,
       0,     0,     0,     0,   790,   791,   792,   793,   794,   795,
     796,   797,     0,   798,   799,   800,   801,   802,   803,   804,
     805,   806,   807,     0,   808,     0,     0,     0,     0,     0,
     810,   811,   812,   813,   814,   815,   816,     0,     0,     0,
       0,   819,   820,     0,     0,     0,   821,   822,   823,   824,
     825,   826,   888,   889,   890,   891,   892,   893,   894,   895,
     896,   897,   898,   899,   900,   901,   902,   903,   904,   905,
    1486,     0,   778,   779,   780,   781,   782,     0,     0,     0,
       0,   787,   788,   789,     0,     0,     0,     0,     0,     0,
       0,   790,   791,   792,   793,   794,   795,   796,   797,     0,
     798,   799,   800,   801,   802,   803,   804,   805,   806,   807,
       0,   808,     0,     0,     0,     0,     0,   810,   811,   812,
     813,   814,   815,   816,     0,     0,     0,     0,   819,   820,
       0,     0,     0,   821,   822,   823,   824,   825,   826,   888,
     889,   890,   891,   892,   893,   894,   895,   896,   897,   898,
     899,   900,   901,   902,   903,   904,   905,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   990,     0,   778,   779,
     780,   781,   782,     0,     0,     0,     0,   787,   788,   789,
       0,     0,     0,     0,     0,     0,     0,   790,   791,   792,
     793,   794,   795,   796,   797,     0,   798,   799,   800,   801,
     802,   803,   804,   805,   806,   807,     0,   808,     0,     0,
       0,     0,     0,   810,   811,   812,   813,   814,   815,   816,
       0,     0,     0,     0,   819,   820,     0,   827,   906,   821,
     822,   823,   824,   825,   826,   888,   889,   890,   891,   892,
     893,   894,   895,   896,   897,   898,   899,   900,   901,   902,
     903,   904,   905,     0,     0,     0,     0,     0,     0,     0,
       0,   907,     0,     0,     0,     0,   908,     0,   909,   910,
     911,   912,     0,   913,   914,   446,   828,     0,   915,     0,
     829,   830,     0,     0,     0,  1146,   978,   917,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,   827,   906,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,   907,     0,
       0,  1487,     0,   908,     0,   909,   910,   911,   912,     0,
     913,   914,   446,   828,     0,   915,     0,   829,   830,     0,
       0,     0,     0,   978,   917,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
     827,   906,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,   907,     0,     0,     0,     0,   908,
       0,   909,   910,   911,   912,     0,   913,   914,   446,   828,
       0,   915,     0,   829,   830,     0,     0,     0,     0,   978,
     917,  1088,     0,   778,   779,   780,   781,   782,     0,     0,
       0,     0,   787,   788,   789,     0,     0,     0,     0,     0,
       0,     0,   790,   791,   792,   793,   794,   795,   796,   797,
       0,   798,   799,   800,   801,   802,   803,   804,   805,   806,
     807,     0,   808,     0,     0,     0,     0,     0,   810,   811,
     812,   813,   814,   815,   816,     0,     0,     0,     0,   819,
     820,     0,     0,     0,   821,   822,   823,   824,   825,   826,
     888,   889,   890,   891,   892,   893,   894,   895,   896,   897,
     898,   899,   900,   901,   902,   903,   904,   905,  1090,     0,
     778,   779,   780,   781,   782,     0,     0,     0,     0,   787,
     788,   789,     0,     0,     0,     0,     0,     0,     0,   790,
     791,   792,   793,   794,   795,   796,   797,     0,   798,   799,
     800,   801,   802,   803,   804,   805,   806,   807,     0,   808,
       0,     0,     0,     0,     0,   810,   811,   812,   813,   814,
     815,   816,     0,     0,     0,     0,   819,   820,     0,     0,
       0,   821,   822,   823,   824,   825,   826,   888,   889,   890,
     891,   892,   893,   894,   895,   896,   897,   898,   899,   900,
     901,   902,   903,   904,   905,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,  1092,     0,   778,   779,   780,   781,
     782,     0,     0,     0,     0,   787,   788,   789,     0,     0,
       0,     0,     0,     0,     0,   790,   791,   792,   793,   794,
     795,   796,   797,     0,   798,   799,   800,   801,   802,   803,
     804,   805,   806,   807,     0,   808,     0,     0,     0,     0,
       0,   810,   811,   812,   813,   814,   815,   816,     0,     0,
       0,     0,   819,   820,     0,   827,   906,   821,   822,   823,
     824,   825,   826,   888,   889,   890,   891,   892,   893,   894,
     895,   896,   897,   898,   899,   900,   901,   902,   903,   904,
     905,     0,     0,     0,     0,     0,     0,     0,     0,   907,
       0,     0,     0,     0,   908,     0,   909,   910,   911,   912,
       0,   913,   914,   446,   828,     0,   915,     0,   829,   830,
       0,     0,     0,     0,   978,   917,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,   827,   906,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   907,     0,     0,     0,
       0,   908,     0,   909,   910,   911,   912,     0,   913,   914,
     446,   828,     0,   915,     0,   829,   830,     0,     0,     0,
       0,   978,   917,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,   827,   906,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,   907,     0,     0,     0,     0,   908,     0,   909,
     910,   911,   912,     0,   913,   914,   446,   828,     0,   915,
       0,   829,   830,     0,     0,     0,     0,   978,   917,  1118,
       0,   778,   779,   780,   781,   782,     0,     0,     0,     0,
     787,   788,   789,     0,     0,     0,     0,     0,     0,     0,
     790,   791,   792,   793,   794,   795,   796,   797,     0,   798,
     799,   800,   801,   802,   803,   804,   805,   806,   807,     0,
     808,     0,     0,     0,     0,     0,   810,   811,   812,   813,
     814,   815,   816,     0,     0,     0,     0,   819,   820,     0,
       0,     0,   821,   822,   823,   824,   825,   826,   888,   889,
     890,   891,   892,   893,   894,   895,   896,   897,   898,   899,
     900,   901,   902,   903,   904,   905,  1152,     0,   778,   779,
     780,   781,   782,     0,     0,     0,     0,   787,   788,   789,
       0,     0,     0,     0,     0,     0,     0,   790,   791,   792,
     793,   794,   795,   796,   797,     0,   798,   799,   800,   801,
     802,   803,   804,   805,   806,   807,     0,   808,     0,     0,
       0,     0,     0,   810,   811,   812,   813,   814,   815,   816,
       0,     0,     0,     0,   819,   820,     0,     0,     0,   821,
     822,   823,   824,   825,   826,   888,   889,   890,   891,   892,
     893,   894,   895,   896,   897,   898,   899,   900,   901,   902,
     903,   904,   905,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,  1096,     0,   778,   779,   780,   781,   782,     0,
       0,     0,     0,   787,   788,   789,     0,     0,     0,     0,
       0,     0,     0,   790,   791,   792,   793,   794,   795,   796,
     797,     0,   798,   799,   800,   801,   802,   803,   804,   805,
     806,   807,     0,   808,     0,     0,     0,     0,     0,   810,
     811,   812,   813,   814,   815,   816,     0,     0,     0,     0,
     819,   820,     0,   827,   906,   821,   822,   823,   824,   825,
     826,   888,   889,   890,   891,   892,   893,   894,   895,   896,
     897,   898,   899,   900,   901,   902,   903,   904,   905,     0,
       0,     0,     0,     0,     0,     0,     0,   907,     0,     0,
       0,     0,   908,     0,   909,   910,   911,   912,     0,   913,
     914,   446,   828,     0,   915,     0,   829,   830,     0,     0,
       0,     0,   978,   917,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
     827,   906,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,   907,     0,     0,     0,     0,   908,
       0,   909,   910,   911,   912,     0,   913,   914,   446,  1153,
       0,   915,     0,   829,   830,     0,     0,     0,     0,   978,
     917,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   827,   906,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
     907,     0,     0,     0,     0,   908,     0,   909,   910,   911,
     912,     0,   913,   914,   446,   828,     0,   915,     0,   829,
     830,     0,     0,     0,     0,   978,   917,  1287,     0,   778,
     779,   780,   781,   782,     0,     0,     0,     0,   787,   788,
     789,     0,     0,     0,     0,     0,     0,     0,   790,   791,
     792,   793,   794,   795,   796,   797,     0,   798,   799,   800,
     801,   802,   803,   804,   805,   806,   807,     0,   808,     0,
       0,     0,     0,     0,   810,   811,   812,   813,   814,   815,
     816,     0,     0,     0,     0,   819,   820,     0,     0,     0,
     821,   822,   823,   824,   825,   826,   888,   889,   890,   891,
     892,   893,   894,   895,   896,   897,   898,   899,   900,   901,
     902,   903,   904,   905,  1309,     0,   778,   779,   780,   781,
     782,     0,     0,     0,     0,   787,   788,   789,     0,     0,
       0,     0,     0,     0,     0,   790,   791,   792,   793,   794,
     795,   796,   797,     0,   798,   799,   800,   801,   802,   803,
     804,   805,   806,   807,     0,   808,     0,     0,     0,     0,
       0,   810,   811,   812,   813,   814,   815,   816,     0,     0,
       0,     0,   819,   820,     0,     0,     0,   821,   822,   823,
     824,   825,   826,   888,   889,   890,   891,   892,   893,   894,
     895,   896,   897,   898,   899,   900,   901,   902,   903,   904,
     905,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
    1311,     0,   778,   779,   780,   781,   782,     0,     0,     0,
       0,   787,   788,   789,     0,     0,     0,     0,     0,     0,
       0,   790,   791,   792,   793,   794,   795,   796,   797,     0,
     798,   799,   800,   801,   802,   803,   804,   805,   806,   807,
       0,   808,     0,     0,     0,     0,     0,   810,   811,   812,
     813,   814,   815,   816,     0,     0,     0,     0,   819,   820,
       0,   827,   906,   821,   822,   823,   824,   825,   826,   888,
     889,   890,   891,   892,   893,   894,   895,   896,   897,   898,
     899,   900,   901,   902,   903,   904,   905,     0,     0,     0,
       0,     0,     0,     0,     0,   907,     0,     0,     0,     0,
     908,     0,   909,   910,   911,   912,     0,   913,   914,   446,
    1288,     0,   915,     0,   829,   830,     0,     0,     0,     0,
     978,   917,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,   827,   906,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,   907,     0,     0,     0,     0,   908,     0,   909,
     910,   911,   912,     0,   913,   914,   446,   828,     0,   915,
       0,   829,   830,     0,     0,     0,     0,   978,   917,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,   827,   906,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,   907,     0,
       0,     0,     0,   908,     0,   909,   910,   911,   912,     0,
     913,   914,   446,   828,     0,   915,     0,   829,   830,     0,
       0,     0,     0,   978,   917,  1318,     0,   778,   779,   780,
     781,   782,     0,     0,     0,     0,   787,   788,   789,     0,
       0,     0,     0,     0,     0,     0,   790,   791,   792,   793,
     794,   795,   796,   797,     0,   798,   799,   800,   801,   802,
     803,   804,   805,   806,   807,     0,   808,     0,     0,     0,
       0,     0,   810,   811,   812,   813,   814,   815,   816,     0,
       0,     0,     0,   819,   820,     0,     0,     0,   821,   822,
     823,   824,   825,   826,   888,   889,   890,   891,   892,   893,
     894,   895,   896,   897,   898,   899,   900,   901,   902,   903,
    1319,   905,  1321,     0,   778,   779,   780,   781,   782,     0,
       0,     0,     0,   787,   788,   789,     0,     0,     0,     0,
       0,     0,     0,   790,   791,   792,   793,   794,   795,   796,
     797,     0,   798,   799,   800,   801,   802,   803,   804,   805,
     806,   807,     0,   808,     0,     0,     0,     0,     0,   810,
     811,   812,   813,   814,   815,   816,     0,     0,     0,     0,
     819,   820,     0,     0,     0,   821,   822,   823,   824,   825,
     826,   888,   889,   890,   891,   892,   893,   894,   895,   896,
     897,   898,   899,   900,   901,   902,   903,   904,   905,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,  1323,     0,
     778,   779,   780,   781,   782,     0,     0,     0,     0,   787,
     788,   789,     0,     0,     0,     0,     0,     0,     0,   790,
     791,   792,   793,   794,   795,   796,   797,     0,   798,   799,
     800,   801,   802,   803,   804,   805,   806,   807,     0,   808,
       0,     0,     0,     0,     0,   810,   811,   812,   813,   814,
     815,   816,     0,     0,     0,     0,   819,   820,     0,   827,
     906,   821,   822,   823,   824,   825,   826,   888,   889,   890,
     891,   892,   893,   894,   895,   896,   897,   898,   899,   900,
     901,   902,   903,   904,   905,     0,     0,     0,     0,     0,
       0,     0,     0,   907,     0,     0,     0,     0,   908,     0,
     909,   910,   911,   912,     0,   913,   914,   446,   828,     0,
     915,     0,   829,   830,     0,     0,     0,     0,   978,   917,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   827,   906,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
     907,     0,     0,     0,     0,   908,     0,   909,   910,   911,
     912,     0,   913,   914,   446,   828,     0,   915,     0,   829,
     830,     0,     0,     0,     0,   978,   917,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,   827,   906,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   907,     0,     0,     0,
       0,   908,     0,   909,   910,   911,   912,     0,   913,   914,
     446,   828,     0,   915,     0,   829,   830,     0,     0,     0,
       0,   978,   917,  1334,     0,   778,   779,   780,   781,   782,
       0,     0,     0,     0,   787,   788,   789,     0,     0,     0,
       0,     0,     0,     0,   790,   791,   792,   793,   794,   795,
     796,   797,     0,   798,   799,   800,   801,   802,   803,   804,
     805,   806,   807,     0,   808,     0,     0,     0,     0,     0,
     810,   811,   812,   813,   814,   815,   816,     0,     0,     0,
       0,   819,   820,     0,     0,     0,   821,   822,   823,   824,
     825,   826,   888,   889,   890,   891,   892,   893,   894,   895,
     896,   897,   898,   899,   900,   901,   902,   903,   904,   905,
    1336,     0,   778,   779,   780,   781,   782,     0,     0,     0,
       0,   787,   788,   789,     0,     0,     0,     0,     0,     0,
       0,   790,   791,   792,   793,   794,   795,   796,   797,     0,
     798,   799,   800,   801,   802,   803,   804,   805,   806,   807,
       0,   808,     0,     0,     0,     0,     0,   810,   811,   812,
     813,   814,   815,   816,     0,     0,     0,     0,   819,   820,
       0,     0,     0,   821,   822,   823,   824,   825,   826,   888,
     889,   890,   891,   892,   893,   894,   895,   896,   897,   898,
     899,   900,   901,   902,   903,   904,   905,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,  1338,     0,   778,   779,
     780,   781,   782,     0,     0,     0,     0,   787,   788,   789,
       0,     0,     0,     0,     0,     0,     0,   790,   791,   792,
     793,   794,   795,   796,   797,     0,   798,   799,   800,   801,
     802,   803,   804,   805,   806,   807,     0,   808,     0,     0,
       0,     0,     0,   810,   811,   812,   813,   814,   815,   816,
       0,     0,     0,     0,   819,   820,     0,   827,   906,   821,
     822,   823,   824,   825,   826,   888,   889,   890,   891,   892,
     893,   894,   895,   896,   897,   898,   899,   900,   901,   902,
     903,   904,   905,     0,     0,     0,     0,     0,     0,     0,
       0,   907,     0,     0,     0,     0,   908,     0,   909,   910,
     911,   912,     0,   913,   914,   446,   828,     0,   915,     0,
     829,   830,     0,     0,     0,     0,   978,   917,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,   827,   906,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,   907,     0,
       0,     0,     0,   908,     0,   909,   910,   911,   912,     0,
     913,   914,   446,   828,     0,   915,     0,   829,   830,     0,
       0,     0,     0,   978,   917,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
     827,   906,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,   907,     0,     0,     0,     0,   908,
       0,   909,   910,   911,   912,     0,   913,   914,   446,   828,
       0,   915,     0,   829,   830,     0,     0,     0,     0,   978,
     917,  1340,     0,   778,   779,   780,   781,   782,     0,     0,
       0,     0,   787,   788,   789,     0,     0,     0,     0,     0,
       0,     0,   790,   791,   792,   793,   794,   795,   796,   797,
       0,   798,   799,   800,   801,   802,   803,   804,   805,   806,
     807,     0,   808,     0,     0,     0,     0,     0,   810,   811,
     812,   813,   814,   815,   816,     0,     0,     0,     0,   819,
     820,     0,     0,     0,   821,   822,   823,   824,   825,   826,
     888,   889,   890,   891,   892,   893,   894,   895,   896,   897,
     898,   899,   900,   901,   902,   903,   904,   905,  1345,     0,
     778,   779,   780,   781,   782,     0,     0,     0,     0,   787,
     788,   789,     0,     0,     0,     0,     0,     0,     0,   790,
     791,   792,   793,   794,   795,   796,   797,     0,   798,   799,
     800,   801,   802,   803,   804,   805,   806,   807,     0,   808,
       0,     0,     0,     0,     0,   810,   811,   812,   813,   814,
     815,   816,     0,     0,     0,     0,   819,   820,     0,     0,
       0,   821,   822,   823,   824,   825,   826,   888,   889,   890,
     891,   892,   893,   894,   895,   896,   897,   898,   899,   900,
     901,   902,   903,   904,   905,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,  1347,     0,   778,   779,   780,   781,
     782,     0,     0,     0,     0,   787,   788,   789,     0,     0,
       0,     0,     0,     0,     0,   790,   791,   792,   793,   794,
     795,   796,   797,     0,   798,   799,   800,   801,   802,   803,
     804,   805,   806,   807,     0,   808,     0,     0,     0,     0,
       0,   810,   811,   812,   813,   814,   815,   816,     0,     0,
       0,     0,   819,   820,     0,   827,   906,   821,   822,   823,
     824,   825,   826,   888,   889,   890,   891,   892,   893,   894,
     895,   896,   897,   898,   899,   900,   901,   902,   903,   904,
     905,     0,     0,     0,     0,     0,     0,     0,     0,   907,
       0,     0,     0,     0,   908,     0,   909,   910,   911,   912,
       0,   913,   914,   446,   828,     0,   915,     0,   829,   830,
       0,     0,     0,     0,   978,   917,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,   827,   906,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   907,     0,     0,     0,
       0,   908,     0,   909,   910,   911,   912,     0,   913,   914,
     446,   828,     0,   915,     0,   829,   830,     0,     0,     0,
       0,   978,   917,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,   827,   906,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,   907,     0,     0,     0,     0,   908,     0,   909,
     910,   911,   912,     0,   913,   914,   446,   828,     0,   915,
       0,   829,   830,     0,     0,     0,     0,   978,   917,  1349,
       0,   778,   779,   780,   781,   782,     0,     0,     0,     0,
     787,   788,   789,     0,     0,     0,     0,     0,     0,     0,
     790,   791,   792,   793,   794,   795,   796,   797,     0,   798,
     799,   800,   801,   802,   803,   804,   805,   806,   807,     0,
     808,     0,     0,     0,     0,     0,   810,   811,   812,   813,
     814,   815,   816,     0,     0,     0,     0,   819,   820,     0,
       0,     0,   821,   822,   823,   824,   825,   826,   888,   889,
     890,   891,   892,   893,   894,   895,   896,   897,   898,   899,
     900,   901,   902,   903,   904,   905,  1351,     0,   778,   779,
     780,   781,   782,     0,     0,     0,     0,   787,   788,   789,
       0,     0,     0,     0,     0,     0,     0,   790,   791,   792,
     793,   794,   795,   796,   797,     0,   798,   799,   800,   801,
     802,   803,   804,   805,   806,   807,     0,   808,     0,     0,
       0,     0,     0,   810,   811,   812,   813,   814,   815,   816,
       0,     0,     0,     0,   819,   820,     0,     0,     0,   821,
     822,   823,   824,   825,   826,   888,   889,   890,   891,   892,
     893,   894,   895,   896,   897,   898,   899,   900,   901,   902,
     903,   904,   905,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,  1353,     0,   778,   779,   780,   781,   782,     0,
       0,     0,     0,   787,   788,   789,     0,     0,     0,     0,
       0,     0,     0,   790,   791,   792,   793,   794,   795,   796,
     797,     0,   798,   799,   800,   801,   802,   803,   804,   805,
     806,   807,     0,   808,     0,     0,     0,     0,     0,   810,
     811,   812,   813,   814,   815,   816,     0,     0,     0,     0,
     819,   820,     0,   827,   906,   821,   822,   823,   824,   825,
     826,   888,   889,   890,   891,   892,   893,   894,   895,   896,
     897,   898,   899,   900,   901,   902,   903,   904,   905,     0,
       0,     0,     0,     0,     0,     0,     0,   907,     0,     0,
       0,     0,   908,     0,   909,   910,   911,   912,     0,   913,
     914,   446,   828,     0,   915,     0,   829,   830,     0,     0,
       0,     0,   978,   917,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
     827,   906,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,   907,     0,     0,     0,     0,   908,
       0,   909,   910,   911,   912,     0,   913,   914,   446,   828,
       0,   915,     0,   829,   830,     0,     0,     0,     0,   978,
     917,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   827,   906,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
     907,     0,     0,     0,     0,   908,     0,   909,   910,   911,
     912,     0,   913,   914,   446,   828,     0,   915,     0,   829,
     830,     0,     0,     0,     0,   978,   917,  1355,     0,   778,
     779,   780,   781,   782,     0,     0,     0,     0,   787,   788,
     789,     0,     0,     0,     0,     0,     0,     0,   790,   791,
     792,   793,   794,   795,   796,   797,     0,   798,   799,   800,
     801,   802,   803,   804,   805,   806,   807,     0,   808,     0,
       0,     0,     0,     0,   810,   811,   812,   813,   814,   815,
     816,     0,     0,     0,     0,   819,   820,     0,     0,     0,
     821,   822,   823,   824,   825,   826,   888,   889,   890,   891,
     892,   893,   894,   895,   896,   897,   898,   899,   900,   901,
     902,   903,   904,   905,  1357,     0,   778,   779,   780,   781,
     782,     0,     0,     0,     0,   787,   788,   789,     0,     0,
       0,     0,     0,     0,     0,   790,   791,   792,   793,   794,
     795,   796,   797,     0,   798,   799,   800,   801,   802,   803,
     804,   805,   806,   807,     0,   808,     0,     0,     0,     0,
       0,   810,   811,   812,   813,   814,   815,   816,     0,     0,
       0,     0,   819,   820,     0,     0,     0,   821,   822,   823,
     824,   825,   826,   888,   889,   890,   891,   892,   893,   894,
     895,   896,   897,   898,   899,   900,   901,   902,   903,   904,
     905,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
    1543,     0,   778,   779,   780,   781,   782,     0,     0,     0,
       0,   787,   788,   789,     0,     0,     0,     0,     0,     0,
       0,   790,   791,   792,   793,   794,   795,   796,   797,     0,
     798,   799,   800,   801,   802,   803,   804,   805,   806,   807,
       0,   808,     0,     0,     0,     0,     0,   810,   811,   812,
     813,   814,   815,   816,     0,     0,     0,     0,   819,   820,
       0,   827,   906,   821,   822,   823,   824,   825,   826,   888,
     889,   890,   891,   892,   893,   894,   895,   896,   897,   898,
     899,   900,   901,   902,   903,   904,   905,     0,     0,     0,
       0,     0,     0,     0,     0,   907,     0,     0,     0,     0,
     908,     0,   909,   910,   911,   912,     0,   913,   914,   446,
     828,     0,   915,     0,   829,   830,     0,     0,     0,     0,
     978,   917,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,   827,   906,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,   907,     0,     0,     0,     0,   908,     0,   909,
     910,   911,   912,     0,   913,   914,   446,   828,     0,   915,
       0,   829,   830,     0,     0,     0,     0,   978,   917,     0,
       0,     0,     0,   778,   779,   780,   781,   782,   783,   784,
     785,   786,   787,   788,   789,     0,     0,     0,     0,     0,
       0,     0,   790,   791,   792,   793,   794,   795,   796,   797,
       0,   798,   799,   800,   801,   802,   803,   804,   805,   806,
     807,     0,   808,     0,   827,   906,   809,     0,   810,   811,
     812,   813,   814,   815,   816,   817,     0,     0,   818,   819,
     820,     0,     0,     0,   821,   822,   823,   824,   825,   826,
       0,     0,     0,     0,     0,     0,     0,     0,   907,     0,
       0,     0,     0,   908,     0,   909,   910,   911,   912,     0,
     913,   914,   446,   828,     0,   915,     0,   829,   830,     0,
       0,     0,     0,   978,   917,   778,   779,   780,   781,   782,
       0,     0,     0,     0,   787,   788,   789,     0,     0,     0,
       0,     0,     0,     0,   790,   791,   792,   793,   794,   795,
     796,   797,     0,   798,   799,   800,   801,   802,   803,   804,
     805,   806,   807,     0,   808,     0,     0,     0,     0,     0,
     810,   811,   812,   813,   814,   815,   816,     0,     0,     0,
       0,   819,   820,     0,     0,     0,   821,   822,   823,   824,
     825,   826,   888,   889,   890,   891,   892,   893,   894,   895,
     896,   897,   898,   899,   900,   901,   902,   903,   904,   905,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,   827,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,  1120,  1121,  1122,  1123,  1124,  1125,  1126,  1127,  1128,
    1129,  1130,  1131,  1132,  1133,  1134,  1135,  1136,  1137,  1138,
    1139,  1140,  1141,  1142,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,   828,     0,     0,     0,   829,   830,
       0,     0,   831,     0,     0,  1189,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,   827,   906,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,   907,     0,     0,     0,     0,   908,     0,   909,   910,
     911,   912,     0,   913,   914,   446,   828,     0,   915,     0,
     829,   830,     0,     0,     0,     0,   916,   917,   778,   779,
     780,   781,   782,     0,     0,     0,     0,   787,   788,   789,
       0,     0,     0,     0,     0,     0,     0,   790,   791,   792,
     793,   794,   795,   796,   797,     0,   798,   799,   800,   801,
     802,   803,   804,   805,   806,   807,     0,   808,     0,     0,
       0,     0,     0,   810,   811,   812,   813,   814,   815,   816,
       0,     0,     0,     0,   819,   820,     0,     0,     0,   821,
     822,   823,   824,   825,   826,   888,   889,   890,   891,   892,
     893,   894,   895,   896,   897,   898,   899,   900,   901,   902,
     903,   904,   905,   778,   779,   780,   781,   782,     0,     0,
       0,     0,   787,   788,   789,     0,     0,     0,     0,     0,
       0,     0,   790,   791,   792,   793,   794,   795,   796,   797,
       0,   798,   799,   800,   801,   802,   803,   804,   805,   806,
     807,     0,   808,     0,     0,     0,     0,     0,   810,   811,
     812,   813,   814,   815,   816,     0,     0,     0,     0,   819,
     820,     0,     0,     0,   821,   822,   823,   824,   825,   826,
     888,   889,   890,   891,   892,   893,   894,   895,   896,   897,
     898,   899,   900,   901,   902,   903,   904,   905,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,   778,   779,   780,   781,   782,     0,     0,     0,     0,
     787,   788,   789,     0,     0,     0,     0,     0,     0,     0,
     790,   791,   792,   793,   794,   795,   796,   797,     0,   798,
     799,   800,   801,   802,   803,   804,   805,   806,   807,     0,
     808,     0,     0,     0,     0,     0,   810,   811,   812,   813,
     814,   815,   816,     0,     0,     0,     0,   819,   820,     0,
     827,   906,   821,   822,   823,   824,   825,   826,   888,   889,
     890,   891,   892,   893,   894,   895,   896,   897,   898,   899,
     900,   901,   902,   903,   904,   905,     0,     0,     0,     0,
       0,     0,     0,     0,   907,     0,     0,     0,     0,   908,
       0,   909,   910,   911,   912,     0,   913,   914,   446,   828,
       0,   915,     0,   829,   830,     0,     0,     0,     0,   937,
     917,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,   827,   906,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,   907,
       0,     0,     0,     0,   908,     0,   909,   910,   911,   912,
       0,   913,   914,   446,   828,     0,   915,     0,   829,   830,
       0,     0,     0,     0,   939,   917,     0,     0,     0,     0,
       0,     0,   778,   779,   780,   781,   782,   783,   784,   785,
     786,   787,   788,   789,     0,     0,     0,     0,     0,     0,
       0,   790,   791,   792,   793,   794,   795,   796,   797,     0,
     798,   799,   800,   801,   802,   803,   804,   805,   806,   807,
       0,   808,     0,   827,   906,   809,     0,   810,   811,   812,
     813,   814,   815,   816,   817,     0,     0,   818,   819,   820,
       0,     0,     0,   821,   822,   823,   824,   825,   826,     0,
       0,     0,     0,     0,     0,     0,     0,   907,     0,     0,
       0,     0,   908,     0,   909,   910,   911,   912,     0,   913,
     914,   446,   828,     0,   915,     0,   829,   830,     0,     0,
       0,     0,   978,   917,   778,   779,   780,   781,   782,     0,
       0,     0,     0,   787,   788,   789,     0,     0,     0,     0,
       0,     0,     0,   790,   791,   792,   793,   794,   795,   796,
     797,     0,   798,   799,   800,   801,   802,   803,   804,   805,
     806,   807,     0,   808,     0,     0,     0,     0,     0,   810,
     811,   812,   813,   814,   815,   816,     0,     0,     0,     0,
     819,   820,     0,     0,     0,   821,   822,   823,   824,   825,
     826,   888,   889,   890,   891,   892,   893,   894,   895,   896,
     897,   898,   899,   900,   901,   902,   903,   904,   905,     0,
       0,     0,  1104,     0,   778,   779,   780,   781,   782,   783,
     784,   785,   786,   787,   788,   789,     0,     0,     0,     0,
       0,     0,     0,   790,   791,   792,   793,   794,   795,   796,
     797,     0,   798,   799,   800,   801,   802,   803,   804,   805,
     806,   807,     0,   808,     0,     0,     0,   809,     0,   810,
     811,   812,   813,   814,   815,   816,   817,     0,     0,   818,
     819,   820,     0,     0,   827,   821,   822,   823,   824,   825,
     826,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,   828,     0,     0,     0,   829,   830,     0,
       0,   831,     0,     0,  1189,   778,   779,   780,   781,   782,
     783,   784,   785,   786,   787,   788,   789,     0,     0,     0,
       0,     0,     0,     0,   790,   791,   792,   793,   794,   795,
     796,   797,     0,   798,   799,   800,   801,   802,   803,   804,
     805,   806,   807,     0,   808,     0,   827,   906,   809,     0,
     810,   811,   812,   813,   814,   815,   816,   817,     0,     0,
     818,   819,   820,     0,     0,     0,   821,   822,   823,   824,
     825,   826,     0,     0,     0,     0,     0,     0,     0,     0,
     907,     0,     0,     0,     0,   908,     0,   909,   910,   911,
     912,     0,   913,   914,  1493,   828,     0,   915,     0,   829,
     830,     0,     0,     0,     0,   978,   917,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   827,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,   828,     0,     0,     0,   829,
     830,     0,     0,   831,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,   827,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
       0,     0,     0,     0,     0,     0,   828,     0,     0,     0,
     829,   830,     0,     0,   831
};

#define yypact_value_is_default(Yystate) \
  (!!((Yystate) == (-1039)))

#define yytable_value_is_error(Yytable_value) \
  YYID (0)

static const yytype_int16 yycheck[] =
{
     196,   165,   362,   199,   426,   195,  1044,   833,   992,     1,
     879,     1,   881,   900,   883,   902,   885,   904,   887,     1,
       1,     1,     1,   849,   220,   851,   222,   853,   224,     1,
     226,   746,   858,   748,   921,     1,   751,     1,     1,   754,
       1,     1,     1,     1,     1,   760,   293,   209,    57,    62,
     303,   300,   304,   306,   306,   746,   303,   748,    62,    62,
     751,     1,     1,   754,     1,    62,    62,     1,   317,   760,
       1,     1,   270,     1,     1,   278,   279,   311,   919,   313,
     283,   284,   923,     1,     1,     1,     1,     1,     1,     1,
       1,     1,   288,   289,   290,   291,   292,   293,   270,     1,
     289,   270,     1,   289,     1,     1,    14,    15,     1,     1,
     270,   300,     1,   270,     1,     1,   476,   303,   833,    56,
      57,   317,   318,   319,     1,    88,    89,    90,    91,     1,
       1,     1,   318,     1,   849,     1,   851,   289,   853,     1,
       1,   927,   833,   858,   771,   772,     1,     1,   300,     1,
     270,     1,     1,   979,    88,    89,    90,    91,   849,     1,
     851,     1,   853,     1,     1,     1,   992,   858,     1,   270,
       1,     1,   270,     0,    61,     1,    88,    89,    90,    91,
     270,     1,     1,   748,     1,     1,   751,     1,     1,   754,
     270,   746,   289,   748,     1,   760,   751,   289,   270,   754,
      61,   916,   917,     1,     1,   760,   303,    61,     1,     1,
       1,   303,    61,   270,   306,   746,   289,   748,     1,   270,
     751,     1,   937,   754,   939,   916,   917,     1,     1,   760,
     303,     1,   311,   306,   313,    61,     1,   270,     1,     1,
       1,     1,     1,   311,     1,   313,   937,  1116,   939,     1,
       1,     1,     1,   270,     1,     1,     1,     1,     1,     1,
       1,     1,     1,   978,   979,     1,    19,   303,   304,     1,
     306,   303,   304,     1,   306,   270,   270,   992,   833,  1105,
       1,   270,   270,     1,     1,     1,   270,   978,   979,     1,
     303,     1,     1,     1,   849,   304,   851,     1,   853,   303,
     303,   992,   833,   858,   315,   270,   303,   303,     1,     1,
       1,   303,  1027,     1,     1,   307,     1,   289,   849,   481,
     851,     1,   853,   313,   314,   317,  1041,   858,   307,     1,
     293,   303,  1047,   315,   315,   317,  1027,   317,   317,   270,
     303,   304,   305,   306,   307,   304,   304,   306,   312,   315,
    1041,   314,   917,   314,   317,   315,  1047,   317,   315,   293,
     317,   916,   917,  1401,   303,   304,   305,   306,   307,   303,
     304,   305,   306,   307,   304,   315,   306,   317,   317,   316,
     314,   293,   937,   317,   939,   916,   917,   315,   315,   317,
    1105,   303,   304,   305,   306,   307,   314,   314,   314,   314,
     314,   314,   270,   314,   314,   317,   937,  1233,   939,   306,
     770,  1395,   314,  1239,  1105,   314,   303,   304,   314,   306,
     307,   314,   314,   978,   979,   314,   313,   289,   314,   306,
     317,   303,  1319,   304,   306,   306,  1222,   992,   306,  1326,
     306,   303,   303,   304,   314,   306,   307,   978,   979,   303,
     304,   313,   306,   307,   303,   304,   317,   306,   307,   314,
       1,   992,   314,   317,   314,   303,   306,   304,   317,   306,
     306,   304,   314,   306,  1189,   313,  1041,   303,   304,     1,
     306,   307,  1047,   314,   314,   746,  1041,   748,   304,     1,
     751,   317,  1047,   754,   314,   314,   303,   314,  1189,   760,
     314,   314,   862,  1541,   255,   303,   304,   305,   306,   307,
    1041,  1226,  1227,  1228,  1229,   306,  1047,   314,  1233,   317,
     303,   314,   314,   306,  1239,     1,   306,     1,     1,     1,
     304,   304,   306,   306,   304,  1226,  1227,  1228,  1229,   304,
     303,   306,  1233,   304,   306,   306,   306,   306,  1239,   306,
    1105,     1,    16,   270,   306,   306,   306,   306,   309,   306,
     306,   306,   306,   306,   306,   306,   306,   306,  1394,  1395,
     306,   303,   833,   289,  1105,   303,   304,   305,   742,   289,
     289,   289,   303,   304,   305,   303,   304,   303,   849,   293,
     851,   303,   853,   303,   303,   303,   289,   858,   289,   303,
       1,   289,   289,  1429,  1430,  1431,  1432,  1476,   293,   289,
     303,   303,   303,     1,     1,   303,   303,  1039,   303,     1,
       1,   981,     1,   303,  1189,   985,     1,   270,   270,     1,
       1,   303,    56,    57,  1189,     1,     1,     1,     1,   746,
       1,   748,     1,     1,   751,     1,     1,   754,     1,     1,
       1,     1,     1,   760,     1,     1,   917,     1,  1189,     1,
       1,  1226,  1227,  1228,  1229,   270,   270,     1,     1,     1,
     270,  1226,  1227,  1228,  1229,   270,   270,     1,  1233,  1394,
    1395,     1,     1,     1,  1239,   270,     1,     1,   311,     1,
     313,     1,     1,     1,     1,  1226,  1227,  1228,  1229,   291,
     292,   293,  1233,  1394,  1395,     1,     1,     1,  1239,   270,
       1,     1,     1,     1,  1429,  1430,  1431,  1432,   979,     1,
    1546,   270,   303,   304,     1,   306,   833,   311,     1,   313,
    1556,   992,     1,     1,  1560,  1561,  1562,     1,  1429,  1430,
    1431,  1432,   849,     1,   851,   270,   853,     1,   289,     1,
       1,   858,     1,     1,     1,     1,   303,     1,     1,     1,
       1,     1,   303,     1,     1,     1,  1027,   963,     1,     1,
       1,     1,     1,     1,     1,  1490,     1,     1,     1,     1,
    1041,   303,     1,     1,     1,   270,  1047,   977,   270,   303,
     304,   303,   306,   270,   311,   985,   313,   270,   270,  1490,
       1,   270,   278,   279,   270,   270,   282,   283,   284,     1,
     917,    88,    89,    90,    91,    88,    89,    90,    91,     1,
       1,     1,   270,   270,     1,     1,     1,  1542,   270,   303,
    1545,  1546,   270,   311,   998,   313,  1000,     1,  1002,  1394,
    1395,  1556,  1557,  1558,  1105,  1560,  1561,  1562,     1,     1,
    1046,  1542,   270,   303,  1545,  1546,     1,   270,     1,     1,
       1,     1,     1,  1394,  1395,  1556,  1557,  1558,   270,  1560,
    1561,  1562,   979,     1,  1429,  1430,  1431,  1432,     1,     1,
       1,     1,     1,     1,     1,   992,     1,     1,   270,     1,
       1,   311,   316,   313,   270,   783,   784,   785,  1429,  1430,
    1431,  1432,   303,   278,   279,     1,   303,   270,   283,   284,
     285,   286,   287,   288,    20,   303,   303,   311,   311,   313,
     313,   809,   303,   303,   303,  1490,   270,   270,  1189,   817,
     818,   303,   303,   270,  1041,  1490,    21,   303,   303,   303,
    1047,   311,   303,   313,   303,   303,   303,   303,   303,   270,
     303,   303,   303,   303,   303,   303,   303,   303,   270,  1490,
     270,   303,   303,   303,   270,  1226,  1227,  1228,  1229,   303,
     303,   303,  1233,   311,   270,   313,   303,  1542,  1239,   303,
    1545,   270,   270,   303,   303,   303,   270,  1542,   303,   303,
    1545,  1546,  1557,  1558,   303,   303,   303,   320,  1105,   270,
     270,  1556,  1557,  1558,   270,  1560,  1561,  1562,   303,   303,
     270,  1542,   303,   303,  1545,  1546,   293,   270,   303,   306,
     293,   303,   910,   911,   912,  1556,  1557,  1558,   303,  1560,
    1561,  1562,   306,   744,   303,   303,   270,   315,   749,   303,
     303,   752,  1402,   300,   755,   303,   311,   935,   313,   303,
     270,   303,   303,   270,   303,   303,   303,   303,   946,   303,
     303,   303,   303,   303,   270,   303,   303,   303,   270,   270,
     303,   303,   303,   303,   303,   303,   303,   270,   303,   303,
     303,   303,  1189,   270,   303,   303,   278,   279,   303,   270,
     282,   283,   284,   270,   270,   270,   278,   279,   278,   279,
     282,   283,   284,   283,   284,   881,   270,   883,     1,   885,
     270,   887,   208,   209,   210,   211,   270,   270,   270,  1226,
    1227,  1228,  1229,   270,   303,   270,  1233,   270,   270,   270,
     270,   270,  1239,  1394,  1395,   270,  1024,  1333,    88,    89,
      90,    91,   270,   289,   311,   303,   313,   270,   270,   270,
     270,   270,   270,   270,   300,   270,   270,   270,   270,   270,
     311,   916,   313,   270,   289,   311,   270,   313,  1429,  1430,
    1431,  1432,   270,   270,   311,   300,   313,   303,    71,    72,
      73,    74,   937,    76,   939,    78,   311,    80,   313,   289,
     290,   291,   292,   293,  1082,  1083,  1084,   257,   258,   259,
     260,   261,   262,   263,   264,   265,  1094,  1095,   270,   270,
     303,   304,   305,   306,   307,   270,   311,   270,   313,  1107,
     270,   314,   270,   978,   317,  1113,  1114,   303,  1116,  1490,
     270,   270,  1120,  1121,  1122,  1123,  1124,  1125,  1126,   270,
     270,   270,   270,   270,   304,   270,   289,   289,   311,   270,
    1138,  1139,  1140,  1141,  1142,  1143,  1144,   300,   300,   270,
     270,  1425,   270,   270,  1428,   270,   270,   289,   311,   311,
     313,   313,   270,   270,  1470,   270,   270,   270,   300,   270,
     270,  1542,   270,   270,  1545,  1546,   270,  1394,  1395,   311,
     270,   313,   270,   270,   270,  1556,  1557,  1558,   311,  1560,
    1561,  1562,   274,   275,   276,   277,   278,   279,   280,   281,
     282,   283,   284,   285,   286,   287,   288,   289,   290,   291,
     292,   293,  1429,  1430,  1431,  1432,   270,   270,  1216,   270,
     303,   304,   305,   306,   307,   270,   270,   270,   270,   270,
    1116,   270,   270,   293,   317,   270,   270,   303,   303,   303,
     303,   303,   303,   303,   304,   305,   306,   307,   303,   318,
     307,   303,   320,   303,   314,   303,   303,   317,   285,   286,
     287,   288,   289,   290,   291,   292,   293,   303,   104,   270,
     303,   303,   303,  1490,   271,   272,   273,   274,   275,   276,
     277,   278,   279,   280,   281,   282,   283,   284,   285,   286,
     287,   288,   289,   290,   291,   292,   293,   273,   274,   275,
     276,   277,   278,   279,   280,   281,   282,   283,   284,   285,
     286,   287,   288,   289,   290,   291,   292,   293,   315,   303,
     303,   303,   303,   303,   303,  1542,   303,   320,  1545,  1546,
     303,   303,   303,   311,   303,   310,   303,   311,   306,  1556,
    1557,  1558,   270,  1560,  1561,  1562,   271,   272,   273,   274,
     275,   276,   277,   278,   279,   280,   281,   282,   283,   284,
     285,   286,   287,   288,   289,   290,   291,   292,   293,   275,
     276,   277,   278,   279,   280,   281,   282,   283,   284,   285,
     286,   287,   288,   289,   290,   291,   292,   293,   313,   318,
     313,   318,   318,   314,   314,   314,   314,   314,   314,   314,
     314,   314,   314,   314,     0,     1,   300,  1405,   312,   312,
     318,   318,   300,  1411,   311,   315,   318,   318,    14,    15,
      16,    17,    18,    19,    20,    21,   278,   279,   280,   281,
     282,   283,   284,   285,   286,   287,   288,   289,   290,   291,
     292,   293,    38,   320,   320,    41,    42,    43,    44,    45,
     315,   314,   314,   306,   313,   313,   313,   313,   313,   313,
     300,    57,   313,   313,   313,   244,   271,   272,   273,   274,
     275,   276,   277,   278,   279,   280,   281,   282,   283,   284,
     285,   286,   287,   288,   289,   290,   291,   292,   293,  1487,
     314,   317,   315,   315,   315,   315,   244,  1495,    94,    95,
      96,    97,    98,    99,   100,   101,   102,   103,   313,   105,
     106,   107,   108,   109,   110,   111,   112,   113,   114,   115,
     116,   117,   118,   119,   120,   121,   122,   123,   124,   125,
     126,   127,   128,   129,   130,   131,   132,   133,   134,   135,
     136,   137,   138,   139,   140,   141,   142,   143,   144,   145,
     146,   147,   148,   149,   150,   151,   152,   153,   154,   155,
     156,   157,   158,   159,   160,   161,   162,   163,   164,   165,
     166,   167,   168,   169,   170,   171,   172,   173,   174,   175,
     176,   177,   178,   179,   180,   181,   182,   183,   184,   185,
     186,   187,   188,   189,   190,   191,   192,   193,   194,   195,
     196,   197,   198,   199,   200,   201,   202,   203,   204,   205,
     206,   207,   303,   303,   313,   313,   212,   213,   214,   215,
     216,   217,   218,   219,   220,   221,   222,   223,   224,   225,
     226,   227,   228,   229,   230,   231,   232,   233,   234,   235,
     236,   237,   238,   239,   240,   241,   242,   243,   313,   245,
     246,   247,   248,   249,   250,   251,   252,   253,   254,   313,
     313,   304,   313,   270,   270,   270,   270,   313,   316,   313,
     317,   267,   268,   269,   311,   313,   271,   272,   273,   274,
     275,   276,   277,   278,   279,   280,   281,   282,   283,   284,
     285,   286,   287,   288,   289,   290,   291,   292,   293,   313,
     311,   313,    47,   313,   313,   313,   270,   320,   304,   306,
     313,   313,   313,   313,   313,   313,   312,   304,   313,   313,
     313,   313,     1,   319,     3,     4,     5,     6,     7,     8,
       9,    10,    11,    12,    13,    14,   313,   313,   313,   313,
     313,   313,   313,    22,    23,    24,    25,    26,    27,    28,
      29,   313,    31,    32,    33,    34,    35,    36,    37,    38,
      39,    40,   313,    42,   313,   313,   313,    46,   313,    48,
      49,    50,    51,    52,    53,    54,    55,   313,   313,    58,
      59,    60,   313,   313,   313,    64,    65,    66,    67,    68,
      69,     1,   313,     3,     4,     5,     6,     7,     8,     9,
      10,    11,    12,    13,    14,   313,   320,   313,   313,   311,
     313,   318,    22,    23,    24,    25,    26,    27,    28,    29,
     313,    31,    32,    33,    34,    35,    36,    37,    38,    39,
      40,   313,    42,   313,   313,   313,    46,   313,    48,    49,
      50,    51,    52,    53,    54,    55,   313,   311,    58,    59,
      60,   306,   300,   313,    64,    65,    66,    67,    68,    69,
       1,   313,     3,     4,     5,     6,     7,     8,     9,    10,
      11,    12,    13,    14,   303,   303,   303,   303,   303,   303,
     303,    22,    23,    24,    25,    26,    27,    28,    29,   303,
      31,    32,    33,    34,    35,    36,    37,    38,    39,    40,
     303,    42,   303,   303,   303,    46,   303,    48,    49,    50,
      51,    52,    53,    54,    55,   303,   303,    58,    59,    60,
     300,   317,   313,    64,    65,    66,    67,    68,    69,   271,
     272,   273,   274,   275,   276,   277,   278,   279,   280,   281,
     282,   283,   284,   285,   286,   287,   288,   289,   290,   291,
     292,   293,   276,   277,   278,   279,   280,   281,   282,   283,
     284,   285,   286,   287,   288,   289,   290,   291,   292,   293,
     313,   313,   292,   306,   306,   316,   255,   303,   313,   313,
     320,   318,   313,   313,   313,   313,   313,   173,   313,   862,
     313,   313,   271,   272,   273,   274,   275,   276,   277,   278,
     279,   280,   281,   282,   283,   284,   285,   286,   287,   288,
     289,   290,   291,   292,   293,   313,   313,   313,   313,   313,
     313,   300,   313,   313,   313,   304,   313,   313,   313,   308,
     309,   313,   311,   312,   313,   314,   315,   313,   313,   313,
     313,   320,   320,   320,   313,   255,   313,   313,   320,   313,
     318,   768,   740,   318,  1084,   742,   477,   320,   320,  1471,
    1086,   271,   272,   273,   274,   275,   276,   277,   278,   279,
     280,   281,   282,   283,   284,   285,   286,   287,   288,   289,
     290,   291,   292,   293,   324,  1487,   325,  1086,  1409,  1217,
      -1,    -1,  1495,    -1,   304,    -1,    -1,    -1,   308,   309,
      -1,   311,   312,   313,   314,   315,    -1,    -1,    -1,    -1,
     320,    -1,    -1,    -1,   255,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     271,   272,   273,   274,   275,   276,   277,   278,   279,   280,
     281,   282,   283,   284,   285,   286,   287,   288,   289,   290,
     291,   292,   293,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,   304,    -1,    -1,    -1,   308,   309,    -1,
     311,   312,   313,    -1,   315,    -1,    -1,    -1,     1,   320,
       3,     4,     5,     6,     7,     8,     9,    10,    11,    12,
      13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,
      23,    24,    25,    26,    27,    28,    29,    -1,    31,    32,
      33,    34,    35,    36,    37,    38,    39,    40,    -1,    42,
      -1,    -1,    -1,    46,    -1,    48,    49,    50,    51,    52,
      53,    54,    55,    -1,    -1,    58,    59,    60,    -1,    -1,
      -1,    64,    65,    66,    67,    68,    69,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,     1,    -1,     3,     4,     5,     6,
       7,    -1,    -1,    -1,    -1,    12,    13,    14,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,
      27,    28,    29,    -1,    31,    32,    33,    34,    35,    36,
      37,    38,    39,    40,    -1,    42,    -1,    -1,    -1,    -1,
      -1,    48,    49,    50,    51,    52,    53,    54,    -1,    -1,
      -1,    -1,    59,    60,    -1,    -1,    -1,    64,    65,    66,
      67,    68,    69,    70,    71,    72,    73,    74,    75,    76,
      77,    78,    79,    80,    81,    82,    83,    84,    85,    86,
      87,   271,   272,   273,   274,   275,   276,   277,   278,   279,
     280,   281,   282,   283,   284,   285,   286,   287,   288,   289,
     290,   291,   292,   293,   277,   278,   279,   280,   281,   282,
     283,   284,   285,   286,   287,   288,   289,   290,   291,   292,
     293,    -1,    -1,   313,   271,   272,   273,   274,   275,   276,
     277,   278,   279,   280,   281,   282,   283,   284,   285,   286,
     287,   288,   289,   290,   291,   292,   293,   272,   273,   274,
     275,   276,   277,   278,   279,   280,   281,   282,   283,   284,
     285,   286,   287,   288,   289,   290,   291,   292,   293,    -1,
      -1,    -1,   255,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   271,   272,
     273,   274,   275,   276,   277,   278,   279,   280,   281,   282,
     283,   284,   285,   286,   287,   288,   289,   290,   291,   292,
     293,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,   304,    -1,    -1,    -1,   308,   309,     1,   311,   312,
     313,    -1,   315,    -1,    -1,    -1,    -1,   320,    -1,    -1,
      14,    15,    16,    17,    18,    19,    20,    21,   255,   256,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    38,    -1,    -1,    41,    42,    43,
      44,    45,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   289,    57,    -1,    -1,    -1,   294,    -1,   296,
     297,   298,   299,    -1,   301,   302,   303,   304,    -1,   306,
     307,   308,   309,    -1,    -1,    -1,    -1,   314,   315,    -1,
     317,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      94,    95,    96,    97,    98,    99,   100,   101,   102,   103,
      -1,   105,   106,   107,   108,   109,   110,   111,   112,   113,
     114,   115,   116,   117,   118,   119,   120,   121,   122,   123,
     124,   125,   126,   127,   128,   129,   130,   131,   132,   133,
     134,   135,   136,   137,   138,   139,   140,   141,   142,   143,
     144,   145,   146,   147,   148,   149,   150,   151,   152,   153,
     154,   155,   156,   157,   158,   159,   160,   161,   162,   163,
     164,   165,   166,   167,   168,   169,   170,   171,   172,   173,
     174,   175,   176,   177,   178,   179,   180,   181,   182,   183,
     184,   185,   186,   187,   188,   189,   190,   191,   192,   193,
     194,   195,   196,   197,   198,   199,   200,   201,   202,   203,
     204,   205,   206,   207,    -1,    -1,    -1,    -1,   212,   213,
     214,   215,   216,   217,   218,   219,   220,   221,   222,   223,
     224,   225,   226,   227,   228,   229,   230,   231,   232,   233,
     234,   235,   236,   237,   238,   239,   240,   241,   242,   243,
      -1,   245,   246,   247,   248,   249,   250,   251,   252,   253,
     254,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,   267,   268,   269,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     304,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   312,    -1,
      -1,    -1,    -1,    -1,     1,   319,     3,     4,     5,     6,
       7,     8,     9,    10,    11,    12,    13,    14,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,
      27,    28,    29,    -1,    31,    32,    33,    34,    35,    36,
      37,    38,    39,    40,    -1,    42,    -1,    -1,    -1,    46,
      -1,    48,    49,    50,    51,    52,    53,    54,    55,    56,
      57,    58,    59,    60,    -1,    -1,    -1,    64,    65,    66,
      67,    68,    69,     1,    -1,     3,     4,     5,     6,     7,
       8,     9,    10,    11,    12,    13,    14,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,    27,
      28,    29,    -1,    31,    32,    33,    34,    35,    36,    37,
      38,    39,    40,    -1,    42,    -1,    -1,    -1,    46,    -1,
      48,    49,    50,    51,    52,    53,    54,    55,    56,    57,
      58,    59,    60,    -1,    -1,    -1,    64,    65,    66,    67,
      68,    69,     1,    -1,     3,     4,     5,     6,     7,     8,
       9,    10,    11,    12,    13,    14,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    22,    23,    24,    25,    26,    27,    28,
      29,    -1,    31,    32,    33,    34,    35,    36,    37,    38,
      39,    40,    -1,    42,    -1,    -1,    -1,    46,    -1,    48,
      49,    50,    51,    52,    53,    54,    55,    56,    57,    58,
      59,    60,    -1,    -1,    -1,    64,    65,    66,    67,    68,
      69,    -1,     1,    -1,     3,     4,     5,     6,     7,     8,
       9,    10,    11,    12,    13,    14,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    22,    23,    24,    25,    26,    27,    28,
      29,    -1,    31,    32,    33,    34,    35,    36,    37,    38,
      39,    40,    -1,    42,    -1,    -1,    -1,    46,   255,    48,
      49,    50,    51,    52,    53,    54,    55,    56,    57,    58,
      59,    60,    -1,    -1,    -1,    64,    65,    66,    67,    68,
      69,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,   304,    -1,    -1,
      -1,   308,   309,    -1,    -1,   312,    -1,    -1,    -1,   316,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,   255,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   304,    -1,    -1,    -1,
     308,   309,    -1,    -1,   312,    -1,    -1,    -1,   316,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   255,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,   304,    -1,    -1,    -1,   308,
     309,    -1,    -1,   312,    -1,    -1,    -1,   316,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   255,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,   304,    -1,    -1,    -1,   308,
     309,    -1,    -1,   312,    -1,    -1,     1,   316,     3,     4,
       5,     6,     7,     8,     9,    10,    11,    12,    13,    14,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,    23,    24,
      25,    26,    27,    28,    29,    -1,    31,    32,    33,    34,
      35,    36,    37,    38,    39,    40,    -1,    42,    -1,    -1,
      -1,    46,    -1,    48,    49,    50,    51,    52,    53,    54,
      55,    56,    57,    58,    59,    60,    -1,    -1,    -1,    64,
      65,    66,    67,    68,    69,     1,    -1,     3,     4,     5,
       6,     7,     8,     9,    10,    11,    12,    13,    14,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    22,    23,    24,    25,
      26,    27,    28,    29,    -1,    31,    32,    33,    34,    35,
      36,    37,    38,    39,    40,    -1,    42,    -1,    -1,    -1,
      46,    -1,    48,    49,    50,    51,    52,    53,    54,    55,
      56,    57,    58,    59,    60,    -1,    -1,    -1,    64,    65,
      66,    67,    68,    69,     1,    -1,     3,     4,     5,     6,
       7,     8,     9,    10,    11,    12,    13,    14,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,
      27,    28,    29,    -1,    31,    32,    33,    34,    35,    36,
      37,    38,    39,    40,    -1,    42,    -1,    -1,    -1,    46,
      -1,    48,    49,    50,    51,    52,    53,    54,    55,    -1,
      -1,    58,    59,    60,    -1,    -1,    -1,    64,    65,    66,
      67,    68,    69,    -1,     1,    -1,     3,     4,     5,     6,
       7,     8,     9,    10,    11,    12,    13,    14,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,
      27,    28,    29,    -1,    31,    32,    33,    34,    35,    36,
      37,    38,    39,    40,    -1,    42,    -1,    -1,    -1,    46,
     255,    48,    49,    50,    51,    52,    53,    54,    55,    -1,
      -1,    58,    59,    60,    -1,    -1,    -1,    64,    65,    66,
      67,    68,    69,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   304,
      -1,    -1,    -1,   308,   309,    -1,    -1,   312,    -1,    -1,
      -1,   316,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   255,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   304,    -1,
      -1,    -1,   308,   309,    -1,    -1,   312,    -1,    -1,    -1,
     316,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   255,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,   304,    -1,    -1,
      -1,   308,   309,    -1,    -1,   312,    -1,    -1,    -1,   316,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   255,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,   304,    -1,    -1,
      -1,   308,   309,    -1,    -1,   312,    -1,    -1,     1,   316,
       3,     4,     5,     6,     7,     8,     9,    10,    11,    12,
      13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,
      23,    24,    25,    26,    27,    28,    29,    -1,    31,    32,
      33,    34,    35,    36,    37,    38,    39,    40,    -1,    42,
      -1,    -1,    -1,    46,    -1,    48,    49,    50,    51,    52,
      53,    54,    55,    -1,    -1,    58,    59,    60,    -1,    -1,
      -1,    64,    65,    66,    67,    68,    69,     1,    -1,     3,
       4,     5,     6,     7,     8,     9,    10,    11,    12,    13,
      14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,    23,
      24,    25,    26,    27,    28,    29,    -1,    31,    32,    33,
      34,    35,    36,    37,    38,    39,    40,    -1,    42,    -1,
      -1,    -1,    46,    -1,    48,    49,    50,    51,    52,    53,
      54,    55,    -1,    -1,    58,    59,    60,    -1,    -1,    -1,
      64,    65,    66,    67,    68,    69,     1,    -1,     3,     4,
       5,     6,     7,     8,     9,    10,    11,    12,    13,    14,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,    23,    24,
      25,    26,    27,    28,    29,    -1,    31,    32,    33,    34,
      35,    36,    37,    38,    39,    40,    -1,    42,    -1,    -1,
      -1,    46,    -1,    48,    49,    50,    51,    52,    53,    54,
      55,    -1,    -1,    58,    59,    60,    -1,    -1,    -1,    64,
      65,    66,    67,    68,    69,    -1,     1,    -1,     3,     4,
       5,     6,     7,     8,     9,    10,    11,    12,    13,    14,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,    23,    24,
      25,    26,    27,    28,    29,    -1,    31,    32,    33,    34,
      35,    36,    37,    38,    39,    40,    -1,    42,    -1,    -1,
      -1,    46,   255,    48,    49,    50,    51,    52,    53,    54,
      55,    -1,    -1,    58,    59,    60,    -1,    -1,    -1,    64,
      65,    66,    67,    68,    69,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,   304,    -1,    -1,    -1,   308,   309,    -1,    -1,   312,
      -1,    -1,    -1,   316,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,   255,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     304,    -1,    -1,    -1,   308,   309,    -1,    -1,   312,    -1,
      -1,    -1,   316,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     255,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   304,
      -1,    -1,    -1,   308,   309,    -1,    -1,   312,    -1,    -1,
      -1,   316,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     255,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   304,
      -1,    -1,    -1,   308,   309,    -1,    -1,   312,    -1,    -1,
       1,   316,     3,     4,     5,     6,     7,     8,     9,    10,
      11,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    22,    23,    24,    25,    26,    27,    28,    29,    -1,
      31,    32,    33,    34,    35,    36,    37,    38,    39,    40,
      -1,    42,    -1,    -1,    -1,    46,    -1,    48,    49,    50,
      51,    52,    53,    54,    55,    -1,    -1,    58,    59,    60,
      -1,    -1,    -1,    64,    65,    66,    67,    68,    69,     1,
      -1,     3,     4,     5,     6,     7,     8,     9,    10,    11,
      12,    13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      22,    23,    24,    25,    26,    27,    28,    29,    -1,    31,
      32,    33,    34,    35,    36,    37,    38,    39,    40,    -1,
      42,    -1,    -1,    -1,    46,    -1,    48,    49,    50,    51,
      52,    53,    54,    55,    -1,    -1,    58,    59,    60,    -1,
      -1,    -1,    64,    65,    66,    67,    68,    69,     1,    -1,
       3,     4,     5,     6,     7,     8,     9,    10,    11,    12,
      13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,
      23,    24,    25,    26,    27,    28,    29,    -1,    31,    32,
      33,    34,    35,    36,    37,    38,    39,    40,    -1,    42,
      -1,    -1,    -1,    46,    -1,    48,    49,    50,    51,    52,
      53,    54,    55,    -1,    -1,    58,    59,    60,    -1,    -1,
      -1,    64,    65,    66,    67,    68,    69,    -1,     1,    -1,
       3,     4,     5,     6,     7,     8,     9,    10,    11,    12,
      13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,
      23,    24,    25,    26,    27,    28,    29,    -1,    31,    32,
      33,    34,    35,    36,    37,    38,    39,    40,    -1,    42,
      -1,    -1,    -1,    46,   255,    48,    49,    50,    51,    52,
      53,    54,    55,    -1,    -1,    58,    59,    60,    -1,    -1,
      -1,    64,    65,    66,    67,    68,    69,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,   304,    -1,    -1,    -1,   308,   309,    -1,
      -1,   312,    -1,    -1,    -1,   316,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,   255,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   304,    -1,    -1,    -1,   308,   309,    -1,    -1,
     312,    -1,    -1,    -1,   316,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   255,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,   304,    -1,    -1,    -1,   308,   309,    -1,    -1,   312,
      -1,    -1,    -1,   316,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   255,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,   304,    -1,    -1,    -1,   308,   309,    -1,    -1,   312,
      -1,    -1,     1,   316,     3,     4,     5,     6,     7,     8,
       9,    10,    11,    12,    13,    14,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    22,    23,    24,    25,    26,    27,    28,
      29,    -1,    31,    32,    33,    34,    35,    36,    37,    38,
      39,    40,    -1,    42,    -1,    -1,    -1,    46,    -1,    48,
      49,    50,    51,    52,    53,    54,    55,    -1,    -1,    58,
      59,    60,    -1,    -1,    -1,    64,    65,    66,    67,    68,
      69,     1,    -1,     3,     4,     5,     6,     7,     8,     9,
      10,    11,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    22,    23,    24,    25,    26,    27,    28,    29,
      -1,    31,    32,    33,    34,    35,    36,    37,    38,    39,
      40,    -1,    42,    -1,    -1,    -1,    46,    -1,    48,    49,
      50,    51,    52,    53,    54,    55,    -1,    -1,    58,    59,
      60,    -1,    -1,    -1,    64,    65,    66,    67,    68,    69,
       1,    -1,     3,     4,     5,     6,     7,     8,     9,    10,
      11,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    22,    23,    24,    25,    26,    27,    28,    29,    -1,
      31,    32,    33,    34,    35,    36,    37,    38,    39,    40,
      -1,    42,    -1,    -1,    -1,    46,    -1,    48,    49,    50,
      51,    52,    53,    54,    55,    -1,    -1,    58,    59,    60,
      -1,    -1,    -1,    64,    65,    66,    67,    68,    69,    -1,
       1,    -1,     3,     4,     5,     6,     7,    -1,    -1,    -1,
      -1,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    22,    23,    24,    25,    26,    27,    28,    29,    -1,
      31,    32,    33,    34,    35,    36,    37,    38,    39,    40,
      -1,    42,    -1,    -1,    -1,    -1,   255,    48,    49,    50,
      51,    52,    53,    54,    -1,    -1,    -1,    -1,    59,    60,
      -1,    -1,    -1,    64,    65,    66,    67,    68,    69,    70,
      71,    72,    73,    74,    75,    76,    77,    78,    79,    80,
      81,    82,    83,    84,    85,    86,    87,    88,    89,    90,
      91,    92,    93,    -1,    -1,   304,    -1,    -1,    -1,   308,
     309,    -1,    -1,   312,    -1,    -1,    -1,   316,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,   255,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,   304,    -1,    -1,    -1,   308,   309,
      -1,    -1,   312,    -1,    -1,    -1,   316,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,   255,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,   304,    -1,    -1,    -1,   308,   309,    -1,
      -1,   312,    -1,    -1,    -1,   316,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,   255,   256,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,
      -1,    -1,   293,   294,    -1,   296,   297,   298,   299,    -1,
     301,   302,   303,   304,    -1,   306,    -1,   308,   309,    -1,
      -1,    -1,    -1,   314,   315,     1,    -1,     3,     4,     5,
       6,     7,    -1,    -1,    -1,    -1,    12,    13,    14,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    22,    23,    24,    25,
      26,    27,    28,    29,    -1,    31,    32,    33,    34,    35,
      36,    37,    38,    39,    40,    -1,    42,    -1,    -1,    -1,
      -1,    -1,    48,    49,    50,    51,    52,    53,    54,    -1,
      -1,    -1,    -1,    59,    60,    -1,    -1,    -1,    64,    65,
      66,    67,    68,    69,    70,    71,    72,    73,    74,    75,
      76,    77,    78,    79,    80,    81,    82,    83,    84,    85,
      86,    87,    88,    89,    90,    91,    92,    93,     1,    -1,
       3,     4,     5,     6,     7,    -1,    -1,    -1,    -1,    12,
      13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,
      23,    24,    25,    26,    27,    28,    29,    -1,    31,    32,
      33,    34,    35,    36,    37,    38,    39,    40,    -1,    42,
      -1,    -1,    -1,    -1,    -1,    48,    49,    50,    51,    52,
      53,    54,    -1,    -1,    -1,    -1,    59,    60,    -1,    -1,
      -1,    64,    65,    66,    67,    68,    69,    70,    71,    72,
      73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
      83,    84,    85,    86,    87,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,     1,    -1,
       3,     4,     5,     6,     7,    -1,    -1,    -1,    -1,    12,
      13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,
      23,    24,    25,    26,    27,    28,    29,    -1,    31,    32,
      33,    34,    35,    36,    37,    38,    39,    40,    -1,    42,
      -1,    -1,    -1,    -1,    -1,    48,    49,    50,    51,    52,
      53,    54,    -1,    -1,    -1,    -1,    59,    60,    -1,   255,
     256,    64,    65,    66,    67,    68,    69,    70,    71,    72,
      73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
      83,    84,    85,    86,    87,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,   289,    -1,    -1,    -1,   293,   294,    -1,
     296,   297,   298,   299,    -1,   301,   302,   303,   304,    -1,
     306,    -1,   308,   309,    -1,    -1,    -1,    -1,   314,   315,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   255,   256,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,    -1,    -1,
      -1,   294,    -1,   296,   297,   298,   299,    -1,   301,   302,
     303,   304,    -1,   306,    -1,   308,   309,    -1,    -1,    -1,
     313,   314,   315,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   255,   256,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,    -1,    -1,
      -1,   294,    -1,   296,   297,   298,   299,    -1,   301,   302,
     303,   304,    -1,   306,    -1,   308,   309,    -1,    -1,    -1,
     313,   314,   315,     1,    -1,     3,     4,     5,     6,     7,
      -1,    -1,    -1,    -1,    12,    13,    14,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,    27,
      28,    29,    -1,    31,    32,    33,    34,    35,    36,    37,
      38,    39,    40,    -1,    42,    -1,    -1,    -1,    -1,    -1,
      48,    49,    50,    51,    52,    53,    54,    -1,    -1,    -1,
      -1,    59,    60,    -1,    -1,    -1,    64,    65,    66,    67,
      68,    69,    70,    71,    72,    73,    74,    75,    76,    77,
      78,    79,    80,    81,    82,    83,    84,    85,    86,    87,
       1,    -1,     3,     4,     5,     6,     7,    -1,    -1,    -1,
      -1,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    22,    23,    24,    25,    26,    27,    28,    29,    -1,
      31,    32,    33,    34,    35,    36,    37,    38,    39,    40,
      -1,    42,    -1,    -1,    -1,    -1,    -1,    48,    49,    50,
      51,    52,    53,    54,    -1,    -1,    -1,    -1,    59,    60,
      -1,    -1,    -1,    64,    65,    66,    67,    68,    69,    70,
      71,    72,    73,    74,    75,    76,    77,    78,    79,    80,
      81,    82,    83,    84,    85,    86,    87,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,     1,    -1,     3,     4,
       5,     6,     7,    -1,    -1,    -1,    -1,    12,    13,    14,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,    23,    24,
      25,    26,    27,    28,    29,    -1,    31,    32,    33,    34,
      35,    36,    37,    38,    39,    40,    -1,    42,    -1,    -1,
      -1,    -1,    -1,    48,    49,    50,    51,    52,    53,    54,
      -1,    -1,    -1,    -1,    59,    60,    -1,   255,   256,    64,
      65,    66,    67,    68,    69,    70,    71,    72,    73,    74,
      75,    76,    77,    78,    79,    80,    81,    82,    83,    84,
      85,    86,    87,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,   289,    -1,    -1,    -1,    -1,   294,    -1,   296,   297,
     298,   299,    -1,   301,   302,   303,   304,    -1,   306,    -1,
     308,   309,    -1,    -1,    -1,   313,   314,   315,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,   255,   256,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,
      -1,   292,    -1,   294,    -1,   296,   297,   298,   299,    -1,
     301,   302,   303,   304,    -1,   306,    -1,   308,   309,    -1,
      -1,    -1,    -1,   314,   315,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     255,   256,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,   289,    -1,    -1,    -1,    -1,   294,
      -1,   296,   297,   298,   299,    -1,   301,   302,   303,   304,
      -1,   306,    -1,   308,   309,    -1,    -1,    -1,    -1,   314,
     315,     1,    -1,     3,     4,     5,     6,     7,    -1,    -1,
      -1,    -1,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    22,    23,    24,    25,    26,    27,    28,    29,
      -1,    31,    32,    33,    34,    35,    36,    37,    38,    39,
      40,    -1,    42,    -1,    -1,    -1,    -1,    -1,    48,    49,
      50,    51,    52,    53,    54,    -1,    -1,    -1,    -1,    59,
      60,    -1,    -1,    -1,    64,    65,    66,    67,    68,    69,
      70,    71,    72,    73,    74,    75,    76,    77,    78,    79,
      80,    81,    82,    83,    84,    85,    86,    87,     1,    -1,
       3,     4,     5,     6,     7,    -1,    -1,    -1,    -1,    12,
      13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,
      23,    24,    25,    26,    27,    28,    29,    -1,    31,    32,
      33,    34,    35,    36,    37,    38,    39,    40,    -1,    42,
      -1,    -1,    -1,    -1,    -1,    48,    49,    50,    51,    52,
      53,    54,    -1,    -1,    -1,    -1,    59,    60,    -1,    -1,
      -1,    64,    65,    66,    67,    68,    69,    70,    71,    72,
      73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
      83,    84,    85,    86,    87,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,     1,    -1,     3,     4,     5,     6,
       7,    -1,    -1,    -1,    -1,    12,    13,    14,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,
      27,    28,    29,    -1,    31,    32,    33,    34,    35,    36,
      37,    38,    39,    40,    -1,    42,    -1,    -1,    -1,    -1,
      -1,    48,    49,    50,    51,    52,    53,    54,    -1,    -1,
      -1,    -1,    59,    60,    -1,   255,   256,    64,    65,    66,
      67,    68,    69,    70,    71,    72,    73,    74,    75,    76,
      77,    78,    79,    80,    81,    82,    83,    84,    85,    86,
      87,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   289,
      -1,    -1,    -1,    -1,   294,    -1,   296,   297,   298,   299,
      -1,   301,   302,   303,   304,    -1,   306,    -1,   308,   309,
      -1,    -1,    -1,    -1,   314,   315,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   255,   256,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,    -1,    -1,
      -1,   294,    -1,   296,   297,   298,   299,    -1,   301,   302,
     303,   304,    -1,   306,    -1,   308,   309,    -1,    -1,    -1,
      -1,   314,   315,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   255,   256,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   289,    -1,    -1,    -1,    -1,   294,    -1,   296,
     297,   298,   299,    -1,   301,   302,   303,   304,    -1,   306,
      -1,   308,   309,    -1,    -1,    -1,    -1,   314,   315,     1,
      -1,     3,     4,     5,     6,     7,    -1,    -1,    -1,    -1,
      12,    13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      22,    23,    24,    25,    26,    27,    28,    29,    -1,    31,
      32,    33,    34,    35,    36,    37,    38,    39,    40,    -1,
      42,    -1,    -1,    -1,    -1,    -1,    48,    49,    50,    51,
      52,    53,    54,    -1,    -1,    -1,    -1,    59,    60,    -1,
      -1,    -1,    64,    65,    66,    67,    68,    69,    70,    71,
      72,    73,    74,    75,    76,    77,    78,    79,    80,    81,
      82,    83,    84,    85,    86,    87,     1,    -1,     3,     4,
       5,     6,     7,    -1,    -1,    -1,    -1,    12,    13,    14,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,    23,    24,
      25,    26,    27,    28,    29,    -1,    31,    32,    33,    34,
      35,    36,    37,    38,    39,    40,    -1,    42,    -1,    -1,
      -1,    -1,    -1,    48,    49,    50,    51,    52,    53,    54,
      -1,    -1,    -1,    -1,    59,    60,    -1,    -1,    -1,    64,
      65,    66,    67,    68,    69,    70,    71,    72,    73,    74,
      75,    76,    77,    78,    79,    80,    81,    82,    83,    84,
      85,    86,    87,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,     1,    -1,     3,     4,     5,     6,     7,    -1,
      -1,    -1,    -1,    12,    13,    14,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    22,    23,    24,    25,    26,    27,    28,
      29,    -1,    31,    32,    33,    34,    35,    36,    37,    38,
      39,    40,    -1,    42,    -1,    -1,    -1,    -1,    -1,    48,
      49,    50,    51,    52,    53,    54,    -1,    -1,    -1,    -1,
      59,    60,    -1,   255,   256,    64,    65,    66,    67,    68,
      69,    70,    71,    72,    73,    74,    75,    76,    77,    78,
      79,    80,    81,    82,    83,    84,    85,    86,    87,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,    -1,
      -1,    -1,   294,    -1,   296,   297,   298,   299,    -1,   301,
     302,   303,   304,    -1,   306,    -1,   308,   309,    -1,    -1,
      -1,    -1,   314,   315,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     255,   256,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,   289,    -1,    -1,    -1,    -1,   294,
      -1,   296,   297,   298,   299,    -1,   301,   302,   303,   304,
      -1,   306,    -1,   308,   309,    -1,    -1,    -1,    -1,   314,
     315,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   255,   256,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     289,    -1,    -1,    -1,    -1,   294,    -1,   296,   297,   298,
     299,    -1,   301,   302,   303,   304,    -1,   306,    -1,   308,
     309,    -1,    -1,    -1,    -1,   314,   315,     1,    -1,     3,
       4,     5,     6,     7,    -1,    -1,    -1,    -1,    12,    13,
      14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,    23,
      24,    25,    26,    27,    28,    29,    -1,    31,    32,    33,
      34,    35,    36,    37,    38,    39,    40,    -1,    42,    -1,
      -1,    -1,    -1,    -1,    48,    49,    50,    51,    52,    53,
      54,    -1,    -1,    -1,    -1,    59,    60,    -1,    -1,    -1,
      64,    65,    66,    67,    68,    69,    70,    71,    72,    73,
      74,    75,    76,    77,    78,    79,    80,    81,    82,    83,
      84,    85,    86,    87,     1,    -1,     3,     4,     5,     6,
       7,    -1,    -1,    -1,    -1,    12,    13,    14,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,
      27,    28,    29,    -1,    31,    32,    33,    34,    35,    36,
      37,    38,    39,    40,    -1,    42,    -1,    -1,    -1,    -1,
      -1,    48,    49,    50,    51,    52,    53,    54,    -1,    -1,
      -1,    -1,    59,    60,    -1,    -1,    -1,    64,    65,    66,
      67,    68,    69,    70,    71,    72,    73,    74,    75,    76,
      77,    78,    79,    80,    81,    82,    83,    84,    85,    86,
      87,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
       1,    -1,     3,     4,     5,     6,     7,    -1,    -1,    -1,
      -1,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    22,    23,    24,    25,    26,    27,    28,    29,    -1,
      31,    32,    33,    34,    35,    36,    37,    38,    39,    40,
      -1,    42,    -1,    -1,    -1,    -1,    -1,    48,    49,    50,
      51,    52,    53,    54,    -1,    -1,    -1,    -1,    59,    60,
      -1,   255,   256,    64,    65,    66,    67,    68,    69,    70,
      71,    72,    73,    74,    75,    76,    77,    78,    79,    80,
      81,    82,    83,    84,    85,    86,    87,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,   289,    -1,    -1,    -1,    -1,
     294,    -1,   296,   297,   298,   299,    -1,   301,   302,   303,
     304,    -1,   306,    -1,   308,   309,    -1,    -1,    -1,    -1,
     314,   315,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   255,   256,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   289,    -1,    -1,    -1,    -1,   294,    -1,   296,
     297,   298,   299,    -1,   301,   302,   303,   304,    -1,   306,
      -1,   308,   309,    -1,    -1,    -1,    -1,   314,   315,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,   255,   256,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,
      -1,    -1,    -1,   294,    -1,   296,   297,   298,   299,    -1,
     301,   302,   303,   304,    -1,   306,    -1,   308,   309,    -1,
      -1,    -1,    -1,   314,   315,     1,    -1,     3,     4,     5,
       6,     7,    -1,    -1,    -1,    -1,    12,    13,    14,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    22,    23,    24,    25,
      26,    27,    28,    29,    -1,    31,    32,    33,    34,    35,
      36,    37,    38,    39,    40,    -1,    42,    -1,    -1,    -1,
      -1,    -1,    48,    49,    50,    51,    52,    53,    54,    -1,
      -1,    -1,    -1,    59,    60,    -1,    -1,    -1,    64,    65,
      66,    67,    68,    69,    70,    71,    72,    73,    74,    75,
      76,    77,    78,    79,    80,    81,    82,    83,    84,    85,
      86,    87,     1,    -1,     3,     4,     5,     6,     7,    -1,
      -1,    -1,    -1,    12,    13,    14,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    22,    23,    24,    25,    26,    27,    28,
      29,    -1,    31,    32,    33,    34,    35,    36,    37,    38,
      39,    40,    -1,    42,    -1,    -1,    -1,    -1,    -1,    48,
      49,    50,    51,    52,    53,    54,    -1,    -1,    -1,    -1,
      59,    60,    -1,    -1,    -1,    64,    65,    66,    67,    68,
      69,    70,    71,    72,    73,    74,    75,    76,    77,    78,
      79,    80,    81,    82,    83,    84,    85,    86,    87,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,     1,    -1,
       3,     4,     5,     6,     7,    -1,    -1,    -1,    -1,    12,
      13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,
      23,    24,    25,    26,    27,    28,    29,    -1,    31,    32,
      33,    34,    35,    36,    37,    38,    39,    40,    -1,    42,
      -1,    -1,    -1,    -1,    -1,    48,    49,    50,    51,    52,
      53,    54,    -1,    -1,    -1,    -1,    59,    60,    -1,   255,
     256,    64,    65,    66,    67,    68,    69,    70,    71,    72,
      73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
      83,    84,    85,    86,    87,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,   289,    -1,    -1,    -1,    -1,   294,    -1,
     296,   297,   298,   299,    -1,   301,   302,   303,   304,    -1,
     306,    -1,   308,   309,    -1,    -1,    -1,    -1,   314,   315,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   255,   256,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     289,    -1,    -1,    -1,    -1,   294,    -1,   296,   297,   298,
     299,    -1,   301,   302,   303,   304,    -1,   306,    -1,   308,
     309,    -1,    -1,    -1,    -1,   314,   315,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   255,   256,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,    -1,    -1,
      -1,   294,    -1,   296,   297,   298,   299,    -1,   301,   302,
     303,   304,    -1,   306,    -1,   308,   309,    -1,    -1,    -1,
      -1,   314,   315,     1,    -1,     3,     4,     5,     6,     7,
      -1,    -1,    -1,    -1,    12,    13,    14,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,    27,
      28,    29,    -1,    31,    32,    33,    34,    35,    36,    37,
      38,    39,    40,    -1,    42,    -1,    -1,    -1,    -1,    -1,
      48,    49,    50,    51,    52,    53,    54,    -1,    -1,    -1,
      -1,    59,    60,    -1,    -1,    -1,    64,    65,    66,    67,
      68,    69,    70,    71,    72,    73,    74,    75,    76,    77,
      78,    79,    80,    81,    82,    83,    84,    85,    86,    87,
       1,    -1,     3,     4,     5,     6,     7,    -1,    -1,    -1,
      -1,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    22,    23,    24,    25,    26,    27,    28,    29,    -1,
      31,    32,    33,    34,    35,    36,    37,    38,    39,    40,
      -1,    42,    -1,    -1,    -1,    -1,    -1,    48,    49,    50,
      51,    52,    53,    54,    -1,    -1,    -1,    -1,    59,    60,
      -1,    -1,    -1,    64,    65,    66,    67,    68,    69,    70,
      71,    72,    73,    74,    75,    76,    77,    78,    79,    80,
      81,    82,    83,    84,    85,    86,    87,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,     1,    -1,     3,     4,
       5,     6,     7,    -1,    -1,    -1,    -1,    12,    13,    14,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,    23,    24,
      25,    26,    27,    28,    29,    -1,    31,    32,    33,    34,
      35,    36,    37,    38,    39,    40,    -1,    42,    -1,    -1,
      -1,    -1,    -1,    48,    49,    50,    51,    52,    53,    54,
      -1,    -1,    -1,    -1,    59,    60,    -1,   255,   256,    64,
      65,    66,    67,    68,    69,    70,    71,    72,    73,    74,
      75,    76,    77,    78,    79,    80,    81,    82,    83,    84,
      85,    86,    87,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,   289,    -1,    -1,    -1,    -1,   294,    -1,   296,   297,
     298,   299,    -1,   301,   302,   303,   304,    -1,   306,    -1,
     308,   309,    -1,    -1,    -1,    -1,   314,   315,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,   255,   256,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,
      -1,    -1,    -1,   294,    -1,   296,   297,   298,   299,    -1,
     301,   302,   303,   304,    -1,   306,    -1,   308,   309,    -1,
      -1,    -1,    -1,   314,   315,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     255,   256,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,   289,    -1,    -1,    -1,    -1,   294,
      -1,   296,   297,   298,   299,    -1,   301,   302,   303,   304,
      -1,   306,    -1,   308,   309,    -1,    -1,    -1,    -1,   314,
     315,     1,    -1,     3,     4,     5,     6,     7,    -1,    -1,
      -1,    -1,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    22,    23,    24,    25,    26,    27,    28,    29,
      -1,    31,    32,    33,    34,    35,    36,    37,    38,    39,
      40,    -1,    42,    -1,    -1,    -1,    -1,    -1,    48,    49,
      50,    51,    52,    53,    54,    -1,    -1,    -1,    -1,    59,
      60,    -1,    -1,    -1,    64,    65,    66,    67,    68,    69,
      70,    71,    72,    73,    74,    75,    76,    77,    78,    79,
      80,    81,    82,    83,    84,    85,    86,    87,     1,    -1,
       3,     4,     5,     6,     7,    -1,    -1,    -1,    -1,    12,
      13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,
      23,    24,    25,    26,    27,    28,    29,    -1,    31,    32,
      33,    34,    35,    36,    37,    38,    39,    40,    -1,    42,
      -1,    -1,    -1,    -1,    -1,    48,    49,    50,    51,    52,
      53,    54,    -1,    -1,    -1,    -1,    59,    60,    -1,    -1,
      -1,    64,    65,    66,    67,    68,    69,    70,    71,    72,
      73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
      83,    84,    85,    86,    87,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,     1,    -1,     3,     4,     5,     6,
       7,    -1,    -1,    -1,    -1,    12,    13,    14,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,
      27,    28,    29,    -1,    31,    32,    33,    34,    35,    36,
      37,    38,    39,    40,    -1,    42,    -1,    -1,    -1,    -1,
      -1,    48,    49,    50,    51,    52,    53,    54,    -1,    -1,
      -1,    -1,    59,    60,    -1,   255,   256,    64,    65,    66,
      67,    68,    69,    70,    71,    72,    73,    74,    75,    76,
      77,    78,    79,    80,    81,    82,    83,    84,    85,    86,
      87,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   289,
      -1,    -1,    -1,    -1,   294,    -1,   296,   297,   298,   299,
      -1,   301,   302,   303,   304,    -1,   306,    -1,   308,   309,
      -1,    -1,    -1,    -1,   314,   315,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   255,   256,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,    -1,    -1,
      -1,   294,    -1,   296,   297,   298,   299,    -1,   301,   302,
     303,   304,    -1,   306,    -1,   308,   309,    -1,    -1,    -1,
      -1,   314,   315,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   255,   256,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   289,    -1,    -1,    -1,    -1,   294,    -1,   296,
     297,   298,   299,    -1,   301,   302,   303,   304,    -1,   306,
      -1,   308,   309,    -1,    -1,    -1,    -1,   314,   315,     1,
      -1,     3,     4,     5,     6,     7,    -1,    -1,    -1,    -1,
      12,    13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      22,    23,    24,    25,    26,    27,    28,    29,    -1,    31,
      32,    33,    34,    35,    36,    37,    38,    39,    40,    -1,
      42,    -1,    -1,    -1,    -1,    -1,    48,    49,    50,    51,
      52,    53,    54,    -1,    -1,    -1,    -1,    59,    60,    -1,
      -1,    -1,    64,    65,    66,    67,    68,    69,    70,    71,
      72,    73,    74,    75,    76,    77,    78,    79,    80,    81,
      82,    83,    84,    85,    86,    87,     1,    -1,     3,     4,
       5,     6,     7,    -1,    -1,    -1,    -1,    12,    13,    14,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,    23,    24,
      25,    26,    27,    28,    29,    -1,    31,    32,    33,    34,
      35,    36,    37,    38,    39,    40,    -1,    42,    -1,    -1,
      -1,    -1,    -1,    48,    49,    50,    51,    52,    53,    54,
      -1,    -1,    -1,    -1,    59,    60,    -1,    -1,    -1,    64,
      65,    66,    67,    68,    69,    70,    71,    72,    73,    74,
      75,    76,    77,    78,    79,    80,    81,    82,    83,    84,
      85,    86,    87,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,     1,    -1,     3,     4,     5,     6,     7,    -1,
      -1,    -1,    -1,    12,    13,    14,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    22,    23,    24,    25,    26,    27,    28,
      29,    -1,    31,    32,    33,    34,    35,    36,    37,    38,
      39,    40,    -1,    42,    -1,    -1,    -1,    -1,    -1,    48,
      49,    50,    51,    52,    53,    54,    -1,    -1,    -1,    -1,
      59,    60,    -1,   255,   256,    64,    65,    66,    67,    68,
      69,    70,    71,    72,    73,    74,    75,    76,    77,    78,
      79,    80,    81,    82,    83,    84,    85,    86,    87,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,    -1,
      -1,    -1,   294,    -1,   296,   297,   298,   299,    -1,   301,
     302,   303,   304,    -1,   306,    -1,   308,   309,    -1,    -1,
      -1,    -1,   314,   315,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     255,   256,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,   289,    -1,    -1,    -1,    -1,   294,
      -1,   296,   297,   298,   299,    -1,   301,   302,   303,   304,
      -1,   306,    -1,   308,   309,    -1,    -1,    -1,    -1,   314,
     315,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   255,   256,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     289,    -1,    -1,    -1,    -1,   294,    -1,   296,   297,   298,
     299,    -1,   301,   302,   303,   304,    -1,   306,    -1,   308,
     309,    -1,    -1,    -1,    -1,   314,   315,     1,    -1,     3,
       4,     5,     6,     7,    -1,    -1,    -1,    -1,    12,    13,
      14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,    23,
      24,    25,    26,    27,    28,    29,    -1,    31,    32,    33,
      34,    35,    36,    37,    38,    39,    40,    -1,    42,    -1,
      -1,    -1,    -1,    -1,    48,    49,    50,    51,    52,    53,
      54,    -1,    -1,    -1,    -1,    59,    60,    -1,    -1,    -1,
      64,    65,    66,    67,    68,    69,    70,    71,    72,    73,
      74,    75,    76,    77,    78,    79,    80,    81,    82,    83,
      84,    85,    86,    87,     1,    -1,     3,     4,     5,     6,
       7,    -1,    -1,    -1,    -1,    12,    13,    14,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,
      27,    28,    29,    -1,    31,    32,    33,    34,    35,    36,
      37,    38,    39,    40,    -1,    42,    -1,    -1,    -1,    -1,
      -1,    48,    49,    50,    51,    52,    53,    54,    -1,    -1,
      -1,    -1,    59,    60,    -1,    -1,    -1,    64,    65,    66,
      67,    68,    69,    70,    71,    72,    73,    74,    75,    76,
      77,    78,    79,    80,    81,    82,    83,    84,    85,    86,
      87,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
       1,    -1,     3,     4,     5,     6,     7,    -1,    -1,    -1,
      -1,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    22,    23,    24,    25,    26,    27,    28,    29,    -1,
      31,    32,    33,    34,    35,    36,    37,    38,    39,    40,
      -1,    42,    -1,    -1,    -1,    -1,    -1,    48,    49,    50,
      51,    52,    53,    54,    -1,    -1,    -1,    -1,    59,    60,
      -1,   255,   256,    64,    65,    66,    67,    68,    69,    70,
      71,    72,    73,    74,    75,    76,    77,    78,    79,    80,
      81,    82,    83,    84,    85,    86,    87,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,   289,    -1,    -1,    -1,    -1,
     294,    -1,   296,   297,   298,   299,    -1,   301,   302,   303,
     304,    -1,   306,    -1,   308,   309,    -1,    -1,    -1,    -1,
     314,   315,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   255,   256,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,   289,    -1,    -1,    -1,    -1,   294,    -1,   296,
     297,   298,   299,    -1,   301,   302,   303,   304,    -1,   306,
      -1,   308,   309,    -1,    -1,    -1,    -1,   314,   315,    -1,
      -1,    -1,    -1,     3,     4,     5,     6,     7,     8,     9,
      10,    11,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    22,    23,    24,    25,    26,    27,    28,    29,
      -1,    31,    32,    33,    34,    35,    36,    37,    38,    39,
      40,    -1,    42,    -1,   255,   256,    46,    -1,    48,    49,
      50,    51,    52,    53,    54,    55,    -1,    -1,    58,    59,
      60,    -1,    -1,    -1,    64,    65,    66,    67,    68,    69,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,
      -1,    -1,    -1,   294,    -1,   296,   297,   298,   299,    -1,
     301,   302,   303,   304,    -1,   306,    -1,   308,   309,    -1,
      -1,    -1,    -1,   314,   315,     3,     4,     5,     6,     7,
      -1,    -1,    -1,    -1,    12,    13,    14,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,    27,
      28,    29,    -1,    31,    32,    33,    34,    35,    36,    37,
      38,    39,    40,    -1,    42,    -1,    -1,    -1,    -1,    -1,
      48,    49,    50,    51,    52,    53,    54,    -1,    -1,    -1,
      -1,    59,    60,    -1,    -1,    -1,    64,    65,    66,    67,
      68,    69,    70,    71,    72,    73,    74,    75,    76,    77,
      78,    79,    80,    81,    82,    83,    84,    85,    86,    87,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,   255,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,   271,   272,   273,   274,   275,   276,   277,   278,   279,
     280,   281,   282,   283,   284,   285,   286,   287,   288,   289,
     290,   291,   292,   293,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,   304,    -1,    -1,    -1,   308,   309,
      -1,    -1,   312,    -1,    -1,   315,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,   255,   256,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,   289,    -1,    -1,    -1,    -1,   294,    -1,   296,   297,
     298,   299,    -1,   301,   302,   303,   304,    -1,   306,    -1,
     308,   309,    -1,    -1,    -1,    -1,   314,   315,     3,     4,
       5,     6,     7,    -1,    -1,    -1,    -1,    12,    13,    14,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    22,    23,    24,
      25,    26,    27,    28,    29,    -1,    31,    32,    33,    34,
      35,    36,    37,    38,    39,    40,    -1,    42,    -1,    -1,
      -1,    -1,    -1,    48,    49,    50,    51,    52,    53,    54,
      -1,    -1,    -1,    -1,    59,    60,    -1,    -1,    -1,    64,
      65,    66,    67,    68,    69,    70,    71,    72,    73,    74,
      75,    76,    77,    78,    79,    80,    81,    82,    83,    84,
      85,    86,    87,     3,     4,     5,     6,     7,    -1,    -1,
      -1,    -1,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    22,    23,    24,    25,    26,    27,    28,    29,
      -1,    31,    32,    33,    34,    35,    36,    37,    38,    39,
      40,    -1,    42,    -1,    -1,    -1,    -1,    -1,    48,    49,
      50,    51,    52,    53,    54,    -1,    -1,    -1,    -1,    59,
      60,    -1,    -1,    -1,    64,    65,    66,    67,    68,    69,
      70,    71,    72,    73,    74,    75,    76,    77,    78,    79,
      80,    81,    82,    83,    84,    85,    86,    87,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,     3,     4,     5,     6,     7,    -1,    -1,    -1,    -1,
      12,    13,    14,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      22,    23,    24,    25,    26,    27,    28,    29,    -1,    31,
      32,    33,    34,    35,    36,    37,    38,    39,    40,    -1,
      42,    -1,    -1,    -1,    -1,    -1,    48,    49,    50,    51,
      52,    53,    54,    -1,    -1,    -1,    -1,    59,    60,    -1,
     255,   256,    64,    65,    66,    67,    68,    69,    70,    71,
      72,    73,    74,    75,    76,    77,    78,    79,    80,    81,
      82,    83,    84,    85,    86,    87,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,   289,    -1,    -1,    -1,    -1,   294,
      -1,   296,   297,   298,   299,    -1,   301,   302,   303,   304,
      -1,   306,    -1,   308,   309,    -1,    -1,    -1,    -1,   314,
     315,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,   255,   256,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,   289,
      -1,    -1,    -1,    -1,   294,    -1,   296,   297,   298,   299,
      -1,   301,   302,   303,   304,    -1,   306,    -1,   308,   309,
      -1,    -1,    -1,    -1,   314,   315,    -1,    -1,    -1,    -1,
      -1,    -1,     3,     4,     5,     6,     7,     8,     9,    10,
      11,    12,    13,    14,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    22,    23,    24,    25,    26,    27,    28,    29,    -1,
      31,    32,    33,    34,    35,    36,    37,    38,    39,    40,
      -1,    42,    -1,   255,   256,    46,    -1,    48,    49,    50,
      51,    52,    53,    54,    55,    -1,    -1,    58,    59,    60,
      -1,    -1,    -1,    64,    65,    66,    67,    68,    69,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,   289,    -1,    -1,
      -1,    -1,   294,    -1,   296,   297,   298,   299,    -1,   301,
     302,   303,   304,    -1,   306,    -1,   308,   309,    -1,    -1,
      -1,    -1,   314,   315,     3,     4,     5,     6,     7,    -1,
      -1,    -1,    -1,    12,    13,    14,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    22,    23,    24,    25,    26,    27,    28,
      29,    -1,    31,    32,    33,    34,    35,    36,    37,    38,
      39,    40,    -1,    42,    -1,    -1,    -1,    -1,    -1,    48,
      49,    50,    51,    52,    53,    54,    -1,    -1,    -1,    -1,
      59,    60,    -1,    -1,    -1,    64,    65,    66,    67,    68,
      69,    70,    71,    72,    73,    74,    75,    76,    77,    78,
      79,    80,    81,    82,    83,    84,    85,    86,    87,    -1,
      -1,    -1,     1,    -1,     3,     4,     5,     6,     7,     8,
       9,    10,    11,    12,    13,    14,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    22,    23,    24,    25,    26,    27,    28,
      29,    -1,    31,    32,    33,    34,    35,    36,    37,    38,
      39,    40,    -1,    42,    -1,    -1,    -1,    46,    -1,    48,
      49,    50,    51,    52,    53,    54,    55,    -1,    -1,    58,
      59,    60,    -1,    -1,   255,    64,    65,    66,    67,    68,
      69,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,   304,    -1,    -1,    -1,   308,   309,    -1,
      -1,   312,    -1,    -1,   315,     3,     4,     5,     6,     7,
       8,     9,    10,    11,    12,    13,    14,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    22,    23,    24,    25,    26,    27,
      28,    29,    -1,    31,    32,    33,    34,    35,    36,    37,
      38,    39,    40,    -1,    42,    -1,   255,   256,    46,    -1,
      48,    49,    50,    51,    52,    53,    54,    55,    -1,    -1,
      58,    59,    60,    -1,    -1,    -1,    64,    65,    66,    67,
      68,    69,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
     289,    -1,    -1,    -1,    -1,   294,    -1,   296,   297,   298,
     299,    -1,   301,   302,   303,   304,    -1,   306,    -1,   308,
     309,    -1,    -1,    -1,    -1,   314,   315,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   255,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,   304,    -1,    -1,    -1,   308,
     309,    -1,    -1,   312,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,   255,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
      -1,    -1,    -1,    -1,    -1,    -1,   304,    -1,    -1,    -1,
     308,   309,    -1,    -1,   312
};

/* YYSTOS[STATE-NUM] -- The (internal number of the) accessing
   symbol of state STATE-NUM.  */
static const yytype_uint16 yystos[] =
{
       0,     1,    17,    18,    38,    41,    42,    43,    44,    45,
      57,    94,    95,    96,    97,    98,    99,   100,   101,   102,
     103,   105,   106,   107,   108,   109,   110,   111,   112,   113,
     114,   115,   116,   117,   118,   119,   120,   121,   122,   123,
     124,   125,   126,   127,   128,   129,   130,   131,   132,   133,
     134,   135,   136,   137,   138,   139,   140,   141,   142,   143,
     144,   145,   146,   147,   148,   149,   150,   151,   152,   153,
     154,   155,   156,   157,   158,   159,   160,   161,   162,   163,
     164,   165,   166,   167,   168,   169,   170,   171,   172,   173,
     174,   175,   176,   177,   178,   179,   180,   181,   182,   183,
     184,   185,   186,   187,   188,   189,   190,   191,   192,   193,
     194,   195,   196,   197,   198,   199,   200,   201,   202,   203,
     204,   205,   206,   207,   212,   213,   214,   215,   216,   217,
     218,   219,   220,   221,   222,   223,   224,   225,   226,   227,
     228,   229,   230,   231,   232,   233,   234,   235,   236,   237,
     238,   239,   240,   241,   242,   243,   245,   246,   247,   248,
     249,   250,   251,   252,   253,   254,   267,   268,   269,   304,
     312,   319,   322,   323,   324,   325,   326,   327,   328,   329,
     340,   344,   347,   348,   349,   350,   359,   360,   366,   270,
       1,   317,     1,   270,   270,   270,   270,   270,   270,   270,
     270,   270,   270,   270,   270,   270,   270,   270,   270,   270,
     270,   270,   270,     1,   270,     1,   270,     1,   270,     1,
     270,     1,   270,     1,   270,     1,   270,     1,   270,     1,
     270,     1,   270,     1,   270,     1,   270,     1,   270,     1,
     270,     1,   270,     1,   270,     1,   270,     1,   270,     1,
     270,     1,   270,     1,   270,     1,   270,     1,   270,     1,
     270,     1,   270,     1,   270,     1,   270,     1,   270,     1,
     270,     1,   270,     1,   270,     1,   270,     1,   270,     1,
     270,   270,   270,   270,   270,   270,   270,   270,   270,   270,
     270,   270,   270,   270,   270,   270,   270,   270,   270,     1,
     306,     1,   270,   306,     1,   314,   270,   270,   270,   270,
     270,   270,   270,   270,   270,   270,   270,   270,   270,   270,
     270,   270,   270,   270,   270,   270,   270,   270,     1,   270,
       1,   270,     1,   270,     1,   270,   270,   270,   270,   270,
     270,   270,   270,   270,   270,   270,   270,   270,   270,   270,
     270,   270,   270,   270,   270,   270,   270,   270,   270,   270,
     270,   270,   270,   270,   270,   270,   270,   270,   270,   270,
     270,   270,   270,   270,   270,   270,   270,   270,   270,     1,
     270,   270,   270,   270,   270,   270,   270,   270,   270,   270,
     270,   270,   270,   270,   270,     1,   304,   306,   341,   342,
     343,     1,   343,   345,   346,     1,   306,     1,   306,     1,
     306,     0,     1,   324,    14,    15,   356,   357,    16,   358,
      19,   363,    20,   364,    21,   365,   300,   317,   315,   303,
     304,   306,   355,     1,   303,     1,   303,     1,    88,    89,
      90,    91,   293,   333,     1,   289,   303,   339,     1,   303,
       1,   303,     1,   339,     1,   303,     1,   303,     1,   303,
       1,   303,     1,   304,     1,   306,     1,   303,     1,   306,
       1,   303,   304,   305,   306,   307,   314,   317,   330,   332,
     333,   337,   338,   351,   352,   353,   354,   380,   381,     1,
     338,     1,   303,     1,   303,     1,   303,   303,   303,   303,
     339,   339,   339,   339,   303,   303,   303,   303,   303,   303,
     303,   303,   303,   303,   303,   303,   303,   303,   303,   303,
     303,   303,   303,   303,   303,   303,   303,   303,   303,   303,
     303,     1,   303,   306,     1,   303,     1,   303,     1,   303,
       1,   303,     1,   303,     1,   339,     1,   339,     1,   339,
       1,   339,     1,   339,     1,   339,     1,   303,     1,   303,
       1,   306,     1,   306,     1,   303,     1,   306,   306,     1,
     303,     1,   303,     1,   304,   306,     1,   304,   306,     1,
     304,   306,     1,   304,   306,     1,   304,   306,     1,   303,
       1,   303,     1,   303,     1,   303,     1,   339,     1,   339,
       1,   339,     1,   304,   306,     1,   303,     1,   303,     1,
     303,     1,   303,   352,     1,   354,     1,   303,     1,   303,
     303,   303,   303,   303,     1,   303,     1,   303,     1,   303,
       1,   303,     1,   303,     1,   303,     1,   303,     1,   303,
       1,   303,     1,   303,     1,   303,     1,   303,     1,   303,
       1,   303,     1,   303,     1,   208,   209,   210,   211,     1,
     303,     1,   303,     1,   303,     1,   303,     1,   306,     1,
     306,     1,   306,     1,   303,     1,   303,     1,   303,     1,
     330,     1,   303,     1,   303,     1,   303,     1,   303,     1,
     303,     1,   303,     1,   303,     1,   303,     1,   303,     1,
     303,     1,   303,     1,   303,     1,   303,     1,   303,     1,
     303,     1,   303,   304,   303,     1,   303,     1,   303,     1,
     303,     1,   303,     1,   303,     1,   306,   303,   303,   303,
       1,   303,     1,   303,     1,   303,     1,   303,     1,   303,
     311,   320,   311,     1,   317,     1,   315,     1,   315,   317,
       1,   315,   317,     1,   315,   317,     1,   317,   348,   303,
     361,   318,   303,   300,   330,   331,   353,   320,   320,   338,
     104,   289,   300,   311,   341,   303,   345,   355,     3,     4,
       5,     6,     7,     8,     9,    10,    11,    12,    13,    14,
      22,    23,    24,    25,    26,    27,    28,    29,    31,    32,
      33,    34,    35,    36,    37,    38,    39,    40,    42,    46,
      48,    49,    50,    51,    52,    53,    54,    55,    58,    59,
      60,    64,    65,    66,    67,    68,    69,   255,   304,   308,
     309,   312,   382,   384,   385,   386,   390,   391,   399,   400,
     402,   403,   406,   407,   409,   413,   414,   415,   419,   384,
     355,   384,   355,   384,   355,   310,   270,   318,   384,     1,
     362,   303,   311,   313,   318,     1,   293,   303,   335,   332,
     330,     1,   303,   380,     1,   380,   306,   318,     1,   314,
       1,   314,     1,   314,     1,   314,     1,   314,    70,    71,
      72,    73,    74,    75,    76,    77,    78,    79,    80,    81,
      82,    83,    84,    85,    86,    87,   256,   289,   294,   296,
     297,   298,   299,   301,   302,   306,   314,   315,   339,   372,
     373,   374,   375,   376,   377,   382,   394,   399,   400,   401,
     402,   404,   406,   407,   410,   411,   412,   314,   412,   314,
     412,     1,   314,     1,   314,     1,   314,   314,     1,   314,
       1,   314,     1,   314,     1,   314,     1,   314,     1,   314,
       1,   314,     1,   314,   314,     1,   314,     1,   314,     1,
     314,     1,   314,   314,   314,   314,   314,   314,   314,   412,
       1,   314,     1,   314,     1,   314,   314,   314,   314,   314,
       1,   412,   412,     1,   314,     1,   314,     1,   314,     1,
     314,     1,   314,     1,   416,     1,   312,     1,   316,   385,
     257,   258,   259,   260,   261,   262,   263,   264,   265,   304,
     396,   397,   398,   270,   408,   312,     1,   314,   312,   316,
     318,   316,   318,   316,   318,   318,     1,   303,   306,   300,
     316,   315,   300,   331,   320,   320,   311,   315,     1,    61,
     304,   306,   313,   351,   379,     1,    61,   306,   351,   379,
       1,    61,   306,   351,   379,     1,    61,   306,   351,   379,
       1,    61,   306,   351,   379,     1,   278,   279,   282,   283,
     284,   367,   369,   367,   367,     1,   367,   395,     1,   412,
       1,   412,     1,   412,   314,   314,     1,   313,   400,   402,
     406,   407,   412,   413,     1,   384,     1,   369,     1,   285,
     286,   287,   288,   367,   368,     1,   369,   398,     1,   412,
     271,   272,   273,   274,   275,   276,   277,   278,   279,   280,
     281,   282,   283,   284,   285,   286,   287,   288,   289,   290,
     291,   292,   293,   370,   371,   313,   313,     1,   303,   306,
       1,   306,     1,   304,   412,   306,     1,   306,     1,   306,
       1,   306,     1,   306,     1,   303,     1,   303,   313,     1,
     313,   339,   313,     1,   306,     1,   306,     1,   306,     1,
     306,   313,   313,   313,     1,   303,   313,     1,   333,   315,
     383,   385,     1,   330,     1,   303,     1,   330,   333,   336,
     313,   313,   313,   313,   315,   383,     1,   306,     1,   306,
     303,   343,   303,   343,   303,   343,   314,   300,   317,   412,
       1,   306,   399,   400,   405,   407,   315,   315,   315,   315,
     244,   244,   348,   384,   303,   335,   303,   306,   339,   384,
     313,   311,   313,   311,   313,   311,   313,   289,   300,   311,
     313,   313,   311,   313,   311,   313,   311,   313,   311,   313,
     313,   311,   313,   311,   313,   311,   313,   311,   313,   313,
     311,   313,   311,   313,   311,   313,   311,   313,   313,   311,
     313,   311,   313,   311,   313,   311,   313,     1,   304,   412,
       1,    88,    89,    90,    91,    92,    93,   293,   334,   412,
       1,   334,   412,     1,   372,   376,   304,   392,   393,     1,
     412,     1,   412,   313,   313,   313,   316,   316,     1,    86,
     412,     1,   412,     1,   412,     1,    86,   303,   304,   351,
     378,   379,   412,   317,     1,   412,     1,   412,     1,   412,
       1,   412,   412,   412,   412,     1,   412,     1,   412,     1,
     412,     1,   412,     1,   412,     1,   412,     1,   412,   313,
     311,   313,   313,   311,   313,   313,   313,   313,   313,   313,
     313,   313,   313,   313,   313,   313,   313,   313,   313,   313,
     313,   313,   313,   313,   313,   313,   313,   313,   313,   313,
     313,   313,   313,   313,   384,    47,   313,   313,   313,   313,
     313,   320,   320,   313,     1,    56,    57,   316,   388,   389,
     313,   311,   313,   313,   313,   313,   313,   313,   313,   313,
     412,   417,   418,   397,   318,   311,   313,   398,   311,   384,
     384,   384,   384,   306,   306,   270,   316,   300,   313,   313,
     316,    62,   303,   303,   303,     1,   304,     1,   304,   303,
      62,   303,   303,   303,   303,    62,   303,   303,   303,   303,
      62,   303,   303,   303,   303,    62,   303,   303,   303,   303,
     317,   300,   313,   313,   313,   313,   292,   339,   306,   306,
     316,   383,     1,   335,   330,   316,     1,   292,   387,   412,
     320,   316,   388,   303,   412,   311,   313,   343,     1,   343,
     316,   316,   316,   316,   303,   306,   303,   313,   313,   313,
     313,   313,   313,   313,   313,   313,   313,   313,   313,   313,
     313,   313,   313,   313,   313,   313,   313,   313,   313,   313,
     313,   313,   306,   339,   392,     1,   303,   351,   318,   313,
     313,   320,   320,     1,   387,   320,   384,   313,   313,   418,
     313,   313,   313,   318,   318,   335,   384,   320,   320,     1,
     384,   384,   384
};

#define yyerrok		(yyerrstatus = 0)
#define yyclearin	(yychar = YYEMPTY)
#define YYEMPTY		(-2)
#define YYEOF		0

#define YYACCEPT	goto yyacceptlab
#define YYABORT		goto yyabortlab
#define YYERROR		goto yyerrorlab


/* Like YYERROR except do call yyerror.  This remains here temporarily
   to ease the transition to the new meaning of YYERROR, for GCC.
   Once GCC version 2 has supplanted version 1, this can go.  However,
   YYFAIL appears to be in use.  Nevertheless, it is formally deprecated
   in Bison 2.4.2's NEWS entry, where a plan to phase it out is
   discussed.  */

#define YYFAIL		goto yyerrlab
#if defined YYFAIL
  /* This is here to suppress warnings from the GCC cpp's
     -Wunused-macros.  Normally we don't worry about that warning, but
     some users do, and we want to make it easy for users to remove
     YYFAIL uses, which will produce warnings from Bison 2.5.  */
#endif

#define YYRECOVERING()  (!!yyerrstatus)

#define YYBACKUP(Token, Value)                                  \
do                                                              \
  if (yychar == YYEMPTY)                                        \
    {                                                           \
      yychar = (Token);                                         \
      yylval = (Value);                                         \
      YYPOPSTACK (yylen);                                       \
      yystate = *yyssp;                                         \
      goto yybackup;                                            \
    }                                                           \
  else                                                          \
    {                                                           \
      yyerror (YY_("syntax error: cannot back up")); \
      YYERROR;							\
    }								\
while (YYID (0))

/* Error token number */
#define YYTERROR	1
#define YYERRCODE	256


/* This macro is provided for backward compatibility. */
#ifndef YY_LOCATION_PRINT
# define YY_LOCATION_PRINT(File, Loc) ((void) 0)
#endif


/* YYLEX -- calling `yylex' with the right arguments.  */
#ifdef YYLEX_PARAM
# define YYLEX yylex (YYLEX_PARAM)
#else
# define YYLEX yylex ()
#endif

/* Enable debugging if requested.  */
#if YYDEBUG

# ifndef YYFPRINTF
#  include <stdio.h> /* INFRINGES ON USER NAME SPACE */
#  define YYFPRINTF fprintf
# endif

# define YYDPRINTF(Args)			\
do {						\
  if (yydebug)					\
    YYFPRINTF Args;				\
} while (YYID (0))

# define YY_SYMBOL_PRINT(Title, Type, Value, Location)			  \
do {									  \
  if (yydebug)								  \
    {									  \
      YYFPRINTF (stderr, "%s ", Title);					  \
      yy_symbol_print (stderr,						  \
		  Type, Value); \
      YYFPRINTF (stderr, "\n");						  \
    }									  \
} while (YYID (0))


/*--------------------------------.
| Print this symbol on YYOUTPUT.  |
`--------------------------------*/

/*ARGSUSED*/
#if (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
static void
yy_symbol_value_print (FILE *yyoutput, int yytype, YYSTYPE const * const yyvaluep)
#else
static void
yy_symbol_value_print (yyoutput, yytype, yyvaluep)
    FILE *yyoutput;
    int yytype;
    YYSTYPE const * const yyvaluep;
#endif
{
  FILE *yyo = yyoutput;
  YYUSE (yyo);
  if (!yyvaluep)
    return;
# ifdef YYPRINT
  if (yytype < YYNTOKENS)
    YYPRINT (yyoutput, yytoknum[yytype], *yyvaluep);
# else
  YYUSE (yyoutput);
# endif
  switch (yytype)
    {
      default:
        break;
    }
}


/*--------------------------------.
| Print this symbol on YYOUTPUT.  |
`--------------------------------*/

#if (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
static void
yy_symbol_print (FILE *yyoutput, int yytype, YYSTYPE const * const yyvaluep)
#else
static void
yy_symbol_print (yyoutput, yytype, yyvaluep)
    FILE *yyoutput;
    int yytype;
    YYSTYPE const * const yyvaluep;
#endif
{
  if (yytype < YYNTOKENS)
    YYFPRINTF (yyoutput, "token %s (", yytname[yytype]);
  else
    YYFPRINTF (yyoutput, "nterm %s (", yytname[yytype]);

  yy_symbol_value_print (yyoutput, yytype, yyvaluep);
  YYFPRINTF (yyoutput, ")");
}

/*------------------------------------------------------------------.
| yy_stack_print -- Print the state stack from its BOTTOM up to its |
| TOP (included).                                                   |
`------------------------------------------------------------------*/

#if (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
static void
yy_stack_print (yytype_int16 *yybottom, yytype_int16 *yytop)
#else
static void
yy_stack_print (yybottom, yytop)
    yytype_int16 *yybottom;
    yytype_int16 *yytop;
#endif
{
  YYFPRINTF (stderr, "Stack now");
  for (; yybottom <= yytop; yybottom++)
    {
      int yybot = *yybottom;
      YYFPRINTF (stderr, " %d", yybot);
    }
  YYFPRINTF (stderr, "\n");
}

# define YY_STACK_PRINT(Bottom, Top)				\
do {								\
  if (yydebug)							\
    yy_stack_print ((Bottom), (Top));				\
} while (YYID (0))


/*------------------------------------------------.
| Report that the YYRULE is going to be reduced.  |
`------------------------------------------------*/

#if (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
static void
yy_reduce_print (YYSTYPE *yyvsp, int yyrule)
#else
static void
yy_reduce_print (yyvsp, yyrule)
    YYSTYPE *yyvsp;
    int yyrule;
#endif
{
  int yynrhs = yyr2[yyrule];
  int yyi;
  unsigned long int yylno = yyrline[yyrule];
  YYFPRINTF (stderr, "Reducing stack by rule %d (line %lu):\n",
	     yyrule - 1, yylno);
  /* The symbols being reduced.  */
  for (yyi = 0; yyi < yynrhs; yyi++)
    {
      YYFPRINTF (stderr, "   $%d = ", yyi + 1);
      yy_symbol_print (stderr, yyrhs[yyprhs[yyrule] + yyi],
		       &(yyvsp[(yyi + 1) - (yynrhs)])
		       		       );
      YYFPRINTF (stderr, "\n");
    }
}

# define YY_REDUCE_PRINT(Rule)		\
do {					\
  if (yydebug)				\
    yy_reduce_print (yyvsp, Rule); \
} while (YYID (0))

/* Nonzero means print parse trace.  It is left uninitialized so that
   multiple parsers can coexist.  */
int yydebug;
#else /* !YYDEBUG */
# define YYDPRINTF(Args)
# define YY_SYMBOL_PRINT(Title, Type, Value, Location)
# define YY_STACK_PRINT(Bottom, Top)
# define YY_REDUCE_PRINT(Rule)
#endif /* !YYDEBUG */


/* YYINITDEPTH -- initial size of the parser's stacks.  */
#ifndef	YYINITDEPTH
# define YYINITDEPTH 200
#endif

/* YYMAXDEPTH -- maximum size the stacks can grow to (effective only
   if the built-in stack extension method is used).

   Do not make this value too large; the results are undefined if
   YYSTACK_ALLOC_MAXIMUM < YYSTACK_BYTES (YYMAXDEPTH)
   evaluated with infinite-precision integer arithmetic.  */

#ifndef YYMAXDEPTH
# define YYMAXDEPTH 10000
#endif


#if YYERROR_VERBOSE

# ifndef yystrlen
#  if defined __GLIBC__ && defined _STRING_H
#   define yystrlen strlen
#  else
/* Return the length of YYSTR.  */
#if (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
static YYSIZE_T
yystrlen (const char *yystr)
#else
static YYSIZE_T
yystrlen (yystr)
    const char *yystr;
#endif
{
  YYSIZE_T yylen;
  for (yylen = 0; yystr[yylen]; yylen++)
    continue;
  return yylen;
}
#  endif
# endif

# ifndef yystpcpy
#  if defined __GLIBC__ && defined _STRING_H && defined _GNU_SOURCE
#   define yystpcpy stpcpy
#  else
/* Copy YYSRC to YYDEST, returning the address of the terminating '\0' in
   YYDEST.  */
#if (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
static char *
yystpcpy (char *yydest, const char *yysrc)
#else
static char *
yystpcpy (yydest, yysrc)
    char *yydest;
    const char *yysrc;
#endif
{
  char *yyd = yydest;
  const char *yys = yysrc;

  while ((*yyd++ = *yys++) != '\0')
    continue;

  return yyd - 1;
}
#  endif
# endif

# ifndef yytnamerr
/* Copy to YYRES the contents of YYSTR after stripping away unnecessary
   quotes and backslashes, so that it's suitable for yyerror.  The
   heuristic is that double-quoting is unnecessary unless the string
   contains an apostrophe, a comma, or backslash (other than
   backslash-backslash).  YYSTR is taken from yytname.  If YYRES is
   null, do not copy; instead, return the length of what the result
   would have been.  */
static YYSIZE_T
yytnamerr (char *yyres, const char *yystr)
{
  if (*yystr == '"')
    {
      YYSIZE_T yyn = 0;
      char const *yyp = yystr;

      for (;;)
	switch (*++yyp)
	  {
	  case '\'':
	  case ',':
	    goto do_not_strip_quotes;

	  case '\\':
	    if (*++yyp != '\\')
	      goto do_not_strip_quotes;
	    /* Fall through.  */
	  default:
	    if (yyres)
	      yyres[yyn] = *yyp;
	    yyn++;
	    break;

	  case '"':
	    if (yyres)
	      yyres[yyn] = '\0';
	    return yyn;
	  }
    do_not_strip_quotes: ;
    }

  if (! yyres)
    return yystrlen (yystr);

  return yystpcpy (yyres, yystr) - yyres;
}
# endif

/* Copy into *YYMSG, which is of size *YYMSG_ALLOC, an error message
   about the unexpected token YYTOKEN for the state stack whose top is
   YYSSP.

   Return 0 if *YYMSG was successfully written.  Return 1 if *YYMSG is
   not large enough to hold the message.  In that case, also set
   *YYMSG_ALLOC to the required number of bytes.  Return 2 if the
   required number of bytes is too large to store.  */
static int
yysyntax_error (YYSIZE_T *yymsg_alloc, char **yymsg,
                yytype_int16 *yyssp, int yytoken)
{
  YYSIZE_T yysize0 = yytnamerr (YY_NULL, yytname[yytoken]);
  YYSIZE_T yysize = yysize0;
  enum { YYERROR_VERBOSE_ARGS_MAXIMUM = 5 };
  /* Internationalized format string. */
  const char *yyformat = YY_NULL;
  /* Arguments of yyformat. */
  char const *yyarg[YYERROR_VERBOSE_ARGS_MAXIMUM];
  /* Number of reported tokens (one for the "unexpected", one per
     "expected"). */
  int yycount = 0;

  /* There are many possibilities here to consider:
     - Assume YYFAIL is not used.  It's too flawed to consider.  See
       <http://lists.gnu.org/archive/html/bison-patches/2009-12/msg00024.html>
       for details.  YYERROR is fine as it does not invoke this
       function.
     - If this state is a consistent state with a default action, then
       the only way this function was invoked is if the default action
       is an error action.  In that case, don't check for expected
       tokens because there are none.
     - The only way there can be no lookahead present (in yychar) is if
       this state is a consistent state with a default action.  Thus,
       detecting the absence of a lookahead is sufficient to determine
       that there is no unexpected or expected token to report.  In that
       case, just report a simple "syntax error".
     - Don't assume there isn't a lookahead just because this state is a
       consistent state with a default action.  There might have been a
       previous inconsistent state, consistent state with a non-default
       action, or user semantic action that manipulated yychar.
     - Of course, the expected token list depends on states to have
       correct lookahead information, and it depends on the parser not
       to perform extra reductions after fetching a lookahead from the
       scanner and before detecting a syntax error.  Thus, state merging
       (from LALR or IELR) and default reductions corrupt the expected
       token list.  However, the list is correct for canonical LR with
       one exception: it will still contain any token that will not be
       accepted due to an error action in a later state.
  */
  if (yytoken != YYEMPTY)
    {
      int yyn = yypact[*yyssp];
      yyarg[yycount++] = yytname[yytoken];
      if (!yypact_value_is_default (yyn))
        {
          /* Start YYX at -YYN if negative to avoid negative indexes in
             YYCHECK.  In other words, skip the first -YYN actions for
             this state because they are default actions.  */
          int yyxbegin = yyn < 0 ? -yyn : 0;
          /* Stay within bounds of both yycheck and yytname.  */
          int yychecklim = YYLAST - yyn + 1;
          int yyxend = yychecklim < YYNTOKENS ? yychecklim : YYNTOKENS;
          int yyx;

          for (yyx = yyxbegin; yyx < yyxend; ++yyx)
            if (yycheck[yyx + yyn] == yyx && yyx != YYTERROR
                && !yytable_value_is_error (yytable[yyx + yyn]))
              {
                if (yycount == YYERROR_VERBOSE_ARGS_MAXIMUM)
                  {
                    yycount = 1;
                    yysize = yysize0;
                    break;
                  }
                yyarg[yycount++] = yytname[yyx];
                {
                  YYSIZE_T yysize1 = yysize + yytnamerr (YY_NULL, yytname[yyx]);
                  if (! (yysize <= yysize1
                         && yysize1 <= YYSTACK_ALLOC_MAXIMUM))
                    return 2;
                  yysize = yysize1;
                }
              }
        }
    }

  switch (yycount)
    {
# define YYCASE_(N, S)                      \
      case N:                               \
        yyformat = S;                       \
      break
      YYCASE_(0, YY_("syntax error"));
      YYCASE_(1, YY_("syntax error, unexpected %s"));
      YYCASE_(2, YY_("syntax error, unexpected %s, expecting %s"));
      YYCASE_(3, YY_("syntax error, unexpected %s, expecting %s or %s"));
      YYCASE_(4, YY_("syntax error, unexpected %s, expecting %s or %s or %s"));
      YYCASE_(5, YY_("syntax error, unexpected %s, expecting %s or %s or %s or %s"));
# undef YYCASE_
    }

  {
    YYSIZE_T yysize1 = yysize + yystrlen (yyformat);
    if (! (yysize <= yysize1 && yysize1 <= YYSTACK_ALLOC_MAXIMUM))
      return 2;
    yysize = yysize1;
  }

  if (*yymsg_alloc < yysize)
    {
      *yymsg_alloc = 2 * yysize;
      if (! (yysize <= *yymsg_alloc
             && *yymsg_alloc <= YYSTACK_ALLOC_MAXIMUM))
        *yymsg_alloc = YYSTACK_ALLOC_MAXIMUM;
      return 1;
    }

  /* Avoid sprintf, as that infringes on the user's name space.
     Don't have undefined behavior even if the translation
     produced a string with the wrong number of "%s"s.  */
  {
    char *yyp = *yymsg;
    int yyi = 0;
    while ((*yyp = *yyformat) != '\0')
      if (*yyp == '%' && yyformat[1] == 's' && yyi < yycount)
        {
          yyp += yytnamerr (yyp, yyarg[yyi++]);
          yyformat += 2;
        }
      else
        {
          yyp++;
          yyformat++;
        }
  }
  return 0;
}
#endif /* YYERROR_VERBOSE */

/*-----------------------------------------------.
| Release the memory associated to this symbol.  |
`-----------------------------------------------*/

/*ARGSUSED*/
#if (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
static void
yydestruct (const char *yymsg, int yytype, YYSTYPE *yyvaluep)
#else
static void
yydestruct (yymsg, yytype, yyvaluep)
    const char *yymsg;
    int yytype;
    YYSTYPE *yyvaluep;
#endif
{
  YYUSE (yyvaluep);

  if (!yymsg)
    yymsg = "Deleting";
  YY_SYMBOL_PRINT (yymsg, yytype, yyvaluep, yylocationp);

  switch (yytype)
    {

      default:
        break;
    }
}




/* The lookahead symbol.  */
int yychar;


#ifndef YY_IGNORE_MAYBE_UNINITIALIZED_BEGIN
# define YY_IGNORE_MAYBE_UNINITIALIZED_BEGIN
# define YY_IGNORE_MAYBE_UNINITIALIZED_END
#endif
#ifndef YY_INITIAL_VALUE
# define YY_INITIAL_VALUE(Value) /* Nothing. */
#endif

/* The semantic value of the lookahead symbol.  */
YYSTYPE yylval YY_INITIAL_VALUE(yyval_default);

/* Number of syntax errors so far.  */
int yynerrs;


/*----------.
| yyparse.  |
`----------*/

#ifdef YYPARSE_PARAM
#if (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
int
yyparse (void *YYPARSE_PARAM)
#else
int
yyparse (YYPARSE_PARAM)
    void *YYPARSE_PARAM;
#endif
#else /* ! YYPARSE_PARAM */
#if (defined __STDC__ || defined __C99__FUNC__ \
     || defined __cplusplus || defined _MSC_VER)
int
yyparse (void)
#else
int
yyparse ()

#endif
#endif
{
    int yystate;
    /* Number of tokens to shift before error messages enabled.  */
    int yyerrstatus;

    /* The stacks and their tools:
       `yyss': related to states.
       `yyvs': related to semantic values.

       Refer to the stacks through separate pointers, to allow yyoverflow
       to reallocate them elsewhere.  */

    /* The state stack.  */
    yytype_int16 yyssa[YYINITDEPTH];
    yytype_int16 *yyss;
    yytype_int16 *yyssp;

    /* The semantic value stack.  */
    YYSTYPE yyvsa[YYINITDEPTH];
    YYSTYPE *yyvs;
    YYSTYPE *yyvsp;

    YYSIZE_T yystacksize;

  int yyn;
  int yyresult;
  /* Lookahead token as an internal (translated) token number.  */
  int yytoken = 0;
  /* The variables used to return semantic value and location from the
     action routines.  */
  YYSTYPE yyval;

#if YYERROR_VERBOSE
  /* Buffer for error messages, and its allocated size.  */
  char yymsgbuf[128];
  char *yymsg = yymsgbuf;
  YYSIZE_T yymsg_alloc = sizeof yymsgbuf;
#endif

#define YYPOPSTACK(N)   (yyvsp -= (N), yyssp -= (N))

  /* The number of symbols on the RHS of the reduced rule.
     Keep to zero when no symbol should be popped.  */
  int yylen = 0;

  yyssp = yyss = yyssa;
  yyvsp = yyvs = yyvsa;
  yystacksize = YYINITDEPTH;

  YYDPRINTF ((stderr, "Starting parse\n"));

  yystate = 0;
  yyerrstatus = 0;
  yynerrs = 0;
  yychar = YYEMPTY; /* Cause a token to be read.  */
  goto yysetstate;

/*------------------------------------------------------------.
| yynewstate -- Push a new state, which is found in yystate.  |
`------------------------------------------------------------*/
 yynewstate:
  /* In all cases, when you get here, the value and location stacks
     have just been pushed.  So pushing a state here evens the stacks.  */
  yyssp++;

 yysetstate:
  *yyssp = yystate;

  if (yyss + yystacksize - 1 <= yyssp)
    {
      /* Get the current used size of the three stacks, in elements.  */
      YYSIZE_T yysize = yyssp - yyss + 1;

#ifdef yyoverflow
      {
	/* Give user a chance to reallocate the stack.  Use copies of
	   these so that the &'s don't force the real ones into
	   memory.  */
	YYSTYPE *yyvs1 = yyvs;
	yytype_int16 *yyss1 = yyss;

	/* Each stack pointer address is followed by the size of the
	   data in use in that stack, in bytes.  This used to be a
	   conditional around just the two extra args, but that might
	   be undefined if yyoverflow is a macro.  */
	yyoverflow (YY_("memory exhausted"),
		    &yyss1, yysize * sizeof (*yyssp),
		    &yyvs1, yysize * sizeof (*yyvsp),
		    &yystacksize);

	yyss = yyss1;
	yyvs = yyvs1;
      }
#else /* no yyoverflow */
# ifndef YYSTACK_RELOCATE
      goto yyexhaustedlab;
# else
      /* Extend the stack our own way.  */
      if (YYMAXDEPTH <= yystacksize)
	goto yyexhaustedlab;
      yystacksize *= 2;
      if (YYMAXDEPTH < yystacksize)
	yystacksize = YYMAXDEPTH;

      {
	yytype_int16 *yyss1 = yyss;
	union yyalloc *yyptr =
	  (union yyalloc *) YYSTACK_ALLOC (YYSTACK_BYTES (yystacksize));
	if (! yyptr)
	  goto yyexhaustedlab;
	YYSTACK_RELOCATE (yyss_alloc, yyss);
	YYSTACK_RELOCATE (yyvs_alloc, yyvs);
#  undef YYSTACK_RELOCATE
	if (yyss1 != yyssa)
	  YYSTACK_FREE (yyss1);
      }
# endif
#endif /* no yyoverflow */

      yyssp = yyss + yysize - 1;
      yyvsp = yyvs + yysize - 1;

      YYDPRINTF ((stderr, "Stack size increased to %lu\n",
		  (unsigned long int) yystacksize));

      if (yyss + yystacksize - 1 <= yyssp)
	YYABORT;
    }

  YYDPRINTF ((stderr, "Entering state %d\n", yystate));

  if (yystate == YYFINAL)
    YYACCEPT;

  goto yybackup;

/*-----------.
| yybackup.  |
`-----------*/
yybackup:

  /* Do appropriate processing given the current state.  Read a
     lookahead token if we need one and don't already have one.  */

  /* First try to decide what to do without reference to lookahead token.  */
  yyn = yypact[yystate];
  if (yypact_value_is_default (yyn))
    goto yydefault;

  /* Not known => get a lookahead token if don't already have one.  */

  /* YYCHAR is either YYEMPTY or YYEOF or a valid lookahead symbol.  */
  if (yychar == YYEMPTY)
    {
      YYDPRINTF ((stderr, "Reading a token: "));
      yychar = YYLEX;
    }

  if (yychar <= YYEOF)
    {
      yychar = yytoken = YYEOF;
      YYDPRINTF ((stderr, "Now at end of input.\n"));
    }
  else
    {
      yytoken = YYTRANSLATE (yychar);
      YY_SYMBOL_PRINT ("Next token is", yytoken, &yylval, &yylloc);
    }

  /* If the proper action on seeing token YYTOKEN is to reduce or to
     detect an error, take that action.  */
  yyn += yytoken;
  if (yyn < 0 || YYLAST < yyn || yycheck[yyn] != yytoken)
    goto yydefault;
  yyn = yytable[yyn];
  if (yyn <= 0)
    {
      if (yytable_value_is_error (yyn))
        goto yyerrlab;
      yyn = -yyn;
      goto yyreduce;
    }

  /* Count tokens shifted since error; after three, turn off error
     status.  */
  if (yyerrstatus)
    yyerrstatus--;

  /* Shift the lookahead token.  */
  YY_SYMBOL_PRINT ("Shifting", yytoken, &yylval, &yylloc);

  /* Discard the shifted token.  */
  yychar = YYEMPTY;

  yystate = yyn;
  YY_IGNORE_MAYBE_UNINITIALIZED_BEGIN
  *++yyvsp = yylval;
  YY_IGNORE_MAYBE_UNINITIALIZED_END

  goto yynewstate;


/*-----------------------------------------------------------.
| yydefault -- do the default action for the current state.  |
`-----------------------------------------------------------*/
yydefault:
  yyn = yydefact[yystate];
  if (yyn == 0)
    goto yyerrlab;
  goto yyreduce;


/*-----------------------------.
| yyreduce -- Do a reduction.  |
`-----------------------------*/
yyreduce:
  /* yyn is the number of a rule to reduce with.  */
  yylen = yyr2[yyn];

  /* If YYLEN is nonzero, implement the default value of the action:
     `$$ = $1'.

     Otherwise, the following line sets YYVAL to garbage.
     This behavior is undocumented and Bison
     users should not rely upon it.  Assigning to YYVAL
     unconditionally makes the parser a bit smaller, and it avoids a
     GCC warning that YYVAL may be used uninitialized.  */
  yyval = yyvsp[1-yylen];


  YY_REDUCE_PRINT (yyn);
  switch (yyn)
    {
        case 3:
/* Line 1792 of yacc.c  */
#line 675 "cfg.y"
    {}
    break;

  case 4:
/* Line 1792 of yacc.c  */
#line 676 "cfg.y"
    {}
    break;

  case 5:
/* Line 1792 of yacc.c  */
#line 677 "cfg.y"
    { yyerror(""); YYABORT;}
    break;

  case 11:
/* Line 1792 of yacc.c  */
#line 685 "cfg.y"
    {rt=REQUEST_ROUTE;}
    break;

  case 13:
/* Line 1792 of yacc.c  */
#line 686 "cfg.y"
    {rt=FAILURE_ROUTE;}
    break;

  case 16:
/* Line 1792 of yacc.c  */
#line 688 "cfg.y"
    {rt=BRANCH_ROUTE;}
    break;

  case 18:
/* Line 1792 of yacc.c  */
#line 689 "cfg.y"
    {rt=ONSEND_ROUTE;}
    break;

  case 20:
/* Line 1792 of yacc.c  */
#line 690 "cfg.y"
    {rt=EVENT_ROUTE;}
    break;

  case 24:
/* Line 1792 of yacc.c  */
#line 695 "cfg.y"
    {
		if ((yyvsp[(1) - (1)].ipaddr)){
			tmp=ip_addr2a((yyvsp[(1) - (1)].ipaddr));
			if (tmp==0) {
				LOG(L_CRIT, "ERROR: cfg. parser: bad ip "
						"address.\n");
				(yyval.strval)=0;
			} else {
				(yyval.strval)=pkg_malloc(strlen(tmp)+1);
				if ((yyval.strval)==0) {
					LOG(L_CRIT, "ERROR: cfg. parser: out of "
							"memory.\n");
				} else {
					strncpy((yyval.strval), tmp, strlen(tmp)+1);
				}
			}
		}
	}
    break;

  case 25:
/* Line 1792 of yacc.c  */
#line 713 "cfg.y"
    {
		(yyval.strval)=pkg_malloc(strlen((yyvsp[(1) - (1)].strval))+1);
		if ((yyval.strval)==0) {
				LOG(L_CRIT, "ERROR: cfg. parser: out of "
						"memory.\n");
		} else {
				strncpy((yyval.strval), (yyvsp[(1) - (1)].strval), strlen((yyvsp[(1) - (1)].strval))+1);
		}
	}
    break;

  case 26:
/* Line 1792 of yacc.c  */
#line 722 "cfg.y"
    {
		if ((yyvsp[(1) - (1)].strval)){
			(yyval.strval)=pkg_malloc(strlen((yyvsp[(1) - (1)].strval))+1);
			if ((yyval.strval)==0) {
					LOG(L_CRIT, "ERROR: cfg. parser: out of "
							"memory.\n");
			} else {
					strncpy((yyval.strval), (yyvsp[(1) - (1)].strval), strlen((yyvsp[(1) - (1)].strval))+1);
			}
		}
	}
    break;

  case 27:
/* Line 1792 of yacc.c  */
#line 737 "cfg.y"
    { (yyval.name_l)=mk_name_lst((yyvsp[(1) - (1)].strval), SI_IS_MHOMED); }
    break;

  case 28:
/* Line 1792 of yacc.c  */
#line 738 "cfg.y"
    { (yyval.name_l)=mk_name_lst((yyvsp[(1) - (3)].strval), SI_IS_MHOMED); 
										if ((yyval.name_l)) (yyval.name_l)->next=(yyvsp[(3) - (3)].name_l);
									}
    break;

  case 29:
/* Line 1792 of yacc.c  */
#line 744 "cfg.y"
    { (yyval.name_l)=(yyvsp[(2) - (3)].name_l); }
    break;

  case 30:
/* Line 1792 of yacc.c  */
#line 745 "cfg.y"
    { (yyval.name_l)=mk_name_lst((yyvsp[(1) - (1)].strval), 0); }
    break;

  case 31:
/* Line 1792 of yacc.c  */
#line 749 "cfg.y"
    { (yyval.intval)=PROTO_UDP; }
    break;

  case 32:
/* Line 1792 of yacc.c  */
#line 750 "cfg.y"
    { (yyval.intval)=PROTO_TCP; }
    break;

  case 33:
/* Line 1792 of yacc.c  */
#line 751 "cfg.y"
    { (yyval.intval)=PROTO_TLS; }
    break;

  case 34:
/* Line 1792 of yacc.c  */
#line 752 "cfg.y"
    { (yyval.intval)=PROTO_SCTP; }
    break;

  case 35:
/* Line 1792 of yacc.c  */
#line 753 "cfg.y"
    { (yyval.intval)=0; }
    break;

  case 36:
/* Line 1792 of yacc.c  */
#line 756 "cfg.y"
    { (yyval.intval)=PROTO_UDP; }
    break;

  case 37:
/* Line 1792 of yacc.c  */
#line 757 "cfg.y"
    { (yyval.intval)=PROTO_TCP; }
    break;

  case 38:
/* Line 1792 of yacc.c  */
#line 758 "cfg.y"
    { (yyval.intval)=PROTO_TLS; }
    break;

  case 39:
/* Line 1792 of yacc.c  */
#line 759 "cfg.y"
    { (yyval.intval)=PROTO_SCTP; }
    break;

  case 40:
/* Line 1792 of yacc.c  */
#line 760 "cfg.y"
    { (yyval.intval)=PROTO_WS; }
    break;

  case 41:
/* Line 1792 of yacc.c  */
#line 761 "cfg.y"
    { (yyval.intval)=PROTO_WSS; }
    break;

  case 42:
/* Line 1792 of yacc.c  */
#line 762 "cfg.y"
    { (yyval.intval)=0; }
    break;

  case 43:
/* Line 1792 of yacc.c  */
#line 765 "cfg.y"
    { (yyval.intval)=(yyvsp[(1) - (1)].intval); }
    break;

  case 44:
/* Line 1792 of yacc.c  */
#line 766 "cfg.y"
    { (yyval.intval)=0; }
    break;

  case 45:
/* Line 1792 of yacc.c  */
#line 769 "cfg.y"
    { (yyval.sockid)=mk_listen_id((yyvsp[(1) - (1)].strval), 0, 0); }
    break;

  case 46:
/* Line 1792 of yacc.c  */
#line 770 "cfg.y"
    { (yyval.sockid)=mk_listen_id((yyvsp[(1) - (3)].strval), 0, (yyvsp[(3) - (3)].intval)); }
    break;

  case 47:
/* Line 1792 of yacc.c  */
#line 771 "cfg.y"
    { (yyval.sockid)=mk_listen_id((yyvsp[(3) - (3)].strval), (yyvsp[(1) - (3)].intval), 0); }
    break;

  case 48:
/* Line 1792 of yacc.c  */
#line 772 "cfg.y"
    { (yyval.sockid)=mk_listen_id((yyvsp[(3) - (5)].strval), (yyvsp[(1) - (5)].intval), (yyvsp[(5) - (5)].intval));}
    break;

  case 49:
/* Line 1792 of yacc.c  */
#line 773 "cfg.y"
    { (yyval.sockid)=0; yyerror(" port number expected"); }
    break;

  case 50:
/* Line 1792 of yacc.c  */
#line 777 "cfg.y"
    { (yyval.sockid)=mk_listen_id2((yyvsp[(1) - (1)].name_l), 0, 0); }
    break;

  case 51:
/* Line 1792 of yacc.c  */
#line 778 "cfg.y"
    { (yyval.sockid)=mk_listen_id2((yyvsp[(1) - (3)].name_l), 0, (yyvsp[(3) - (3)].intval)); }
    break;

  case 52:
/* Line 1792 of yacc.c  */
#line 779 "cfg.y"
    { (yyval.sockid)=mk_listen_id2((yyvsp[(3) - (3)].name_l), (yyvsp[(1) - (3)].intval), 0); }
    break;

  case 53:
/* Line 1792 of yacc.c  */
#line 780 "cfg.y"
    { (yyval.sockid)=mk_listen_id2((yyvsp[(3) - (5)].name_l), (yyvsp[(1) - (5)].intval), (yyvsp[(5) - (5)].intval));}
    break;

  case 54:
/* Line 1792 of yacc.c  */
#line 781 "cfg.y"
    { (yyval.sockid)=0; yyerror(" port number expected"); }
    break;

  case 55:
/* Line 1792 of yacc.c  */
#line 785 "cfg.y"
    {  (yyval.sockid)=(yyvsp[(1) - (1)].sockid) ; }
    break;

  case 56:
/* Line 1792 of yacc.c  */
#line 786 "cfg.y"
    { (yyval.sockid)=(yyvsp[(1) - (2)].sockid);  if ((yyval.sockid)) (yyval.sockid)->next=(yyvsp[(2) - (2)].sockid); }
    break;

  case 58:
/* Line 1792 of yacc.c  */
#line 790 "cfg.y"
    { (yyval.intval)=-(yyvsp[(2) - (2)].intval); }
    break;

  case 60:
/* Line 1792 of yacc.c  */
#line 794 "cfg.y"
    { yyerror("flag list expected\n"); }
    break;

  case 63:
/* Line 1792 of yacc.c  */
#line 800 "cfg.y"
    { if (register_flag((yyvsp[(1) - (1)].strval),-1)<0)
								yyerror("register flag failed");
						}
    break;

  case 64:
/* Line 1792 of yacc.c  */
#line 803 "cfg.y"
    {
						if (register_flag((yyvsp[(1) - (3)].strval), (yyvsp[(3) - (3)].intval))<0)
								yyerror("register flag failed");
										}
    break;

  case 65:
/* Line 1792 of yacc.c  */
#line 809 "cfg.y"
    { (yyval.strval)=(yyvsp[(1) - (1)].strval); }
    break;

  case 66:
/* Line 1792 of yacc.c  */
#line 810 "cfg.y"
    { (yyval.strval)=(yyvsp[(1) - (1)].strval); }
    break;

  case 68:
/* Line 1792 of yacc.c  */
#line 815 "cfg.y"
    { yyerror("avpflag list expected\n"); }
    break;

  case 71:
/* Line 1792 of yacc.c  */
#line 822 "cfg.y"
    {
		if (register_avpflag((yyvsp[(1) - (1)].strval))==0)
			yyerror("cannot declare avpflag");
	}
    break;

  case 72:
/* Line 1792 of yacc.c  */
#line 828 "cfg.y"
    { default_core_cfg.debug=(yyvsp[(3) - (3)].intval); }
    break;

  case 73:
/* Line 1792 of yacc.c  */
#line 829 "cfg.y"
    { yyerror("number  expected"); }
    break;

  case 74:
/* Line 1792 of yacc.c  */
#line 830 "cfg.y"
    { dont_fork= ! (yyvsp[(3) - (3)].intval); }
    break;

  case 75:
/* Line 1792 of yacc.c  */
#line 831 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 76:
/* Line 1792 of yacc.c  */
#line 832 "cfg.y"
    { set_fork_delay((yyvsp[(3) - (3)].intval)); }
    break;

  case 77:
/* Line 1792 of yacc.c  */
#line 833 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 78:
/* Line 1792 of yacc.c  */
#line 834 "cfg.y"
    { set_modinit_delay((yyvsp[(3) - (3)].intval)); }
    break;

  case 79:
/* Line 1792 of yacc.c  */
#line 835 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 80:
/* Line 1792 of yacc.c  */
#line 836 "cfg.y"
    { if (!config_check)  /* if set from cmd line, don't overwrite from yyparse()*/ 
					if(log_stderr == 0) log_stderr=(yyvsp[(3) - (3)].intval); 
				   }
    break;

  case 81:
/* Line 1792 of yacc.c  */
#line 839 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 82:
/* Line 1792 of yacc.c  */
#line 840 "cfg.y"
    {
		if ( (i_tmp=str2facility((yyvsp[(3) - (3)].strval)))==-1)
			yyerror("bad facility (see syslog(3) man page)");
		if (!config_check)
			default_core_cfg.log_facility=i_tmp;
	}
    break;

  case 83:
/* Line 1792 of yacc.c  */
#line 846 "cfg.y"
    { yyerror("ID expected"); }
    break;

  case 84:
/* Line 1792 of yacc.c  */
#line 847 "cfg.y"
    { log_name=(yyvsp[(3) - (3)].strval); }
    break;

  case 85:
/* Line 1792 of yacc.c  */
#line 848 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 86:
/* Line 1792 of yacc.c  */
#line 849 "cfg.y"
    { log_color=(yyvsp[(3) - (3)].intval); }
    break;

  case 87:
/* Line 1792 of yacc.c  */
#line 850 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 88:
/* Line 1792 of yacc.c  */
#line 851 "cfg.y"
    { log_prefix_fmt=(yyvsp[(3) - (3)].strval); }
    break;

  case 89:
/* Line 1792 of yacc.c  */
#line 852 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 90:
/* Line 1792 of yacc.c  */
#line 853 "cfg.y"
    { received_dns|= ((yyvsp[(3) - (3)].intval))?DO_DNS:0; }
    break;

  case 91:
/* Line 1792 of yacc.c  */
#line 854 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 92:
/* Line 1792 of yacc.c  */
#line 855 "cfg.y"
    { received_dns|= ((yyvsp[(3) - (3)].intval))?DO_REV_DNS:0; }
    break;

  case 93:
/* Line 1792 of yacc.c  */
#line 856 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 94:
/* Line 1792 of yacc.c  */
#line 857 "cfg.y"
    { default_core_cfg.dns_try_ipv6=(yyvsp[(3) - (3)].intval); }
    break;

  case 95:
/* Line 1792 of yacc.c  */
#line 858 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 96:
/* Line 1792 of yacc.c  */
#line 859 "cfg.y"
    { IF_NAPTR(default_core_cfg.dns_try_naptr=(yyvsp[(3) - (3)].intval)); }
    break;

  case 97:
/* Line 1792 of yacc.c  */
#line 860 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 98:
/* Line 1792 of yacc.c  */
#line 861 "cfg.y"
    { IF_DNS_FAILOVER(default_core_cfg.dns_srv_lb=(yyvsp[(3) - (3)].intval)); }
    break;

  case 99:
/* Line 1792 of yacc.c  */
#line 862 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 100:
/* Line 1792 of yacc.c  */
#line 863 "cfg.y"
    { IF_NAPTR(default_core_cfg.dns_udp_pref=(yyvsp[(3) - (3)].intval));}
    break;

  case 101:
/* Line 1792 of yacc.c  */
#line 864 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 102:
/* Line 1792 of yacc.c  */
#line 865 "cfg.y"
    { IF_NAPTR(default_core_cfg.dns_tcp_pref=(yyvsp[(3) - (3)].intval));}
    break;

  case 103:
/* Line 1792 of yacc.c  */
#line 866 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 104:
/* Line 1792 of yacc.c  */
#line 867 "cfg.y"
    { IF_NAPTR(default_core_cfg.dns_tls_pref=(yyvsp[(3) - (3)].intval));}
    break;

  case 105:
/* Line 1792 of yacc.c  */
#line 868 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 106:
/* Line 1792 of yacc.c  */
#line 869 "cfg.y"
    { 
								IF_NAPTR(default_core_cfg.dns_sctp_pref=(yyvsp[(3) - (3)].intval)); }
    break;

  case 107:
/* Line 1792 of yacc.c  */
#line 871 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 108:
/* Line 1792 of yacc.c  */
#line 872 "cfg.y"
    { default_core_cfg.dns_retr_time=(yyvsp[(3) - (3)].intval); }
    break;

  case 109:
/* Line 1792 of yacc.c  */
#line 873 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 110:
/* Line 1792 of yacc.c  */
#line 874 "cfg.y"
    { default_core_cfg.dns_retr_no=(yyvsp[(3) - (3)].intval); }
    break;

  case 111:
/* Line 1792 of yacc.c  */
#line 875 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 112:
/* Line 1792 of yacc.c  */
#line 876 "cfg.y"
    { default_core_cfg.dns_servers_no=(yyvsp[(3) - (3)].intval); }
    break;

  case 113:
/* Line 1792 of yacc.c  */
#line 877 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 114:
/* Line 1792 of yacc.c  */
#line 878 "cfg.y"
    { default_core_cfg.dns_search_list=(yyvsp[(3) - (3)].intval); }
    break;

  case 115:
/* Line 1792 of yacc.c  */
#line 879 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 116:
/* Line 1792 of yacc.c  */
#line 880 "cfg.y"
    { default_core_cfg.dns_search_fmatch=(yyvsp[(3) - (3)].intval); }
    break;

  case 117:
/* Line 1792 of yacc.c  */
#line 881 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 118:
/* Line 1792 of yacc.c  */
#line 882 "cfg.y"
    { default_core_cfg.dns_naptr_ignore_rfc=(yyvsp[(3) - (3)].intval); }
    break;

  case 119:
/* Line 1792 of yacc.c  */
#line 883 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 120:
/* Line 1792 of yacc.c  */
#line 884 "cfg.y"
    { IF_DNS_CACHE(dns_cache_init=(yyvsp[(3) - (3)].intval)); }
    break;

  case 121:
/* Line 1792 of yacc.c  */
#line 885 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 122:
/* Line 1792 of yacc.c  */
#line 886 "cfg.y"
    { IF_DNS_CACHE(default_core_cfg.use_dns_cache=(yyvsp[(3) - (3)].intval)); }
    break;

  case 123:
/* Line 1792 of yacc.c  */
#line 887 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 124:
/* Line 1792 of yacc.c  */
#line 888 "cfg.y"
    { IF_DNS_FAILOVER(default_core_cfg.use_dns_failover=(yyvsp[(3) - (3)].intval));}
    break;

  case 125:
/* Line 1792 of yacc.c  */
#line 889 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 126:
/* Line 1792 of yacc.c  */
#line 890 "cfg.y"
    { IF_DNS_CACHE(default_core_cfg.dns_cache_flags=(yyvsp[(3) - (3)].intval)); }
    break;

  case 127:
/* Line 1792 of yacc.c  */
#line 891 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 128:
/* Line 1792 of yacc.c  */
#line 892 "cfg.y"
    { IF_DNS_CACHE(default_core_cfg.dns_neg_cache_ttl=(yyvsp[(3) - (3)].intval)); }
    break;

  case 129:
/* Line 1792 of yacc.c  */
#line 893 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 130:
/* Line 1792 of yacc.c  */
#line 894 "cfg.y"
    { IF_DNS_CACHE(default_core_cfg.dns_cache_max_ttl=(yyvsp[(3) - (3)].intval)); }
    break;

  case 131:
/* Line 1792 of yacc.c  */
#line 895 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 132:
/* Line 1792 of yacc.c  */
#line 896 "cfg.y"
    { IF_DNS_CACHE(default_core_cfg.dns_cache_min_ttl=(yyvsp[(3) - (3)].intval)); }
    break;

  case 133:
/* Line 1792 of yacc.c  */
#line 897 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 134:
/* Line 1792 of yacc.c  */
#line 898 "cfg.y"
    { IF_DNS_CACHE(default_core_cfg.dns_cache_max_mem=(yyvsp[(3) - (3)].intval)); }
    break;

  case 135:
/* Line 1792 of yacc.c  */
#line 899 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 136:
/* Line 1792 of yacc.c  */
#line 900 "cfg.y"
    { IF_DNS_CACHE(dns_timer_interval=(yyvsp[(3) - (3)].intval)); }
    break;

  case 137:
/* Line 1792 of yacc.c  */
#line 901 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 138:
/* Line 1792 of yacc.c  */
#line 902 "cfg.y"
    { IF_DNS_CACHE(default_core_cfg.dns_cache_del_nonexp=(yyvsp[(3) - (3)].intval)); }
    break;

  case 139:
/* Line 1792 of yacc.c  */
#line 903 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 140:
/* Line 1792 of yacc.c  */
#line 904 "cfg.y"
    { IF_DNS_CACHE(default_core_cfg.dns_cache_rec_pref=(yyvsp[(3) - (3)].intval)); }
    break;

  case 141:
/* Line 1792 of yacc.c  */
#line 905 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 142:
/* Line 1792 of yacc.c  */
#line 906 "cfg.y"
    {IF_AUTO_BIND_IPV6(auto_bind_ipv6 = (yyvsp[(3) - (3)].intval));}
    break;

  case 143:
/* Line 1792 of yacc.c  */
#line 907 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 144:
/* Line 1792 of yacc.c  */
#line 908 "cfg.y"
    { IF_DST_BLACKLIST(dst_blacklist_init=(yyvsp[(3) - (3)].intval)); }
    break;

  case 145:
/* Line 1792 of yacc.c  */
#line 909 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 146:
/* Line 1792 of yacc.c  */
#line 910 "cfg.y"
    {
		IF_DST_BLACKLIST(default_core_cfg.use_dst_blacklist=(yyvsp[(3) - (3)].intval));
	}
    break;

  case 147:
/* Line 1792 of yacc.c  */
#line 913 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 148:
/* Line 1792 of yacc.c  */
#line 914 "cfg.y"
    {
		IF_DST_BLACKLIST(default_core_cfg.blst_max_mem=(yyvsp[(3) - (3)].intval)); 
	}
    break;

  case 149:
/* Line 1792 of yacc.c  */
#line 917 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 150:
/* Line 1792 of yacc.c  */
#line 918 "cfg.y"
    {
		IF_DST_BLACKLIST(default_core_cfg.blst_timeout=(yyvsp[(3) - (3)].intval));
	}
    break;

  case 151:
/* Line 1792 of yacc.c  */
#line 921 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 152:
/* Line 1792 of yacc.c  */
#line 922 "cfg.y"
    { IF_DST_BLACKLIST(blst_timer_interval=(yyvsp[(3) - (3)].intval));}
    break;

  case 153:
/* Line 1792 of yacc.c  */
#line 923 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 154:
/* Line 1792 of yacc.c  */
#line 924 "cfg.y"
    {
		IF_DST_BLACKLIST(default_core_cfg.blst_udp_imask=(yyvsp[(3) - (3)].intval));
	}
    break;

  case 155:
/* Line 1792 of yacc.c  */
#line 927 "cfg.y"
    { yyerror("number(flags) expected"); }
    break;

  case 156:
/* Line 1792 of yacc.c  */
#line 928 "cfg.y"
    {
		IF_DST_BLACKLIST(default_core_cfg.blst_tcp_imask=(yyvsp[(3) - (3)].intval));
	}
    break;

  case 157:
/* Line 1792 of yacc.c  */
#line 931 "cfg.y"
    { yyerror("number(flags) expected"); }
    break;

  case 158:
/* Line 1792 of yacc.c  */
#line 932 "cfg.y"
    {
		IF_DST_BLACKLIST(default_core_cfg.blst_tls_imask=(yyvsp[(3) - (3)].intval));
	}
    break;

  case 159:
/* Line 1792 of yacc.c  */
#line 935 "cfg.y"
    { yyerror("number(flags) expected"); }
    break;

  case 160:
/* Line 1792 of yacc.c  */
#line 936 "cfg.y"
    {
		IF_DST_BLACKLIST(default_core_cfg.blst_sctp_imask=(yyvsp[(3) - (3)].intval));
	}
    break;

  case 161:
/* Line 1792 of yacc.c  */
#line 939 "cfg.y"
    { yyerror("number(flags) expected"); }
    break;

  case 162:
/* Line 1792 of yacc.c  */
#line 940 "cfg.y"
    { port_no=(yyvsp[(3) - (3)].intval); }
    break;

  case 163:
/* Line 1792 of yacc.c  */
#line 941 "cfg.y"
    {
		#ifdef STATS
				stat_file=(yyvsp[(3) - (3)].strval);
		#endif
	}
    break;

  case 164:
/* Line 1792 of yacc.c  */
#line 946 "cfg.y"
    { maxbuffer=(yyvsp[(3) - (3)].intval); }
    break;

  case 165:
/* Line 1792 of yacc.c  */
#line 947 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 166:
/* Line 1792 of yacc.c  */
#line 948 "cfg.y"
    { sql_buffer_size=(yyvsp[(3) - (3)].intval); }
    break;

  case 167:
/* Line 1792 of yacc.c  */
#line 949 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 168:
/* Line 1792 of yacc.c  */
#line 950 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 169:
/* Line 1792 of yacc.c  */
#line 951 "cfg.y"
    { children_no=(yyvsp[(3) - (3)].intval); }
    break;

  case 170:
/* Line 1792 of yacc.c  */
#line 952 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 171:
/* Line 1792 of yacc.c  */
#line 953 "cfg.y"
    { socket_workers=(yyvsp[(3) - (3)].intval); }
    break;

  case 172:
/* Line 1792 of yacc.c  */
#line 954 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 173:
/* Line 1792 of yacc.c  */
#line 955 "cfg.y"
    { async_task_set_workers((yyvsp[(3) - (3)].intval)); }
    break;

  case 174:
/* Line 1792 of yacc.c  */
#line 956 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 175:
/* Line 1792 of yacc.c  */
#line 957 "cfg.y"
    { check_via=(yyvsp[(3) - (3)].intval); }
    break;

  case 176:
/* Line 1792 of yacc.c  */
#line 958 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 177:
/* Line 1792 of yacc.c  */
#line 959 "cfg.y"
    { phone2tel=(yyvsp[(3) - (3)].intval); }
    break;

  case 178:
/* Line 1792 of yacc.c  */
#line 960 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 179:
/* Line 1792 of yacc.c  */
#line 961 "cfg.y"
    { default_core_cfg.memlog=(yyvsp[(3) - (3)].intval); }
    break;

  case 180:
/* Line 1792 of yacc.c  */
#line 962 "cfg.y"
    { yyerror("int value expected"); }
    break;

  case 181:
/* Line 1792 of yacc.c  */
#line 963 "cfg.y"
    { default_core_cfg.memdbg=(yyvsp[(3) - (3)].intval); }
    break;

  case 182:
/* Line 1792 of yacc.c  */
#line 964 "cfg.y"
    { yyerror("int value expected"); }
    break;

  case 183:
/* Line 1792 of yacc.c  */
#line 965 "cfg.y"
    { default_core_cfg.mem_summary=(yyvsp[(3) - (3)].intval); }
    break;

  case 184:
/* Line 1792 of yacc.c  */
#line 966 "cfg.y"
    { yyerror("int value expected"); }
    break;

  case 185:
/* Line 1792 of yacc.c  */
#line 967 "cfg.y"
    { default_core_cfg.mem_safety=(yyvsp[(3) - (3)].intval); }
    break;

  case 186:
/* Line 1792 of yacc.c  */
#line 968 "cfg.y"
    { yyerror("int value expected"); }
    break;

  case 187:
/* Line 1792 of yacc.c  */
#line 969 "cfg.y"
    { default_core_cfg.mem_join=(yyvsp[(3) - (3)].intval); }
    break;

  case 188:
/* Line 1792 of yacc.c  */
#line 970 "cfg.y"
    { yyerror("int value expected"); }
    break;

  case 189:
/* Line 1792 of yacc.c  */
#line 971 "cfg.y"
    { default_core_cfg.corelog=(yyvsp[(3) - (3)].intval); }
    break;

  case 190:
/* Line 1792 of yacc.c  */
#line 972 "cfg.y"
    { yyerror("int value expected"); }
    break;

  case 191:
/* Line 1792 of yacc.c  */
#line 973 "cfg.y"
    { sip_warning=(yyvsp[(3) - (3)].intval); }
    break;

  case 192:
/* Line 1792 of yacc.c  */
#line 974 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 193:
/* Line 1792 of yacc.c  */
#line 975 "cfg.y"
    { version_table.s=(yyvsp[(3) - (3)].strval);
			version_table.len=strlen(version_table.s);
	}
    break;

  case 194:
/* Line 1792 of yacc.c  */
#line 978 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 195:
/* Line 1792 of yacc.c  */
#line 979 "cfg.y"
    {
		if (shm_initialized())
			yyerror("user must be before any modparam or the"
					" route blocks");
		else if (user==0)
			user=(yyvsp[(3) - (3)].strval); 
	}
    break;

  case 196:
/* Line 1792 of yacc.c  */
#line 986 "cfg.y"
    {
		if (shm_initialized())
			yyerror("user must be before any modparam or the"
					" route blocks");
		else if (user==0)
			user=(yyvsp[(3) - (3)].strval);
	}
    break;

  case 197:
/* Line 1792 of yacc.c  */
#line 993 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 198:
/* Line 1792 of yacc.c  */
#line 994 "cfg.y"
    { group=(yyvsp[(3) - (3)].strval); }
    break;

  case 199:
/* Line 1792 of yacc.c  */
#line 995 "cfg.y"
    { group=(yyvsp[(3) - (3)].strval); }
    break;

  case 200:
/* Line 1792 of yacc.c  */
#line 996 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 201:
/* Line 1792 of yacc.c  */
#line 997 "cfg.y"
    { chroot_dir=(yyvsp[(3) - (3)].strval); }
    break;

  case 202:
/* Line 1792 of yacc.c  */
#line 998 "cfg.y"
    { chroot_dir=(yyvsp[(3) - (3)].strval); }
    break;

  case 203:
/* Line 1792 of yacc.c  */
#line 999 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 204:
/* Line 1792 of yacc.c  */
#line 1000 "cfg.y"
    { working_dir=(yyvsp[(3) - (3)].strval); }
    break;

  case 205:
/* Line 1792 of yacc.c  */
#line 1001 "cfg.y"
    { working_dir=(yyvsp[(3) - (3)].strval); }
    break;

  case 206:
/* Line 1792 of yacc.c  */
#line 1002 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 207:
/* Line 1792 of yacc.c  */
#line 1003 "cfg.y"
    { runtime_dir=(yyvsp[(3) - (3)].strval); }
    break;

  case 208:
/* Line 1792 of yacc.c  */
#line 1004 "cfg.y"
    { runtime_dir=(yyvsp[(3) - (3)].strval); }
    break;

  case 209:
/* Line 1792 of yacc.c  */
#line 1005 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 210:
/* Line 1792 of yacc.c  */
#line 1006 "cfg.y"
    { mhomed=(yyvsp[(3) - (3)].intval); }
    break;

  case 211:
/* Line 1792 of yacc.c  */
#line 1007 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 212:
/* Line 1792 of yacc.c  */
#line 1008 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_disable=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 213:
/* Line 1792 of yacc.c  */
#line 1015 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 214:
/* Line 1792 of yacc.c  */
#line 1016 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.accept_aliases=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 215:
/* Line 1792 of yacc.c  */
#line 1023 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 216:
/* Line 1792 of yacc.c  */
#line 1024 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_cfg_children_no=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 217:
/* Line 1792 of yacc.c  */
#line 1031 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 218:
/* Line 1792 of yacc.c  */
#line 1032 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.connect_timeout_s=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 219:
/* Line 1792 of yacc.c  */
#line 1039 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 220:
/* Line 1792 of yacc.c  */
#line 1040 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.send_timeout=S_TO_TICKS((yyvsp[(3) - (3)].intval));
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 221:
/* Line 1792 of yacc.c  */
#line 1047 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 222:
/* Line 1792 of yacc.c  */
#line 1048 "cfg.y"
    {
		#ifdef USE_TCP
			if ((yyvsp[(3) - (3)].intval)<0)
				tcp_default_cfg.con_lifetime=-1;
			else
				tcp_default_cfg.con_lifetime=S_TO_TICKS((yyvsp[(3) - (3)].intval));
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 223:
/* Line 1792 of yacc.c  */
#line 1058 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 224:
/* Line 1792 of yacc.c  */
#line 1059 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_poll_method=get_poll_type((yyvsp[(3) - (3)].strval));
			if (tcp_poll_method==POLL_NONE) {
				LOG(L_CRIT, "bad poll method name:"
						" %s\n, try one of %s.\n",
						(yyvsp[(3) - (3)].strval), poll_support);
				yyerror("bad tcp_poll_method "
						"value");
			}
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 225:
/* Line 1792 of yacc.c  */
#line 1073 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_poll_method=get_poll_type((yyvsp[(3) - (3)].strval));
			if (tcp_poll_method==POLL_NONE) {
				LOG(L_CRIT, "bad poll method name:"
						" %s\n, try one of %s.\n",
						(yyvsp[(3) - (3)].strval), poll_support);
				yyerror("bad tcp_poll_method "
						"value");
			}
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 226:
/* Line 1792 of yacc.c  */
#line 1087 "cfg.y"
    { yyerror("poll method name expected"); }
    break;

  case 227:
/* Line 1792 of yacc.c  */
#line 1088 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_max_connections=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 228:
/* Line 1792 of yacc.c  */
#line 1095 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 229:
/* Line 1792 of yacc.c  */
#line 1096 "cfg.y"
    {
		#ifdef USE_TLS
			tls_max_connections=(yyvsp[(3) - (3)].intval);
		#else
			warn("tls support not compiled in");
		#endif
	}
    break;

  case 230:
/* Line 1792 of yacc.c  */
#line 1103 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 231:
/* Line 1792 of yacc.c  */
#line 1104 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.no_connect=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 232:
/* Line 1792 of yacc.c  */
#line 1111 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 233:
/* Line 1792 of yacc.c  */
#line 1112 "cfg.y"
    {
		#ifdef USE_TCP
			if (tcp_set_src_addr((yyvsp[(3) - (3)].ipaddr))<0)
				warn("tcp_source_ipv4 failed");
		#else
			warn("tcp support not compiled in");
		#endif
		pkg_free((yyvsp[(3) - (3)].ipaddr));
	}
    break;

  case 234:
/* Line 1792 of yacc.c  */
#line 1121 "cfg.y"
    { yyerror("IPv4 address expected"); }
    break;

  case 235:
/* Line 1792 of yacc.c  */
#line 1122 "cfg.y"
    {
		#ifdef USE_TCP
				if (tcp_set_src_addr((yyvsp[(3) - (3)].ipaddr))<0)
					warn("tcp_source_ipv6 failed");
		#else
			warn("tcp support not compiled in");
		#endif
		pkg_free((yyvsp[(3) - (3)].ipaddr));
	}
    break;

  case 236:
/* Line 1792 of yacc.c  */
#line 1131 "cfg.y"
    { yyerror("IPv6 address expected"); }
    break;

  case 237:
/* Line 1792 of yacc.c  */
#line 1132 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.fd_cache=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 238:
/* Line 1792 of yacc.c  */
#line 1139 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 239:
/* Line 1792 of yacc.c  */
#line 1140 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.async=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 240:
/* Line 1792 of yacc.c  */
#line 1147 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 241:
/* Line 1792 of yacc.c  */
#line 1148 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.tcpconn_wq_max=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 242:
/* Line 1792 of yacc.c  */
#line 1155 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 243:
/* Line 1792 of yacc.c  */
#line 1156 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.tcp_wq_max=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 244:
/* Line 1792 of yacc.c  */
#line 1163 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 245:
/* Line 1792 of yacc.c  */
#line 1164 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.rd_buf_size=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 246:
/* Line 1792 of yacc.c  */
#line 1171 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 247:
/* Line 1792 of yacc.c  */
#line 1172 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.wq_blk_size=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 248:
/* Line 1792 of yacc.c  */
#line 1179 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 249:
/* Line 1792 of yacc.c  */
#line 1180 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.defer_accept=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 250:
/* Line 1792 of yacc.c  */
#line 1187 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 251:
/* Line 1792 of yacc.c  */
#line 1188 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.delayed_ack=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 252:
/* Line 1792 of yacc.c  */
#line 1195 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 253:
/* Line 1792 of yacc.c  */
#line 1196 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.syncnt=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 254:
/* Line 1792 of yacc.c  */
#line 1203 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 255:
/* Line 1792 of yacc.c  */
#line 1204 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.linger2=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 256:
/* Line 1792 of yacc.c  */
#line 1211 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 257:
/* Line 1792 of yacc.c  */
#line 1212 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.keepalive=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 258:
/* Line 1792 of yacc.c  */
#line 1219 "cfg.y"
    { yyerror("boolean value expected");}
    break;

  case 259:
/* Line 1792 of yacc.c  */
#line 1220 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.keepidle=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 260:
/* Line 1792 of yacc.c  */
#line 1227 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 261:
/* Line 1792 of yacc.c  */
#line 1228 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.keepintvl=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 262:
/* Line 1792 of yacc.c  */
#line 1235 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 263:
/* Line 1792 of yacc.c  */
#line 1236 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.keepcnt=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 264:
/* Line 1792 of yacc.c  */
#line 1243 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 265:
/* Line 1792 of yacc.c  */
#line 1244 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.crlf_ping=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 266:
/* Line 1792 of yacc.c  */
#line 1251 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 267:
/* Line 1792 of yacc.c  */
#line 1252 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_default_cfg.accept_no_cl=(yyvsp[(3) - (3)].intval);
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 268:
/* Line 1792 of yacc.c  */
#line 1259 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 269:
/* Line 1792 of yacc.c  */
#line 1260 "cfg.y"
    {
		#ifdef USE_TCP
			tcp_set_clone_rcvbuf((yyvsp[(3) - (3)].intval));
		#else
			warn("tcp support not compiled in");
		#endif
	}
    break;

  case 270:
/* Line 1792 of yacc.c  */
#line 1267 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 271:
/* Line 1792 of yacc.c  */
#line 1268 "cfg.y"
    {
		#ifdef USE_TLS
			tls_disable=(yyvsp[(3) - (3)].intval);
		#else
			warn("tls support not compiled in");
		#endif
	}
    break;

  case 272:
/* Line 1792 of yacc.c  */
#line 1275 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 273:
/* Line 1792 of yacc.c  */
#line 1276 "cfg.y"
    {
		#ifdef USE_TLS
			tls_disable=!((yyvsp[(3) - (3)].intval));
		#else
			warn("tls support not compiled in");
		#endif
	}
    break;

  case 274:
/* Line 1792 of yacc.c  */
#line 1283 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 275:
/* Line 1792 of yacc.c  */
#line 1284 "cfg.y"
    {
		#ifdef CORE_TLS
			tls_log=(yyvsp[(3) - (3)].intval);
		#else
			warn("tls-in-core support not compiled in");
		#endif
	}
    break;

  case 276:
/* Line 1792 of yacc.c  */
#line 1291 "cfg.y"
    { yyerror("int value expected"); }
    break;

  case 277:
/* Line 1792 of yacc.c  */
#line 1292 "cfg.y"
    {
		#ifdef USE_TLS
			tls_port_no=(yyvsp[(3) - (3)].intval);
		#else
			warn("tls support not compiled in");
		#endif
	}
    break;

  case 278:
/* Line 1792 of yacc.c  */
#line 1299 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 279:
/* Line 1792 of yacc.c  */
#line 1300 "cfg.y"
    {
		#ifdef CORE_TLS
			tls_method=TLS_USE_SSLv23;
		#else
			warn("tls-in-core support not compiled in");
		#endif
	}
    break;

  case 280:
/* Line 1792 of yacc.c  */
#line 1307 "cfg.y"
    {
		#ifdef CORE_TLS
			tls_method=TLS_USE_SSLv2;
		#else
			warn("tls-in-core support not compiled in");
		#endif
	}
    break;

  case 281:
/* Line 1792 of yacc.c  */
#line 1314 "cfg.y"
    {
		#ifdef CORE_TLS
			tls_method=TLS_USE_SSLv3;
		#else
			warn("tls-in-core support not compiled in");
		#endif
	}
    break;

  case 282:
/* Line 1792 of yacc.c  */
#line 1321 "cfg.y"
    {
		#ifdef CORE_TLS
			tls_method=TLS_USE_TLSv1;
		#else
			warn("tls-in-core support not compiled in");
		#endif
	}
    break;

  case 283:
/* Line 1792 of yacc.c  */
#line 1328 "cfg.y"
    {
		#ifdef CORE_TLS
			yyerror("SSLv23, SSLv2, SSLv3 or TLSv1 expected");
		#else
			warn("tls-in-core support not compiled in");
		#endif
	}
    break;

  case 284:
/* Line 1792 of yacc.c  */
#line 1335 "cfg.y"
    {
		#ifdef CORE_TLS
			tls_verify_cert=(yyvsp[(3) - (3)].intval);
		#else
			warn("tls-in-core support not compiled in");
		#endif
	}
    break;

  case 285:
/* Line 1792 of yacc.c  */
#line 1342 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 286:
/* Line 1792 of yacc.c  */
#line 1343 "cfg.y"
    {
		#ifdef CORE_TLS
			tls_require_cert=(yyvsp[(3) - (3)].intval);
		#else
			warn( "tls-in-core support not compiled in");
		#endif
	}
    break;

  case 287:
/* Line 1792 of yacc.c  */
#line 1350 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 288:
/* Line 1792 of yacc.c  */
#line 1351 "cfg.y"
    {
		#ifdef CORE_TLS
			tls_cert_file=(yyvsp[(3) - (3)].strval);
		#else
			warn("tls-in-core support not compiled in");
		#endif
	}
    break;

  case 289:
/* Line 1792 of yacc.c  */
#line 1358 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 290:
/* Line 1792 of yacc.c  */
#line 1359 "cfg.y"
    {
		#ifdef CORE_TLS
			tls_pkey_file=(yyvsp[(3) - (3)].strval);
		#else
			warn("tls-in-core support not compiled in");
		#endif
	}
    break;

  case 291:
/* Line 1792 of yacc.c  */
#line 1366 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 292:
/* Line 1792 of yacc.c  */
#line 1367 "cfg.y"
    {
		#ifdef CORE_TLS
			tls_ca_file=(yyvsp[(3) - (3)].strval);
		#else
			warn("tls-in-core support not compiled in");
		#endif
	}
    break;

  case 293:
/* Line 1792 of yacc.c  */
#line 1374 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 294:
/* Line 1792 of yacc.c  */
#line 1375 "cfg.y"
    {
		#ifdef CORE_TLS
			tls_handshake_timeout=(yyvsp[(3) - (3)].intval);
		#else
			warn("tls-in-core support not compiled in");
		#endif
	}
    break;

  case 295:
/* Line 1792 of yacc.c  */
#line 1382 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 296:
/* Line 1792 of yacc.c  */
#line 1383 "cfg.y"
    {
		#ifdef CORE_TLS
			tls_send_timeout=(yyvsp[(3) - (3)].intval);
		#else
			warn("tls-in-core support not compiled in");
		#endif
	}
    break;

  case 297:
/* Line 1792 of yacc.c  */
#line 1390 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 298:
/* Line 1792 of yacc.c  */
#line 1391 "cfg.y"
    {
		#ifdef USE_SCTP
			sctp_disable=(yyvsp[(3) - (3)].intval);
		#else
			warn("sctp support not compiled in");
		#endif
	}
    break;

  case 299:
/* Line 1792 of yacc.c  */
#line 1398 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 300:
/* Line 1792 of yacc.c  */
#line 1399 "cfg.y"
    {
		#ifdef USE_SCTP
			sctp_disable=((yyvsp[(3) - (3)].intval)<=1)?!(yyvsp[(3) - (3)].intval):(yyvsp[(3) - (3)].intval);
		#else
			warn("sctp support not compiled in");
		#endif
	}
    break;

  case 301:
/* Line 1792 of yacc.c  */
#line 1406 "cfg.y"
    { yyerror("boolean or number expected"); }
    break;

  case 302:
/* Line 1792 of yacc.c  */
#line 1407 "cfg.y"
    {
		#ifdef USE_SCTP
			sctp_children_no=(yyvsp[(3) - (3)].intval);
		#else
			warn("sctp support not compiled in");
		#endif
	}
    break;

  case 303:
/* Line 1792 of yacc.c  */
#line 1414 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 304:
/* Line 1792 of yacc.c  */
#line 1415 "cfg.y"
    { server_signature=(yyvsp[(3) - (3)].intval); }
    break;

  case 305:
/* Line 1792 of yacc.c  */
#line 1416 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 306:
/* Line 1792 of yacc.c  */
#line 1417 "cfg.y"
    { server_hdr.s=(yyvsp[(3) - (3)].strval);
			server_hdr.len=strlen(server_hdr.s);
	}
    break;

  case 307:
/* Line 1792 of yacc.c  */
#line 1420 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 308:
/* Line 1792 of yacc.c  */
#line 1421 "cfg.y"
    { user_agent_hdr.s=(yyvsp[(3) - (3)].strval);
			user_agent_hdr.len=strlen(user_agent_hdr.s);
	}
    break;

  case 309:
/* Line 1792 of yacc.c  */
#line 1424 "cfg.y"
    { yyerror("string value expected"); }
    break;

  case 310:
/* Line 1792 of yacc.c  */
#line 1425 "cfg.y"
    { reply_to_via=(yyvsp[(3) - (3)].intval); }
    break;

  case 311:
/* Line 1792 of yacc.c  */
#line 1426 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 312:
/* Line 1792 of yacc.c  */
#line 1427 "cfg.y"
    {
		for(lst_tmp=(yyvsp[(3) - (3)].sockid); lst_tmp; lst_tmp=lst_tmp->next) {
			if (add_listen_iface(	lst_tmp->addr_lst->name,
									lst_tmp->addr_lst->next,
									lst_tmp->port, lst_tmp->proto,
									lst_tmp->flags)!=0) {
				LOG(L_CRIT,  "ERROR: cfg. parser: failed to add listen"
								" address\n");
				break;
			}
		}
		free_socket_id_lst((yyvsp[(3) - (3)].sockid));
	}
    break;

  case 313:
/* Line 1792 of yacc.c  */
#line 1440 "cfg.y"
    {
		for(lst_tmp=(yyvsp[(3) - (7)].sockid); lst_tmp; lst_tmp=lst_tmp->next) {
			if (add_listen_advertise_iface(	lst_tmp->addr_lst->name,
									lst_tmp->addr_lst->next,
									lst_tmp->port, lst_tmp->proto,
									(yyvsp[(5) - (7)].strval), (yyvsp[(7) - (7)].intval),
									lst_tmp->flags)!=0) {
				LOG(L_CRIT,  "ERROR: cfg. parser: failed to add listen"
								" address\n");
				break;
			}
		}
		free_socket_id_lst((yyvsp[(3) - (7)].sockid));
	}
    break;

  case 314:
/* Line 1792 of yacc.c  */
#line 1454 "cfg.y"
    { yyerror("ip address, interface name or"
									" hostname expected"); }
    break;

  case 315:
/* Line 1792 of yacc.c  */
#line 1456 "cfg.y"
    {
		for(lst_tmp=(yyvsp[(3) - (3)].sockid); lst_tmp; lst_tmp=lst_tmp->next){
			add_alias(	lst_tmp->addr_lst->name,
						strlen(lst_tmp->addr_lst->name),
						lst_tmp->port, lst_tmp->proto);
			for (nl_tmp=lst_tmp->addr_lst->next; nl_tmp; nl_tmp=nl_tmp->next)
				add_alias(nl_tmp->name, strlen(nl_tmp->name),
							lst_tmp->port, lst_tmp->proto);
		}
		free_socket_id_lst((yyvsp[(3) - (3)].sockid));
	}
    break;

  case 316:
/* Line 1792 of yacc.c  */
#line 1467 "cfg.y"
    { yyerror(" hostname expected"); }
    break;

  case 317:
/* Line 1792 of yacc.c  */
#line 1468 "cfg.y"
    { sr_auto_aliases=(yyvsp[(3) - (3)].intval); }
    break;

  case 318:
/* Line 1792 of yacc.c  */
#line 1469 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 319:
/* Line 1792 of yacc.c  */
#line 1470 "cfg.y"
    {
		if ((yyvsp[(3) - (3)].strval)){
			default_global_address.s=(yyvsp[(3) - (3)].strval);
			default_global_address.len=strlen((yyvsp[(3) - (3)].strval));
		}
	}
    break;

  case 320:
/* Line 1792 of yacc.c  */
#line 1476 "cfg.y"
    {yyerror("ip address or hostname expected"); }
    break;

  case 321:
/* Line 1792 of yacc.c  */
#line 1477 "cfg.y"
    {
		tmp=int2str((yyvsp[(3) - (3)].intval), &i_tmp);
		if ((default_global_port.s=pkg_malloc(i_tmp))==0) {
			LOG(L_CRIT, "ERROR: cfg. parser: out of memory.\n");
			default_global_port.len=0;
		} else {
			default_global_port.len=i_tmp;
			memcpy(default_global_port.s, tmp, default_global_port.len);
		};
	}
    break;

  case 322:
/* Line 1792 of yacc.c  */
#line 1487 "cfg.y"
    {yyerror("ip address or hostname expected"); }
    break;

  case 323:
/* Line 1792 of yacc.c  */
#line 1488 "cfg.y"
    { disable_core_dump=(yyvsp[(3) - (3)].intval); }
    break;

  case 324:
/* Line 1792 of yacc.c  */
#line 1489 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 325:
/* Line 1792 of yacc.c  */
#line 1490 "cfg.y"
    { open_files_limit=(yyvsp[(3) - (3)].intval); }
    break;

  case 326:
/* Line 1792 of yacc.c  */
#line 1491 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 327:
/* Line 1792 of yacc.c  */
#line 1492 "cfg.y"
    {
		if (shm_initialized())
			yyerror("shm/shm_mem_size must be before any modparam or the"
					" route blocks");
		else if (shm_mem_size == 0)
			shm_mem_size=(yyvsp[(3) - (3)].intval) * 1024 * 1024;
	}
    break;

  case 328:
/* Line 1792 of yacc.c  */
#line 1499 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 329:
/* Line 1792 of yacc.c  */
#line 1500 "cfg.y"
    {
		if (shm_initialized())
			yyerror("shm_force_alloc must be before any modparam or the"
					" route blocks");
		else
			shm_force_alloc=(yyvsp[(3) - (3)].intval);
	}
    break;

  case 330:
/* Line 1792 of yacc.c  */
#line 1507 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 331:
/* Line 1792 of yacc.c  */
#line 1508 "cfg.y"
    { mlock_pages=(yyvsp[(3) - (3)].intval); }
    break;

  case 332:
/* Line 1792 of yacc.c  */
#line 1509 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 333:
/* Line 1792 of yacc.c  */
#line 1510 "cfg.y"
    { real_time=(yyvsp[(3) - (3)].intval); }
    break;

  case 334:
/* Line 1792 of yacc.c  */
#line 1511 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 335:
/* Line 1792 of yacc.c  */
#line 1512 "cfg.y"
    { rt_prio=(yyvsp[(3) - (3)].intval); }
    break;

  case 336:
/* Line 1792 of yacc.c  */
#line 1513 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 337:
/* Line 1792 of yacc.c  */
#line 1514 "cfg.y"
    { rt_policy=(yyvsp[(3) - (3)].intval); }
    break;

  case 338:
/* Line 1792 of yacc.c  */
#line 1515 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 339:
/* Line 1792 of yacc.c  */
#line 1516 "cfg.y"
    { rt_timer1_prio=(yyvsp[(3) - (3)].intval); }
    break;

  case 340:
/* Line 1792 of yacc.c  */
#line 1517 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 341:
/* Line 1792 of yacc.c  */
#line 1518 "cfg.y"
    { rt_timer1_policy=(yyvsp[(3) - (3)].intval); }
    break;

  case 342:
/* Line 1792 of yacc.c  */
#line 1519 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 343:
/* Line 1792 of yacc.c  */
#line 1520 "cfg.y"
    { rt_timer2_prio=(yyvsp[(3) - (3)].intval); }
    break;

  case 344:
/* Line 1792 of yacc.c  */
#line 1521 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 345:
/* Line 1792 of yacc.c  */
#line 1522 "cfg.y"
    { rt_timer2_policy=(yyvsp[(3) - (3)].intval); }
    break;

  case 346:
/* Line 1792 of yacc.c  */
#line 1523 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 347:
/* Line 1792 of yacc.c  */
#line 1524 "cfg.y"
    {
		#ifdef USE_MCAST
			mcast_loopback=(yyvsp[(3) - (3)].intval);
		#else
			warn("no multicast support compiled in");
		#endif
	}
    break;

  case 348:
/* Line 1792 of yacc.c  */
#line 1531 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 349:
/* Line 1792 of yacc.c  */
#line 1532 "cfg.y"
    {
		#ifdef USE_MCAST
			mcast_ttl=(yyvsp[(3) - (3)].intval);
		#else
			warn("no multicast support compiled in");
		#endif
	}
    break;

  case 350:
/* Line 1792 of yacc.c  */
#line 1539 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 351:
/* Line 1792 of yacc.c  */
#line 1540 "cfg.y"
    { tos=(yyvsp[(3) - (3)].intval); }
    break;

  case 352:
/* Line 1792 of yacc.c  */
#line 1541 "cfg.y"
    { if (strcasecmp((yyvsp[(3) - (3)].strval),"IPTOS_LOWDELAY")) {
			tos=IPTOS_LOWDELAY;
		} else if (strcasecmp((yyvsp[(3) - (3)].strval),"IPTOS_THROUGHPUT")) {
			tos=IPTOS_THROUGHPUT;
		} else if (strcasecmp((yyvsp[(3) - (3)].strval),"IPTOS_RELIABILITY")) {
			tos=IPTOS_RELIABILITY;
#if defined(IPTOS_MINCOST)
		} else if (strcasecmp((yyvsp[(3) - (3)].strval),"IPTOS_MINCOST")) {
			tos=IPTOS_MINCOST;
#endif
#if defined(IPTOS_LOWCOST)
		} else if (strcasecmp((yyvsp[(3) - (3)].strval),"IPTOS_LOWCOST")) {
			tos=IPTOS_LOWCOST;
#endif
		} else {
			yyerror("invalid tos value - allowed: "
				"IPTOS_LOWDELAY,IPTOS_THROUGHPUT,"
				"IPTOS_RELIABILITY"
#if defined(IPTOS_LOWCOST)
				",IPTOS_LOWCOST"
#endif
#if !defined(IPTOS_MINCOST)
				",IPTOS_MINCOST"
#endif
				"\n");
		}
	}
    break;

  case 353:
/* Line 1792 of yacc.c  */
#line 1568 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 354:
/* Line 1792 of yacc.c  */
#line 1569 "cfg.y"
    { pmtu_discovery=(yyvsp[(3) - (3)].intval); }
    break;

  case 355:
/* Line 1792 of yacc.c  */
#line 1570 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 356:
/* Line 1792 of yacc.c  */
#line 1571 "cfg.y"
    { ser_kill_timeout=(yyvsp[(3) - (3)].intval); }
    break;

  case 357:
/* Line 1792 of yacc.c  */
#line 1572 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 358:
/* Line 1792 of yacc.c  */
#line 1573 "cfg.y"
    { default_core_cfg.max_while_loops=(yyvsp[(3) - (3)].intval); }
    break;

  case 359:
/* Line 1792 of yacc.c  */
#line 1574 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 360:
/* Line 1792 of yacc.c  */
#line 1575 "cfg.y"
    { pv_set_buffer_size((yyvsp[(3) - (3)].intval)); }
    break;

  case 361:
/* Line 1792 of yacc.c  */
#line 1576 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 362:
/* Line 1792 of yacc.c  */
#line 1577 "cfg.y"
    { pv_set_buffer_slots((yyvsp[(3) - (3)].intval)); }
    break;

  case 363:
/* Line 1792 of yacc.c  */
#line 1578 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 364:
/* Line 1792 of yacc.c  */
#line 1579 "cfg.y"
    { http_reply_parse=(yyvsp[(3) - (3)].intval); }
    break;

  case 365:
/* Line 1792 of yacc.c  */
#line 1580 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 366:
/* Line 1792 of yacc.c  */
#line 1581 "cfg.y"
    { server_id=(yyvsp[(3) - (3)].intval); }
    break;

  case 367:
/* Line 1792 of yacc.c  */
#line 1582 "cfg.y"
    { set_max_recursive_level((yyvsp[(3) - (3)].intval)); }
    break;

  case 368:
/* Line 1792 of yacc.c  */
#line 1583 "cfg.y"
    { sr_dst_max_branches = (yyvsp[(3) - (3)].intval); }
    break;

  case 369:
/* Line 1792 of yacc.c  */
#line 1584 "cfg.y"
    { default_core_cfg.latency_log=(yyvsp[(3) - (3)].intval); }
    break;

  case 370:
/* Line 1792 of yacc.c  */
#line 1585 "cfg.y"
    { yyerror("number  expected"); }
    break;

  case 371:
/* Line 1792 of yacc.c  */
#line 1586 "cfg.y"
    { default_core_cfg.latency_limit_db=(yyvsp[(3) - (3)].intval); }
    break;

  case 372:
/* Line 1792 of yacc.c  */
#line 1587 "cfg.y"
    { yyerror("number  expected"); }
    break;

  case 373:
/* Line 1792 of yacc.c  */
#line 1588 "cfg.y"
    { default_core_cfg.latency_limit_action=(yyvsp[(3) - (3)].intval); }
    break;

  case 374:
/* Line 1792 of yacc.c  */
#line 1589 "cfg.y"
    { yyerror("number  expected"); }
    break;

  case 375:
/* Line 1792 of yacc.c  */
#line 1590 "cfg.y"
    { sr_msg_time=(yyvsp[(3) - (3)].intval); }
    break;

  case 376:
/* Line 1792 of yacc.c  */
#line 1591 "cfg.y"
    { yyerror("number  expected"); }
    break;

  case 377:
/* Line 1792 of yacc.c  */
#line 1592 "cfg.y"
    { onsend_route_reply=(yyvsp[(3) - (3)].intval); }
    break;

  case 378:
/* Line 1792 of yacc.c  */
#line 1593 "cfg.y"
    { yyerror("int value expected"); }
    break;

  case 379:
/* Line 1792 of yacc.c  */
#line 1594 "cfg.y"
    { default_core_cfg.udp_mtu=(yyvsp[(3) - (3)].intval); }
    break;

  case 380:
/* Line 1792 of yacc.c  */
#line 1595 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 381:
/* Line 1792 of yacc.c  */
#line 1597 "cfg.y"
    { default_core_cfg.force_rport=(yyvsp[(3) - (3)].intval); fix_global_req_flags(0, 0); }
    break;

  case 382:
/* Line 1792 of yacc.c  */
#line 1598 "cfg.y"
    { yyerror("boolean value expected"); }
    break;

  case 383:
/* Line 1792 of yacc.c  */
#line 1600 "cfg.y"
    { default_core_cfg.udp_mtu_try_proto=(yyvsp[(3) - (3)].intval); fix_global_req_flags(0, 0); }
    break;

  case 384:
/* Line 1792 of yacc.c  */
#line 1602 "cfg.y"
    { yyerror("TCP, TLS, SCTP or UDP expected"); }
    break;

  case 385:
/* Line 1792 of yacc.c  */
#line 1603 "cfg.y"
    { IF_RAW_SOCKS(default_core_cfg.udp4_raw=(yyvsp[(3) - (3)].intval)); }
    break;

  case 386:
/* Line 1792 of yacc.c  */
#line 1604 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 387:
/* Line 1792 of yacc.c  */
#line 1605 "cfg.y"
    {
		IF_RAW_SOCKS(default_core_cfg.udp4_raw_mtu=(yyvsp[(3) - (3)].intval));
	}
    break;

  case 388:
/* Line 1792 of yacc.c  */
#line 1608 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 389:
/* Line 1792 of yacc.c  */
#line 1609 "cfg.y"
    {
		IF_RAW_SOCKS(default_core_cfg.udp4_raw_ttl=(yyvsp[(3) - (3)].intval));
	}
    break;

  case 390:
/* Line 1792 of yacc.c  */
#line 1612 "cfg.y"
    { yyerror("number expected"); }
    break;

  case 392:
/* Line 1792 of yacc.c  */
#line 1614 "cfg.y"
    { yyerror("unknown config variable"); }
    break;

  case 394:
/* Line 1792 of yacc.c  */
#line 1618 "cfg.y"
    { (yyval.strval)="default" ; }
    break;

  case 395:
/* Line 1792 of yacc.c  */
#line 1622 "cfg.y"
    {
		if (cfg_declare_int((yyvsp[(1) - (5)].strval), (yyvsp[(3) - (5)].strval), (yyvsp[(5) - (5)].intval), 0, 0, NULL)) {
			yyerror("variable cannot be declared");
		}
	}
    break;

  case 396:
/* Line 1792 of yacc.c  */
#line 1627 "cfg.y"
    {
		if (cfg_declare_str((yyvsp[(1) - (5)].strval), (yyvsp[(3) - (5)].strval), (yyvsp[(5) - (5)].strval), NULL)) {
			yyerror("variable cannot be declared");
		}
	}
    break;

  case 397:
/* Line 1792 of yacc.c  */
#line 1632 "cfg.y"
    {
		if (cfg_declare_int((yyvsp[(1) - (7)].strval), (yyvsp[(3) - (7)].strval), (yyvsp[(5) - (7)].intval), 0, 0, (yyvsp[(7) - (7)].strval))) {
			yyerror("variable cannot be declared");
		}
	}
    break;

  case 398:
/* Line 1792 of yacc.c  */
#line 1637 "cfg.y"
    {
		if (cfg_declare_str((yyvsp[(1) - (7)].strval), (yyvsp[(3) - (7)].strval), (yyvsp[(5) - (7)].strval), (yyvsp[(7) - (7)].strval))) {
			yyerror("variable cannot be declared");
		}
	}
    break;

  case 399:
/* Line 1792 of yacc.c  */
#line 1642 "cfg.y"
    { 
		yyerror("number or string expected"); 
	}
    break;

  case 400:
/* Line 1792 of yacc.c  */
#line 1645 "cfg.y"
    {
		if (cfg_ginst_var_int((yyvsp[(1) - (8)].strval), (yyvsp[(3) - (8)].intval), (yyvsp[(6) - (8)].strval), (yyvsp[(8) - (8)].intval))) {
			yyerror("variable cannot be added to the group instance");
		}
	}
    break;

  case 401:
/* Line 1792 of yacc.c  */
#line 1650 "cfg.y"
    {
		if (cfg_ginst_var_string((yyvsp[(1) - (8)].strval), (yyvsp[(3) - (8)].intval), (yyvsp[(6) - (8)].strval), (yyvsp[(8) - (8)].strval))) {
			yyerror("variable cannot be added to the group instance");
		}
	}
    break;

  case 402:
/* Line 1792 of yacc.c  */
#line 1658 "cfg.y"
    {
		DBG("loading module %s\n", (yyvsp[(2) - (2)].strval));
			if (load_module((yyvsp[(2) - (2)].strval))!=0) {
				yyerror("failed to load module");
			}
	}
    break;

  case 403:
/* Line 1792 of yacc.c  */
#line 1664 "cfg.y"
    { yyerror("string expected"); }
    break;

  case 404:
/* Line 1792 of yacc.c  */
#line 1665 "cfg.y"
    {
		if(mods_dir_cmd==0) {
			DBG("loading modules under %s\n", (yyvsp[(2) - (2)].strval));
			printf("loading modules under config path: %s\n", (yyvsp[(2) - (2)].strval));
			mods_dir = (yyvsp[(2) - (2)].strval);
		} else {
			DBG("ignoring mod path given in config: %s\n", (yyvsp[(2) - (2)].strval));
			printf("loading modules under command line path: %s\n", mods_dir);
		}
	}
    break;

  case 405:
/* Line 1792 of yacc.c  */
#line 1675 "cfg.y"
    { yyerror("string expected"); }
    break;

  case 406:
/* Line 1792 of yacc.c  */
#line 1676 "cfg.y"
    {
		if(mods_dir_cmd==0) {
			DBG("loading modules under %s\n", (yyvsp[(3) - (3)].strval));
			printf("loading modules under config path: %s\n", (yyvsp[(3) - (3)].strval));
			mods_dir = (yyvsp[(3) - (3)].strval);
		} else {
			DBG("ignoring mod path given in config: %s\n", (yyvsp[(3) - (3)].strval));
			printf("loading modules under command line path: %s\n", mods_dir);
		}
	}
    break;

  case 407:
/* Line 1792 of yacc.c  */
#line 1686 "cfg.y"
    { yyerror("string expected"); }
    break;

  case 408:
/* Line 1792 of yacc.c  */
#line 1687 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		if (set_mod_param_regex((yyvsp[(3) - (8)].strval), (yyvsp[(5) - (8)].strval), PARAM_STRING, (yyvsp[(7) - (8)].strval)) != 0) {
			 yyerror("Can't set module parameter");
		}
	}
    break;

  case 409:
/* Line 1792 of yacc.c  */
#line 1698 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		if (set_mod_param_regex((yyvsp[(3) - (8)].strval), (yyvsp[(5) - (8)].strval), PARAM_INT, (void*)(yyvsp[(7) - (8)].intval)) != 0) {
			 yyerror("Can't set module parameter");
		}
	}
    break;

  case 410:
/* Line 1792 of yacc.c  */
#line 1709 "cfg.y"
    { yyerror("Invalid arguments"); }
    break;

  case 411:
/* Line 1792 of yacc.c  */
#line 1712 "cfg.y"
    { (yyval.ipaddr)=(yyvsp[(1) - (1)].ipaddr); }
    break;

  case 412:
/* Line 1792 of yacc.c  */
#line 1713 "cfg.y"
    { (yyval.ipaddr)=(yyvsp[(1) - (1)].ipaddr); }
    break;

  case 413:
/* Line 1792 of yacc.c  */
#line 1716 "cfg.y"
    {
		(yyval.ipaddr)=pkg_malloc(sizeof(struct ip_addr));
		if ((yyval.ipaddr)==0) {
			LOG(L_CRIT, "ERROR: cfg. parser: out of memory.\n");
		} else {
			memset((yyval.ipaddr), 0, sizeof(struct ip_addr));
			(yyval.ipaddr)->af=AF_INET;
			(yyval.ipaddr)->len=4;
			if (((yyvsp[(1) - (7)].intval)>255) || ((yyvsp[(1) - (7)].intval)<0) ||
				((yyvsp[(3) - (7)].intval)>255) || ((yyvsp[(3) - (7)].intval)<0) ||
				((yyvsp[(5) - (7)].intval)>255) || ((yyvsp[(5) - (7)].intval)<0) ||
				((yyvsp[(7) - (7)].intval)>255) || ((yyvsp[(7) - (7)].intval)<0)) {
				yyerror("invalid ipv4 address");
				(yyval.ipaddr)->u.addr32[0]=0;
				/* $$=0; */
			} else {
				(yyval.ipaddr)->u.addr[0]=(yyvsp[(1) - (7)].intval);
				(yyval.ipaddr)->u.addr[1]=(yyvsp[(3) - (7)].intval);
				(yyval.ipaddr)->u.addr[2]=(yyvsp[(5) - (7)].intval);
				(yyval.ipaddr)->u.addr[3]=(yyvsp[(7) - (7)].intval);
				/*
				$$=htonl( ($1<<24)|
				($3<<16)| ($5<<8)|$7 );
				*/
			}
		}
	}
    break;

  case 414:
/* Line 1792 of yacc.c  */
#line 1745 "cfg.y"
    {
		(yyval.ipaddr)=pkg_malloc(sizeof(struct ip_addr));
		if ((yyval.ipaddr)==0) {
			LOG(L_CRIT, "ERROR: cfg. parser: out of memory.\n");
		} else {
			memset((yyval.ipaddr), 0, sizeof(struct ip_addr));
			(yyval.ipaddr)->af=AF_INET6;
			(yyval.ipaddr)->len=16;
			if (inet_pton(AF_INET6, (yyvsp[(1) - (1)].strval), (yyval.ipaddr)->u.addr)<=0) {
				yyerror("bad ipv6 address");
			}
		}
	}
    break;

  case 415:
/* Line 1792 of yacc.c  */
#line 1760 "cfg.y"
    { (yyval.ipaddr)=(yyvsp[(1) - (1)].ipaddr); }
    break;

  case 416:
/* Line 1792 of yacc.c  */
#line 1761 "cfg.y"
    {(yyval.ipaddr)=(yyvsp[(2) - (3)].ipaddr); }
    break;

  case 417:
/* Line 1792 of yacc.c  */
#line 1765 "cfg.y"
    {
					tmp=int2str((yyvsp[(1) - (1)].intval), &i_tmp);
					if (((yyval.strval)=pkg_malloc(i_tmp+1))==0) {
						yyerror("out of  memory");
						YYABORT;
					} else {
						memcpy((yyval.strval), tmp, i_tmp);
						(yyval.strval)[i_tmp]=0;
					}
					routename = tmp;
						}
    break;

  case 418:
/* Line 1792 of yacc.c  */
#line 1776 "cfg.y"
    { routename = (yyvsp[(1) - (1)].strval); (yyval.strval)=(yyvsp[(1) - (1)].strval); }
    break;

  case 419:
/* Line 1792 of yacc.c  */
#line 1777 "cfg.y"
    { routename = (yyvsp[(1) - (1)].strval); (yyval.strval)=(yyvsp[(1) - (1)].strval); }
    break;

  case 420:
/* Line 1792 of yacc.c  */
#line 1781 "cfg.y"
    { ; }
    break;

  case 421:
/* Line 1792 of yacc.c  */
#line 1782 "cfg.y"
    { ; }
    break;

  case 422:
/* Line 1792 of yacc.c  */
#line 1786 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		push((yyvsp[(3) - (4)].action), &main_rt.rlist[DEFAULT_RT]);
	}
    break;

  case 423:
/* Line 1792 of yacc.c  */
#line 1795 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		i_tmp=route_get(&main_rt, (yyvsp[(3) - (7)].strval));
		if (i_tmp==-1){
			yyerror("internal error");
			YYABORT;
		}
		if (main_rt.rlist[i_tmp]){
			yyerror("duplicate route");
			YYABORT;
		}
		push((yyvsp[(6) - (7)].action), &main_rt.rlist[i_tmp]);
	}
    break;

  case 424:
/* Line 1792 of yacc.c  */
#line 1813 "cfg.y"
    { yyerror("invalid  route  statement"); }
    break;

  case 425:
/* Line 1792 of yacc.c  */
#line 1814 "cfg.y"
    { yyerror("invalid  request_route  statement"); }
    break;

  case 426:
/* Line 1792 of yacc.c  */
#line 1817 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		push((yyvsp[(3) - (4)].action), &failure_rt.rlist[DEFAULT_RT]);
	}
    break;

  case 427:
/* Line 1792 of yacc.c  */
#line 1826 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		i_tmp=route_get(&failure_rt, (yyvsp[(3) - (7)].strval));
		if (i_tmp==-1){
			yyerror("internal error");
			YYABORT;
		}
		if (failure_rt.rlist[i_tmp]){
			yyerror("duplicate route");
			YYABORT;
		}
		push((yyvsp[(6) - (7)].action), &failure_rt.rlist[i_tmp]);
	}
    break;

  case 428:
/* Line 1792 of yacc.c  */
#line 1844 "cfg.y"
    { yyerror("invalid failure_route statement"); }
    break;

  case 429:
/* Line 1792 of yacc.c  */
#line 1848 "cfg.y"
    { ; }
    break;

  case 430:
/* Line 1792 of yacc.c  */
#line 1849 "cfg.y"
    { ; }
    break;

  case 431:
/* Line 1792 of yacc.c  */
#line 1854 "cfg.y"
    {rt=CORE_ONREPLY_ROUTE;}
    break;

  case 432:
/* Line 1792 of yacc.c  */
#line 1854 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		push((yyvsp[(4) - (5)].action), &onreply_rt.rlist[DEFAULT_RT]);
	}
    break;

  case 433:
/* Line 1792 of yacc.c  */
#line 1863 "cfg.y"
    { yyerror("invalid onreply_route statement"); }
    break;

  case 434:
/* Line 1792 of yacc.c  */
#line 1864 "cfg.y"
    { yyerror("invalid onreply_route statement"); }
    break;

  case 435:
/* Line 1792 of yacc.c  */
#line 1866 "cfg.y"
    {rt=(*(yyvsp[(3) - (4)].strval)=='0' && (yyvsp[(3) - (4)].strval)[1]==0)?CORE_ONREPLY_ROUTE:TM_ONREPLY_ROUTE;}
    break;

  case 436:
/* Line 1792 of yacc.c  */
#line 1867 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		if (*(yyvsp[(3) - (8)].strval)=='0' && (yyvsp[(3) - (8)].strval)[1]==0){
			/* onreply_route[0] {} is equivalent with onreply_route {}*/
			push((yyvsp[(7) - (8)].action), &onreply_rt.rlist[DEFAULT_RT]);
		}else{
			i_tmp=route_get(&onreply_rt, (yyvsp[(3) - (8)].strval));
			if (i_tmp==-1){
				yyerror("internal error");
				YYABORT;
			}
			if (onreply_rt.rlist[i_tmp]){
				yyerror("duplicate route");
				YYABORT;
			}
			push((yyvsp[(7) - (8)].action), &onreply_rt.rlist[i_tmp]);
		}
	}
    break;

  case 437:
/* Line 1792 of yacc.c  */
#line 1890 "cfg.y"
    {
		yyerror("invalid onreply_route statement");
	}
    break;

  case 438:
/* Line 1792 of yacc.c  */
#line 1895 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		push((yyvsp[(3) - (4)].action), &branch_rt.rlist[DEFAULT_RT]);
	}
    break;

  case 439:
/* Line 1792 of yacc.c  */
#line 1904 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		i_tmp=route_get(&branch_rt, (yyvsp[(3) - (7)].strval));
		if (i_tmp==-1){
			yyerror("internal error");
			YYABORT;
		}
		if (branch_rt.rlist[i_tmp]){
			yyerror("duplicate route");
			YYABORT;
		}
		push((yyvsp[(6) - (7)].action), &branch_rt.rlist[i_tmp]);
	}
    break;

  case 440:
/* Line 1792 of yacc.c  */
#line 1922 "cfg.y"
    { yyerror("invalid branch_route statement"); }
    break;

  case 441:
/* Line 1792 of yacc.c  */
#line 1924 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		push((yyvsp[(3) - (4)].action), &onsend_rt.rlist[DEFAULT_RT]);
	}
    break;

  case 442:
/* Line 1792 of yacc.c  */
#line 1933 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		i_tmp=route_get(&onsend_rt, (yyvsp[(3) - (7)].strval));
		if (i_tmp==-1){
			yyerror("internal error");
			YYABORT;
		}
		if (onsend_rt.rlist[i_tmp]){
			yyerror("duplicate route");
			YYABORT;
		}
		push((yyvsp[(6) - (7)].action), &onsend_rt.rlist[i_tmp]);
	}
    break;

  case 443:
/* Line 1792 of yacc.c  */
#line 1951 "cfg.y"
    { yyerror("invalid onsend_route statement"); }
    break;

  case 444:
/* Line 1792 of yacc.c  */
#line 1953 "cfg.y"
    {
	#ifdef SHM_MEM
		if (!shm_initialized() && init_shm()<0) {
			yyerror("Can't initialize shared memory");
			YYABORT;
		}
	#endif /* SHM_MEM */
		i_tmp=route_get(&event_rt, (yyvsp[(3) - (7)].strval));
		if (i_tmp==-1){
			yyerror("internal error");
			YYABORT;
		}
		if (event_rt.rlist[i_tmp]){
			yyerror("duplicate route");
			YYABORT;
		}
		push((yyvsp[(6) - (7)].action), &event_rt.rlist[i_tmp]);
	}
    break;

  case 445:
/* Line 1792 of yacc.c  */
#line 1972 "cfg.y"
    { yyerror("invalid event_route statement"); }
    break;

  case 446:
/* Line 1792 of yacc.c  */
#line 1975 "cfg.y"
    { if(pp_subst_add((yyvsp[(2) - (2)].strval))<0) YYERROR; }
    break;

  case 447:
/* Line 1792 of yacc.c  */
#line 1976 "cfg.y"
    { yyerror("invalid subst preprocess statement"); }
    break;

  case 448:
/* Line 1792 of yacc.c  */
#line 1977 "cfg.y"
    { if(pp_substdef_add((yyvsp[(2) - (2)].strval), 0)<0) YYERROR; }
    break;

  case 449:
/* Line 1792 of yacc.c  */
#line 1978 "cfg.y"
    { yyerror("invalid substdef preprocess statement"); }
    break;

  case 450:
/* Line 1792 of yacc.c  */
#line 1979 "cfg.y"
    { if(pp_substdef_add((yyvsp[(2) - (2)].strval), 1)<0) YYERROR; }
    break;

  case 451:
/* Line 1792 of yacc.c  */
#line 1980 "cfg.y"
    { yyerror("invalid substdefs preprocess statement"); }
    break;

  case 452:
/* Line 1792 of yacc.c  */
#line 2002 "cfg.y"
    {(yyval.intval)=EQUAL_OP; }
    break;

  case 453:
/* Line 1792 of yacc.c  */
#line 2003 "cfg.y"
    {(yyval.intval)=DIFF_OP; }
    break;

  case 454:
/* Line 1792 of yacc.c  */
#line 2004 "cfg.y"
    {(yyval.intval)=EQUAL_OP; }
    break;

  case 455:
/* Line 1792 of yacc.c  */
#line 2005 "cfg.y"
    {(yyval.intval)=DIFF_OP; }
    break;

  case 456:
/* Line 1792 of yacc.c  */
#line 2008 "cfg.y"
    {(yyval.intval)=GT_OP; }
    break;

  case 457:
/* Line 1792 of yacc.c  */
#line 2009 "cfg.y"
    {(yyval.intval)=LT_OP; }
    break;

  case 458:
/* Line 1792 of yacc.c  */
#line 2010 "cfg.y"
    {(yyval.intval)=GTE_OP; }
    break;

  case 459:
/* Line 1792 of yacc.c  */
#line 2011 "cfg.y"
    {(yyval.intval)=LTE_OP; }
    break;

  case 460:
/* Line 1792 of yacc.c  */
#line 2014 "cfg.y"
    {(yyval.intval)=(yyvsp[(1) - (1)].intval); }
    break;

  case 461:
/* Line 1792 of yacc.c  */
#line 2015 "cfg.y"
    {(yyval.intval)=MATCH_OP; }
    break;

  case 462:
/* Line 1792 of yacc.c  */
#line 2021 "cfg.y"
    {(yyval.intval)=RVE_EQ_OP; }
    break;

  case 463:
/* Line 1792 of yacc.c  */
#line 2022 "cfg.y"
    {(yyval.intval)=RVE_DIFF_OP; }
    break;

  case 464:
/* Line 1792 of yacc.c  */
#line 2023 "cfg.y"
    {(yyval.intval)=RVE_IEQ_OP; }
    break;

  case 465:
/* Line 1792 of yacc.c  */
#line 2024 "cfg.y"
    {(yyval.intval)=RVE_IDIFF_OP; }
    break;

  case 466:
/* Line 1792 of yacc.c  */
#line 2025 "cfg.y"
    {(yyval.intval)=RVE_STREQ_OP; }
    break;

  case 467:
/* Line 1792 of yacc.c  */
#line 2026 "cfg.y"
    {(yyval.intval)=RVE_STRDIFF_OP; }
    break;

  case 468:
/* Line 1792 of yacc.c  */
#line 2027 "cfg.y"
    {(yyval.intval)=RVE_MATCH_OP; }
    break;

  case 469:
/* Line 1792 of yacc.c  */
#line 2030 "cfg.y"
    {(yyval.intval)=RVE_GT_OP; }
    break;

  case 470:
/* Line 1792 of yacc.c  */
#line 2031 "cfg.y"
    {(yyval.intval)=RVE_LT_OP; }
    break;

  case 471:
/* Line 1792 of yacc.c  */
#line 2032 "cfg.y"
    {(yyval.intval)=RVE_GTE_OP; }
    break;

  case 472:
/* Line 1792 of yacc.c  */
#line 2033 "cfg.y"
    {(yyval.intval)=RVE_LTE_OP; }
    break;

  case 473:
/* Line 1792 of yacc.c  */
#line 2040 "cfg.y"
    {(yyval.intval)=URI_O;}
    break;

  case 474:
/* Line 1792 of yacc.c  */
#line 2041 "cfg.y"
    {(yyval.intval)=FROM_URI_O;}
    break;

  case 475:
/* Line 1792 of yacc.c  */
#line 2042 "cfg.y"
    {(yyval.intval)=TO_URI_O;}
    break;

  case 476:
/* Line 1792 of yacc.c  */
#line 2049 "cfg.y"
    { (yyval.intval)=SNDPORT_O; }
    break;

  case 477:
/* Line 1792 of yacc.c  */
#line 2050 "cfg.y"
    { (yyval.intval)=TOPORT_O; }
    break;

  case 478:
/* Line 1792 of yacc.c  */
#line 2051 "cfg.y"
    { (yyval.intval)=SNDAF_O; }
    break;

  case 479:
/* Line 1792 of yacc.c  */
#line 2055 "cfg.y"
    { (yyval.intval)=SRCPORT_O; }
    break;

  case 480:
/* Line 1792 of yacc.c  */
#line 2056 "cfg.y"
    { (yyval.intval)=DSTPORT_O; }
    break;

  case 481:
/* Line 1792 of yacc.c  */
#line 2057 "cfg.y"
    { (yyval.intval)=AF_O; }
    break;

  case 482:
/* Line 1792 of yacc.c  */
#line 2058 "cfg.y"
    { (yyval.intval)=MSGLEN_O; }
    break;

  case 484:
/* Line 1792 of yacc.c  */
#line 2064 "cfg.y"
    { onsend_check("snd_ip"); (yyval.intval)=SNDIP_O; }
    break;

  case 485:
/* Line 1792 of yacc.c  */
#line 2065 "cfg.y"
    { onsend_check("to_ip");  (yyval.intval)=TOIP_O; }
    break;

  case 486:
/* Line 1792 of yacc.c  */
#line 2068 "cfg.y"
    { (yyval.intval)=SRCIP_O; }
    break;

  case 487:
/* Line 1792 of yacc.c  */
#line 2069 "cfg.y"
    { (yyval.intval)=DSTIP_O; }
    break;

  case 489:
/* Line 1792 of yacc.c  */
#line 2077 "cfg.y"
    {(yyval.expr)= mk_elem((yyvsp[(2) - (3)].intval), METHOD_O, 0, RVE_ST, (yyvsp[(3) - (3)].rv_expr));}
    break;

  case 490:
/* Line 1792 of yacc.c  */
#line 2079 "cfg.y"
    {(yyval.expr) = mk_elem((yyvsp[(2) - (3)].intval), METHOD_O, 0, STRING_ST,(yyvsp[(3) - (3)].strval)); }
    break;

  case 491:
/* Line 1792 of yacc.c  */
#line 2080 "cfg.y"
    { (yyval.expr)=0; yyerror("string expected"); }
    break;

  case 492:
/* Line 1792 of yacc.c  */
#line 2082 "cfg.y"
    { (yyval.expr)=0; yyerror("invalid operator,== , !=, or =~ expected"); }
    break;

  case 493:
/* Line 1792 of yacc.c  */
#line 2084 "cfg.y"
    {(yyval.expr) = mk_elem((yyvsp[(2) - (3)].intval), (yyvsp[(1) - (3)].intval), 0, RVE_ST, (yyvsp[(3) - (3)].rv_expr)); }
    break;

  case 494:
/* Line 1792 of yacc.c  */
#line 2086 "cfg.y"
    {(yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), (yyvsp[(1) - (3)].intval), 0, MYSELF_ST, 0); }
    break;

  case 495:
/* Line 1792 of yacc.c  */
#line 2088 "cfg.y"
    { (yyval.expr)=0; yyerror("string or MYSELF expected"); }
    break;

  case 496:
/* Line 1792 of yacc.c  */
#line 2090 "cfg.y"
    { (yyval.expr)=0; yyerror("invalid operator, == , != or =~ expected"); }
    break;

  case 497:
/* Line 1792 of yacc.c  */
#line 2091 "cfg.y"
    { (yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), (yyvsp[(1) - (3)].intval), 0, RVE_ST, (yyvsp[(3) - (3)].rv_expr) ); }
    break;

  case 498:
/* Line 1792 of yacc.c  */
#line 2093 "cfg.y"
    { (yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), (yyvsp[(1) - (3)].intval), 0, RVE_ST, (yyvsp[(3) - (3)].rv_expr) ); }
    break;

  case 499:
/* Line 1792 of yacc.c  */
#line 2094 "cfg.y"
    { (yyval.expr)=0; yyerror("number expected"); }
    break;

  case 500:
/* Line 1792 of yacc.c  */
#line 2095 "cfg.y"
    { (yyval.expr)=0; yyerror("number expected"); }
    break;

  case 501:
/* Line 1792 of yacc.c  */
#line 2096 "cfg.y"
    { (yyval.expr)=0; yyerror("==, !=, <,>, >= or <=  expected"); }
    break;

  case 502:
/* Line 1792 of yacc.c  */
#line 2098 "cfg.y"
    { (yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), PROTO_O, 0, NUMBER_ST, (void*)(yyvsp[(3) - (3)].intval) ); }
    break;

  case 503:
/* Line 1792 of yacc.c  */
#line 2100 "cfg.y"
    { (yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), PROTO_O, 0, RVE_ST, (yyvsp[(3) - (3)].rv_expr) ); }
    break;

  case 504:
/* Line 1792 of yacc.c  */
#line 2102 "cfg.y"
    { (yyval.expr)=0; yyerror("protocol expected (udp, tcp, tls, sctp, ws, or wss)"); }
    break;

  case 505:
/* Line 1792 of yacc.c  */
#line 2104 "cfg.y"
    { (yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), SNDPROTO_O, 0, NUMBER_ST, (void*)(yyvsp[(3) - (3)].intval) ); }
    break;

  case 506:
/* Line 1792 of yacc.c  */
#line 2106 "cfg.y"
    { (yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), SNDPROTO_O, 0, RVE_ST, (yyvsp[(3) - (3)].rv_expr) ); }
    break;

  case 507:
/* Line 1792 of yacc.c  */
#line 2108 "cfg.y"
    { (yyval.expr)=0; yyerror("protocol expected (udp, tcp, tls, sctp, ws, or wss)"); }
    break;

  case 508:
/* Line 1792 of yacc.c  */
#line 2109 "cfg.y"
    { (yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), (yyvsp[(1) - (3)].intval), 0, NET_ST, (yyvsp[(3) - (3)].ipnet)); }
    break;

  case 509:
/* Line 1792 of yacc.c  */
#line 2110 "cfg.y"
    {
			s_tmp.s=0;
			(yyval.expr)=0;
			if (rve_is_constant((yyvsp[(3) - (3)].rv_expr))){
				i_tmp=rve_guess_type((yyvsp[(3) - (3)].rv_expr));
				if (i_tmp==RV_INT)
					yyerror("string expected");
				else if (i_tmp==RV_STR){
					if (((rval_tmp=rval_expr_eval(0, 0, (yyvsp[(3) - (3)].rv_expr)))==0) ||
								(rval_get_str(0, 0, &s_tmp, rval_tmp, 0)<0)){
						rval_destroy(rval_tmp);
						yyerror("bad rvalue expression");
					}else{
						rval_destroy(rval_tmp);
					}
				}else{
					yyerror("BUG: unexpected dynamic type");
				}
			}else{
					/* warn("non constant rvalue in ip comparison") */;
			}
			if (s_tmp.s){
				ip_tmp=str2ip(&s_tmp);
				if (ip_tmp==0)
					ip_tmp=str2ip6(&s_tmp);
				pkg_free(s_tmp.s);
				if (ip_tmp) {
					(yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), (yyvsp[(1) - (3)].intval), 0, NET_ST, 
								mk_new_net_bitlen(ip_tmp, ip_tmp->len*8) );
				} else {
					(yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), (yyvsp[(1) - (3)].intval), 0, RVE_ST, (yyvsp[(3) - (3)].rv_expr));
				}
			}else{
				(yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), (yyvsp[(1) - (3)].intval), 0, RVE_ST, (yyvsp[(3) - (3)].rv_expr));
			}
		}
    break;

  case 510:
/* Line 1792 of yacc.c  */
#line 2147 "cfg.y"
    { (yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), (yyvsp[(1) - (3)].intval), 0, STRING_ST, (yyvsp[(3) - (3)].strval)); }
    break;

  case 511:
/* Line 1792 of yacc.c  */
#line 2149 "cfg.y"
    { (yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), (yyvsp[(1) - (3)].intval), 0, MYSELF_ST, 0); }
    break;

  case 512:
/* Line 1792 of yacc.c  */
#line 2151 "cfg.y"
    { (yyval.expr)=0; yyerror( "ip address or hostname expected" ); }
    break;

  case 513:
/* Line 1792 of yacc.c  */
#line 2153 "cfg.y"
    { (yyval.expr)=0; yyerror("invalid operator, ==, != or =~ expected");}
    break;

  case 514:
/* Line 1792 of yacc.c  */
#line 2156 "cfg.y"
    { (yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), (yyvsp[(3) - (3)].intval), 0, MYSELF_ST, 0); }
    break;

  case 515:
/* Line 1792 of yacc.c  */
#line 2158 "cfg.y"
    { (yyval.expr)=mk_elem((yyvsp[(2) - (3)].intval), (yyvsp[(3) - (3)].intval), 0, MYSELF_ST, 0); }
    break;

  case 516:
/* Line 1792 of yacc.c  */
#line 2160 "cfg.y"
    { (yyval.expr)=0; yyerror(" URI, SRCIP or DSTIP expected"); }
    break;

  case 517:
/* Line 1792 of yacc.c  */
#line 2161 "cfg.y"
    { (yyval.expr)=0; yyerror ("invalid operator, == or != expected"); }
    break;

  case 518:
/* Line 1792 of yacc.c  */
#line 2165 "cfg.y"
    { (yyval.ipnet)=mk_new_net((yyvsp[(1) - (3)].ipaddr), (yyvsp[(3) - (3)].ipaddr)); }
    break;

  case 519:
/* Line 1792 of yacc.c  */
#line 2166 "cfg.y"
    {
		if (((yyvsp[(3) - (3)].intval)<0) || ((yyvsp[(3) - (3)].intval)>(yyvsp[(1) - (3)].ipaddr)->len*8)) {
			yyerror("invalid bit number in netmask");
			(yyval.ipnet)=0;
		} else {
			(yyval.ipnet)=mk_new_net_bitlen((yyvsp[(1) - (3)].ipaddr), (yyvsp[(3) - (3)].intval));
		/*
			$$=mk_new_net($1, htonl( ($3)?~( (1<<(32-$3))-1 ):0 ) );
		*/
		}
	}
    break;

  case 520:
/* Line 1792 of yacc.c  */
#line 2177 "cfg.y"
    { (yyval.ipnet)=mk_new_net_bitlen((yyvsp[(1) - (1)].ipaddr), (yyvsp[(1) - (1)].ipaddr)->len*8); }
    break;

  case 521:
/* Line 1792 of yacc.c  */
#line 2178 "cfg.y"
    { (yyval.ipnet)=0; yyerror("netmask (eg:255.0.0.0 or 8) expected"); }
    break;

  case 522:
/* Line 1792 of yacc.c  */
#line 2182 "cfg.y"
    { (yyval.strval)=(yyvsp[(1) - (1)].strval); }
    break;

  case 523:
/* Line 1792 of yacc.c  */
#line 2183 "cfg.y"
    {
		if ((yyvsp[(1) - (3)].strval)){
			(yyval.strval)=(char*)pkg_malloc(strlen((yyvsp[(1) - (3)].strval))+1+strlen((yyvsp[(3) - (3)].strval))+1);
			if ((yyval.strval)==0) {
				LOG(L_CRIT, "ERROR: cfg. parser: memory allocation"
							" failure while parsing host\n");
			} else {
				memcpy((yyval.strval), (yyvsp[(1) - (3)].strval), strlen((yyvsp[(1) - (3)].strval)));
				(yyval.strval)[strlen((yyvsp[(1) - (3)].strval))]='.';
				memcpy((yyval.strval)+strlen((yyvsp[(1) - (3)].strval))+1, (yyvsp[(3) - (3)].strval), strlen((yyvsp[(3) - (3)].strval)));
				(yyval.strval)[strlen((yyvsp[(1) - (3)].strval))+1+strlen((yyvsp[(3) - (3)].strval))]=0;
			}
			pkg_free((yyvsp[(1) - (3)].strval));
		}
		if ((yyvsp[(3) - (3)].strval)) pkg_free((yyvsp[(3) - (3)].strval));
	}
    break;

  case 524:
/* Line 1792 of yacc.c  */
#line 2199 "cfg.y"
    {
		if ((yyvsp[(1) - (3)].strval)){
			(yyval.strval)=(char*)pkg_malloc(strlen((yyvsp[(1) - (3)].strval))+1+strlen((yyvsp[(3) - (3)].strval))+1);
			if ((yyval.strval)==0) {
				LOG(L_CRIT, "ERROR: cfg. parser: memory allocation"
							" failure while parsing host\n");
			} else {
				memcpy((yyval.strval), (yyvsp[(1) - (3)].strval), strlen((yyvsp[(1) - (3)].strval)));
				(yyval.strval)[strlen((yyvsp[(1) - (3)].strval))]='-';
				memcpy((yyval.strval)+strlen((yyvsp[(1) - (3)].strval))+1, (yyvsp[(3) - (3)].strval), strlen((yyvsp[(3) - (3)].strval)));
				(yyval.strval)[strlen((yyvsp[(1) - (3)].strval))+1+strlen((yyvsp[(3) - (3)].strval))]=0;
			}
			pkg_free((yyvsp[(1) - (3)].strval));
		}
		if ((yyvsp[(3) - (3)].strval)) pkg_free((yyvsp[(3) - (3)].strval));
	}
    break;

  case 525:
/* Line 1792 of yacc.c  */
#line 2215 "cfg.y"
    { (yyval.strval)=0; pkg_free((yyvsp[(1) - (3)].strval)); yyerror("invalid hostname"); }
    break;

  case 526:
/* Line 1792 of yacc.c  */
#line 2216 "cfg.y"
    { (yyval.strval)=0; pkg_free((yyvsp[(1) - (3)].strval)); yyerror("invalid hostname"); }
    break;

  case 529:
/* Line 1792 of yacc.c  */
#line 2221 "cfg.y"
    {
			/* get string version */
			(yyval.strval)=pkg_malloc(strlen(yy_number_str)+1);
			if ((yyval.strval))
				strcpy((yyval.strval), yy_number_str);
		}
    break;

  case 530:
/* Line 1792 of yacc.c  */
#line 2230 "cfg.y"
    { (yyval.strval)=(yyvsp[(1) - (1)].strval); }
    break;

  case 531:
/* Line 1792 of yacc.c  */
#line 2231 "cfg.y"
    {
		if ((yyvsp[(1) - (3)].strval)){
			(yyval.strval)=(char*)pkg_malloc(strlen((yyvsp[(1) - (3)].strval))+1+strlen((yyvsp[(3) - (3)].strval))+1);
			if ((yyval.strval)==0) {
				LOG(L_CRIT, "ERROR: cfg. parser: memory allocation"
							" failure while parsing host/interface name\n");
			} else {
				memcpy((yyval.strval), (yyvsp[(1) - (3)].strval), strlen((yyvsp[(1) - (3)].strval)));
				(yyval.strval)[strlen((yyvsp[(1) - (3)].strval))]='.';
				memcpy((yyval.strval)+strlen((yyvsp[(1) - (3)].strval))+1, (yyvsp[(3) - (3)].strval), strlen((yyvsp[(3) - (3)].strval)));
				(yyval.strval)[strlen((yyvsp[(1) - (3)].strval))+1+strlen((yyvsp[(3) - (3)].strval))]=0;
			}
			pkg_free((yyvsp[(1) - (3)].strval));
		}
		if ((yyvsp[(3) - (3)].strval)) pkg_free((yyvsp[(3) - (3)].strval));
	}
    break;

  case 532:
/* Line 1792 of yacc.c  */
#line 2247 "cfg.y"
    {
		if ((yyvsp[(1) - (3)].strval)){
			(yyval.strval)=(char*)pkg_malloc(strlen((yyvsp[(1) - (3)].strval))+1+strlen((yyvsp[(3) - (3)].strval))+1);
			if ((yyval.strval)==0) {
				LOG(L_CRIT, "ERROR: cfg. parser: memory allocation"
							" failure while parsing host/interface name\n");
			} else {
				memcpy((yyval.strval), (yyvsp[(1) - (3)].strval), strlen((yyvsp[(1) - (3)].strval)));
				(yyval.strval)[strlen((yyvsp[(1) - (3)].strval))]='-';
				memcpy((yyval.strval)+strlen((yyvsp[(1) - (3)].strval))+1, (yyvsp[(3) - (3)].strval), strlen((yyvsp[(3) - (3)].strval)));
				(yyval.strval)[strlen((yyvsp[(1) - (3)].strval))+1+strlen((yyvsp[(3) - (3)].strval))]=0;
			}
			pkg_free((yyvsp[(1) - (3)].strval));
		}
		if ((yyvsp[(3) - (3)].strval)) pkg_free((yyvsp[(3) - (3)].strval));
	}
    break;

  case 533:
/* Line 1792 of yacc.c  */
#line 2263 "cfg.y"
    { (yyval.strval)=0; pkg_free((yyvsp[(1) - (3)].strval));
								yyerror("invalid host or interface name"); }
    break;

  case 534:
/* Line 1792 of yacc.c  */
#line 2265 "cfg.y"
    { (yyval.strval)=0; pkg_free((yyvsp[(1) - (3)].strval));
								yyerror("invalid host or interface name"); }
    break;

  case 535:
/* Line 1792 of yacc.c  */
#line 2272 "cfg.y"
    {
		/* check if allowed */
		if ((yyvsp[(1) - (1)].action) && rt==ONSEND_ROUTE) {
			switch((yyvsp[(1) - (1)].action)->type) {
				case DROP_T:
				case LOG_T:
				case SETFLAG_T:
				case RESETFLAG_T:
				case ISFLAGSET_T:
				case IF_T:
				case MODULE0_T:
				case MODULE1_T:
				case MODULE2_T:
				case MODULE3_T:
				case MODULE4_T:
				case MODULE5_T:
				case MODULE6_T:
				case MODULEX_T:
				case SET_FWD_NO_CONNECT_T:
				case SET_RPL_NO_CONNECT_T:
				case SET_FWD_CLOSE_T:
				case SET_RPL_CLOSE_T:
					(yyval.action)=(yyvsp[(1) - (1)].action);
					break;
				default:
					(yyval.action)=0;
					yyerror("command not allowed in onsend_route\n");
			}
		} else {
			(yyval.action)=(yyvsp[(1) - (1)].action);
		}
	}
    break;

  case 536:
/* Line 1792 of yacc.c  */
#line 2314 "cfg.y"
    { (yyval.action)=(yyvsp[(1) - (1)].action); }
    break;

  case 537:
/* Line 1792 of yacc.c  */
#line 2315 "cfg.y"
    { (yyval.action)=(yyvsp[(2) - (3)].action); }
    break;

  case 538:
/* Line 1792 of yacc.c  */
#line 2318 "cfg.y"
    {(yyval.action)=append_action((yyvsp[(1) - (2)].action), (yyvsp[(2) - (2)].action)); }
    break;

  case 539:
/* Line 1792 of yacc.c  */
#line 2319 "cfg.y"
    {(yyval.action)=(yyvsp[(1) - (1)].action);}
    break;

  case 540:
/* Line 1792 of yacc.c  */
#line 2320 "cfg.y"
    { (yyval.action)=0; yyerror("bad command"); }
    break;

  case 541:
/* Line 1792 of yacc.c  */
#line 2323 "cfg.y"
    {(yyval.action)=(yyvsp[(1) - (2)].action);}
    break;

  case 542:
/* Line 1792 of yacc.c  */
#line 2324 "cfg.y"
    {(yyval.action)=(yyvsp[(1) - (1)].action);}
    break;

  case 543:
/* Line 1792 of yacc.c  */
#line 2325 "cfg.y"
    {(yyval.action)=(yyvsp[(1) - (1)].action);}
    break;

  case 544:
/* Line 1792 of yacc.c  */
#line 2326 "cfg.y"
    { (yyval.action)=(yyvsp[(1) - (1)].action); }
    break;

  case 545:
/* Line 1792 of yacc.c  */
#line 2327 "cfg.y"
    { (yyval.action)=(yyvsp[(1) - (2)].action); }
    break;

  case 546:
/* Line 1792 of yacc.c  */
#line 2328 "cfg.y"
    {(yyval.action)=(yyvsp[(1) - (2)].action);}
    break;

  case 547:
/* Line 1792 of yacc.c  */
#line 2329 "cfg.y"
    {(yyval.action)=0;}
    break;

  case 548:
/* Line 1792 of yacc.c  */
#line 2330 "cfg.y"
    { (yyval.action)=0; yyerror("bad command: missing ';'?"); }
    break;

  case 549:
/* Line 1792 of yacc.c  */
#line 2333 "cfg.y"
    {
		if ((yyvsp[(2) - (3)].rv_expr) && rval_expr_int_check((yyvsp[(2) - (3)].rv_expr))>=0){
			warn_ct_rve((yyvsp[(2) - (3)].rv_expr), "if");
			(yyval.action)=mk_action( IF_T, 3, RVE_ST, (yyvsp[(2) - (3)].rv_expr), ACTIONS_ST, (yyvsp[(3) - (3)].action), NOSUBTYPE, 0);
			set_cfg_pos((yyval.action));
		}else
			YYERROR;
	}
    break;

  case 550:
/* Line 1792 of yacc.c  */
#line 2341 "cfg.y"
    { 
		if ((yyvsp[(2) - (5)].rv_expr) && rval_expr_int_check((yyvsp[(2) - (5)].rv_expr))>=0){
			warn_ct_rve((yyvsp[(2) - (5)].rv_expr), "if");
			(yyval.action)=mk_action( IF_T, 3, RVE_ST, (yyvsp[(2) - (5)].rv_expr), ACTIONS_ST, (yyvsp[(3) - (5)].action), ACTIONS_ST, (yyvsp[(5) - (5)].action));
			set_cfg_pos((yyval.action));
		}else
			YYERROR;
	}
    break;

  case 551:
/* Line 1792 of yacc.c  */
#line 2351 "cfg.y"
    {
			(yyval.rv_expr)=0;
			if ((yyvsp[(1) - (1)].rv_expr) && !rve_is_constant((yyvsp[(1) - (1)].rv_expr))){
				yyerror("constant expected");
				YYERROR;
			/*
			} else if ($1 &&
						!rve_check_type((enum rval_type*)&i_tmp, $1, 0, 0 ,0)){
				yyerror("invalid expression (bad type)");
			}else if ($1 && i_tmp!=RV_INT){
				yyerror("invalid expression type, int expected\n");
			*/
			}else
				(yyval.rv_expr)=(yyvsp[(1) - (1)].rv_expr);
		}
    break;

  case 552:
/* Line 1792 of yacc.c  */
#line 2368 "cfg.y"
    {
		(yyval.case_stms)=0;
		if ((yyvsp[(2) - (4)].rv_expr)==0) { yyerror ("bad case label"); YYERROR; }
		else if ((((yyval.case_stms)=mk_case_stm((yyvsp[(2) - (4)].rv_expr), 0, (yyvsp[(4) - (4)].action), &i_tmp))==0) && (i_tmp==-10)){
				YYABORT;
		}
	}
    break;

  case 553:
/* Line 1792 of yacc.c  */
#line 2375 "cfg.y"
    {
		(yyval.case_stms)=0;
		if ((yyvsp[(3) - (5)].rv_expr)==0) { yyerror ("bad case label"); YYERROR; }
		else if ((((yyval.case_stms)=mk_case_stm((yyvsp[(3) - (5)].rv_expr), 1, (yyvsp[(5) - (5)].action), &i_tmp))==0) && (i_tmp==-10)){
				YYABORT;
		}
	}
    break;

  case 554:
/* Line 1792 of yacc.c  */
#line 2382 "cfg.y"
    {
		(yyval.case_stms)=0;
		if ((yyvsp[(2) - (3)].rv_expr)==0) { yyerror ("bad case label"); YYERROR; }
		else if ((((yyval.case_stms)=mk_case_stm((yyvsp[(2) - (3)].rv_expr), 0, 0, &i_tmp))==0) && (i_tmp==-10)){
				YYABORT;
		}
	}
    break;

  case 555:
/* Line 1792 of yacc.c  */
#line 2389 "cfg.y"
    {
		(yyval.case_stms)=0;
		if ((yyvsp[(3) - (4)].rv_expr)==0) { yyerror ("bad regex case label"); YYERROR; }
		else if ((((yyval.case_stms)=mk_case_stm((yyvsp[(3) - (4)].rv_expr), 1, 0, &i_tmp))==0) && (i_tmp==-10)){
				YYABORT;
		}
	}
    break;

  case 556:
/* Line 1792 of yacc.c  */
#line 2396 "cfg.y"
    {
		if ((((yyval.case_stms)=mk_case_stm(0, 0, (yyvsp[(3) - (3)].action), &i_tmp))==0) && (i_tmp=-10)){
				YYABORT;
		}
	}
    break;

  case 557:
/* Line 1792 of yacc.c  */
#line 2401 "cfg.y"
    {
		if ((((yyval.case_stms)=mk_case_stm(0, 0, 0, &i_tmp))==0) && (i_tmp==-10)){
				YYABORT;
		}
	}
    break;

  case 558:
/* Line 1792 of yacc.c  */
#line 2406 "cfg.y"
    { (yyval.case_stms)=0; yyerror("bad case label"); }
    break;

  case 559:
/* Line 1792 of yacc.c  */
#line 2407 "cfg.y"
    { (yyval.case_stms)=0; yyerror("bad case regex label"); }
    break;

  case 560:
/* Line 1792 of yacc.c  */
#line 2408 "cfg.y"
    { (yyval.case_stms)=0; yyerror("bad case label"); }
    break;

  case 561:
/* Line 1792 of yacc.c  */
#line 2409 "cfg.y"
    { (yyval.case_stms)=0; yyerror("bad case regex label"); }
    break;

  case 562:
/* Line 1792 of yacc.c  */
#line 2410 "cfg.y"
    { (yyval.case_stms)=0; yyerror ("bad case body"); }
    break;

  case 563:
/* Line 1792 of yacc.c  */
#line 2413 "cfg.y"
    {
		(yyval.case_stms)=(yyvsp[(1) - (2)].case_stms);
		if ((yyvsp[(2) - (2)].case_stms)==0) yyerror ("bad case");
		if ((yyval.case_stms)){
			*((yyval.case_stms)->append)=(yyvsp[(2) - (2)].case_stms);
			if (*((yyval.case_stms)->append)!=0)
				(yyval.case_stms)->append=&((*((yyval.case_stms)->append))->next);
		}
	}
    break;

  case 564:
/* Line 1792 of yacc.c  */
#line 2422 "cfg.y"
    {
		(yyval.case_stms)=(yyvsp[(1) - (1)].case_stms);
		if ((yyvsp[(1) - (1)].case_stms)==0) yyerror ("bad case");
		else (yyval.case_stms)->append=&((yyval.case_stms)->next);
	}
    break;

  case 565:
/* Line 1792 of yacc.c  */
#line 2429 "cfg.y"
    { 
		(yyval.action)=0;
		if ((yyvsp[(2) - (5)].rv_expr)==0){
			yyerror("bad expression in switch(...)");
			YYERROR;
		}else if ((yyvsp[(4) - (5)].case_stms)==0){
			yyerror ("bad switch body");
			YYERROR;
		}else if (case_check_default((yyvsp[(4) - (5)].case_stms))!=0){
			yyerror_at(&(yyvsp[(2) - (5)].rv_expr)->fpos, "bad switch(): too many "
							"\"default:\" labels\n");
			YYERROR;
		}else if (case_check_type((yyvsp[(4) - (5)].case_stms))!=0){
			yyerror_at(&(yyvsp[(2) - (5)].rv_expr)->fpos, "bad switch(): mixed integer and"
							" string/RE cases not allowed\n");
			YYERROR;
		}else{
			(yyval.action)=mk_action(SWITCH_T, 2, RVE_ST, (yyvsp[(2) - (5)].rv_expr), CASE_ST, (yyvsp[(4) - (5)].case_stms));
			if ((yyval.action)==0) {
				yyerror("internal error");
				YYABORT;
			}
			set_cfg_pos((yyval.action));
		}
	}
    break;

  case 566:
/* Line 1792 of yacc.c  */
#line 2454 "cfg.y"
    {
		(yyval.action)=0;
		warn("empty switch()");
		if ((yyvsp[(2) - (4)].rv_expr)==0){
			yyerror("bad expression in switch(...)");
			YYERROR;
		}else{
			/* it might have sideffects, so leave it for the optimizer */
			(yyval.action)=mk_action(SWITCH_T, 2, RVE_ST, (yyvsp[(2) - (4)].rv_expr), CASE_ST, 0);
			if ((yyval.action)==0) {
				yyerror("internal error");
				YYABORT;
			}
			set_cfg_pos((yyval.action));
		}
	}
    break;

  case 567:
/* Line 1792 of yacc.c  */
#line 2470 "cfg.y"
    { (yyval.action)=0; yyerror ("bad expression in switch(...)"); }
    break;

  case 568:
/* Line 1792 of yacc.c  */
#line 2472 "cfg.y"
    {(yyval.action)=0; yyerror ("bad switch body"); }
    break;

  case 569:
/* Line 1792 of yacc.c  */
#line 2476 "cfg.y"
    {
		if ((yyvsp[(2) - (3)].rv_expr) && rval_expr_int_check((yyvsp[(2) - (3)].rv_expr))>=0){
			warn_ct_rve((yyvsp[(2) - (3)].rv_expr), "while");
			(yyval.action)=mk_action( WHILE_T, 2, RVE_ST, (yyvsp[(2) - (3)].rv_expr), ACTIONS_ST, (yyvsp[(3) - (3)].action));
			set_cfg_pos((yyval.action));
		}else{
			yyerror_at(&(yyvsp[(2) - (3)].rv_expr)->fpos, "bad while(...) expression");
			YYERROR;
		}
	}
    break;

  case 570:
/* Line 1792 of yacc.c  */
#line 2495 "cfg.y"
    {
		if (sel.n >= MAX_SELECT_PARAMS-1) {
			yyerror("Select identifier too long\n");
		}
		sel.params[sel.n].type = SEL_PARAM_STR;
		sel.params[sel.n].v.s.s = (yyvsp[(1) - (1)].strval);
		sel.params[sel.n].v.s.len = strlen((yyvsp[(1) - (1)].strval));
		sel.n++;
	}
    break;

  case 571:
/* Line 1792 of yacc.c  */
#line 2504 "cfg.y"
    {
		if (sel.n >= MAX_SELECT_PARAMS-2) {
			yyerror("Select identifier too long\n");
		}
		sel.params[sel.n].type = SEL_PARAM_STR;
		sel.params[sel.n].v.s.s = (yyvsp[(1) - (4)].strval);
		sel.params[sel.n].v.s.len = strlen((yyvsp[(1) - (4)].strval));
		sel.n++;
		sel.params[sel.n].type = SEL_PARAM_INT;
		sel.params[sel.n].v.i = (yyvsp[(3) - (4)].intval);
		sel.n++;
	}
    break;

  case 572:
/* Line 1792 of yacc.c  */
#line 2516 "cfg.y"
    {
		if (sel.n >= MAX_SELECT_PARAMS-2) {
			yyerror("Select identifier too long\n");
		}
		sel.params[sel.n].type = SEL_PARAM_STR;
		sel.params[sel.n].v.s.s = (yyvsp[(1) - (4)].strval);
		sel.params[sel.n].v.s.len = strlen((yyvsp[(1) - (4)].strval));
		sel.n++;
		sel.params[sel.n].type = SEL_PARAM_STR;
		sel.params[sel.n].v.s.s = (yyvsp[(3) - (4)].strval);
		sel.params[sel.n].v.s.len = strlen((yyvsp[(3) - (4)].strval));
		sel.n++;
	}
    break;

  case 575:
/* Line 1792 of yacc.c  */
#line 2535 "cfg.y"
    { sel.n = 0; sel.f[0] = 0; }
    break;

  case 576:
/* Line 1792 of yacc.c  */
#line 2535 "cfg.y"
    {
		sel_ptr = (select_t*)pkg_malloc(sizeof(select_t));
		if (!sel_ptr) {
			yyerror("No memory left to allocate select structure\n");
		}
		memcpy(sel_ptr, &sel, sizeof(select_t));
		(yyval.select) = sel_ptr;
	}
    break;

  case 577:
/* Line 1792 of yacc.c  */
#line 2545 "cfg.y"
    { s_attr->type |= AVP_TRACK_FROM; }
    break;

  case 578:
/* Line 1792 of yacc.c  */
#line 2546 "cfg.y"
    { s_attr->type |= AVP_TRACK_TO; }
    break;

  case 579:
/* Line 1792 of yacc.c  */
#line 2547 "cfg.y"
    { s_attr->type |= AVP_TRACK_FROM | AVP_CLASS_URI; }
    break;

  case 580:
/* Line 1792 of yacc.c  */
#line 2548 "cfg.y"
    { s_attr->type |= AVP_TRACK_TO | AVP_CLASS_URI; }
    break;

  case 581:
/* Line 1792 of yacc.c  */
#line 2549 "cfg.y"
    { s_attr->type |= AVP_TRACK_FROM | AVP_CLASS_USER; }
    break;

  case 582:
/* Line 1792 of yacc.c  */
#line 2550 "cfg.y"
    { s_attr->type |= AVP_TRACK_TO | AVP_CLASS_USER; }
    break;

  case 583:
/* Line 1792 of yacc.c  */
#line 2551 "cfg.y"
    { s_attr->type |= AVP_TRACK_FROM | AVP_CLASS_DOMAIN; }
    break;

  case 584:
/* Line 1792 of yacc.c  */
#line 2552 "cfg.y"
    { s_attr->type |= AVP_TRACK_TO | AVP_CLASS_DOMAIN; }
    break;

  case 585:
/* Line 1792 of yacc.c  */
#line 2553 "cfg.y"
    { s_attr->type |= AVP_TRACK_ALL | AVP_CLASS_GLOBAL; }
    break;

  case 586:
/* Line 1792 of yacc.c  */
#line 2556 "cfg.y"
    { s_attr->type |= AVP_NAME_STR; s_attr->name.s.s = (yyvsp[(1) - (1)].strval); s_attr->name.s.len = strlen ((yyvsp[(1) - (1)].strval)); }
    break;

  case 589:
/* Line 1792 of yacc.c  */
#line 2563 "cfg.y"
    {
		s_attr = (struct avp_spec*)pkg_malloc(sizeof(struct avp_spec));
		if (!s_attr) { yyerror("No memory left"); YYABORT; }
		else s_attr->type = 0;
	}
    break;

  case 590:
/* Line 1792 of yacc.c  */
#line 2570 "cfg.y"
    { (yyval.attr) = s_attr; }
    break;

  case 591:
/* Line 1792 of yacc.c  */
#line 2573 "cfg.y"
    {
		s_attr->type|= (AVP_NAME_STR | ((yyvsp[(4) - (5)].intval)<0?AVP_INDEX_BACKWARD:AVP_INDEX_FORWARD));
		s_attr->index = ((yyvsp[(4) - (5)].intval)<0?-(yyvsp[(4) - (5)].intval):(yyvsp[(4) - (5)].intval));
		(yyval.attr) = s_attr;
	}
    break;

  case 592:
/* Line 1792 of yacc.c  */
#line 2580 "cfg.y"
    {
		s_attr->type|= AVP_INDEX_ALL;
		(yyval.attr) = s_attr;
	}
    break;

  case 599:
/* Line 1792 of yacc.c  */
#line 2602 "cfg.y"
    {
		if ((yyvsp[(1) - (1)].lval)->type==LV_AVP){
			s_attr = pkg_malloc(sizeof(struct avp_spec));
			if (!s_attr) { yyerror("No memory left"); YYABORT; }
			else{
				*s_attr=(yyvsp[(1) - (1)].lval)->lv.avps;
			}
			(yyval.attr)=s_attr;
		}else
			(yyval.attr)=0; /* not an avp, a pvar */
		pkg_free((yyvsp[(1) - (1)].lval));
	}
    break;

  case 600:
/* Line 1792 of yacc.c  */
#line 2614 "cfg.y"
    {
		avp_spec_t *avp_spec;
		str s;
		int type, idx;
		avp_spec = pkg_malloc(sizeof(*avp_spec));
		if (!avp_spec) {
			yyerror("Not enough memory");
			YYABORT;
		}
		s.s = (yyvsp[(1) - (1)].strval);
		if (s.s[0] == '$')
			s.s++;
		s.len = strlen(s.s);
		if (parse_avp_name(&s, &type, &avp_spec->name, &idx)) {
			yyerror("error when parsing AVP");
			pkg_free(avp_spec);
			YYABORT;
		}
		avp_spec->type = type;
		avp_spec->index = idx;
		(yyval.attr) = avp_spec;
	}
    break;

  case 601:
/* Line 1792 of yacc.c  */
#line 2638 "cfg.y"
    {
			s_tmp.s=(yyvsp[(1) - (1)].strval); s_tmp.len=strlen((yyvsp[(1) - (1)].strval));
			pv_spec=pv_cache_get(&s_tmp);
			if (!pv_spec) {
				yyerror("Can't get from cache: %s", (yyvsp[(1) - (1)].strval));
				YYABORT;
			}
			(yyval.pvar)=pv_spec;
		}
    break;

  case 602:
/* Line 1792 of yacc.c  */
#line 2649 "cfg.y"
    {
				lval_tmp=pkg_malloc(sizeof(*lval_tmp));
				if (!lval_tmp) {
					yyerror("Not enough memory");
					YYABORT;
				}
				memset(lval_tmp, 0, sizeof(*lval_tmp));
				s_tmp.s=(yyvsp[(1) - (1)].strval); s_tmp.len=strlen(s_tmp.s);
				lval_tmp->lv.pvs = pv_cache_get(&s_tmp);
				if (lval_tmp->lv.pvs==NULL){
					lval_tmp->lv.avps.type|= AVP_NAME_STR;
					lval_tmp->lv.avps.name.s.s = s_tmp.s+1;
					lval_tmp->lv.avps.name.s.len = s_tmp.len-1;
					lval_tmp->type=LV_AVP;
				}else{
					lval_tmp->type=LV_PVAR;
				}
				(yyval.lval) = lval_tmp;
				DBG("parsed ambigous avp/pvar \"%.*s\" to %d\n",
							s_tmp.len, s_tmp.s, lval_tmp->type);
			}
    break;

  case 603:
/* Line 1792 of yacc.c  */
#line 2680 "cfg.y"
    { (yyval.intval) = ASSIGN_T; }
    break;

  case 604:
/* Line 1792 of yacc.c  */
#line 2684 "cfg.y"
    {
					lval_tmp=pkg_malloc(sizeof(*lval_tmp));
					if (!lval_tmp) {
						yyerror("Not enough memory");
						YYABORT;
					}
					lval_tmp->type=LV_AVP; lval_tmp->lv.avps=*(yyvsp[(1) - (1)].attr);
					pkg_free((yyvsp[(1) - (1)].attr)); /* free the avp spec we just copied */
					(yyval.lval)=lval_tmp;
				}
    break;

  case 605:
/* Line 1792 of yacc.c  */
#line 2694 "cfg.y"
    {
					if (!pv_is_w((yyvsp[(1) - (1)].pvar)))
						yyerror("read only pvar in assignment left side");
					if ((yyvsp[(1) - (1)].pvar)->trans!=0)
						yyerror("pvar with transformations in assignment"
								" left side");
					lval_tmp=pkg_malloc(sizeof(*lval_tmp));
					if (!lval_tmp) {
						yyerror("Not enough memory");
						YYABORT;
					}
					lval_tmp->type=LV_PVAR; lval_tmp->lv.pvs=(yyvsp[(1) - (1)].pvar);
					(yyval.lval)=lval_tmp;
				}
    break;

  case 606:
/* Line 1792 of yacc.c  */
#line 2708 "cfg.y"
    {
					if (((yyvsp[(1) - (1)].lval))->type==LV_PVAR){
						if (!pv_is_w((yyvsp[(1) - (1)].lval)->lv.pvs))
							yyerror("read only pvar in assignment left side");
						if ((yyvsp[(1) - (1)].lval)->lv.pvs->trans!=0)
							yyerror("pvar with transformations in assignment"
									" left side");
					}
					(yyval.lval)=(yyvsp[(1) - (1)].lval);
				}
    break;

  case 607:
/* Line 1792 of yacc.c  */
#line 2720 "cfg.y"
    {(yyval.rv_expr)=mk_rve_rval(RV_INT, (void*)(yyvsp[(1) - (1)].intval)); }
    break;

  case 608:
/* Line 1792 of yacc.c  */
#line 2721 "cfg.y"
    {	s_tmp.s=(yyvsp[(1) - (1)].strval); s_tmp.len=strlen((yyvsp[(1) - (1)].strval));
							(yyval.rv_expr)=mk_rve_rval(RV_STR, &s_tmp); }
    break;

  case 609:
/* Line 1792 of yacc.c  */
#line 2723 "cfg.y"
    {(yyval.rv_expr)=mk_rve_rval(RV_AVP, (yyvsp[(1) - (1)].attr)); pkg_free((yyvsp[(1) - (1)].attr)); }
    break;

  case 610:
/* Line 1792 of yacc.c  */
#line 2724 "cfg.y"
    {(yyval.rv_expr)=mk_rve_rval(RV_PVAR, (yyvsp[(1) - (1)].pvar)); }
    break;

  case 611:
/* Line 1792 of yacc.c  */
#line 2725 "cfg.y"
    {
							switch((yyvsp[(1) - (1)].lval)->type){
								case LV_AVP:
									(yyval.rv_expr)=mk_rve_rval(RV_AVP, &(yyvsp[(1) - (1)].lval)->lv.avps);
									break;
								case LV_PVAR:
									(yyval.rv_expr)=mk_rve_rval(RV_PVAR, (yyvsp[(1) - (1)].lval)->lv.pvs);
									break;
								default:
									yyerror("BUG: invalid lvalue type ");
									YYABORT;
							}
							pkg_free((yyvsp[(1) - (1)].lval)); /* not needed anymore */
						}
    break;

  case 612:
/* Line 1792 of yacc.c  */
#line 2739 "cfg.y"
    {(yyval.rv_expr)=mk_rve_rval(RV_SEL, (yyvsp[(1) - (1)].select)); pkg_free((yyvsp[(1) - (1)].select)); }
    break;

  case 613:
/* Line 1792 of yacc.c  */
#line 2740 "cfg.y"
    {(yyval.rv_expr)=mk_rve_rval(RV_ACTION_ST, (yyvsp[(1) - (1)].action)); }
    break;

  case 614:
/* Line 1792 of yacc.c  */
#line 2741 "cfg.y"
    { (yyval.rv_expr)=mk_rve_rval(RV_BEXPR, (yyvsp[(1) - (1)].expr)); }
    break;

  case 615:
/* Line 1792 of yacc.c  */
#line 2742 "cfg.y"
    {(yyval.rv_expr)=mk_rve_rval(RV_ACTION_ST, (yyvsp[(2) - (3)].action)); }
    break;

  case 616:
/* Line 1792 of yacc.c  */
#line 2743 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad command block"); }
    break;

  case 617:
/* Line 1792 of yacc.c  */
#line 2744 "cfg.y"
    {(yyval.rv_expr)=mk_rve_rval(RV_ACTION_ST, (yyvsp[(2) - (3)].action)); }
    break;

  case 618:
/* Line 1792 of yacc.c  */
#line 2745 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 619:
/* Line 1792 of yacc.c  */
#line 2749 "cfg.y"
    { (yyval.intval)=RVE_LNOT_OP; }
    break;

  case 620:
/* Line 1792 of yacc.c  */
#line 2750 "cfg.y"
    { (yyval.intval)=RVE_BNOT_OP; }
    break;

  case 621:
/* Line 1792 of yacc.c  */
#line 2751 "cfg.y"
    { (yyval.intval)=RVE_UMINUS_OP; }
    break;

  case 622:
/* Line 1792 of yacc.c  */
#line 2764 "cfg.y"
    { (yyval.rv_expr)=(yyvsp[(1) - (1)].rv_expr);
										if ((yyval.rv_expr)==0){
											/*yyerror("out of memory\n");*/
											YYERROR;
										}
									}
    break;

  case 623:
/* Line 1792 of yacc.c  */
#line 2770 "cfg.y"
    {(yyval.rv_expr)=mk_rve1((yyvsp[(1) - (2)].intval), (yyvsp[(2) - (2)].rv_expr)); }
    break;

  case 624:
/* Line 1792 of yacc.c  */
#line 2771 "cfg.y"
    {(yyval.rv_expr)=mk_rve1(RVE_INT_OP, (yyvsp[(2) - (2)].rv_expr)); }
    break;

  case 625:
/* Line 1792 of yacc.c  */
#line 2772 "cfg.y"
    {(yyval.rv_expr)=mk_rve1(RVE_STR_OP, (yyvsp[(2) - (2)].rv_expr)); }
    break;

  case 626:
/* Line 1792 of yacc.c  */
#line 2773 "cfg.y"
    {(yyval.rv_expr)=mk_rve2(RVE_PLUS_OP, (yyvsp[(1) - (3)].rv_expr), (yyvsp[(3) - (3)].rv_expr)); }
    break;

  case 627:
/* Line 1792 of yacc.c  */
#line 2774 "cfg.y"
    {(yyval.rv_expr)=mk_rve2(RVE_MINUS_OP, (yyvsp[(1) - (3)].rv_expr), (yyvsp[(3) - (3)].rv_expr)); }
    break;

  case 628:
/* Line 1792 of yacc.c  */
#line 2775 "cfg.y"
    {(yyval.rv_expr)=mk_rve2(RVE_MUL_OP, (yyvsp[(1) - (3)].rv_expr), (yyvsp[(3) - (3)].rv_expr)); }
    break;

  case 629:
/* Line 1792 of yacc.c  */
#line 2776 "cfg.y"
    {(yyval.rv_expr)=mk_rve2(RVE_DIV_OP, (yyvsp[(1) - (3)].rv_expr), (yyvsp[(3) - (3)].rv_expr)); }
    break;

  case 630:
/* Line 1792 of yacc.c  */
#line 2777 "cfg.y"
    {(yyval.rv_expr)=mk_rve2(RVE_MOD_OP, (yyvsp[(1) - (3)].rv_expr), (yyvsp[(3) - (3)].rv_expr)); }
    break;

  case 631:
/* Line 1792 of yacc.c  */
#line 2778 "cfg.y"
    {(yyval.rv_expr)=mk_rve2(RVE_BOR_OP, (yyvsp[(1) - (3)].rv_expr),  (yyvsp[(3) - (3)].rv_expr)); }
    break;

  case 632:
/* Line 1792 of yacc.c  */
#line 2779 "cfg.y"
    {(yyval.rv_expr)=mk_rve2(RVE_BAND_OP, (yyvsp[(1) - (3)].rv_expr),  (yyvsp[(3) - (3)].rv_expr));}
    break;

  case 633:
/* Line 1792 of yacc.c  */
#line 2780 "cfg.y"
    {(yyval.rv_expr)=mk_rve2(RVE_BXOR_OP, (yyvsp[(1) - (3)].rv_expr),  (yyvsp[(3) - (3)].rv_expr));}
    break;

  case 634:
/* Line 1792 of yacc.c  */
#line 2781 "cfg.y"
    {(yyval.rv_expr)=mk_rve2(RVE_BLSHIFT_OP, (yyvsp[(1) - (3)].rv_expr),  (yyvsp[(3) - (3)].rv_expr));}
    break;

  case 635:
/* Line 1792 of yacc.c  */
#line 2782 "cfg.y"
    {(yyval.rv_expr)=mk_rve2(RVE_BRSHIFT_OP, (yyvsp[(1) - (3)].rv_expr),  (yyvsp[(3) - (3)].rv_expr));}
    break;

  case 636:
/* Line 1792 of yacc.c  */
#line 2783 "cfg.y"
    { (yyval.rv_expr)=mk_rve2( (yyvsp[(2) - (3)].intval), (yyvsp[(1) - (3)].rv_expr), (yyvsp[(3) - (3)].rv_expr));}
    break;

  case 637:
/* Line 1792 of yacc.c  */
#line 2784 "cfg.y"
    {
			/* comparing with $null => treat as defined or !defined */
			if((yyvsp[(3) - (3)].rv_expr)->op==RVE_RVAL_OP && (yyvsp[(3) - (3)].rv_expr)->left.rval.type==RV_PVAR
					&& (yyvsp[(3) - (3)].rv_expr)->left.rval.v.pvs.type==PVT_NULL) {
				if((yyvsp[(2) - (3)].intval)==RVE_DIFF_OP || (yyvsp[(2) - (3)].intval)==RVE_IDIFF_OP
						|| (yyvsp[(2) - (3)].intval)==RVE_STRDIFF_OP) {
					DBG("comparison with $null switched to notdefined operator\n");
					(yyval.rv_expr)=mk_rve1(RVE_DEFINED_OP, (yyvsp[(1) - (3)].rv_expr));
				} else {
					DBG("comparison with $null switched to defined operator\n");
					(yyval.rv_expr)=mk_rve1(RVE_NOTDEFINED_OP, (yyvsp[(1) - (3)].rv_expr));
				}
				/* free rve struct for $null */
				rve_destroy((yyvsp[(3) - (3)].rv_expr));
			} else {
				(yyval.rv_expr)=mk_rve2((yyvsp[(2) - (3)].intval), (yyvsp[(1) - (3)].rv_expr), (yyvsp[(3) - (3)].rv_expr));
			}
		}
    break;

  case 638:
/* Line 1792 of yacc.c  */
#line 2802 "cfg.y"
    { (yyval.rv_expr)=mk_rve2(RVE_LAND_OP, (yyvsp[(1) - (3)].rv_expr), (yyvsp[(3) - (3)].rv_expr));}
    break;

  case 639:
/* Line 1792 of yacc.c  */
#line 2803 "cfg.y"
    { (yyval.rv_expr)=mk_rve2(RVE_LOR_OP, (yyvsp[(1) - (3)].rv_expr), (yyvsp[(3) - (3)].rv_expr));}
    break;

  case 640:
/* Line 1792 of yacc.c  */
#line 2804 "cfg.y"
    { (yyval.rv_expr)=(yyvsp[(2) - (3)].rv_expr);}
    break;

  case 641:
/* Line 1792 of yacc.c  */
#line 2805 "cfg.y"
    { (yyval.rv_expr)=mk_rve1(RVE_STRLEN_OP, (yyvsp[(3) - (4)].rv_expr));}
    break;

  case 642:
/* Line 1792 of yacc.c  */
#line 2806 "cfg.y"
    {(yyval.rv_expr)=mk_rve1(RVE_STREMPTY_OP, (yyvsp[(3) - (4)].rv_expr));}
    break;

  case 643:
/* Line 1792 of yacc.c  */
#line 2807 "cfg.y"
    { (yyval.rv_expr)=mk_rve1(RVE_DEFINED_OP, (yyvsp[(2) - (2)].rv_expr));}
    break;

  case 644:
/* Line 1792 of yacc.c  */
#line 2808 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 645:
/* Line 1792 of yacc.c  */
#line 2809 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 646:
/* Line 1792 of yacc.c  */
#line 2810 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 647:
/* Line 1792 of yacc.c  */
#line 2811 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 648:
/* Line 1792 of yacc.c  */
#line 2812 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 649:
/* Line 1792 of yacc.c  */
#line 2813 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 650:
/* Line 1792 of yacc.c  */
#line 2814 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 651:
/* Line 1792 of yacc.c  */
#line 2815 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 652:
/* Line 1792 of yacc.c  */
#line 2816 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 653:
/* Line 1792 of yacc.c  */
#line 2817 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 654:
/* Line 1792 of yacc.c  */
#line 2819 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 655:
/* Line 1792 of yacc.c  */
#line 2821 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 656:
/* Line 1792 of yacc.c  */
#line 2822 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 657:
/* Line 1792 of yacc.c  */
#line 2823 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 658:
/* Line 1792 of yacc.c  */
#line 2824 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 659:
/* Line 1792 of yacc.c  */
#line 2825 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 660:
/* Line 1792 of yacc.c  */
#line 2826 "cfg.y"
    { (yyval.rv_expr)=0; yyerror("bad expression"); }
    break;

  case 661:
/* Line 1792 of yacc.c  */
#line 2829 "cfg.y"
    { (yyval.action)=mk_action((yyvsp[(2) - (3)].intval), 2, LVAL_ST, (yyvsp[(1) - (3)].lval), 
														 	  RVE_ST, (yyvsp[(3) - (3)].rv_expr));
											set_cfg_pos((yyval.action));
										}
    break;

  case 662:
/* Line 1792 of yacc.c  */
#line 2847 "cfg.y"
    { (yyval.intval) = 1; }
    break;

  case 663:
/* Line 1792 of yacc.c  */
#line 2848 "cfg.y"
    { (yyval.intval) = 0; }
    break;

  case 664:
/* Line 1792 of yacc.c  */
#line 2849 "cfg.y"
    { (yyval.intval) = -1; }
    break;

  case 665:
/* Line 1792 of yacc.c  */
#line 2852 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_T, 2, URIHOST_ST, 0, URIPORT_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 666:
/* Line 1792 of yacc.c  */
#line 2853 "cfg.y"
    { (yyval.action)=mk_action(	FORWARD_T, 2, STRING_ST, (yyvsp[(3) - (4)].strval), NUMBER_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 667:
/* Line 1792 of yacc.c  */
#line 2854 "cfg.y"
    { (yyval.action)=mk_action(	FORWARD_T, 2, STRING_ST, (yyvsp[(3) - (4)].strval), NUMBER_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 668:
/* Line 1792 of yacc.c  */
#line 2855 "cfg.y"
    { (yyval.action)=mk_action(	FORWARD_T, 2, IP_ST, (void*)(yyvsp[(3) - (4)].ipaddr), NUMBER_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 669:
/* Line 1792 of yacc.c  */
#line 2856 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 670:
/* Line 1792 of yacc.c  */
#line 2857 "cfg.y"
    {(yyval.action)=mk_action(FORWARD_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 671:
/* Line 1792 of yacc.c  */
#line 2858 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_T, 2, IP_ST, (void*)(yyvsp[(3) - (6)].ipaddr), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 672:
/* Line 1792 of yacc.c  */
#line 2859 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_T, 2, URIHOST_ST, 0, URIPORT_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 673:
/* Line 1792 of yacc.c  */
#line 2860 "cfg.y"
    {(yyval.action)=mk_action(FORWARD_T, 2, URIHOST_ST, 0, NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 674:
/* Line 1792 of yacc.c  */
#line 2861 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_T, 2, URIHOST_ST, 0, NUMBER_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 675:
/* Line 1792 of yacc.c  */
#line 2862 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 676:
/* Line 1792 of yacc.c  */
#line 2863 "cfg.y"
    { (yyval.action)=0; yyerror("bad forward argument"); }
    break;

  case 677:
/* Line 1792 of yacc.c  */
#line 2864 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_UDP_T, 2, STRING_ST, (yyvsp[(3) - (4)].strval), NUMBER_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 678:
/* Line 1792 of yacc.c  */
#line 2865 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_UDP_T, 2, STRING_ST, (yyvsp[(3) - (4)].strval), NUMBER_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 679:
/* Line 1792 of yacc.c  */
#line 2866 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_UDP_T, 2, IP_ST, (void*)(yyvsp[(3) - (4)].ipaddr), NUMBER_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 680:
/* Line 1792 of yacc.c  */
#line 2867 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_UDP_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 681:
/* Line 1792 of yacc.c  */
#line 2868 "cfg.y"
    {(yyval.action)=mk_action(FORWARD_UDP_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 682:
/* Line 1792 of yacc.c  */
#line 2869 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_UDP_T, 2, IP_ST, (void*)(yyvsp[(3) - (6)].ipaddr), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 683:
/* Line 1792 of yacc.c  */
#line 2870 "cfg.y"
    {(yyval.action)=mk_action(FORWARD_UDP_T, 2, URIHOST_ST, 0, URIPORT_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 684:
/* Line 1792 of yacc.c  */
#line 2871 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_UDP_T, 2, URIHOST_ST, 0, NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 685:
/* Line 1792 of yacc.c  */
#line 2872 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_UDP_T, 2, URIHOST_ST, 0, NUMBER_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 686:
/* Line 1792 of yacc.c  */
#line 2873 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 687:
/* Line 1792 of yacc.c  */
#line 2874 "cfg.y"
    { (yyval.action)=0; yyerror("bad forward_udp argument"); }
    break;

  case 688:
/* Line 1792 of yacc.c  */
#line 2875 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_TCP_T, 2, STRING_ST, (yyvsp[(3) - (4)].strval), NUMBER_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 689:
/* Line 1792 of yacc.c  */
#line 2876 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_TCP_T, 2, STRING_ST, (yyvsp[(3) - (4)].strval), NUMBER_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 690:
/* Line 1792 of yacc.c  */
#line 2877 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_TCP_T, 2, IP_ST, (void*)(yyvsp[(3) - (4)].ipaddr), NUMBER_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 691:
/* Line 1792 of yacc.c  */
#line 2878 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_TCP_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 692:
/* Line 1792 of yacc.c  */
#line 2879 "cfg.y"
    {(yyval.action)=mk_action(FORWARD_TCP_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 693:
/* Line 1792 of yacc.c  */
#line 2880 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_TCP_T, 2, IP_ST, (void*)(yyvsp[(3) - (6)].ipaddr), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 694:
/* Line 1792 of yacc.c  */
#line 2881 "cfg.y"
    {(yyval.action)=mk_action(FORWARD_TCP_T, 2, URIHOST_ST, 0, URIPORT_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 695:
/* Line 1792 of yacc.c  */
#line 2882 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_TCP_T, 2, URIHOST_ST, 0, NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 696:
/* Line 1792 of yacc.c  */
#line 2883 "cfg.y"
    { (yyval.action)=mk_action(FORWARD_TCP_T, 2, URIHOST_ST, 0, NUMBER_ST, 0); set_cfg_pos((yyval.action)); }
    break;

  case 697:
/* Line 1792 of yacc.c  */
#line 2884 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 698:
/* Line 1792 of yacc.c  */
#line 2885 "cfg.y"
    { (yyval.action)=0; yyerror("bad forward_tcp argument"); }
    break;

  case 699:
/* Line 1792 of yacc.c  */
#line 2886 "cfg.y"
    {
		#ifdef USE_TLS
			(yyval.action)=mk_action(FORWARD_TLS_T, 2, STRING_ST, (yyvsp[(3) - (4)].strval), NUMBER_ST, 0); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("tls support not compiled in");
		#endif
	}
    break;

  case 700:
/* Line 1792 of yacc.c  */
#line 2894 "cfg.y"
    {
		#ifdef USE_TLS
			(yyval.action)=mk_action(FORWARD_TLS_T, 2, STRING_ST, (yyvsp[(3) - (4)].strval), NUMBER_ST, 0); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("tls support not compiled in");
		#endif
	}
    break;

  case 701:
/* Line 1792 of yacc.c  */
#line 2902 "cfg.y"
    {
		#ifdef USE_TLS
			(yyval.action)=mk_action(FORWARD_TLS_T, 2, IP_ST, (void*)(yyvsp[(3) - (4)].ipaddr), NUMBER_ST, 0); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("tls support not compiled in");
		#endif
	}
    break;

  case 702:
/* Line 1792 of yacc.c  */
#line 2910 "cfg.y"
    {
		#ifdef USE_TLS
			(yyval.action)=mk_action(FORWARD_TLS_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("tls support not compiled in");
		#endif
	}
    break;

  case 703:
/* Line 1792 of yacc.c  */
#line 2918 "cfg.y"
    {
		#ifdef USE_TLS
			(yyval.action)=mk_action(FORWARD_TLS_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("tls support not compiled in");
		#endif
	}
    break;

  case 704:
/* Line 1792 of yacc.c  */
#line 2926 "cfg.y"
    {
		#ifdef USE_TLS
			(yyval.action)=mk_action(FORWARD_TLS_T, 2, IP_ST, (void*)(yyvsp[(3) - (6)].ipaddr), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("tls support not compiled in");
		#endif
					}
    break;

  case 705:
/* Line 1792 of yacc.c  */
#line 2934 "cfg.y"
    {
		#ifdef USE_TLS
			(yyval.action)=mk_action(FORWARD_TLS_T, 2, URIHOST_ST, 0, URIPORT_ST, 0); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("tls support not compiled in");
		#endif
	}
    break;

  case 706:
/* Line 1792 of yacc.c  */
#line 2942 "cfg.y"
    {
		#ifdef USE_TLS
			(yyval.action)=mk_action(FORWARD_TLS_T, 2, URIHOST_ST, 0, NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("tls support not compiled in");
		#endif
	}
    break;

  case 707:
/* Line 1792 of yacc.c  */
#line 2950 "cfg.y"
    {
		#ifdef USE_TLS
			(yyval.action)=mk_action(FORWARD_TLS_T, 2, URIHOST_ST, 0, NUMBER_ST, 0); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("tls support not compiled in");
		#endif
	}
    break;

  case 708:
/* Line 1792 of yacc.c  */
#line 2958 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 709:
/* Line 1792 of yacc.c  */
#line 2959 "cfg.y"
    { (yyval.action)=0; 
									yyerror("bad forward_tls argument"); }
    break;

  case 710:
/* Line 1792 of yacc.c  */
#line 2961 "cfg.y"
    {
		#ifdef USE_SCTP
			(yyval.action)=mk_action(FORWARD_SCTP_T, 2, STRING_ST, (yyvsp[(3) - (4)].strval), NUMBER_ST, 0); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("sctp support not compiled in");
		#endif
	}
    break;

  case 711:
/* Line 1792 of yacc.c  */
#line 2969 "cfg.y"
    {
		#ifdef USE_SCTP
			(yyval.action)=mk_action(FORWARD_SCTP_T, 2, STRING_ST, (yyvsp[(3) - (4)].strval), NUMBER_ST, 0); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("sctp support not compiled in");
		#endif
	}
    break;

  case 712:
/* Line 1792 of yacc.c  */
#line 2977 "cfg.y"
    {
		#ifdef USE_SCTP
			(yyval.action)=mk_action(FORWARD_SCTP_T, 2, IP_ST, (void*)(yyvsp[(3) - (4)].ipaddr), NUMBER_ST, 0); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("sctp support not compiled in");
		#endif
	}
    break;

  case 713:
/* Line 1792 of yacc.c  */
#line 2985 "cfg.y"
    {
		#ifdef USE_SCTP
			(yyval.action)=mk_action(FORWARD_SCTP_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), NUMBER_ST,
							(void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("sctp support not compiled in");
		#endif
	}
    break;

  case 714:
/* Line 1792 of yacc.c  */
#line 2994 "cfg.y"
    {
		#ifdef USE_SCTP
			(yyval.action)=mk_action(FORWARD_SCTP_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), NUMBER_ST,
							(void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("sctp support not compiled in");
		#endif
	}
    break;

  case 715:
/* Line 1792 of yacc.c  */
#line 3003 "cfg.y"
    {
		#ifdef USE_SCTP
			(yyval.action)=mk_action(FORWARD_SCTP_T, 2, IP_ST, (void*)(yyvsp[(3) - (6)].ipaddr), NUMBER_ST, 
							(void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("sctp support not compiled in");
		#endif
					}
    break;

  case 716:
/* Line 1792 of yacc.c  */
#line 3012 "cfg.y"
    {
		#ifdef USE_SCTP
			(yyval.action)=mk_action(FORWARD_SCTP_T, 2, URIHOST_ST, 0, URIPORT_ST, 0); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("sctp support not compiled in");
		#endif
	}
    break;

  case 717:
/* Line 1792 of yacc.c  */
#line 3020 "cfg.y"
    {
		#ifdef USE_SCTP
			(yyval.action)=mk_action(FORWARD_SCTP_T, 2, URIHOST_ST, 0, NUMBER_ST,
							(void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("sctp support not compiled in");
		#endif
	}
    break;

  case 718:
/* Line 1792 of yacc.c  */
#line 3029 "cfg.y"
    {
		#ifdef USE_SCTP
			(yyval.action)=mk_action(FORWARD_SCTP_T, 2, URIHOST_ST, 0, NUMBER_ST, 0); set_cfg_pos((yyval.action));
		#else
			(yyval.action)=0;
			yyerror("tls support not compiled in");
		#endif
	}
    break;

  case 719:
/* Line 1792 of yacc.c  */
#line 3037 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 720:
/* Line 1792 of yacc.c  */
#line 3038 "cfg.y"
    { (yyval.action)=0; 
									yyerror("bad forward_sctp argument"); }
    break;

  case 721:
/* Line 1792 of yacc.c  */
#line 3040 "cfg.y"
    {(yyval.action)=mk_action(LOG_T, 2, NUMBER_ST,
										(void*)(L_DBG+1), STRING_ST, (yyvsp[(3) - (4)].strval));
									set_cfg_pos((yyval.action)); }
    break;

  case 722:
/* Line 1792 of yacc.c  */
#line 3043 "cfg.y"
    {(yyval.action)=mk_action(LOG_T, 2, NUMBER_ST, (void*)(yyvsp[(3) - (6)].intval), STRING_ST, (yyvsp[(5) - (6)].strval)); set_cfg_pos((yyval.action)); }
    break;

  case 723:
/* Line 1792 of yacc.c  */
#line 3044 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 724:
/* Line 1792 of yacc.c  */
#line 3045 "cfg.y"
    { (yyval.action)=0; yyerror("bad log argument"); }
    break;

  case 725:
/* Line 1792 of yacc.c  */
#line 3046 "cfg.y"
    {
							if (check_flag((yyvsp[(3) - (4)].intval))==-1)
								yyerror("bad flag value");
							(yyval.action)=mk_action(SETFLAG_T, 1, NUMBER_ST,
													(void*)(yyvsp[(3) - (4)].intval));
							set_cfg_pos((yyval.action));
									}
    break;

  case 726:
/* Line 1792 of yacc.c  */
#line 3053 "cfg.y"
    {
							i_tmp=get_flag_no((yyvsp[(3) - (4)].strval), strlen((yyvsp[(3) - (4)].strval)));
							if (i_tmp<0) yyerror("flag not declared");
							(yyval.action)=mk_action(SETFLAG_T, 1, NUMBER_ST,
										(void*)(long)i_tmp);
							set_cfg_pos((yyval.action));
									}
    break;

  case 727:
/* Line 1792 of yacc.c  */
#line 3060 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')'?"); }
    break;

  case 728:
/* Line 1792 of yacc.c  */
#line 3061 "cfg.y"
    {
							if (check_flag((yyvsp[(3) - (4)].intval))==-1)
								yyerror("bad flag value");
							(yyval.action)=mk_action(RESETFLAG_T, 1, NUMBER_ST, (void*)(yyvsp[(3) - (4)].intval));
							set_cfg_pos((yyval.action));
									}
    break;

  case 729:
/* Line 1792 of yacc.c  */
#line 3067 "cfg.y"
    {
							i_tmp=get_flag_no((yyvsp[(3) - (4)].strval), strlen((yyvsp[(3) - (4)].strval)));
							if (i_tmp<0) yyerror("flag not declared");
							(yyval.action)=mk_action(RESETFLAG_T, 1, NUMBER_ST,
										(void*)(long)i_tmp);
							set_cfg_pos((yyval.action));
									}
    break;

  case 730:
/* Line 1792 of yacc.c  */
#line 3074 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')'?"); }
    break;

  case 731:
/* Line 1792 of yacc.c  */
#line 3075 "cfg.y"
    {
							if (check_flag((yyvsp[(3) - (4)].intval))==-1)
								yyerror("bad flag value");
							(yyval.action)=mk_action(ISFLAGSET_T, 1, NUMBER_ST, (void*)(yyvsp[(3) - (4)].intval));
							set_cfg_pos((yyval.action));
									}
    break;

  case 732:
/* Line 1792 of yacc.c  */
#line 3081 "cfg.y"
    {
							i_tmp=get_flag_no((yyvsp[(3) - (4)].strval), strlen((yyvsp[(3) - (4)].strval)));
							if (i_tmp<0) yyerror("flag not declared");
							(yyval.action)=mk_action(ISFLAGSET_T, 1, NUMBER_ST,
										(void*)(long)i_tmp);
							set_cfg_pos((yyval.action));
									}
    break;

  case 733:
/* Line 1792 of yacc.c  */
#line 3088 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')'?"); }
    break;

  case 734:
/* Line 1792 of yacc.c  */
#line 3089 "cfg.y"
    {
		i_tmp=get_avpflag_no((yyvsp[(5) - (6)].strval));
		if (i_tmp==0) yyerror("avpflag not declared");
		(yyval.action)=mk_action(AVPFLAG_OPER_T, 3, AVP_ST, (yyvsp[(3) - (6)].attr), NUMBER_ST, (void*)(long)i_tmp, NUMBER_ST, (void*)(yyvsp[(1) - (6)].intval));
		set_cfg_pos((yyval.action));
	}
    break;

  case 735:
/* Line 1792 of yacc.c  */
#line 3095 "cfg.y"
    {
		(yyval.action)=0; yyerror("error parsing flag name");
	}
    break;

  case 736:
/* Line 1792 of yacc.c  */
#line 3098 "cfg.y"
    {
		(yyval.action)=0; yyerror("error parsing first parameter (avp or string)");
	}
    break;

  case 737:
/* Line 1792 of yacc.c  */
#line 3101 "cfg.y"
    { (yyval.action)=0; yyerror("bad parameters"); }
    break;

  case 738:
/* Line 1792 of yacc.c  */
#line 3102 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')'?"); }
    break;

  case 739:
/* Line 1792 of yacc.c  */
#line 3103 "cfg.y"
    {(yyval.action)=mk_action(ERROR_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), STRING_ST, (yyvsp[(5) - (6)].strval));
			set_cfg_pos((yyval.action));
	}
    break;

  case 740:
/* Line 1792 of yacc.c  */
#line 3106 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 741:
/* Line 1792 of yacc.c  */
#line 3107 "cfg.y"
    { (yyval.action)=0; yyerror("bad error argument"); }
    break;

  case 742:
/* Line 1792 of yacc.c  */
#line 3108 "cfg.y"
    {
		if ((yyvsp[(3) - (4)].rv_expr)) {
			(yyval.action) = mk_action(ROUTE_T, 1, RVE_ST, (void*)(yyvsp[(3) - (4)].rv_expr));
			set_cfg_pos((yyval.action));
		} else {
			(yyval.action) = 0;
			YYERROR;
		}
	}
    break;

  case 743:
/* Line 1792 of yacc.c  */
#line 3117 "cfg.y"
    {
		if ((yyvsp[(3) - (4)].strval)) {
			(yyval.action) = mk_action(ROUTE_T, 1, STRING_ST, (void*)(yyvsp[(3) - (4)].strval));
			set_cfg_pos((yyval.action));
		} else {
			(yyval.action) = 0;
			YYERROR;
		}
	}
    break;

  case 744:
/* Line 1792 of yacc.c  */
#line 3126 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 745:
/* Line 1792 of yacc.c  */
#line 3127 "cfg.y"
    { (yyval.action)=0; yyerror("bad route argument"); }
    break;

  case 746:
/* Line 1792 of yacc.c  */
#line 3128 "cfg.y"
    { (yyval.action)=mk_action(EXEC_T, 1, STRING_ST, (yyvsp[(3) - (4)].strval)); set_cfg_pos((yyval.action)); }
    break;

  case 747:
/* Line 1792 of yacc.c  */
#line 3129 "cfg.y"
    { (yyval.action)=mk_action(SET_HOST_T, 1, STRING_ST, (yyvsp[(3) - (4)].strval)); set_cfg_pos((yyval.action)); }
    break;

  case 748:
/* Line 1792 of yacc.c  */
#line 3130 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 749:
/* Line 1792 of yacc.c  */
#line 3131 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, string expected"); }
    break;

  case 750:
/* Line 1792 of yacc.c  */
#line 3132 "cfg.y"
    { (yyval.action)=mk_action(PREFIX_T, 1, STRING_ST,  (yyvsp[(3) - (4)].strval)); set_cfg_pos((yyval.action)); }
    break;

  case 751:
/* Line 1792 of yacc.c  */
#line 3133 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 752:
/* Line 1792 of yacc.c  */
#line 3134 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, string expected"); }
    break;

  case 753:
/* Line 1792 of yacc.c  */
#line 3135 "cfg.y"
    { (yyval.action)=mk_action(STRIP_TAIL_T, 1, NUMBER_ST, (void*)(yyvsp[(3) - (4)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 754:
/* Line 1792 of yacc.c  */
#line 3136 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 755:
/* Line 1792 of yacc.c  */
#line 3137 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, number expected"); }
    break;

  case 756:
/* Line 1792 of yacc.c  */
#line 3138 "cfg.y"
    { (yyval.action)=mk_action(STRIP_T, 1, NUMBER_ST, (void*) (yyvsp[(3) - (4)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 757:
/* Line 1792 of yacc.c  */
#line 3139 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 758:
/* Line 1792 of yacc.c  */
#line 3140 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, number expected"); }
    break;

  case 759:
/* Line 1792 of yacc.c  */
#line 3141 "cfg.y"
    { (yyval.action)=mk_action(SET_USERPHONE_T, 0); set_cfg_pos((yyval.action)); }
    break;

  case 760:
/* Line 1792 of yacc.c  */
#line 3142 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 761:
/* Line 1792 of yacc.c  */
#line 3143 "cfg.y"
    {
			(yyval.action)=mk_action(REMOVE_BRANCH_T, 1, NUMBER_ST, (void*)(yyvsp[(3) - (4)].intval));
			set_cfg_pos((yyval.action));
	}
    break;

  case 762:
/* Line 1792 of yacc.c  */
#line 3147 "cfg.y"
    {
			(yyval.action)=mk_action(REMOVE_BRANCH_T, 0);
			set_cfg_pos((yyval.action));
	}
    break;

  case 763:
/* Line 1792 of yacc.c  */
#line 3151 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 764:
/* Line 1792 of yacc.c  */
#line 3152 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, number expected"); }
    break;

  case 765:
/* Line 1792 of yacc.c  */
#line 3153 "cfg.y"
    { (yyval.action)=mk_action(CLEAR_BRANCHES_T, 0); set_cfg_pos((yyval.action)); }
    break;

  case 766:
/* Line 1792 of yacc.c  */
#line 3154 "cfg.y"
    { (yyval.action)=mk_action(SET_HOSTPORT_T, 1, STRING_ST, (yyvsp[(3) - (4)].strval)); set_cfg_pos((yyval.action)); }
    break;

  case 767:
/* Line 1792 of yacc.c  */
#line 3155 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 768:
/* Line 1792 of yacc.c  */
#line 3156 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, string expected"); }
    break;

  case 769:
/* Line 1792 of yacc.c  */
#line 3157 "cfg.y"
    { (yyval.action)=mk_action(SET_HOSTPORTTRANS_T, 1, STRING_ST, (yyvsp[(3) - (4)].strval)); set_cfg_pos((yyval.action)); }
    break;

  case 770:
/* Line 1792 of yacc.c  */
#line 3158 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 771:
/* Line 1792 of yacc.c  */
#line 3159 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, string expected"); }
    break;

  case 772:
/* Line 1792 of yacc.c  */
#line 3160 "cfg.y"
    { (yyval.action)=mk_action(SET_PORT_T, 1, STRING_ST, (yyvsp[(3) - (4)].strval)); set_cfg_pos((yyval.action)); }
    break;

  case 773:
/* Line 1792 of yacc.c  */
#line 3161 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 774:
/* Line 1792 of yacc.c  */
#line 3162 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, string expected"); }
    break;

  case 775:
/* Line 1792 of yacc.c  */
#line 3163 "cfg.y"
    { (yyval.action)=mk_action(SET_USER_T, 1, STRING_ST, (yyvsp[(3) - (4)].strval)); set_cfg_pos((yyval.action)); }
    break;

  case 776:
/* Line 1792 of yacc.c  */
#line 3164 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 777:
/* Line 1792 of yacc.c  */
#line 3165 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, string expected"); }
    break;

  case 778:
/* Line 1792 of yacc.c  */
#line 3166 "cfg.y"
    { (yyval.action)=mk_action(SET_USERPASS_T, 1, STRING_ST, (yyvsp[(3) - (4)].strval)); set_cfg_pos((yyval.action)); }
    break;

  case 779:
/* Line 1792 of yacc.c  */
#line 3167 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 780:
/* Line 1792 of yacc.c  */
#line 3168 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, string expected"); }
    break;

  case 781:
/* Line 1792 of yacc.c  */
#line 3169 "cfg.y"
    { (yyval.action)=mk_action(SET_URI_T, 1, STRING_ST,(yyvsp[(3) - (4)].strval)); set_cfg_pos((yyval.action)); }
    break;

  case 782:
/* Line 1792 of yacc.c  */
#line 3170 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 783:
/* Line 1792 of yacc.c  */
#line 3171 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, string expected"); }
    break;

  case 784:
/* Line 1792 of yacc.c  */
#line 3172 "cfg.y"
    { (yyval.action)=mk_action(REVERT_URI_T, 0); set_cfg_pos((yyval.action)); }
    break;

  case 785:
/* Line 1792 of yacc.c  */
#line 3173 "cfg.y"
    { (yyval.action)=mk_action(REVERT_URI_T, 0); set_cfg_pos((yyval.action)); }
    break;

  case 786:
/* Line 1792 of yacc.c  */
#line 3174 "cfg.y"
    { (yyval.action)=mk_action(FORCE_RPORT_T, 0); set_cfg_pos((yyval.action)); }
    break;

  case 787:
/* Line 1792 of yacc.c  */
#line 3175 "cfg.y"
    {(yyval.action)=mk_action(FORCE_RPORT_T, 0); set_cfg_pos((yyval.action)); }
    break;

  case 788:
/* Line 1792 of yacc.c  */
#line 3176 "cfg.y"
    { (yyval.action)=mk_action(ADD_LOCAL_RPORT_T, 0); set_cfg_pos((yyval.action)); }
    break;

  case 789:
/* Line 1792 of yacc.c  */
#line 3177 "cfg.y"
    {(yyval.action)=mk_action(ADD_LOCAL_RPORT_T, 0); set_cfg_pos((yyval.action)); }
    break;

  case 790:
/* Line 1792 of yacc.c  */
#line 3178 "cfg.y"
    {
		#ifdef USE_TCP
			(yyval.action)=mk_action(FORCE_TCP_ALIAS_T, 1, NUMBER_ST, (void*)(yyvsp[(3) - (4)].intval));
			set_cfg_pos((yyval.action));
		#else
			yyerror("tcp support not compiled in");
		#endif
	}
    break;

  case 791:
/* Line 1792 of yacc.c  */
#line 3186 "cfg.y"
    {
		#ifdef USE_TCP
			(yyval.action)=mk_action(FORCE_TCP_ALIAS_T, 0);
			set_cfg_pos((yyval.action));
		#else
			yyerror("tcp support not compiled in");
		#endif
	}
    break;

  case 792:
/* Line 1792 of yacc.c  */
#line 3194 "cfg.y"
    {
		#ifdef USE_TCP
			(yyval.action)=mk_action(FORCE_TCP_ALIAS_T, 0);
			set_cfg_pos((yyval.action));
		#else
			yyerror("tcp support not compiled in");
		#endif
	}
    break;

  case 793:
/* Line 1792 of yacc.c  */
#line 3202 "cfg.y"
    {(yyval.action)=0; yyerror("bad argument, number expected"); }
    break;

  case 794:
/* Line 1792 of yacc.c  */
#line 3204 "cfg.y"
    { (yyval.action)=mk_action(UDP_MTU_TRY_PROTO_T, 1, NUMBER_ST, (yyvsp[(3) - (4)].intval)); set_cfg_pos((yyval.action)); }
    break;

  case 795:
/* Line 1792 of yacc.c  */
#line 3206 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, UDP, TCP, TLS or SCTP expected"); }
    break;

  case 796:
/* Line 1792 of yacc.c  */
#line 3207 "cfg.y"
    {
		(yyval.action)=0;
		if ((str_tmp=pkg_malloc(sizeof(str)))==0) {
			LOG(L_CRIT, "ERROR: cfg. parser: out of memory.\n");
		} else {
			str_tmp->s=(yyvsp[(3) - (4)].strval);
			str_tmp->len=(yyvsp[(3) - (4)].strval)?strlen((yyvsp[(3) - (4)].strval)):0;
			(yyval.action)=mk_action(SET_ADV_ADDR_T, 1, STR_ST, str_tmp);
			set_cfg_pos((yyval.action));
		}
	}
    break;

  case 797:
/* Line 1792 of yacc.c  */
#line 3218 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, string expected"); }
    break;

  case 798:
/* Line 1792 of yacc.c  */
#line 3219 "cfg.y"
    {(yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 799:
/* Line 1792 of yacc.c  */
#line 3220 "cfg.y"
    {
		(yyval.action)=0;
		tmp=int2str((yyvsp[(3) - (4)].intval), &i_tmp);
		if ((str_tmp=pkg_malloc(sizeof(str)))==0) {
			LOG(L_CRIT, "ERROR: cfg. parser: out of memory.\n");
		} else {
			if ((str_tmp->s=pkg_malloc(i_tmp))==0) {
				LOG(L_CRIT, "ERROR: cfg. parser: out of memory.\n");
			} else {
				memcpy(str_tmp->s, tmp, i_tmp);
				str_tmp->len=i_tmp;
				(yyval.action)=mk_action(SET_ADV_PORT_T, 1, STR_ST, str_tmp);
				set_cfg_pos((yyval.action));
			}
		}
	}
    break;

  case 800:
/* Line 1792 of yacc.c  */
#line 3236 "cfg.y"
    { (yyval.action)=0; yyerror("bad argument, string expected"); }
    break;

  case 801:
/* Line 1792 of yacc.c  */
#line 3237 "cfg.y"
    {(yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 802:
/* Line 1792 of yacc.c  */
#line 3238 "cfg.y"
    { 
		(yyval.action)=mk_action(FORCE_SEND_SOCKET_T, 1, SOCKID_ST, (yyvsp[(3) - (4)].sockid));
		set_cfg_pos((yyval.action));
	}
    break;

  case 803:
/* Line 1792 of yacc.c  */
#line 3242 "cfg.y"
    {
		(yyval.action)=0; yyerror("bad argument, [proto:]host[:port] expected");
	}
    break;

  case 804:
/* Line 1792 of yacc.c  */
#line 3245 "cfg.y"
    {(yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 805:
/* Line 1792 of yacc.c  */
#line 3246 "cfg.y"
    {
		(yyval.action)=mk_action(SET_FWD_NO_CONNECT_T, 0); set_cfg_pos((yyval.action));
	}
    break;

  case 806:
/* Line 1792 of yacc.c  */
#line 3249 "cfg.y"
    {
		(yyval.action)=mk_action(SET_FWD_NO_CONNECT_T, 0); set_cfg_pos((yyval.action));
	}
    break;

  case 807:
/* Line 1792 of yacc.c  */
#line 3252 "cfg.y"
    {
		(yyval.action)=mk_action(SET_RPL_NO_CONNECT_T, 0); set_cfg_pos((yyval.action));
	}
    break;

  case 808:
/* Line 1792 of yacc.c  */
#line 3255 "cfg.y"
    {
		(yyval.action)=mk_action(SET_RPL_NO_CONNECT_T, 0); set_cfg_pos((yyval.action));
	}
    break;

  case 809:
/* Line 1792 of yacc.c  */
#line 3258 "cfg.y"
    {
		(yyval.action)=mk_action(SET_FWD_CLOSE_T, 0); set_cfg_pos((yyval.action));
	}
    break;

  case 810:
/* Line 1792 of yacc.c  */
#line 3261 "cfg.y"
    {
		(yyval.action)=mk_action(SET_FWD_CLOSE_T, 0); set_cfg_pos((yyval.action));
	}
    break;

  case 811:
/* Line 1792 of yacc.c  */
#line 3264 "cfg.y"
    {
		(yyval.action)=mk_action(SET_RPL_CLOSE_T, 0); set_cfg_pos((yyval.action));
	}
    break;

  case 812:
/* Line 1792 of yacc.c  */
#line 3267 "cfg.y"
    {
		(yyval.action)=mk_action(SET_RPL_CLOSE_T, 0); set_cfg_pos((yyval.action));
	}
    break;

  case 813:
/* Line 1792 of yacc.c  */
#line 3270 "cfg.y"
    {
		(yyval.action)=mk_action(CFG_SELECT_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), NUMBER_ST, (void*)(yyvsp[(5) - (6)].intval)); set_cfg_pos((yyval.action));
	}
    break;

  case 814:
/* Line 1792 of yacc.c  */
#line 3273 "cfg.y"
    {
		(yyval.action)=mk_action(CFG_SELECT_T, 2, STRING_ST, (yyvsp[(3) - (6)].strval), RVE_ST, (yyvsp[(5) - (6)].rv_expr)); set_cfg_pos((yyval.action));
	}
    break;

  case 815:
/* Line 1792 of yacc.c  */
#line 3276 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 816:
/* Line 1792 of yacc.c  */
#line 3277 "cfg.y"
    { (yyval.action)=0; yyerror("bad arguments, string and number expected"); }
    break;

  case 817:
/* Line 1792 of yacc.c  */
#line 3278 "cfg.y"
    {
		(yyval.action)=mk_action(CFG_RESET_T, 1, STRING_ST, (yyvsp[(3) - (4)].strval)); set_cfg_pos((yyval.action));
	}
    break;

  case 818:
/* Line 1792 of yacc.c  */
#line 3281 "cfg.y"
    { (yyval.action)=0; yyerror("missing '(' or ')' ?"); }
    break;

  case 819:
/* Line 1792 of yacc.c  */
#line 3282 "cfg.y"
    { (yyval.action)=0; yyerror("bad arguments, string expected"); }
    break;

  case 820:
/* Line 1792 of yacc.c  */
#line 3283 "cfg.y"
    {mod_func_action = mk_action(MODULE0_T, 2, MODEXP_ST, NULL, NUMBER_ST,
			0); }
    break;

  case 821:
/* Line 1792 of yacc.c  */
#line 3284 "cfg.y"
    {
		mod_func_action->val[0].u.data =
			find_export_record((yyvsp[(1) - (5)].strval), mod_func_action->val[1].u.number, rt,
								&u_tmp);
		if (mod_func_action->val[0].u.data == 0) {
			if (find_export_record((yyvsp[(1) - (5)].strval), mod_func_action->val[1].u.number, 0,
									&u_tmp) ) {
					LOG(L_ERR, "misused command %s\n", (yyvsp[(1) - (5)].strval));
					yyerror("Command cannot be used in the block\n");
			} else {
				LOG(L_ERR, "cfg. parser: failed to find command %s (params %ld)\n",
						(yyvsp[(1) - (5)].strval), mod_func_action->val[1].u.number);
				yyerror("unknown command, missing loadmodule?\n");
			}
			free_mod_func_action(mod_func_action);
			mod_func_action=0;
		}else{
			if (mod_func_action && mod_f_params_pre_fixup(mod_func_action)<0) {
				/* error messages are printed inside the function */
				free_mod_func_action(mod_func_action);
				mod_func_action = 0;
				YYERROR;
			}
		}
		(yyval.action) = mod_func_action;
		set_cfg_pos((yyval.action));
	}
    break;

  case 822:
/* Line 1792 of yacc.c  */
#line 3311 "cfg.y"
    { yyerror("'('')' expected (function call)");}
    break;

  case 824:
/* Line 1792 of yacc.c  */
#line 3315 "cfg.y"
    { }
    break;

  case 825:
/* Line 1792 of yacc.c  */
#line 3316 "cfg.y"
    {}
    break;

  case 826:
/* Line 1792 of yacc.c  */
#line 3319 "cfg.y"
    {
		if ((yyvsp[(1) - (1)].rv_expr) && mod_func_action->val[1].u.number < MAX_ACTIONS-2) {
			mod_func_action->val[mod_func_action->val[1].u.number+2].type =
				RVE_ST;
			mod_func_action->val[mod_func_action->val[1].u.number+2].u.data =
				(yyvsp[(1) - (1)].rv_expr);
			mod_func_action->val[1].u.number++;
		} else if ((yyvsp[(1) - (1)].rv_expr)) {
			yyerror("Too many arguments\n");
			YYERROR;
		} else {
			YYERROR;
		}
	}
    break;

  case 827:
/* Line 1792 of yacc.c  */
#line 3336 "cfg.y"
    {
		(yyval.action)=mk_action(DROP_T, 2, NUMBER_ST, 0, NUMBER_ST,
						(void*)(DROP_R_F|EXIT_R_F)); set_cfg_pos((yyval.action));
	}
    break;

  case 828:
/* Line 1792 of yacc.c  */
#line 3340 "cfg.y"
    {
		(yyval.action)=mk_action(DROP_T, 2, RVE_ST, (yyvsp[(2) - (2)].rv_expr), NUMBER_ST,
						(void*)(DROP_R_F|EXIT_R_F)); set_cfg_pos((yyval.action));
	}
    break;

  case 829:
/* Line 1792 of yacc.c  */
#line 3344 "cfg.y"
    {
		(yyval.action)=mk_action(DROP_T, 2, NUMBER_ST, 0, NUMBER_ST, 
						(void*)(DROP_R_F|EXIT_R_F)); set_cfg_pos((yyval.action));
	}
    break;

  case 830:
/* Line 1792 of yacc.c  */
#line 3348 "cfg.y"
    {
		(yyval.action)=mk_action(DROP_T, 2, NUMBER_ST, (void*)1, NUMBER_ST,
						(void*)EXIT_R_F);
		set_cfg_pos((yyval.action));
	}
    break;

  case 831:
/* Line 1792 of yacc.c  */
#line 3353 "cfg.y"
    {
		(yyval.action)=mk_action(DROP_T, 2, RVE_ST, (yyvsp[(2) - (2)].rv_expr), NUMBER_ST, (void*)EXIT_R_F);
		set_cfg_pos((yyval.action));
	}
    break;

  case 832:
/* Line 1792 of yacc.c  */
#line 3357 "cfg.y"
    {
		(yyval.action)=mk_action(DROP_T, 2, NUMBER_ST, (void*)1, NUMBER_ST,
						(void*)EXIT_R_F);
		set_cfg_pos((yyval.action));
	}
    break;

  case 833:
/* Line 1792 of yacc.c  */
#line 3362 "cfg.y"
    {
		(yyval.action)=mk_action(DROP_T, 2, NUMBER_ST, (void*)1, NUMBER_ST,
						(void*)RETURN_R_F); set_cfg_pos((yyval.action));
	}
    break;

  case 834:
/* Line 1792 of yacc.c  */
#line 3366 "cfg.y"
    {
		(yyval.action)=mk_action(DROP_T, 2, NUMBER_ST, (void*)1, NUMBER_ST,
						(void*)RETURN_R_F); set_cfg_pos((yyval.action));
	}
    break;

  case 835:
/* Line 1792 of yacc.c  */
#line 3370 "cfg.y"
    {
		(yyval.action)=mk_action(DROP_T, 2, RVE_ST, (yyvsp[(2) - (2)].rv_expr), NUMBER_ST, (void*)RETURN_R_F);
		set_cfg_pos((yyval.action));
	}
    break;

  case 836:
/* Line 1792 of yacc.c  */
#line 3374 "cfg.y"
    {
		(yyval.action)=mk_action(DROP_T, 2, NUMBER_ST, 0, NUMBER_ST, (void*)BREAK_R_F);
		set_cfg_pos((yyval.action));
	}
    break;


/* Line 1792 of yacc.c  */
#line 11834 "cfg.tab.c"
      default: break;
    }
  /* User semantic actions sometimes alter yychar, and that requires
     that yytoken be updated with the new translation.  We take the
     approach of translating immediately before every use of yytoken.
     One alternative is translating here after every semantic action,
     but that translation would be missed if the semantic action invokes
     YYABORT, YYACCEPT, or YYERROR immediately after altering yychar or
     if it invokes YYBACKUP.  In the case of YYABORT or YYACCEPT, an
     incorrect destructor might then be invoked immediately.  In the
     case of YYERROR or YYBACKUP, subsequent parser actions might lead
     to an incorrect destructor call or verbose syntax error message
     before the lookahead is translated.  */
  YY_SYMBOL_PRINT ("-> $$ =", yyr1[yyn], &yyval, &yyloc);

  YYPOPSTACK (yylen);
  yylen = 0;
  YY_STACK_PRINT (yyss, yyssp);

  *++yyvsp = yyval;

  /* Now `shift' the result of the reduction.  Determine what state
     that goes to, based on the state we popped back to and the rule
     number reduced by.  */

  yyn = yyr1[yyn];

  yystate = yypgoto[yyn - YYNTOKENS] + *yyssp;
  if (0 <= yystate && yystate <= YYLAST && yycheck[yystate] == *yyssp)
    yystate = yytable[yystate];
  else
    yystate = yydefgoto[yyn - YYNTOKENS];

  goto yynewstate;


/*------------------------------------.
| yyerrlab -- here on detecting error |
`------------------------------------*/
yyerrlab:
  /* Make sure we have latest lookahead translation.  See comments at
     user semantic actions for why this is necessary.  */
  yytoken = yychar == YYEMPTY ? YYEMPTY : YYTRANSLATE (yychar);

  /* If not already recovering from an error, report this error.  */
  if (!yyerrstatus)
    {
      ++yynerrs;
#if ! YYERROR_VERBOSE
      yyerror (YY_("syntax error"));
#else
# define YYSYNTAX_ERROR yysyntax_error (&yymsg_alloc, &yymsg, \
                                        yyssp, yytoken)
      {
        char const *yymsgp = YY_("syntax error");
        int yysyntax_error_status;
        yysyntax_error_status = YYSYNTAX_ERROR;
        if (yysyntax_error_status == 0)
          yymsgp = yymsg;
        else if (yysyntax_error_status == 1)
          {
            if (yymsg != yymsgbuf)
              YYSTACK_FREE (yymsg);
            yymsg = (char *) YYSTACK_ALLOC (yymsg_alloc);
            if (!yymsg)
              {
                yymsg = yymsgbuf;
                yymsg_alloc = sizeof yymsgbuf;
                yysyntax_error_status = 2;
              }
            else
              {
                yysyntax_error_status = YYSYNTAX_ERROR;
                yymsgp = yymsg;
              }
          }
        yyerror (yymsgp);
        if (yysyntax_error_status == 2)
          goto yyexhaustedlab;
      }
# undef YYSYNTAX_ERROR
#endif
    }



  if (yyerrstatus == 3)
    {
      /* If just tried and failed to reuse lookahead token after an
	 error, discard it.  */

      if (yychar <= YYEOF)
	{
	  /* Return failure if at end of input.  */
	  if (yychar == YYEOF)
	    YYABORT;
	}
      else
	{
	  yydestruct ("Error: discarding",
		      yytoken, &yylval);
	  yychar = YYEMPTY;
	}
    }

  /* Else will try to reuse lookahead token after shifting the error
     token.  */
  goto yyerrlab1;


/*---------------------------------------------------.
| yyerrorlab -- error raised explicitly by YYERROR.  |
`---------------------------------------------------*/
yyerrorlab:

  /* Pacify compilers like GCC when the user code never invokes
     YYERROR and the label yyerrorlab therefore never appears in user
     code.  */
  if (/*CONSTCOND*/ 0)
     goto yyerrorlab;

  /* Do not reclaim the symbols of the rule which action triggered
     this YYERROR.  */
  YYPOPSTACK (yylen);
  yylen = 0;
  YY_STACK_PRINT (yyss, yyssp);
  yystate = *yyssp;
  goto yyerrlab1;


/*-------------------------------------------------------------.
| yyerrlab1 -- common code for both syntax error and YYERROR.  |
`-------------------------------------------------------------*/
yyerrlab1:
  yyerrstatus = 3;	/* Each real token shifted decrements this.  */

  for (;;)
    {
      yyn = yypact[yystate];
      if (!yypact_value_is_default (yyn))
	{
	  yyn += YYTERROR;
	  if (0 <= yyn && yyn <= YYLAST && yycheck[yyn] == YYTERROR)
	    {
	      yyn = yytable[yyn];
	      if (0 < yyn)
		break;
	    }
	}

      /* Pop the current state because it cannot handle the error token.  */
      if (yyssp == yyss)
	YYABORT;


      yydestruct ("Error: popping",
		  yystos[yystate], yyvsp);
      YYPOPSTACK (1);
      yystate = *yyssp;
      YY_STACK_PRINT (yyss, yyssp);
    }

  YY_IGNORE_MAYBE_UNINITIALIZED_BEGIN
  *++yyvsp = yylval;
  YY_IGNORE_MAYBE_UNINITIALIZED_END


  /* Shift the error token.  */
  YY_SYMBOL_PRINT ("Shifting", yystos[yyn], yyvsp, yylsp);

  yystate = yyn;
  goto yynewstate;


/*-------------------------------------.
| yyacceptlab -- YYACCEPT comes here.  |
`-------------------------------------*/
yyacceptlab:
  yyresult = 0;
  goto yyreturn;

/*-----------------------------------.
| yyabortlab -- YYABORT comes here.  |
`-----------------------------------*/
yyabortlab:
  yyresult = 1;
  goto yyreturn;

#if !defined yyoverflow || YYERROR_VERBOSE
/*-------------------------------------------------.
| yyexhaustedlab -- memory exhaustion comes here.  |
`-------------------------------------------------*/
yyexhaustedlab:
  yyerror (YY_("memory exhausted"));
  yyresult = 2;
  /* Fall through.  */
#endif

yyreturn:
  if (yychar != YYEMPTY)
    {
      /* Make sure we have latest lookahead translation.  See comments at
         user semantic actions for why this is necessary.  */
      yytoken = YYTRANSLATE (yychar);
      yydestruct ("Cleanup: discarding lookahead",
                  yytoken, &yylval);
    }
  /* Do not reclaim the symbols of the rule which action triggered
     this YYABORT or YYACCEPT.  */
  YYPOPSTACK (yylen);
  YY_STACK_PRINT (yyss, yyssp);
  while (yyssp != yyss)
    {
      yydestruct ("Cleanup: popping",
		  yystos[*yyssp], yyvsp);
      YYPOPSTACK (1);
    }
#ifndef yyoverflow
  if (yyss != yyssa)
    YYSTACK_FREE (yyss);
#endif
#if YYERROR_VERBOSE
  if (yymsg != yymsgbuf)
    YYSTACK_FREE (yymsg);
#endif
  /* Make sure YYID is used.  */
  return YYID (yyresult);
}


/* Line 2055 of yacc.c  */
#line 3380 "cfg.y"


static void get_cpos(struct cfg_pos* pos)
{
	pos->s_line=startline;
	pos->e_line=line;
	pos->s_col=startcolumn;
	pos->e_col=column-1;
	if(finame==0)
		finame = (cfg_file!=0)?cfg_file:"default";
	pos->fname=finame;
	pos->rname=(routename!=0)?routename:default_routename;
}


static void warn_at(struct cfg_pos* p, char* format, ...)
{
	va_list ap;
	char s[256];
	
	va_start(ap, format);
	vsnprintf(s, sizeof(s), format, ap);
	va_end(ap);
	if (p->e_line!=p->s_line)
		LOG(L_WARN, "warning in config file %s, from line %d, column %d to"
					" line %d, column %d: %s\n",
					p->fname, p->s_line, p->s_col, p->e_line, p->e_col, s);
	else if (p->s_col!=p->e_col)
		LOG(L_WARN, "warning in config file %s, line %d, column %d-%d: %s\n",
					p->fname, p->s_line, p->s_col, p->e_col, s);
	else
		LOG(L_WARN, "warning in config file %s, line %d, column %d: %s\n",
				p->fname, p->s_line, p->s_col, s);
	cfg_warnings++;
}



static void yyerror_at(struct cfg_pos* p, char* format, ...)
{
	va_list ap;
	char s[256];
	
	va_start(ap, format);
	vsnprintf(s, sizeof(s), format, ap);
	va_end(ap);
	if (p->e_line!=p->s_line)
		LOG(L_CRIT, "parse error in config file %s, from line %d, column %d"
					" to line %d, column %d: %s\n",
					p->fname, p->s_line, p->s_col, p->e_line, p->e_col, s);
	else if (p->s_col!=p->e_col)
		LOG(L_CRIT,"parse error in config file %s, line %d, column %d-%d: %s\n",
					p->fname, p->s_line, p->s_col, p->e_col, s);
	else
		LOG(L_CRIT, "parse error in config file %s, line %d, column %d: %s\n",
					p->fname, p->s_line, p->s_col, s);
	cfg_errors++;
}



static void warn(char* format, ...)
{
	va_list ap;
	char s[256];
	struct cfg_pos pos;
	
	get_cpos(&pos);
	va_start(ap, format);
	vsnprintf(s, sizeof(s), format, ap);
	va_end(ap);
	warn_at(&pos, s);
}



static void yyerror(char* format, ...)
{
	va_list ap;
	char s[256];
	struct cfg_pos pos;
	
	get_cpos(&pos);
	va_start(ap, format);
	vsnprintf(s, sizeof(s), format, ap);
	va_end(ap);
	yyerror_at(&pos, s);
}



/** mk_rval_expr_v wrapper.
 *  checks mk_rval_expr_v return value and sets the cfg. pos
 *  (line and column numbers)
 *  @return rval_expr* on success, 0 on error (@see mk_rval_expr_v)
 */
static struct rval_expr* mk_rve_rval(enum rval_type type, void* v)
{
	struct rval_expr* ret;
	struct cfg_pos pos;

	get_cpos(&pos);
	ret=mk_rval_expr_v(type, v, &pos);
	if (ret==0){
		yyerror("internal error: failed to create rval expr");
		/* YYABORT; */
	}
	return ret;
}


/** mk_rval_expr1 wrapper.
 *  checks mk_rval_expr1 return value (!=0 and type checking)
 *  @return rval_expr* on success, 0 on error (@see mk_rval_expr1)
 */
static struct rval_expr* mk_rve1(enum rval_expr_op op, struct rval_expr* rve1)
{
	struct rval_expr* ret;
	struct rval_expr* bad_rve;
	enum rval_type type, bad_t, exp_t;
	
	if (rve1==0)
		return 0;
	ret=mk_rval_expr1(op, rve1, &rve1->fpos);
	if (ret && (rve_check_type(&type, ret, &bad_rve, &bad_t, &exp_t)!=1)){
		yyerror_at(&rve1->fpos, "bad expression: type mismatch"
					" (%s instead of %s)", rval_type_name(bad_t),
					rval_type_name(exp_t));
		rve_destroy(ret);
		ret=0;
	}
	return ret;
}


/** mk_rval_expr2 wrapper.
 *  checks mk_rval_expr2 return value (!=0 and type checking)
 *  @return rval_expr* on success, 0 on error (@see mk_rval_expr2)
 */
static struct rval_expr* mk_rve2(enum rval_expr_op op, struct rval_expr* rve1,
									struct rval_expr* rve2)
{
	struct rval_expr* ret;
	struct rval_expr* bad_rve;
	enum rval_type type, bad_t, exp_t;
	struct cfg_pos pos;
	
	if ((rve1==0) || (rve2==0))
		return 0;
	bad_rve=0;
	bad_t=0;
	exp_t=0;
	cfg_pos_join(&pos, &rve1->fpos, &rve2->fpos);
	ret=mk_rval_expr2(op, rve1, rve2, &pos);
	if (ret && (rve_check_type(&type, ret, &bad_rve, &bad_t, &exp_t)!=1)){
		if (bad_rve)
			yyerror_at(&pos, "bad expression: type mismatch:"
						" %s instead of %s at (%d,%d)",
						rval_type_name(bad_t), rval_type_name(exp_t),
						bad_rve->fpos.s_line, bad_rve->fpos.s_col);
		else
			yyerror("BUG: unexpected null \"bad\" expression\n");
		rve_destroy(ret);
		ret=0;
	}
	return ret;
}


/** check if the expression is an int.
 * if the expression does not evaluate to an int return -1 and
 * log an error.
 * @return 0 success, no warnings; 1 success but warnings; -1 on error */
static int rval_expr_int_check(struct rval_expr *rve)
{
	struct rval_expr* bad_rve;
	enum rval_type type, bad_t, exp_t;
	
	if (rve==0){
		yyerror("invalid expression");
		return -1;
	}else if (!rve_check_type(&type, rve, &bad_rve, &bad_t ,&exp_t)){
		if (bad_rve)
			yyerror_at(&rve->fpos, "bad expression: type mismatch:"
						" %s instead of %s at (%d,%d)",
						rval_type_name(bad_t), rval_type_name(exp_t),
						bad_rve->fpos.s_line, bad_rve->fpos.s_col);
		else
			yyerror("BUG: unexpected null \"bad\" expression\n");
		return -1;
	}else if (type!=RV_INT && type!=RV_NONE){
		warn_at(&rve->fpos, "non-int expression (you might want to use"
				" casts)\n");
		return 1;
	}
	return 0;
}


/** warn if the expression is constant.
 * @return 0 on success (no warning), 1 when warning */
static int warn_ct_rve(struct rval_expr *rve, char* name)
{
	if (rve && rve_is_constant(rve)){
		warn_at(&rve->fpos, "constant value in %s%s",
				name?name:"expression", name?"(...)":"");
		return 1;
	}
	return 0;
}


static struct name_lst* mk_name_lst(char* host, int flags)
{
	struct name_lst* l;
	if (host==0) return 0;
	l=pkg_malloc(sizeof(struct name_lst));
	if (l==0) {
		LOG(L_CRIT,"ERROR: cfg. parser: out of memory.\n");
	} else {
		l->name=host;
		l->flags=flags;
		l->next=0;
	}
	return l;
}


static struct socket_id* mk_listen_id(char* host, int proto, int port)
{
	struct socket_id* l;
	if (host==0) return 0;
	l=pkg_malloc(sizeof(struct socket_id));
	if (l==0) {
		LOG(L_CRIT,"ERROR: cfg. parser: out of memory.\n");
	} else {
		l->addr_lst=mk_name_lst(host, 0);
		if (l->addr_lst==0){
			pkg_free(l);
			return 0;
		}
		l->flags=0;
		l->port=port;
		l->proto=proto;
		l->next=0;
	}
	return l;
}


static void free_name_lst(struct name_lst* lst)
{
	struct name_lst* tmp;
	
	while(lst){
		tmp=lst;
		lst=lst->next;
		pkg_free(tmp);
	}
}


static struct socket_id* mk_listen_id2(struct name_lst* addr_l, int proto,
										int port)
{
	struct socket_id* l;
	if (addr_l==0) return 0;
	l=pkg_malloc(sizeof(struct socket_id));
	if (l==0) {
		LOG(L_CRIT,"ERROR: cfg. parser: out of memory.\n");
	} else {
		l->flags=addr_l->flags;
		l->port=port;
		l->proto=proto;
		l->addr_lst=addr_l;
		l->next=0;
	}
	return l;
}


static void free_socket_id(struct socket_id* i)
{
	free_name_lst(i->addr_lst);
	pkg_free(i);
}


static void free_socket_id_lst(struct socket_id* lst)
{
	struct socket_id* tmp;
	
	while(lst){
		tmp=lst;
		lst=lst->next;
		free_socket_id(tmp);
	}
}


/** create a temporary case statmenet structure.
 *  *err will be filled in case of error (return == 0):
 *   -1 - non constant expression
 *   -2 - expression error (bad type)
 *   -10 - memory allocation error
 */
static struct case_stms* mk_case_stm(struct rval_expr* ct, int is_re,
											struct action* a, int* err)
{
	struct case_stms* s;
	struct rval_expr* bad_rve;
	enum rval_type type, bad_t, exp_t;
	enum match_str_type t;
	
	t=MATCH_UNKNOWN;
	if (ct){
		/* if ct!=0 => case, else if ct==0 is a default */
		if (!rve_is_constant(ct)){
			yyerror_at(&ct->fpos, "non constant expression in case");
			*err=-1;
			return 0;
		}
		if (rve_check_type(&type, ct, &bad_rve, &bad_t, &exp_t)!=1){
			yyerror_at(&ct->fpos, "bad expression: type mismatch:"
							" %s instead of %s at (%d,%d)",
							rval_type_name(bad_t), rval_type_name(exp_t),
							bad_rve->fpos.s_line, bad_rve->fpos.s_col);
			*err=-2;
			return 0;
		}
		if (is_re)
			t=MATCH_RE;
		else if (type==RV_STR)
			t=MATCH_STR;
		else
			t=MATCH_INT;
	}

	s=pkg_malloc(sizeof(*s));
	if (s==0) {
		yyerror("internal error: memory allocation failure");
		*err=-10;
	} else {
		memset(s, 0, sizeof(*s));
		s->ct_rve=ct;
		s->type=t;
		s->actions=a;
		s->next=0;
		s->append=0;
	}
	return s;
}


/*
 * @return 0 on success, -1 on error.
 */
static int case_check_type(struct case_stms* stms)
{
	struct case_stms* c;
	struct case_stms* s;
	
	for(c=stms; c ; c=c->next){
		if (!c->ct_rve) continue;
		for (s=c->next; s; s=s->next){
			if (!s->ct_rve) continue;
			if ((s->type!=c->type) &&
				!(	(c->type==MATCH_STR || c->type==MATCH_RE) &&
					(s->type==MATCH_STR || s->type==MATCH_RE) ) ){
					yyerror_at(&s->ct_rve->fpos, "type mismatch in case");
					return -1;
			}
		}
	}
	return 0;
}


/*
 * @return 0 on success, -1 on error.
 */
static int case_check_default(struct case_stms* stms)
{
	struct case_stms* c;
	int default_no;
	
	default_no=0;
	for(c=stms; c ; c=c->next)
		if (c->ct_rve==0) default_no++;
	return (default_no<=1)?0:-1;
}



/** fixes the parameters and the type of a module function call.
 * It is done here instead of fix action, to have quicker feedback
 * on error cases (e.g. passing a non constant to a function with a 
 * declared fixup) 
 * The rest of the fixup is done inside do_action().
 * @param a - filled module function call (MODULE*_T) action structure
 *            complete with parameters, starting at val[2] and parameter
 *            number at val[1].
 * @return 0 on success, -1 on error (it will also print the error msg.).
 *
 */
static int mod_f_params_pre_fixup(struct action* a)
{
	sr31_cmd_export_t* cmd_exp;
	action_u_t* params;
	int param_no;
	struct rval_expr* rve;
	struct rvalue* rv;
	int r;
	str s;
	
	cmd_exp = a->val[0].u.data;
	param_no = a->val[1].u.number;
	params = &a->val[2];
	
	switch(cmd_exp->param_no) {
		case 0:
			a->type = MODULE0_T;
			break;
		case 1:
			a->type = MODULE1_T;
			break;
		case 2:
			a->type = MODULE2_T;
			break;
		case 3:
			a->type = MODULE3_T;
			break;
		case 4:
			a->type = MODULE4_T;
			break;
		case 5:
			a->type = MODULE5_T;
			break;
		case 6:
			a->type = MODULE6_T;
			break;
		case VAR_PARAM_NO:
			a->type = MODULEX_T;
			break;
		default:
			yyerror("function %s: bad definition"
					" (invalid number of parameters)", cmd_exp->name);
			return -1;
	}
	
	if ( cmd_exp->fixup) {
		if (is_fparam_rve_fixup(cmd_exp->fixup))
			/* mark known fparam rve safe fixups */
			cmd_exp->fixup_flags  |= FIXUP_F_FPARAM_RVE;
		else if (!(cmd_exp->fixup_flags & FIXUP_F_FPARAM_RVE) &&
				 cmd_exp->free_fixup == 0) {
			/* v0 or v1 functions that have fixups and no coresp. fixup_free
			   functions, need constant, string params.*/
			for (r=0; r < param_no; r++) {
				rve=params[r].u.data;
				if (!rve_is_constant(rve)) {
					yyerror_at(&rve->fpos, "function %s: parameter %d is not"
								" constant\n", cmd_exp->name, r+1);
					return -1;
				}
				if ((rv = rval_expr_eval(0, 0, rve)) == 0 ||
						rval_get_str(0, 0, &s, rv, 0) < 0 ) {
					/* out of mem or bug ? */
					rval_destroy(rv);
					yyerror_at(&rve->fpos, "function %s: bad parameter %d"
									" expression\n", cmd_exp->name, r+1);
					return -1;
				}
				rval_destroy(rv);
				rve_destroy(rve);
				params[r].type = STRING_ST; /* asciiz */
				params[r].u.string = s.s;
				params[r].u.str.len = s.len; /* not used right now */
			}
		}
	}/* else
		if no fixups are present, the RVEs can be transformed
		into strings at runtime, allowing seamless var. use
		even with old functions.
		Further optimizations -> in fix_actions()
		*/
	return 0;
}



/** frees a filled module function call action structure.
 * @param a - filled module function call action structure
 *            complete with parameters, starting at val[2] and parameter
 *            number at val[1].
 */
static void free_mod_func_action(struct action* a)
{
	action_u_t* params;
	int param_no;
	int r;
	
	param_no = a->val[1].u.number;
	params = &a->val[2];
	
	for (r=0; r < param_no; r++)
		if (params[r].u.data)
			rve_destroy(params[r].u.data);
	pkg_free(a);
}



/*
int main(int argc, char ** argv)
{
	if (yyparse()!=0)
		fprintf(stderr, "parsing error\n");
}
*/
