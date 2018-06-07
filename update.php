<?php
ob_start();
require './config/config.php';
include'frontend/functions.php';

$updatestops = "ALTER TABLE `stops` CHANGE `sid` `sid` INT(6) UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE `quested` `quested` INT(1) NULL, CHANGE `quest` `actquest` INT(3) NULL, CHANGE `reward` `actreward` INT(3) NULL, CHANGE `lured` `lured` INT(1) NULL, CHANGE `type` `type` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `date` `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;";
	if(!mysqli_query($conn,$updatestops))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}
			
$updatequests = "ALTER TABLE `quests` CHANGE `quest` `qname` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `type` `type` VARCHAR(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;";
	if(!mysqli_query($conn,$updatequests))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}
			
$updaterewards = "ALTER TABLE `rewards` CHANGE `reward` `rname` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `type` `type` VARCHAR(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;";
	if(!mysqli_query($conn,$updaterewards))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}
			
$modifyspots = "ALTER TABLE `stops` ADD `hour` INT(2) NOT NULL AFTER `actreward`, ADD `min` INT(2) NOT NULL AFTER `hour`, ADD `ampm` INT(2) NOT NULL AFTER `min`;";
	if(!mysqli_query($conn,$modifyspots))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}
					
    header('Location:index.php');
?>

