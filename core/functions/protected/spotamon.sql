
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


CREATE TABLE IF NOT EXISTS `exraidatt` (
  `attid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `exid` int(10) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`attid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `exraids` (
  `exid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `gname` int(10) NOT NULL,
  `exraiddate` datetime DEFAULT NULL,
  `spotter` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`exid`),
  UNIQUE KEY `gname` (`gname`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `gyms` (
  `gid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `gname` varchar(255) NOT NULL,
  `glatitude` decimal(10,6) NOT NULL,
  `glongitude` decimal(10,6) NOT NULL,
  `gteam` int(2) NOT NULL,
  `actraid` varchar(255) NOT NULL,
  `actboss` int(3) DEFAULT NULL,
  `hour` varchar(2) NOT NULL,
  `min` varchar(2) NOT NULL,
  `ampm` varchar(2) NOT NULL,
  `egg` int(1) NOT NULL,
  `type` varchar(25) NOT NULL,
  `eggby` varchar(100) NOT NULL,
  `teamby` varchar(100) NOT NULL,
  `raidby` varchar(100) NOT NULL,
  `exraid` int(1) NOT NULL DEFAULT 0,
  `exraiddate` datetime DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`gid`),
  UNIQUE KEY `gname` (`gname`),
  UNIQUE KEY `glatitude` (`glatitude`),
  UNIQUE KEY `glongitude` (`glongitude`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `expokedex` (
	`id` INT(4) NOT NULL,
	`rarity` TINYINT(1) NULL DEFAULT NULL,
	`rlevel` TINYINT(1) NULL DEFAULT NULL,
	`rcp` INT(5) NULL DEFAULT NULL,
	`type1` VARCHAR(14) NOT NULL,
	`tcolor` VARCHAR(7) NOT NULL,
	`type2` VARCHAR(14) NULL DEFAULT NULL,
	`t2color` VARCHAR(7) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) DEFAULT NULL,
  `to_user` varchar(30) DEFAULT NULL,
  `del_in` int(1) NOT NULL DEFAULT 0,
  `del_out` int(1) NOT NULL DEFAULT 0,
  `from_user` varchar(30) DEFAULT NULL,
  `unread` int(10) NOT NULL,
  `message` varchar(1000) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `offers` (
  `oid` int(10) NOT NULL AUTO_INCREMENT,
  `offmon` varchar(30) NOT NULL,
  `cp` int(6) NOT NULL,
  `iv` int(3) NOT NULL,
  `tradeloc` varchar(100) NOT NULL,
  `reqmon` varchar(30) NOT NULL,
  `tname` varchar(100) NOT NULL,
  `accepted` int(1) DEFAULT NULL,
  `opentrade` int(1) NOT NULL,
  `shiny` int(1) NOT NULL,
  `alolan` int(1) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `complete` int(1) NOT NULL,
  `cloc` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`oid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `pokedex` (
  `id` int(6) NOT NULL,
  `monster` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `quests` (
  `qid` int(6) NOT NULL,
  `qname` varchar(255) NOT NULL,
  `type` varchar(8) NOT NULL,
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `raidbosses` (
  `rid` int(6) NOT NULL,
  `rcp` int(6) NOT NULL,
  `rlvl` int(1) NOT NULL,
  `rboss` varchar(25) NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `reset` (
  `resetid` int(10) NOT NULL AUTO_INCREMENT,
  `uname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL,
  PRIMARY KEY (`resetid`),
  UNIQUE KEY `uname` (`uname`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `rewards` (
  `reid` int(4) NOT NULL,
  `rname` varchar(255) NOT NULL,
  `type` varchar(8) NOT NULL,
  PRIMARY KEY (`reid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `spotraid` (
  `rid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `rboss` varchar(30) NOT NULL,
  `rhour` varchar(2) NOT NULL,
  `rmin` varchar(2) NOT NULL,
  `rampm` varchar(2) NOT NULL,
  `rdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `spotter` varchar(100) NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `spots` (
  `spotid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `pokemon` varchar(30) NOT NULL,
  `cp` int(6) NOT NULL,
  `iv` int(3) NOT NULL,
  `hour` varchar(2) NOT NULL,
  `min` varchar(2) NOT NULL,
  `ampm` varchar(2) NOT NULL,
  `latitude` decimal(10,6) NOT NULL,
  `longitude` decimal(10,6) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `fulladdress` varchar(128) NOT NULL,
  `good` int(3) NOT NULL,
  `bad` int(1) NOT NULL,
  `spotter` varchar(100) NOT NULL,
  PRIMARY KEY (`spotid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `stops` (
  `sid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `sname` varchar(255) NOT NULL,
  `slatitude` decimal(10,6) NOT NULL,
  `slongitude` decimal(10,6) NOT NULL,
  `quested` int(1) NOT NULL,
  `actquest` int(3) NOT NULL,
  `actreward` int(3) NOT NULL,
  `hour` varchar(2) NOT NULL,
  `min` varchar(2) NOT NULL,
  `ampm` varchar(2) NOT NULL,
  `lured` int(1) NOT NULL,
  `type` varchar(25) NOT NULL,
  `questby` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`sid`),
  UNIQUE KEY `sname` (`sname`),
  UNIQUE KEY `slatitude` (`slatitude`),
  UNIQUE KEY `slongitude` (`slongitude`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `teams` (
  `tid` int(6) NOT NULL,
  `tname` varchar(15) NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `tradeoffers` (
  `toid` int(10) NOT NULL AUTO_INCREMENT,
  `oid` int(10) NOT NULL,
  `coffer` int(10) NOT NULL,
  `offerby` varchar(255) NOT NULL,
  `cofferby` varchar(255) NOT NULL,
  `ccp` int(10) NOT NULL,
  `civ` int(10) NOT NULL,
  `cshiny` int(1) NOT NULL,
  `calolan` int(1) NOT NULL,
  `accepted` int(1) NOT NULL,
  `complete` int(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`toid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `trades` (
  `tid` int(10) NOT NULL AUTO_INCREMENT,
  `oid` int(10) NOT NULL,
  `tradeloc` varchar(100) NOT NULL,
  `tname` varchar(100) NOT NULL,
  `rname` varchar(100) NOT NULL,
  `offmon` varchar(30) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `usergroup` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `uname` varchar(100) NOT NULL,
  `upass` varchar(225) NOT NULL,
  `usergroup` varchar(1) NOT NULL DEFAULT '1',
  `trn_date` datetime NOT NULL DEFAULT current_timestamp(),
  `url` text DEFAULT NULL,
  `lastUpload` varchar(200) DEFAULT NULL,
  `offtrades` int(9) NOT NULL DEFAULT 0,
  `reqtrades` int(9) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `user_extended` (
  `email` varchar(100) NOT NULL,
  `discord_id` varchar(50) DEFAULT NULL,
  `silph_name` varchar(50) DEFAULT NULL,
  `discord_uname` varchar(50) DEFAULT NULL,
  `token` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `discord_profile` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `silph_profile` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `discord_id` (`discord_id`),
  CONSTRAINT `FK_user_extended_users` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='additional user rescources';


CREATE TABLE IF NOT EXISTS `version` (
  `version` int(3) NOT NULL,
  `minor_v` int(3) unsigned zerofill DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
