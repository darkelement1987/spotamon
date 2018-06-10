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
?>

