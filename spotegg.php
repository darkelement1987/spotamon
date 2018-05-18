<?php

ob_start();
require 'config/config.php';
include'functions.php';
$gname = $conn->real_escape_string($_POST['gname']);
$egg = $conn->real_escape_string($_POST['egg']);
$rhour = $conn->real_escape_string($_POST['rhour']);
$rmin = $conn->real_escape_string($_POST['rmin']);
$rampm = $conn->real_escape_string($_POST['rampm']);


$sql = "UPDATE gyms SET egg='$egg',hour='$rhour',min='$rmin',ampm='$rampm' WHERE gid='$gname'";
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
