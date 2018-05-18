<?php

ob_start();
require 'config/config.php';
include'functions.php';
$gname = $conn->real_escape_string($_POST['gname']);
$tname = $conn->real_escape_string($_POST['tname']);


$sql = "UPDATE gyms SET gteam='$tname' WHERE gid='$gname'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}	

	header('Location:map.php');
	
?>
