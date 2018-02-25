/*
Navicat MySQL Data Transfer

Source Server         : 47.52.246.121
Source Server Version : 50636
Source Host           : 47.52.246.121:3306
Source Database       : ttcbet

Target Server Type    : MYSQL
Target Server Version : 50636
File Encoding         : 65001

Date: 2018-01-23 14:04:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for conf_templates
-- ----------------------------
DROP TABLE IF EXISTS `conf_templates`;
CREATE TABLE `conf_templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL COMMENT '模板类型 1=PC前端模板，2=PC移动模板,3=代理模板',
  `value` varchar(255) DEFAULT NULL COMMENT '模板名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of conf_templates
-- ----------------------------
INSERT INTO `conf_templates` VALUES ('1', '1', 'template_one', '2018-01-20 11:56:34', '2018-01-20 11:56:36');
INSERT INTO `conf_templates` VALUES ('2', '2', 'mobile', '2018-01-20 11:57:03', '2018-01-20 11:57:06');
INSERT INTO `conf_templates` VALUES ('3', '3', 'template_agent_one', '2018-01-20 11:57:33', '2018-01-20 11:57:37');
INSERT INTO `conf_templates` VALUES ('4', '3', 'Template_Agent_Admin_One', '2018-01-20 11:58:12', '2018-01-22 19:27:12');
INSERT INTO `conf_templates` VALUES ('5', '1', '11111111111111111', '2018-01-20 14:19:04', '2018-01-20 14:19:04');
INSERT INTO `conf_templates` VALUES ('6', '2', '111111111111122222222222', '2018-01-20 14:19:54', '2018-01-20 14:50:20');
INSERT INTO `conf_templates` VALUES ('7', '2', '6564', '2018-01-20 14:50:53', '2018-01-20 14:50:53');
INSERT INTO `conf_templates` VALUES ('8', '2', '121212', '2018-01-22 15:17:43', '2018-01-22 15:17:43');
