# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.26)
# Database: Actress
# Generation Time: 2017-01-23 07:15:28 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table Act_Actress_News
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Act_Actress_News`;

CREATE TABLE `Act_Actress_News` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Title` varchar(128) NOT NULL COMMENT '文章标题',
  `ListImage` varchar(200) NOT NULL COMMENT '列表页图片路径',
  `Attachment` varchar(200) NOT NULL COMMENT '附件路径，根据NewType字段判断是音频还是视频',
  `NewsType` tinyint(1) NOT NULL DEFAULT '1' COMMENT '文章类型,见App\\Helper\\Enum\\NewsType',
  `Content` mediumtext NOT NULL COMMENT '文章正文',
  `IsRecommend` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否推荐,1:否,2:是',
  `CreateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布日期',
  `CreatorID` int(11) NOT NULL COMMENT '发布人ID',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态,见App\\Helper\\Enum::Status',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Act_Actress_Recommend
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Act_Actress_Recommend`;

CREATE TABLE `Act_Actress_Recommend` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NickName` varchar(32) NOT NULL COMMENT '主播昵称',
  `AdvImage` varchar(200) NOT NULL COMMENT '海报图片路径',
  `CreateTime` datetime NOT NULL COMMENT '创建日期',
  `CreatorID` int(11) NOT NULL COMMENT '创建人ID',
  `ShowOrder` int(11) NOT NULL DEFAULT '1' COMMENT '排序顺序',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态,见App\\Helper\\Enum::Status',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Act_AdvImage
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Act_AdvImage`;

CREATE TABLE `Act_AdvImage` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Path` varchar(200) NOT NULL COMMENT '文件路径',
  `Position` tinyint(2) NOT NULL COMMENT '发布位置，见App\\Helper\\Enum::AdvPosition',
  `CreateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布日期',
  `CreatorID` int(11) NOT NULL COMMENT '发布人ID',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态,见App\\Helper\\Enum::Status',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

LOCK TABLES `Act_AdvImage` WRITE;
/*!40000 ALTER TABLE `Act_AdvImage` DISABLE KEYS */;

INSERT INTO `Act_AdvImage` (`ID`, `Path`, `Position`, `CreateTime`, `CreatorID`, `Status`)
VALUES
	(1,'http://img.mp.itc.cn/upload/20170122/d1be9987ea1849fc9cb87617b6d6cf6e_th.jpeg',1,'2017-01-22 15:47:14',1,1),
	(2,'http://img.mp.itc.cn/upload/20170122/054ee8ae5ded44daa96271e9ce756ddf_th.jpg',1,'2017-01-22 15:50:11',1,1),
	(3,'http://img3.utuku.china.com/600x0/news/20170120/0ca950db-e7c4-4f18-9a1b-d028a77f959b.jpg',1,'2017-01-22 15:50:22',1,1),
	(4,'http://images.haiwainet.cn/2017/0121/20170121122705540.jpg',1,'2017-01-22 15:50:35',1,1),
	(5,'http://imgtu.lishiquwen.com/20170104/c75881e077f8192a5b9027a37075479e.jpg',1,'2017-01-22 15:50:51',1,1),
	(6,'http://i.guancha.cn/news/2017/01/20/20170120170800270.gif',1,'2017-01-22 15:51:11',1,1),
	(7,'http://oh0w1vops.bkt.clouddn.com/o_1b73agtgfgu01nmoaf11gfs151r7.png',2,'2017-01-22 22:51:47',1,1),
	(8,'http://oh0w1vops.bkt.clouddn.com/o_1b73ak7b61u3eb79hjm1nbtr8o7.png',1,'2017-01-22 22:53:00',1,1),
	(9,'http://oh0w1vops.bkt.clouddn.com/o_1b73amhje25er04110rgqhdp67.png',1,'2017-01-22 22:54:17',1,1),
	(10,'http://oh0w1vops.bkt.clouddn.com/o_1b73aqlud1l4ot1bbb5fqsrg47.png',1,'2017-01-22 22:56:19',1,2),
	(11,'http://oh0w1vops.bkt.clouddn.com/o_1b73bdota1rdusfsqgt1r1v85h7.png',3,'2017-01-22 23:06:56',1,2);

/*!40000 ALTER TABLE `Act_AdvImage` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table Act_Business
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Act_Business`;

CREATE TABLE `Act_Business` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ProjectName` varchar(128) NOT NULL COMMENT '合作项目名称',
  `PublishTime` datetime NOT NULL COMMENT '发布时间',
  `ActressID` varchar(1024) NOT NULL COMMENT '推荐的主播ID，以英文逗号连接',
  `CreatorID` int(11) NOT NULL COMMENT '创建人ID',
  `CreateTime` datetime NOT NULL COMMENT '创建时间',
  `Status` tinyint(1) NOT NULL COMMENT '状态,见App\\Helper\\Enum::Status',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Act_LivePlatform
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Act_LivePlatform`;

CREATE TABLE `Act_LivePlatform` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Platform` varchar(32) NOT NULL DEFAULT '' COMMENT '直播平台',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `Act_LivePlatform` WRITE;
/*!40000 ALTER TABLE `Act_LivePlatform` DISABLE KEYS */;

