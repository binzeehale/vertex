/*
SQLyog 企业版 - MySQL GUI v8.14 
MySQL - 5.1.49-community : Database - vertex2
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vertex2` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `vertex2`;

/*Table structure for table `attendance_log` */

DROP TABLE IF EXISTS `attendance_log`;

CREATE TABLE `attendance_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_name` varchar(256) DEFAULT NULL,
  `class_name` varchar(256) DEFAULT NULL,
  `teacher_name` varchar(256) DEFAULT NULL,
  `teacher_earnings` float DEFAULT NULL,
  `student_cost` float DEFAULT NULL,
  `sign_date` varchar(256) DEFAULT NULL,
  `user_name` varchar(256) DEFAULT NULL,
  `cost_from` varchar(256) DEFAULT NULL,
  `aviliable` tinyint(1) DEFAULT NULL,
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1034 DEFAULT CHARSET=utf8;

/*Table structure for table `class` */

DROP TABLE IF EXISTS `class`;

CREATE TABLE `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `teacher_fee` varchar(256) DEFAULT NULL,
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

/*Table structure for table `class_fee` */

DROP TABLE IF EXISTS `class_fee`;

CREATE TABLE `class_fee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `grade` varchar(128) DEFAULT NULL,
  `cost` float DEFAULT NULL,
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;

/*Table structure for table `mapping_student_class` */

DROP TABLE IF EXISTS `mapping_student_class`;

CREATE TABLE `mapping_student_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_fee_id` int(11) DEFAULT NULL,
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=425 DEFAULT CHARSET=utf8;

/*Table structure for table `mapping_teacher_class` */

DROP TABLE IF EXISTS `mapping_teacher_class`;

CREATE TABLE `mapping_teacher_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `fee` float DEFAULT NULL,
  `create_date` varchar(256) DEFAULT NULL,
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

/*Table structure for table `student` */

DROP TABLE IF EXISTS `student`;

CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `sex` varchar(128) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `school` varchar(256) DEFAULT NULL,
  `grade` varchar(256) DEFAULT NULL,
  `mather_phone` varchar(256) DEFAULT NULL,
  `father_phone` varchar(256) DEFAULT NULL,
  `landline` varchar(256) DEFAULT NULL,
  `state` varchar(256) DEFAULT NULL,
  `banlance` varchar(128) DEFAULT '0.00',
  `registeration_date` varchar(128) DEFAULT NULL,
  `last_update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1293 DEFAULT CHARSET=utf8;

/*Table structure for table `teacher` */

DROP TABLE IF EXISTS `teacher`;

CREATE TABLE `teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `phone` varchar(256) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Table structure for table `transaction_log` */

DROP TABLE IF EXISTS `transaction_log`;

CREATE TABLE `transaction_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `income` float DEFAULT '0',
  `expenses` float DEFAULT '0',
  `type` varchar(256) DEFAULT NULL,
  `student_name` varchar(256) DEFAULT NULL,
  `class_name` varchar(256) DEFAULT NULL,
  `relate_id` int(11) DEFAULT '0',
  `reason` blob,
  `user_name` varchar(256) DEFAULT NULL,
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=476 DEFAULT CHARSET=utf8;

/*Table structure for table `transaction_student_class` */

DROP TABLE IF EXISTS `transaction_student_class`;

CREATE TABLE `transaction_student_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `income` float DEFAULT NULL,
  `cost` float DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1523 DEFAULT CHARSET=utf8;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`weight`,`last_update_time`) values (1,'admin','21232f297a57a5a743894a0e4a801fc3',1,'2013-10-06 11:00:03');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
