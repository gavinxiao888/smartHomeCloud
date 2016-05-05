#################################################################################################################
# Copyright (C), 2016-2017,  Everyoo. Co., Ltd.
#  File name:      smarthomecloud.sql
#  Author:  maxuejie     Version: 0.1.0       DATE: 2016.03.09
#  Description:   Use this file to create all tables about smarthomecloud in db
#  History:          
#    1. DATE:	2016.03.09
#       Author:  maxuejie 
#       Modification:   init this file
#################################################################################################################

#####################Create smarthomecloud database#####################################################################
DROP  DATABASE IF EXISTS  `smarthomecloud`;
CREATE DATABASE  `smarthomecloud` DEFAULT CHARSET utf8;
USE	`smarthomecloud`;
#####################Create svowdb database end##################################################################

SET FOREIGN_KEY_CHECKS=0;

#########################################Tables##################################################################

/* 反馈内容 */
#create feedback_content table
CREATE TABLE `feedback_content` (
  `id` varchar(36) NOT NULL COMMENT '主键',
  `feedback_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `content` varchar(100) DEFAULT NULL COMMENT '反馈联系方式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
#create feedback_content table end

/* 用户反馈 */
#create user_feedback table
CREATE TABLE `user_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` varchar(36) DEFAULT NULL COMMENT '用户ID',
  `tel` varchar(100) DEFAULT NULL COMMENT '反馈联系方式',
  `init_time` datetime DEFAULT NULL COMMENT '提交时间',
  `remark` varchar(300) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
#create user_feedback table end

/* 管理员列表 */
#create admin_manage table
CREATE TABLE admin_manage (
  `id` char(36) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sex` tinyint(100) DEFAULT '1',
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `remark` varchar(300) DEFAULT NULL,
  `role` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create admin_manage table end

/* 手机App发送验证码表 */
#create app_auth_code table
CREATE TABLE app_auth_code (
  `id` char(36) NOT NULL,
  `code` varchar(200) DEFAULT NULL,
  `user_id` char(36) DEFAULT NULL,
  `type` tinyint(4) DEFAULT '1',
  `user_name` varchar(200) DEFAULT NULL,
  `device_id` varchar(200) DEFAULT NULL,
  `app_id` varchar(200) DEFAULT NULL,
  `expire_time` datetime DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `remark` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create app_auth_code table end

/* 指令集定义表 */
#create define_action table
CREATE TABLE define_action (
  `id` char(36) NOT NULL,
  `action_name` varchar(100) DEFAULT NULL,
  `content` varchar(300) DEFAULT NULL,
  `protocol` tinyint(4) DEFAULT '1',
  `init_time` datetime DEFAULT NULL,
  `charge_id` char(36) DEFAULT NULL,
  `enable` tinyint(4) DEFAULT '1',
  `update_time` datetime DEFAULT NULL,
  `remark` varchar(300) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#create define_action table end

/* 设备指令集映射表 */
#create define_ctrl table
CREATE TABLE define_ctrl (
  `id` char(36) NOT NULL,
  `bind_id` char(36) DEFAULT NULL,
  `ctrl_id` varchar(36) DEFAULT NULL,
  `node_id` int(12) DEFAULT NULL,
  `device_id` varchar(36) DEFAULT NULL,
  `action_id` char(36) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `charge_id` char(36) DEFAULT NULL,
  `enable` tinyint(4) DEFAULT '1',
  `update_time` datetime DEFAULT NULL,
  `remark` varchar(300) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create define_ctrl table end

/* 联动定义表 */
#create define_link_age table
CREATE TABLE define_link_age (
  `id` char(36) NOT NULL,
  `bind_id` varchar(36) DEFAULT NULL,
  `link_age_id` varchar(36) DEFAULT NULL,
  `link_age_name` varchar(100) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `begin` varchar(36) DEFAULT NULL,
  `end` varchar(36) DEFAULT NULL,
  `triggered` varchar(300) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `enable` tinyint(4) DEFAULT '1',
  `status` tinyint(4) DEFAULT '1',
  `remark` varchar(300) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create define_link_age table end

/* 场景定义表 */
#create define_robot table
CREATE TABLE `define_robot` (
  `id` char(36) NOT NULL,
  `bind_id` varchar(36) DEFAULT NULL,
  `robot_id` varchar(36) DEFAULT NULL,
  `robot_name` varchar(100) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `begin` varchar(36) DEFAULT NULL,
  `end` varchar(36) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `charge_id` varchar(36) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `enable` tinyint(4) DEFAULT '1',
  `status` tinyint(4) DEFAULT '1',
  `is_init` tinyint(4) DEFAULT '0' COMMENT '1绑定网关创建默认5个不可删除的场景，0非',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create define_robot table end

/* 定时定义表 */
#create define_timer table
CREATE TABLE `define_timer` (
  `id` char(36) NOT NULL,
  `bind_id` varchar(36) DEFAULT NULL,
  `timer_id` int(11) DEFAULT NULL,
  `ctrl_id` varchar(36) DEFAULT NULL,
  `device_id` varchar(36) DEFAULT NULL,
  `alarm_time` datetime DEFAULT NULL,
  `loop` varchar(100) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `create_time` varchar(100) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `charge_id` varchar(36) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `enable` tinyint(4) DEFAULT '1',
  `remark` varchar(300) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create define_timer table end

/* 联动条件定义表 */
#create define_triggered table
CREATE TABLE `define_triggered` (
  `id` char(36) NOT NULL,
  `bind_id` varchar(36) DEFAULT NULL,
  `triggered_id` varchar(36) DEFAULT NULL,
  `ctrl_id` varchar(36) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `rule` tinyint(4) DEFAULT NULL,
  `deviceid` int(10) default null,
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `enable` tinyint(4) DEFAULT '1',
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create define_triggered table end

/* 设备指令集映射表 */
#create device_action table
CREATE TABLE `device_action` (
  `id` char(36) NOT NULL,
  `device_type_id` int(12) DEFAULT NULL,
  `action_id` char(36) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `charge_id` char(36) DEFAULT NULL,
  `enable` tinyint(4) DEFAULT '1',
  `update_time` datetime DEFAULT NULL,
  `remark` varchar(300) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create device_action table end

/* 单类设备与类型映射 */
#create device_attributes table
CREATE TABLE `device_attributes` (
  `id` char(36) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `manufacturer_id` varchar(200) DEFAULT NULL,
  `product_id` varchar(200) DEFAULT NULL,
  `product_type` varchar(200) DEFAULT NULL,
  `device_type` varchar(100) DEFAULT NULL,
  `charge_id` char(36) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `delete_id` char(36) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `remark` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create device_attributes table end

/* 设备日志表 */
#create device_log table
CREATE TABLE `device_log` (
  `id` char(36) NOT NULL,
  `log_id` int(11) DEFAULT NULL,
  `user_id` char(36) DEFAULT NULL,
  `bind_id` char(36) DEFAULT NULL,
  `device_type` tinyint(4) DEFAULT NULL,
  `device_id` varchar(36) DEFAULT NULL,
  `event_time` datetime DEFAULT NULL,
  `ctrl_id` varchar(100) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create device_log table end

/* 设备管理表 */
#create device_manage table
CREATE TABLE `device_manage` (
  `id` char(36) NOT NULL,
  `bind_id` char(36) DEFAULT NULL,
  `device_type` varchar(100) DEFAULT NULL,
  `device_id` int(36) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `remark` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create device_manage table end

/*网关的设备对应关系备份*/
CREATE TABLE `device_relation` (
  `id` char(36) NOT NULL,
  `gateway_id` varchar(100) DEFAULT NULL COMMENT '网关id',
  `device_id` int(10) DEFAULT NULL COMMENT '设备id',
  `device_type` int(10) DEFAULT NULL COMMENT '设备类型',
  `init_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网关的设备对应关系备份'
#create device_relation table end

/* 设备分类 */
#create device_type table
CREATE TABLE `device_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `type_condition` tinyint(4) DEFAULT '0' COMMENT '条件分类：1条件,2不可做条件',
  `type_controll` tinyint(4) DEFAULT '0' COMMENT '控制分类: 1可控制，2不可控制',
  `type_feature` tinyint(4) DEFAULT '0' COMMENT '按功能分类：1灯光,2传感器,3窗帘,4插座',
  `status` tinyint(4) DEFAULT '1',
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `charge_id` char(36) DEFAULT '7de3346a-82be-11e5-a4a7-abd117feb58f',
  `delete_id` char(36) DEFAULT NULL,
  `remark` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create device_type table end

/* 故障记录表 */
#create fault_remark table
CREATE TABLE `fault_remark` (
  `id` char(36) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `county` varchar(100) DEFAULT NULL,
  `community` varchar(100) DEFAULT NULL,
  `floor` varchar(100) DEFAULT NULL,
  `tel` varchar(100) DEFAULT NULL,
  `fault_time` datetime DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `charge_id` char(36) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `remark` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create fault_remark table end

/* 网关管理表 */
#create gateway_manage table
CREATE TABLE `gateway_manage` (
  `id` varchar(36) NOT NULL,
  `init_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `remark` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create gateway_manage table end

/* 网关地理位置信息记录 */
#create gateway_remark table
CREATE TABLE `gateway_remark` (
  `id` char(36) NOT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `county` varchar(100) DEFAULT NULL,
  `community` varchar(100) DEFAULT NULL,
  `floor` varchar(100) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `charge_id` char(36) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `remark` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#create gateway_remark table end

/* 网关在线状态 */
#create gateway_status table
CREATE TABLE `gateway_status` (
  `id` char(36) NOT NULL,
  `online` tinyint(4) DEFAULT '1',
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `remark` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create gateway_status table end


/* 主\子用户表 */
#create gateway_user_account table
CREATE TABLE `gateway_user_account` (
  `id` char(36) NOT NULL,
  `bind_id` char(36) DEFAULT NULL,
  `user_id` char(36) DEFAULT NULL,
  `role` tinyint(4) DEFAULT '1',
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `remark` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create gateway_user_account table end

/* 网关版本信息管理表 */
#create gateway_version_manage table
CREATE TABLE `gateway_version_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` int(20) DEFAULT NULL COMMENT '版本号',
  `href` varchar(200) DEFAULT NULL COMMENT '下载地址',
  `charage_id` varchar(100) DEFAULT NULL COMMENT '上传者',
  `type` tinyint(4) DEFAULT '1' COMMENT '版本类型，默认网关',
  `init_time` datetime DEFAULT NULL COMMENT '添加时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `enable` tinyint(4) DEFAULT '1' COMMENT '启用状态',
  `status` tinyint(4) DEFAULT '1' COMMENT '删除状态',
  `remark` varchar(300) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
#create gateway_version_manage table end

/* 联动指令集映射表 */
#create link_age_ctrl table
CREATE TABLE `link_age_ctrl` (
  `id` char(36) NOT NULL,
  `bind_id` varchar(36) DEFAULT NULL,
  `link_age_id` varchar(36) DEFAULT NULL,
  `ctrl_id` varchar(36) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `deviceid` INT(10) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `enable` tinyint(4) DEFAULT '1',
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create link_age_ctrl table end

/* 手机APP注册信息管理表 */
#create register table
CREATE TABLE `register` (
  `id` char(36) NOT NULL,
  `user` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `app_type` varchar(200) DEFAULT NULL,
  `app_name` varchar(200) DEFAULT NULL,
  `app_key` varchar(200) DEFAULT NULL,
  `platform_type` tinyint(4) DEFAULT NULL,
  `device_id` varchar(200) DEFAULT NULL,
  `push_id` varchar(200) DEFAULT NULL,
  `captcha` varchar(200) DEFAULT NULL,
  `expire_time` datetime DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `remark` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create register table end

/* 场景指令集映射表 */
#create robot_ctrl table
CREATE TABLE `robot_ctrl` (
  `id` char(36) NOT NULL,
  `bind_id` varchar(36) DEFAULT NULL,
  `robot_id` varchar(36) DEFAULT NULL,
  `device_id` int(11) DEFAULT NULL,
  `ctrl_id` varchar(36) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `enable` tinyint(4) DEFAULT '1',
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create robot_ctrl table end

/* 服务指南记录表 */
#create terms_of_service table
CREATE TABLE `terms_of_service` (
  `id` char(36) NOT NULL,
  `page_name` varchar(100) DEFAULT NULL,
  `content` varchar(10000) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `charge_id` char(36) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `remark` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create terms_of_service table end

/* 第三方登录记录表 */
#create third_parties table
CREATE TABLE `third_parties` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `app_id` varchar(200) DEFAULT NULL,
  `platform` tinyint(4) DEFAULT '1',
  `device_id` varchar(200) DEFAULT NULL,
  `third_type` tinyint(4) DEFAULT '1',
  `third_id` varchar(100) DEFAULT NULL,
  `third_name` int(16) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `role` tinyint(4) DEFAULT '0',
  `status` tinyint(4) DEFAULT '1',
  `remark` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create third_parties table end

/* APP登录token表 */
#create token table
CREATE TABLE `token` (
  `id` char(36) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `user_id` char(36) NOT NULL,
  `token` varchar(100) NOT NULL,
  `init_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` varchar(300) DEFAULT NULL,
  `remark` varchar(300) DEFAULT NULL,
  `device_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create token table end

/* 手机信息记录表 */
#create user_device table
CREATE TABLE `user_device` (
  `id` char(36) NOT NULL,
  `app_type` varchar(200) DEFAULT NULL,
  `app_name` varchar(200) DEFAULT NULL,
  `app_key` varchar(200) DEFAULT NULL,
  `platform_type` tinyint(4) DEFAULT NULL,
  `device_id` varchar(200) DEFAULT NULL,
  `push_id` varchar(200) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `remark` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create user_device table end

/* 网关绑定关系表 */
#create user_gateway_bind table
CREATE TABLE `user_gateway_bind` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `gateway_id` varchar(100) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `sip_user` varchar(100) DEFAULT NULL,
  `init_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `remark` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create user_gateway_bind table end

/* 用户详细信息表 */
#create user_info table
CREATE TABLE `user_info` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `real_name` varchar(50) DEFAULT NULL,
  `gender` int(1) NOT NULL DEFAULT '0',
  `birthday` datetime DEFAULT NULL,
  `problem_first` char(36) NOT NULL,
  `problem_second` char(36) NOT NULL,
  `problem_third` char(36) NOT NULL,
  `answer_first` varchar(100) NOT NULL,
  `answer_second` varchar(100) NOT NULL,
  `answer_third` varchar(100) NOT NULL,
  `headimg` varchar(1000) DEFAULT NULL,
  `signature` varchar(1000) DEFAULT NULL,
  `safe_level` int(1) NOT NULL DEFAULT '0',
  `delete` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create user_info table end

/* 用户表 */
#create user table
CREATE TABLE `user` (
  `id` char(36) NOT NULL,
  `init_time` datetime NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `passwd` char(32) NOT NULL,
  `delete` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#create user table end

/*修改password字段长度为36*/
ALTER TABLE `subscriber` CHANGE `password` `password` VARCHAR(36) CHARSET latin1 COLLATE latin1_swedish_ci DEFAULT '' NOT NULL;

#########################################Tables End###############################################################

SET FOREIGN_KEY_CHECKS=1;
