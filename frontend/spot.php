<?php 
require '../config/config.php';
include '../frontend/functions.php';

$pokemon = $_POST['pokemon'];
$cp = $_POST['cp'];
$hour = $_POST['hour'];
$min = $_POST['min'];
$ampm = $_POST['ampm'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$address = $_POST['address'];

$sql = "INSERT INTO spots (pokemon, monid, cp, hour, min, ampm, latitude, longitude, address) VALUES ('$pokemon',(SELECT dexentry FROM pokedex WHERE monster='$pokemon'),'$cp','$hour','$min','$ampm','$latitude','$longitude','$address')";

if(!mysqli_query($conn,$sql))
{
	echo 'Not Inserted';
}
	else{
		echo 'Inserted';
	}
	
header('Location: index.php');
?>
