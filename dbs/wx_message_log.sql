/*
Navicat MySQL Data Transfer

Source Server         : yii-advance-learn
Source Server Version : 50721
Source Host           : 118.25.38.240:3306
Source Database       : yii_advance_test

Target Server Type    : MYSQL
Target Server Version : 50721
File Encoding         : 65001

Date: 2019-01-21 14:53:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wx_message_log
-- ----------------------------
DROP TABLE IF EXISTS `wx_message_log`;
CREATE TABLE `wx_message_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `openid` varchar(100) NOT NULL COMMENT '用户的openid',
  `type` varchar(30) NOT NULL COMMENT '类型，dev：开发者通知',
  `snippet` varchar(255) NOT NULL DEFAULT '' COMMENT '发送的信息摘要',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '插入的时间',
  `errcode` int(11) NOT NULL COMMENT '发送时返回的状态码',
  `errmsg` varchar(120) NOT NULL COMMENT '发送时返回的状态信息',
  `msgid` varchar(120) NOT NULL COMMENT '消息id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
