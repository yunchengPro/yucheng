/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.16
Source Server Version : 50717
Source Host           : 192.168.1.16:3306
Source Database       : nnh

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2017-11-07 15:42:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for art_category
-- ----------------------------
DROP TABLE IF EXISTS `art_category`;
CREATE TABLE `art_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `parentid` int(1) DEFAULT '0' COMMENT '父类ID',
  `categoryname` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `category_icon` varchar(255) DEFAULT '' COMMENT '分类图标',
  `sort` int(1) DEFAULT '0' COMMENT '排序',
  `catetype` tinyint(1) DEFAULT '0' COMMENT '分类类型1为牛品分类2为牛店分类',
  `enable` tinyint(1) DEFAULT '1' COMMENT '启用状态1启用-1禁用',
  `isdelete` tinyint(1) DEFAULT '0' COMMENT '是否删除0未删除1已删除',
  `addtime` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='资讯分类表';

-- ----------------------------
-- Records of art_category
-- ----------------------------
INSERT INTO `art_category` VALUES ('1', '0', '数码', '', '3', '1', '1', '0', '2017-09-11 15:00:45');
INSERT INTO `art_category` VALUES ('2', '0', '美搭', '', '8', '1', '-1', '0', '2017-09-11 14:46:20');
INSERT INTO `art_category` VALUES ('3', '0', '时尚', '', '7', '1', '1', '0', '2017-09-04 21:23:36');
INSERT INTO `art_category` VALUES ('4', '0', '居家', '', '6', '1', '1', '0', '2017-09-04 21:23:36');
INSERT INTO `art_category` VALUES ('5', '0', '美搭1', '', '1', '1', '1', '0', '2017-09-11 18:04:15');
INSERT INTO `art_category` VALUES ('6', '0', '数码', '', '2', '2', '1', '0', '2017-09-11 15:08:42');
INSERT INTO `art_category` VALUES ('7', '0', '美搭', '', '4', '2', '1', '0', '2017-09-11 17:51:06');
INSERT INTO `art_category` VALUES ('8', '0', '时尚', '', '6', '2', '1', '0', '2017-09-11 17:54:46');
INSERT INTO `art_category` VALUES ('9', '0', '美搭1', '', '1', '2', '1', '0', '2017-09-11 18:04:04');
INSERT INTO `art_category` VALUES ('12', '0', '数码1', '', '5', '2', '1', '0', '2017-09-04 21:26:06');
INSERT INTO `art_category` VALUES ('13', '0', '数码3', '', '3', '2', '1', '1', '2017-09-11 15:08:29');
INSERT INTO `art_category` VALUES ('14', '0', '科技', 'NNH/images/2017-09-04/1504520685sk486.jpg', '8', '2', '1', '0', '2017-09-05 21:19:56');
INSERT INTO `art_category` VALUES ('15', '0', '娱乐', 'NNH/images/2017-09-04/1504520825il872.jpg', '7', '2', '1', '0', '2017-09-04 18:27:10');
INSERT INTO `art_category` VALUES ('16', '0', '拍卖', 'NNH/images/2017-09-04/1504522624rq324.jpg', '4', '1', '1', '1', '2017-09-11 14:49:08');
INSERT INTO `art_category` VALUES ('17', '0', '头条', 'NNH/images/2017-09-04/1504522854ya613.jpg', '5', '1', '1', '0', '2017-09-04 21:23:36');
INSERT INTO `art_category` VALUES ('18', '0', '这好', 'NNH/images/2017-09-11/1505112670mf956.png', '2', '1', '1', '1', '2017-09-11 14:51:34');
INSERT INTO `art_category` VALUES ('19', '0', '大胆', 'NNH/images/2017-09-11/1505112912hq998.png', '23', '1', '1', '1', '2017-09-11 15:08:55');
INSERT INTO `art_category` VALUES ('20', '0', '大答复', 'NNH/images/2017-09-11/1505113786yq581.png', '2', '1', '1', '1', '2017-09-11 15:21:34');
