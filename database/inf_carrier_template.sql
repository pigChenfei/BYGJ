/*
Navicat MySQL Data Transfer

Source Server         : 47.52.246.121
Source Server Version : 50636
Source Host           : 47.52.246.121:3306
Source Database       : ttcbet

Target Server Type    : MYSQL
Target Server Version : 50636
File Encoding         : 65001

Date: 2018-01-23 14:04:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inf_carrier_template
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_template`;
CREATE TABLE `inf_carrier_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '运营商id',
  `template_id` int(11) NOT NULL COMMENT '模板id',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of inf_carrier_template
-- ----------------------------
INSERT INTO `inf_carrier_template` VALUES ('2', '1', '1', '2018-01-22 16:56:22', '2018-01-22 16:56:22');
INSERT INTO `inf_carrier_template` VALUES ('5', '1', '2', '2018-01-22 16:57:50', '2018-01-22 16:57:50');
INSERT INTO `inf_carrier_template` VALUES ('6', '2', '2', '2018-01-22 16:57:50', '2018-01-22 16:57:50');
INSERT INTO `inf_carrier_template` VALUES ('11', '1', '5', '2018-01-22 16:57:50', '2018-01-22 16:57:50');
INSERT INTO `inf_carrier_template` VALUES ('12', '2', '5', '2018-01-22 16:57:50', '2018-01-22 16:57:50');
INSERT INTO `inf_carrier_template` VALUES ('13', '1', '6', '2018-01-22 16:57:50', '2018-01-22 16:57:50');
INSERT INTO `inf_carrier_template` VALUES ('14', '2', '6', '2018-01-22 16:57:50', '2018-01-22 16:57:50');
INSERT INTO `inf_carrier_template` VALUES ('15', '1', '7', '2018-01-22 16:57:50', '2018-01-22 16:57:50');
INSERT INTO `inf_carrier_template` VALUES ('16', '2', '7', '2018-01-22 16:57:50', '2018-01-22 16:57:50');
INSERT INTO `inf_carrier_template` VALUES ('17', '1', '8', '2018-01-22 16:57:50', '2018-01-22 16:57:50');
INSERT INTO `inf_carrier_template` VALUES ('18', '2', '8', '2018-01-22 16:57:50', '2018-01-22 16:57:50');
INSERT INTO `inf_carrier_template` VALUES ('19', '1', '3', '2018-01-22 16:58:41', '2018-01-22 16:58:41');
INSERT INTO `inf_carrier_template` VALUES ('20', '1', '4', '2018-01-22 16:58:41', '2018-01-22 16:58:41');
