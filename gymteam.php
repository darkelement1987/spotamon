<?php

ob_start();
require 'config/config.php';
include'functions.php';
$gname = $_POST['gname'];
$tname = $_POST['tname'];


$sql = "UPDATE gyms SET gteam='$tname' WHERE gid='$gname'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}	

	header('Location:submit-team.php');
	
?>
