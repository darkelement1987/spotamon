<?php
require 'config/config.php';
include 'frontend/functions.php';
$spotid = $conn->real_escape_string($_POST['spotid']);
$bad = $conn->real_escape_string($_POST['bad']);

$sql = "SELECT bad FROM spots WHERE spotid='$spotid'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not correct';
		}
			else
			{
				$bad = ++$bad;
			}

$sql1 = "UPDATE spots SET bad='$bad' WHERE spotid='$spotid'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }
            else
            {
               header('Location:/pokemon.php');				
            }


?>