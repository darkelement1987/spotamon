<?php
ob_start();
require './config/config.php';
include'frontend/functions.php';

echo '<h2><strong>Database Update:</strong></h2><hr>';

// Column update for 'spots -> iv'
$update1 = "ALTER TABLE `spots` ADD `iv` INT(3) NOT NULL AFTER `cp`;";
	if(!mysqli_query($conn,$update1))
		{
			echo '- Not updated, column "IV" already exists :-)';
		}
			else
			{
				echo '- Added column \'iv\' to `spots`';
			}
					
    header("Refresh: 3; url= index.php");

echo '<br><hr>';

// Column update for 'users -> url' and 'users -> lastUplooad'	
$update2 = "ALTER TABLE `users` ADD `url` TEXT NOT NULL AFTER `trn_date`, ADD `lastUpload` VARCHAR(200) NOT NULL AFTER `url`;";
	if(!mysqli_query($conn,$update2))
		{
			echo '- Not updated, profile pic columns already exist :-)';
		}
			else
			{
				echo '- Added columns \'lastUpload\' and \'url\' to `users`';
			}
					
    header("Refresh: 3; url= index.php");
	
	echo '<br><hr>';
	
// Column update for ex-raids	
$update3 = "ALTER TABLE `gyms` ADD `exraid` INT(1) NOT NULL AFTER `raidby`, ADD `exraiddate` DATETIME NULL AFTER `exraid`;";
	if(!mysqli_query($conn,$update3))
		{
			echo '- Not updated, ex-raid columns already exist :-)';
		}
			else
			{
				echo '- Added columns \'exraid\' and \'exraiddate\' to `gyms`';
			}
					
    header("Refresh: 3; url= index.php");
	
	echo '<br><hr>';
	
	echo '<br><b>Back to index in 3 seconds..</b>';

?>

