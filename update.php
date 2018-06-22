<?php
ob_start();
require './config/config.php';
require './config/version.php';
include'frontend/functions.php';

echo '<h2><strong>Database Update was necessary:</strong></h2>';
echo '<h4>Version is now: "'.$lastversion.'"<br><br><hr>';

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
					
    header("Refresh: 5; url= index.php");

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
					
    header("Refresh: 5; url= index.php");
	
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
					
    header("Refresh: 5; url= index.php");
	
	echo '<br><hr>';
	
// Make `uname` UNIQUE	

$unamequery = "SHOW KEYS FROM users WHERE Key_name='uname'";
		$unameresult     = $conn->query($unamequery);
		$row_cnt        = $unameresult->num_rows;
		if ($row_cnt !== 1) {
			
$update4 = "CREATE TABLE users2 LIKE users;";
$update4.= "ALTER IGNORE TABLE users2 ADD UNIQUE(uname);";
$update4.= "INSERT IGNORE INTO users2 SELECT * FROM users;";
$update4.= "RENAME TABLE users TO old_users, users2 TO users;";
$update4.= "DROP TABLE old_users;";

	if(!mysqli_multi_query($conn,$update4))
		{}
			else
			{
				echo '- \"uname\" is now UNIQUE';
			}
					
    header("Refresh: 5; url= ./index.php");
	
	echo '<br><hr>';
	
	echo '<br><b>Back to index in 5 seconds..</b>';
	
		} else { echo "- Not updated, \"uname\" already UNIQUE :-)";
		    header("Refresh: 5; url= index.php");
	
	echo '<br><hr>';
	
		}
		
		// END OF UNAME QUERY
		
// Create new columns @ users for trading	

$update5 = "ALTER TABLE `users` ADD `offtrades` INT(9) NOT NULL DEFAULT '0' AFTER `lastUpload`, ADD `reqtrades` INT(9) NOT NULL DEFAULT '0' AFTER `offtrades`;";
	if(!mysqli_query($conn,$update5))
		{
			echo '- Not updated, trading columns already exist :-)';
		}
			else
			{
				echo '- Added columns \'offtrades\' and \'reqtrades\' to `users`';
			}
					
    header("Refresh: 5; url= ./index.php");
	
	echo '<br><hr>';
	echo '<br><b>Back to index in 5 seconds..</b>';	
		
		// End make columns for trading

?>

