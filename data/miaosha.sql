-- MySQL dump 10.13  Distrib 5.7.18, for osx10.10 (x86_64)
--
-- Host: localhost    Database: miaosha
-- ------------------------------------------------------
-- Server version	5.7.18

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ms_active`
--

DROP TABLE IF EXISTS `ms_active`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_active` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动ID',
  `title` varchar(255) NOT NULL COMMENT '活动名称',
  `time_begin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `time_end` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `sys_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sys_lastmodify` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `sys_status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '状态，0 待上线，1 已上线，2 已下线',
  `sys_ip` varchar(50) NOT NULL COMMENT '创建人IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='活动信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_active`
--

LOCK TABLES `ms_active` WRITE;
/*!40000 ALTER TABLE `ms_active` DISABLE KEYS */;
INSERT INTO `ms_active` VALUES (1,'360手机N5首批抢购',1500184800,1532588400,1500178490,1500825650,1,'127.0.0.1'),(2,'360手机N5s抢购',1499652000,1532923200,1500179072,1500825643,2,'127.0.0.1');
/*!40000 ALTER TABLE `ms_active` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_goods`
--

DROP TABLE IF EXISTS `ms_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `active_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动ID',
  `title` varchar(255) NOT NULL COMMENT '商品名称',
  `description` text NOT NULL COMMENT '描述信息，文本，要支持HTML',
  `img` varchar(255) NOT NULL COMMENT '小图标，列表中显示',
  `price_normal` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '原价',
  `price_discount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '秒杀价',
  `num_total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总数量',
  `num_user` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '单个用户限购数量',
  `num_left` int(11) NOT NULL DEFAULT '0' COMMENT '剩余可购买数量',
  `sys_dateline` int(11) NOT NULL DEFAULT '0' COMMENT '信息创建时间',
  `sys_lastmodify` int(11) NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `sys_status` int(11) NOT NULL DEFAULT '0' COMMENT '状态，0 待上线，1 已上线，2 已下线',
  `sys_ip` varchar(50) NOT NULL COMMENT '创建人的IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='商品信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_goods`
--

LOCK TABLES `ms_goods` WRITE;
/*!40000 ALTER TABLE `ms_goods` DISABLE KEYS */;
INSERT INTO `ms_goods` VALUES (1,1,'360手机N5','6G大内存，超快不卡顿','/static/goods/star-product-n5.jpg',1399,1299,10000,1,10000,1500190931,1500827880,1,'127.0.0.1'),(2,2,'360手机N5s','360手机N5s','/static/goods/star-product-n5s.jpg',1699,1599,10000,1,10000,1500214234,1500827871,1,'127.0.0.1'),(3,2,'360手机N5s低配版','6+32G，极速快充','/static/goods/star-product-n5s.jpg',1499,1399,10000,1,10000,1500822303,1500827863,1,'127.0.0.1');
/*!40000 ALTER TABLE `ms_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_log`
--

DROP TABLE IF EXISTS `ms_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `active_id` int(10) unsigned NOT NULL COMMENT '活动ID',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `action` varchar(50) NOT NULL COMMENT '操作名称',
  `result` varchar(50) NOT NULL COMMENT '返回信息',
  `info` text NOT NULL COMMENT '操作详情,JSON格式保存，比如：POST，refer, 浏览器等信息',
  `sys_dateline` int(10) unsigned NOT NULL COMMENT '创建时间',
  `sys_lastmodify` int(10) unsigned NOT NULL COMMENT '最后修改时间',
  `sys_status` int(10) unsigned NOT NULL COMMENT '状态，0 正常，1 异常，2 已处理的异常',
  `sys_ip` varchar(50) NOT NULL COMMENT '用户IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='秒杀的详细操作日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_log`
--

LOCK TABLES `ms_log` WRITE;
/*!40000 ALTER TABLE `ms_log` DISABLE KEYS */;
INSERT INTO `ms_log` VALUES (1,1,1,'buy','success','问答：\r\n浏览器：\r\n商品：',1500286500,1500286500,0,'127.0.0.1'),(2,1,1,'buy','fail','问答：\r\n浏览器：\r\n商品：\r\n验证：',0,0,2,'127.0.0.1'),(3,1,1,'buy','fail','问答：\r\n浏览器：\r\n商品：\r\n验证：',1500287220,1500287220,1,'127.0.0.1');
/*!40000 ALTER TABLE `ms_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_question`
--

DROP TABLE IF EXISTS `ms_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '问答ID',
  `active_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属活动ID',
  `title` varchar(255) NOT NULL COMMENT '问题描述',
  `ask1` varchar(255) NOT NULL COMMENT '问题1',
  `answer1` varchar(255) NOT NULL COMMENT '答案1',
  `ask2` varchar(255) NOT NULL,
  `answer2` varchar(255) NOT NULL,
  `ask3` varchar(255) NOT NULL,
  `answer3` varchar(255) NOT NULL,
  `ask4` varchar(255) NOT NULL,
  `answer4` varchar(255) NOT NULL,
  `ask5` varchar(255) NOT NULL,
  `answer5` varchar(255) NOT NULL,
  `ask6` varchar(255) NOT NULL,
  `answer6` varchar(255) NOT NULL,
  `ask7` varchar(255) NOT NULL,
  `answer7` varchar(255) NOT NULL,
  `ask8` varchar(255) NOT NULL,
  `answer8` varchar(255) NOT NULL,
  `ask9` varchar(255) NOT NULL,
  `answer9` varchar(255) NOT NULL,
  `ask10` varchar(255) NOT NULL,
  `answer10` varchar(255) NOT NULL,
  `sys_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sys_lastmodify` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `sys_status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '状态，0 正常，1 删除',
  `sys_ip` varchar(50) NOT NULL COMMENT '发布人的IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='问答信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_question`
--

LOCK TABLES `ms_question` WRITE;
/*!40000 ALTER TABLE `ms_question` DISABLE KEYS */;
INSERT INTO `ms_question` VALUES (1,1,'下面的哪个是正确的翻译?','春天','spring','夏天','summer','冬天','winter','秋天','autumn','红色','red','蓝色','blue','黄色','yellow','白色','white','黑色','black','橙色','orange',1500198704,1500199367,0,'127.0.0.1'),(2,2,'下面哪个是正确的省会城市','河北','石家庄','河南','郑州','山西','太原','陕西','西安','甘肃','兰州','江西','南昌','浙江','杭州','广东','广州','江苏','南京','安徽','合肥',1500561787,1500561787,0,'127.0.0.1');
/*!40000 ALTER TABLE `ms_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ms_trade`
--

DROP TABLE IF EXISTS `ms_trade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ms_trade` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `active_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动ID',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `num_total` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '购买的单品数量',
  `num_goods` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购买的商品种类数量',
  `price_total` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '订单总金额',
  `price_discount` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '优惠后实际金额',
  `time_confirm` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '确认订单时间',
  `time_pay` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `time_over` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `time_cancel` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '取消时间',
  `goods_info` mediumtext NOT NULL COMMENT '订单商品详情，JSON格式保存',
  `sys_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sys_lastmodify` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `sys_status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '状态，0 初始状态，1 待支付，2 已支付，3 已过期，4 管理员已确认，5 已取消，6 已删除，7 已发货，8 已收货，9 已完成',
  `sys_ip` varchar(50) NOT NULL COMMENT '用户IP',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `active_id` (`active_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='订单信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ms_trade`
--

LOCK TABLES `ms_trade` WRITE;
/*!40000 ALTER TABLE `ms_trade` DISABLE KEYS */;
/*!40000 ALTER TABLE `ms_trade` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-07-24  0:47:21
