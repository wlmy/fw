/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : fuwu

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-10-18 19:34:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for contract
-- ----------------------------
DROP TABLE IF EXISTS `contract`;
CREATE TABLE `contract` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `contractId` char(100) NOT NULL DEFAULT '0' COMMENT '合同ID',
  `minutePerPeriod` char(100) NOT NULL DEFAULT '0' COMMENT '单课时长',
  `unitPrice` char(100) NOT NULL DEFAULT '0' COMMENT '公司报价',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of contract
-- ----------------------------
INSERT INTO `contract` VALUES ('1', 'T234234234242', 'erewrwerewrw', '100', '2017-10-17 17:06:27', '2017-10-17 17:06:27');
INSERT INTO `contract` VALUES ('2', 'T234233', 'erewrwerewrw', '100', '2017-10-17 17:09:17', '2017-10-17 17:09:17');
INSERT INTO `contract` VALUES ('3', '', '', '', '2017-10-17 17:39:02', '2017-10-17 17:39:02');
INSERT INTO `contract` VALUES ('4', 'X20011710000408', '45', '160', '2017-10-17 17:46:25', '2017-10-17 17:46:25');
INSERT INTO `contract` VALUES ('5', 'T234233w', 'erewdee', '100', '2017-10-17 18:24:54', '2017-10-17 18:24:54');
INSERT INTO `contract` VALUES ('6', 'X20011710000416', '45', '120', '2017-10-17 18:49:56', '2017-10-17 18:49:56');
INSERT INTO `contract` VALUES ('7', 'X20011710000419', '60', '{\"text\":\"高一A-40课时\",\"value\":\"6\",\"price\":\"250元/课时\"}', '2017-10-17 19:21:15', '2017-10-17 19:21:15');
INSERT INTO `contract` VALUES ('8', 'T234233ddddddddd', '34534534535fdgdfgfdgfdgdgdgdgdgdgdg', '100', '2017-10-18 13:48:11', '2017-10-18 13:48:11');
