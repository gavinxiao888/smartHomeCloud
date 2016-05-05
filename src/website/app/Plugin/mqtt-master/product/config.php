<?php

define("CONFIG_VERSION", 'DEVELOP');//使用配置类型分为 	{测试：TEST、生产：PRODUCE、开发：DEVELOP

switch (CONFIG_VERSION) {
	case 'TEST':
		define("DB_IP", '192.168.9.162');////数据库IP地址
		define("DB_PORT", 3306);//数据库端口
		define("DB_USER", 'iteuser');//数据库用户名
		define("DB_PASSWD", 'itepass');//数据库密码
		define("DB_NAME", 'everyoov2');//数据库

        define('MQTT_SERVER', 'tcp://192.168.100.166:1883/');//MQTT服务器端口
        define('MQTT_USER', '1');//MQTT用户
        define('MQTT_PASSWD', '1');//MQTT密码

        define('SETTING_INTERFACE', 'http://192.168.100.165:86');//查询SETTING信息的接口地址

        define('DOMAIN', '192.168.100.165');

		// define('REDIS_IP','192.168.100.165');//Redis Server的IP
		define('REDIS_IP','127.0.0.1');
		define('REDIS_PORT', 6379);//Redis Server的端口
				
		break;
		
	case 'PRODUCE':
		define("DB_IP", '192.168.101.12');////数据库IP地址
		define("DB_PORT", 3306);//数据库端口
		define("DB_USER", 'iteuser');//数据库用户名
		define("DB_PASSWD", 'itepasswd');//数据库密码
		define("DB_NAME", 'everyoov2');//数据库

        define('MQTT_SERVER', 'tcp://192.168.100.166:1883/');//MQTT服务器端口
        define('MQTT_USER', '1');//MQTT用户
        define('MQTT_PASSWD', '1');//MQTT密码

        define('SETTING_INTERFACE', 'http://192.168.100.165:86');//查询SETTING信息的接口地址

        define('DOMAIN', 'everyoo.com');

		define('REDIS_IP','192.168.101.12');//Redis Server的IP
		define('REDIS_PORT', 6379);//Redis Server的端口
		
		break;
		
	case 'DEVELOP':
		define("DB_IP", '192.168.100.166');////数据库IP地址
		define("DB_PORT", 3306);//数据库端口
		define("DB_USER", 'root');//数据库用户名
		define("DB_PASSWD", 'everyoo');//数据库密码
		define("DB_NAME", 'everyoo');//数据库

        define('MQTT_SERVER', 'tcp://192.168.100.166:1883/');//MQTT服务器端口
        define('MQTT_USER', '1');//MQTT用户
        define('MQTT_PASSWD', '1');//MQTT密码

        define('SETTING_INTERFACE', 'http://192.168.100.165:86');//查询SETTING信息的接口地址

        define('DOMAIN', '127.0.0.1');

		define('REDIS_IP','192.168.100.166');//Redis Server的IP
		define('REDIS_PORT', 6379);//Redis Server的端口
	
		break;
	case 'bendi':
		define("DB_IP", '127.0.0.1');////数据库IP地址
		define("DB_PORT", 3306);//数据库端口
		define("DB_USER", 'root');//数据库用户名
		define("DB_PASSWD", '');//数据库密码
		define("DB_NAME", 'everyoo');//数据库

        define('MQTT_SERVER', 'tcp://192.168.100.166:1883/');//MQTT服务器端口
        define('MQTT_USER', '1');//MQTT用户
        define('MQTT_PASSWD', '1');//MQTT密码

        define('SETTING_INTERFACE', 'http://192.168.100.165:86');//查询SETTING信息的接口地址

        define('DOMAIN', '127.0.0.1');

		define('REDIS_IP','192.168.100.166');//Redis Server的IP
		define('REDIS_PORT', 6379);//Redis Server的端口
		
		break;
	default:
		break;
}

define('SMS_USER', 'Zhsh888');//短信账户用户名
define('SMS_PASSWD', 'Zhsh888888');//短信账户密码


define('EMAIL_DRIVER', 'smtp');//EMAIL的服务驱动
define('EMAIL_HOST', 'smtp.everyoo.com');//EMAIL的服务器地址
define('EMAIL_PORT', '25');//EMAIL的端口
define('EMAIL_FROM_ADDRESS', 'sdsmartlife@everyoo.com');//EMAIL发送地址
define('EMAIL_FROM_NAME', '山东智慧生活数据系统有限公司');//EMAIL发送名称
define('EMAIL_USERNAME', 'sdsmartlife@everyoo.com');//EMAIL地址
define('EMAIL_PASSWD', 'everyoo');
define('ENCRYPTION', '');?>