INSERT INTO `Act_LivePlatform` (`ID`, `Platform`)
VALUES
	(1,'YY'),
	(2,'映客'),
	(3,'花椒'),
	(4,'小米'),
	(5,'ME'),
	(6,'网易');

/*!40000 ALTER TABLE `Act_LivePlatform` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table Act_Partner
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Act_Partner`;

CREATE TABLE `Act_Partner` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Logo` varchar(200) NOT NULL COMMENT 'Logo路径',
  `CreateTime` datetime NOT NULL COMMENT '创建时间',
  `CreatorID` int(11) NOT NULL COMMENT '创建人ID',
  `ShowOrder` int(11) NOT NULL DEFAULT '1' COMMENT '排序顺序',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态,见App\\Helper\\Enum::Status',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Act_Qualified_Actress
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Act_Qualified_Actress`;

CREATE TABLE `Act_Qualified_Actress` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TrueName` varchar(32) NOT NULL DEFAULT '' COMMENT '主播名称',
  `Gender` tinyint(1) unsigned NOT NULL COMMENT '主播性别',
  `HireDate` date NOT NULL COMMENT '入职时间',
  `ContractYears` tinyint(2) NOT NULL DEFAULT '0' COMMENT '签约年限',
  `NickName` varchar(32) NOT NULL DEFAULT '' COMMENT '主播昵称',
  `WorkCity` varchar(32) NOT NULL DEFAULT '' COMMENT '常驻城市',
  `Birthday` date NOT NULL COMMENT '生日',
  `Height` tinyint(3) NOT NULL COMMENT '身高，单位CM',
  `Mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '联系电话',
  `Wechat` varchar(32) NOT NULL DEFAULT '' COMMENT '微信号',
  `LivePlatform` varchar(32) NOT NULL DEFAULT '''''' COMMENT '直播平台ID,对应LivePlatform表的ID，英文逗号连接',
  `HotDegree` int(11) NOT NULL DEFAULT '0' COMMENT '人气热度',
  `TotalFans` int(11) NOT NULL DEFAULT '0' COMMENT '粉丝关注',
  `Talent` varchar(32) NOT NULL DEFAULT '''''' COMMENT '直播特长,对应Talent表的ID，英文逗号连接',
  `Experientce` varchar(5) NOT NULL DEFAULT '0' COMMENT '直播经验，单位:年',
  `ImageAttach` varchar(1024) NOT NULL DEFAULT '''''' COMMENT '个人图片，json格式',
  `ImageVideo` varchar(200) NOT NULL DEFAULT '''''' COMMENT '视频短片',
  `CreateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '录入时间',
  `CreatorID` int(11) NOT NULL DEFAULT '0' COMMENT '录入者ID',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，对应App\\Helper\\Enum::Status',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Act_Register_Actress
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Act_Register_Actress`;

CREATE TABLE `Act_Register_Actress` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `NickName` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `WorkCity` varchar(16) NOT NULL DEFAULT '''''' COMMENT '所在城市',
  `Birthday` date NOT NULL COMMENT '出生日期',
  `Mobile` varchar(11) NOT NULL DEFAULT '''''' COMMENT '联系手机',
  `Wechat` varchar(11) NOT NULL DEFAULT '''''' COMMENT '微信号',
  `Talent` varchar(32) NOT NULL DEFAULT '''''' COMMENT '特长描述',
  `ImageAttach` varchar(1024) NOT NULL DEFAULT '''''' COMMENT '个人图片，json格式',
  `VideoAttach` varchar(200) NOT NULL DEFAULT '''''' COMMENT '视频短片地址',
  `CreateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '录入时间',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Act_Talent
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Act_Talent`;

CREATE TABLE `Act_Talent` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `TalentName` varchar(8) NOT NULL DEFAULT '' COMMENT '特长',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

LOCK TABLES `Act_Talent` WRITE;
/*!40000 ALTER TABLE `Act_Talent` DISABLE KEYS */;

INSERT INTO `Act_Talent` (`ID`, `TalentName`)
VALUES
	(1,'唱歌'),
	(2,'跳舞'),
	(3,'模特'),
	(4,'乐器'),
	(5,'户外'),
	(6,'母婴'),
	(7,'美妆'),
	(8,'美食'),
	(9,'搞笑'),
	(10,'主持'),
	(11,'情感'),
	(12,'电台'),
	(13,'旅行'),
	(14,'脱口秀');

/*!40000 ALTER TABLE `Act_Talent` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table Act_User
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Act_User`;

CREATE TABLE `Act_User` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserName` varchar(16) NOT NULL COMMENT '帐号使用者姓名',
  `Account` varchar(32) NOT NULL COMMENT '登录帐号',
  `Passwd` varchar(32) NOT NULL COMMENT '登录密码',
  `CreateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '帐号创建时间',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '帐号状态，见App\\Helper\\Enum',
  `IsFixed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是固定用户(超管)',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `AccountIdx` (`Account`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `Act_User` WRITE;
/*!40000 ALTER TABLE `Act_User` DISABLE KEYS */;

INSERT INTO `Act_User` (`ID`, `UserName`, `Account`, `Passwd`, `CreateTime`, `Status`, `IsFixed`)
VALUES
	(1,'超级管理员','admin','6266d4f13e622e81860359287e990922','2017-01-22 14:04:48',1,1);

/*!40000 ALTER TABLE `Act_User` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
