<?php

ob_start();
require 'config/config.php';
include'functions.php';
$rboss = $_POST['rboss'];
$rlvl = $_POST['rlvl'];
$rhour = $_POST['rhour'];
$rmin = $_POST['rmin'];
$rampm = $_POST['rampm'];
$rlatitude = $_POST['rlatitude'];
$rlongitude = $_POST['rlongitude'];

$sql = "INSERT INTO spotraid (rboss, rlvl, rhour, rmin, rampm, rlatitude, rlongitude) VALUES ('$rboss','$rlvl','$rhour','$rmin','$rampm','$rlatitude','$rlongitude')";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}	

	header('Location:submit-raid.php');
	
?>
