<?php

ob_start();
require 'config/config.php';
include'functions.php';
$rboss = $_POST['rboss'];
$rhour = $_POST['rhour'];
$rmin = $_POST['rmin'];
$rampm = $_POST['rampm'];
$gname = $_POST['gname'];

$sql = "INSERT INTO spotraid (rboss, rhour, rmin, rampm) VALUES ('$rboss','$rhour','$rmin','$rampm')";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}
$sql1 = "UPDATE gyms SET actraid='1',actboss='$rboss' WHERE gid='$gname'";
	if(!mysqli_query($conn,$sql1))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}				

	header('Location:submit-raid.php');
	
?>
