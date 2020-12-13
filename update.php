<?php
ob_start();
require './config/config.php';
require './config/version.php';
include './frontend/functions.php';

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
	
// Create new columns @ users for trading part 2	

$update6 = "ALTER TABLE `offers` ADD `opentrade` INT(1) NOT NULL AFTER `accepted`, ADD `shiny` INT(1) NOT NULL AFTER `opentrade`, ADD `alolan` INT(1) NOT NULL AFTER `shiny`, ADD `notes` INT(255) NOT NULL AFTER `alolan`, ADD `complete` INT(1) NOT NULL AFTER `notes`, ADD `cloc` INT(255) NOT NULL AFTER `complete`;";
	if(!mysqli_query($conn,$update6))
		{
			echo '- Not updated, trading columns already exist :-)';
		}
			else
			{
				echo '- Added columns \'opentrades, shiny, alolan, notes, complete, cloc\' to `offers`';
			}
					
    header("Refresh: 5; url= ./index.php");
	
	echo '<br><hr>';	
	
// Create new columns @ users for trading part 3	

$update7 = "ALTER TABLE `trades` ADD `oid` INT(10) NOT NULL AFTER `tid`;";
	if(!mysqli_query($conn,$update7))
		{
			echo '- Not updated, trading columns already exist :-)';
		}
			else
			{
				echo '- Added columns \'oid\' to `trades`';
			}
					
    header("Refresh: 5; url= ./index.php");
	
	echo '<br><hr>';	
	
// Create new columns @ messages for pm deletion	

$update8 = "ALTER TABLE `messages` ADD `del_in` INT(1) NOT NULL DEFAULT '0' AFTER `from_user`, ADD `del_out` INT(1) NOT NULL DEFAULT '0' AFTER `del_in`;";
	if(!mysqli_query($conn,$update8))
		{
			echo '- Not updated, columns already exist :-)';
		}
			else
			{
				echo '- Added columns \'del_in\' and \'del_out\' to `messages`';
			}
					
    header("Refresh: 5; url= ./index.php");
	
	echo '<br><hr>';
	echo '<br><b>Back to index in 5 seconds..</b>';	
		
		// End make columns for trading

?>

