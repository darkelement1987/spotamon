<?php
ob_start();
require './config/config.php';
include'frontend/functions.php';

echo '<h2><strong>Database Update:</strong></h2><hr>';

// Column update for 'spots -> iv'
$addiv = "ALTER TABLE `spots` ADD `iv` INT(3) NOT NULL AFTER `cp`;";
	if(!mysqli_query($conn,$addiv))
		{
			echo '- Not updated, column "IV" already exists :-)';
		}
			else
			{
				echo '- Added column \'iv\' to `spots`';
			}
					
    header("Refresh: 3; url= index.php");

echo '<br>';

// Column update for 'users -> url' and 'users -> lastUplooad'	
$addpics = "ALTER TABLE `users` ADD `url` TEXT NOT NULL AFTER `trn_date`, ADD `lastUpload` VARCHAR(200) NOT NULL AFTER `url`;";
	if(!mysqli_query($conn,$addpics))
		{
			echo '- Not updated, profile pic columns already exist :-)';
		}
			else
			{
				echo '- Added columns \'lastUpload\' and \'url\' to `users`';
			}
					
    header("Refresh: 3; url= index.php");
	
	echo '<br><hr>';
	echo '<br>Back to index in 3 seconds..';

?>

