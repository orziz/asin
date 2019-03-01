<?php

require_once 'source/class/class_core.php';
require_once 'source/function/function_conn.php';

$sql = <<<EOF

CREATE TABLE IF NOT EXISTS `cdb_userinfo` (
  `qq` bigint NOT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `sex` int DEFAULT NULL,
  `age` int NOT NULL,
  `height` int NOT NULL,
  `weight` int NOT NULL,
  `str` int NOT NULL,
  `dex` int NOT NULL,
  `con` int NOT NULL,
  `ine` int NOT NULL,
  `wis` int NOT NULL,
  `cha` int NOT NULL,
  `arms` mediumtext NOT NULL,
  `skill` mediumtext NOT NULL,
  `weakness` mediumtext NOT NULL,
  `introduce` mediumtext NOT NULL,
  PRIMARY KEY (`qq`)
);

CREATE TABLE IF NOT EXISTS `cdb_userscore` (
	`qq` bigint NOT NULL,
	`score` int DEFAULT NULL,
	`utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`qq`),
	KEY `idx1` (`score`, `utime`) USING BTREE
);

CREATE TABLE IF NOT EXISTS `asin_checkin` (
	`day` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`member` mediumtext DEFAULT NULL,
	PRIMARY KEY (`day`)
);


CREATE TABLE IF NOT EXISTS `asin_userskill` (
  `qq` bigint NOT NULL,
  `skill1` mediumtext DEFAULT NULL,
  `skill1cd` int DEFAULT NULL,
  `skill1time` timestamp DEFAULT NULL DEFAULT CURRENT_TIMESTAMP,
  `skill2` mediumtext DEFAULT NULL,
  `skill2cd` int DEFAULT NULL,
  `skill2time` timestamp DEFAULT NULL DEFAULT CURRENT_TIMESTAMP,
  `skill3` mediumtext DEFAULT NULL,
  `skill3cd` int DEFAULT NULL,
  `skill3time` timestamp DEFAULT NULL DEFAULT CURRENT_TIMESTAMP,
  `skill4` mediumtext DEFAULT NULL,
  `skill4cd` int DEFAULT NULL,
  `skill4time` timestamp DEFAULT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`qq`)
);

CREATE TABLE IF NOT EXISTS `asin_taskjoin` (
  `day` date NOT NULL,
  `maxmember` int DEFAULT NULL,
  `begintime` varchar(255) DEFAULT NULL,
  `member` mediumtext DEFAULT NULL,
  PRIMARY KEY (`day`)
);

CREATE TABLE IF NOT EXISTS `asin_userfight` (
  `id` int auto_increment NOT NULL,
  `qq` bigint DEFAULT NULL,
  `pkqq` bigint DEFAULT NULL,
  `isend` int DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `asin_userbag` (
  `qq` bigint NOT NULL,
  `bag` mediumtext DEFAULT NULL,
  PRIMARY KEY (`qq`)
);


EOF;

runquery($sql);