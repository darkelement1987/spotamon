<?php
require 'config/config.php';
include 'frontend/functions.php';
$spotid = $conn->real_escape_string($_POST['spotid']);
$good = $conn->real_escape_string($_POST['good']);

$sql = "SELECT good FROM spots WHERE spotid='$spotid'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not correct';
		}
			else
			{
				$good = ++$good;
			}

$sql1 = "UPDATE spots SET good='$good' WHERE spotid='$spotid'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }
            else
            {
               echo "<meta http-equiv=\"refresh\" content=\"0;URL=./pokemon.php\">";				
            }


?>