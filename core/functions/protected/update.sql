
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


DROP PROCEDURE IF EXISTS `error_ignore`;
DELIMITER //
CREATE PROCEDURE `error_ignore`()
BEGIN
  DECLARE CONTINUE HANDLER FOR SQLEXCEPTION BEGIN END;

ALTER TABLE `spots` ADD `iv` INT(3) NOT NULL AFTER `cp`;

ALTER  TABLE `users` ADD `url` TEXT NOT NULL AFTER `trn_date`, ADD `lastUpload` VARCHAR(200) NOT NULL AFTER `url`;

ALTER  TABLE `gyms` ADD `exraid` INT(1) NOT NULL AFTER `raidby`, ADD `exraiddate` DATETIME NULL AFTER `exraid`;

CREATE TABLE users2 LIKE users;
ALTER TABLE users2 ADD UNIQUE(uname);
INSERT IGNORE INTO users2 SELECT * FROM users;
RENAME TABLE users TO old_users, users2 TO users;
DROP TABLE old_users;

ALTER TABLE `users` ADD `offtrades` INT(9) NOT NULL DEFAULT '0' AFTER `lastUpload`, ADD `reqtrades` INT(9) NOT NULL DEFAULT '0' AFTER `offtrades`;

ALTER TABLE `offers` ADD `opentrade` INT(1) NOT NULL AFTER `accepted`, ADD `shiny` INT(1) NOT NULL AFTER `opentrade`, ADD `alolan` INT(1) NOT NULL AFTER `shiny`, ADD `notes` INT(255) NOT NULL AFTER `alolan`, ADD `complete` INT(1) NOT NULL AFTER `notes`, ADD `cloc` INT(255) NOT NULL AFTER `complete`;

ALTER TABLE `trades` ADD `oid` INT(10) NOT NULL AFTER `tid`;

ALTER TABLE `messages` ADD `del_in` INT(1) NOT NULL DEFAULT '0' AFTER `from_user`, ADD `del_out` INT(1) NOT NULL DEFAULT '0' AFTER `del_in`;

ALTER TABLE `version`
ADD COLUMN `minor_v` INT(3) UNSIGNED ZEROFILL NULL AFTER `version`;

ALTER TABLE `users1`	CHANGE COLUMN `usergroup` `usergroup` VARCHAR(1) NOT NULL DEFAULT '1' AFTER `upass`,	CHANGE COLUMN `trn_date` `trn_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `usergroup`,	CHANGE COLUMN `url` `url` TEXT NULL DEFAULT NULL AFTER`trn_date`,	CHANGE COLUMN `lastUpload` `lastUpload` VARCHAR(200) NULL DEFAULT NULL AFTER `url`,	CHANGE COLUMN `offtrades` `offtrades` INT(9) NOT NULL DEFAULT '0' AFTER `lastUpload`,	CHANGE COLUMN `reqtrades` `reqtrades` INT(9) NOT NULL DEFAULT '0' AFTER `offtrades`,	ADD UNIQUE INDEX `email` (`email`),	ADD UNIQUE INDEX `uname` (`uname`);	

CREATE TABLE `user_extended` (
	`email` VARCHAR(100) NOT NULL,
	`discord_id` VARCHAR(50) NULL DEFAULT NULL,
	`silph_name` VARCHAR(50) NULL DEFAULT NULL,
	`discord_uname` VARCHAR(50) NULL DEFAULT NULL,
	`token` LONGTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_bin',
	`discord_profile` LONGTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_bin',
	`silph_profile` LONGTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_bin',
	`avatar` VARCHAR(100) NULL DEFAULT NULL,
	UNIQUE INDEX `email` (`email`),
	UNIQUE INDEX `discord_id` (`discord_id`),
	CONSTRAINT `FK_user_extended_users` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COMMENT='additional user rescources'
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


END //
DELIMITER ;
CALL `error_ignore`();
DROP PROCEDURE `error_ignore`;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
