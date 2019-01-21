/*
Navicat MySQL Data Transfer

Source Server         : yii-advance-learn
Source Server Version : 50721
Source Host           : 118.25.38.240:3306
Source Database       : yii_advance_test

Target Server Type    : MYSQL
Target Server Version : 50721
File Encoding         : 65001

Date: 2019-01-21 12:46:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wx_message_for_dev
-- ----------------------------
DROP TABLE IF EXISTS `wx_message_for_dev`;
CREATE TABLE `wx_message_for_dev` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `level` tinyint(255) unsigned NOT NULL COMMENT '通知级别',
  `name` varchar(255) NOT NULL COMMENT '开发者姓名',
  `openid` varchar(255) NOT NULL COMMENT '开发者openid',
  `project` varchar(255) NOT NULL COMMENT '项目名称',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
