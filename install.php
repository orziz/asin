<?php

require_once 'source/class/class_core.php';
require_once 'source/function/function_conn.php';

$sql = <<<EOF

CREATE TABLE IF NOT EXISTS `cdb_userinfo` (
  `qq` bigint NOT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `sex` int DEFAULT 0,
  `age` int DEFAULT 0,
  `height` int DEFAULT 0,
  `weight` int DEFAULT 0,
  `arms` mediumtext DEFAULT NULL,
  `introduce` mediumtext  DEFAULT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`qq`)
);

CREATE TABLE IF NOT EXISTS `cdb_userscore` (
	`qq` bigint NOT NULL,
	`score` int DEFAULT 0,
  `credit` bigint DEFAULT 0,
  `rank` int DEFAULT 0,
  `scorerank` bigint DEFAULT 0,
	PRIMARY KEY (`qq`),
	KEY `scorerank` (`scorerank`) USING BTREE,
  KEY `credit` (`credit`) USING BTREE
);

CREATE TABLE IF NOT EXISTS `cdb_userattr` (
  `qq` bigint NOT NULL,
  `str` int DEFAULT 0,
  `dex` int DEFAULT 0,
  `con` int DEFAULT 0,
  `ine` int DEFAULT 0,
  `wis` int DEFAULT 0,
  `cha` int DEFAULT 0,
  `free` int DEFAULT 0,
  PRIMARY KEY (`qq`)
);

CREATE TABLE IF NOT EXISTS `cdb_userskill` (
  `qq` bigint NOT NULL,
  `skill1` mediumtext DEFAULT NULL,
  `skill2` mediumtext DEFAULT NULL,
  `skill3` mediumtext DEFAULT NULL,
  `skill4` mediumtext DEFAULT NULL,
  PRIMARY KEY (`qq`)
);

CREATE TABLE IF NOT EXISTS `cdb_checkin` (
  `qq` bigint NOT NULL,
  `count` int DEFAUTL 0,
	`lday` date NOT NULL,
	PRIMARY KEY (`qq`),
  KEY `lday` (`lday`) USING BTREE
);

CREATE TABLE IF NOT EXISTS `cdb_taskjoin` (
  `day` date NOT NULL,
  `maxmember` int DEFAULT NULL,
  `begintime` varchar(255) DEFAULT NULL,
  `member` mediumtext DEFAULT NULL,
  PRIMARY KEY (`day`)
);

CREATE TABLE IF NOT EXISTS `cdb_userfight` (
  `id` int auto_increment NOT NULL,
  `qq` bigint DEFAULT NULL,
  `pkqq` bigint DEFAULT NULL,
  `isend` int DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `cdb_userbag` (
  `qq` bigint NOT NULL,
  `bag` mediumtext DEFAULT NULL,
  PRIMARY KEY (`qq`)
);


EOF;

runquery($sql);