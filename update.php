<?php
ob_start();
require './config/config.php';
include'frontend/functions.php';

$addiv = "ALTER TABLE `spots` ADD `iv` INT(3) NOT NULL AFTER `cp`;";
	if(!mysqli_query($conn,$addiv))
		{
			echo 'Not updated, column "IV" already exists :-)';
		}
			else
			{
				echo 'Updating database...';
			}
					
    header("Refresh: 3; url= index.php");
	
$addpics = "ALTER TABLE `users` ADD `url` TEXT NOT NULL AFTER `trn_date`, ADD `lastUpload` VARCHAR(200) NOT NULL AFTER `url`;";
	if(!mysqli_query($conn,$addpics))
		{
			echo 'Not updated, profile pic columns already exist :-)';
		}
			else
			{
				echo 'Updating database...';
			}
					
    header("Refresh: 3; url= index.php");

?>

