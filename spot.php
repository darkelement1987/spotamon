<?php
ob_start();
require 'config/config.php';
include'functions.php';
$pokemon = $conn->real_escape_string($_POST['pokemon']);
$cp = $conn->real_escape_string($_POST['cp']);
$hour = $conn->real_escape_string($_POST['hour']);
$min = $conn->real_escape_string($_POST['min']);
$ampm = $conn->real_escape_string($_POST['ampm']);
$latitude = $conn->real_escape_string($_POST['latitude']);
$longitude = $conn->real_escape_string($_POST['longitude']);
$url  = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=false&key=".$gmaps;
$json = @file_get_contents($url);
$data = json_decode($json);
$status = $data->status;
$address = '';
if($status == "OK")
{
    $address = $conn->real_escape_string($data->results[0]->formatted_address);
}
else
{
    $address = "Cannot retrieve address";
}
echo $address;
$sql = "INSERT INTO spots (pokemon, cp, hour, min, ampm, latitude, longitude, fulladdress) VALUES ('$pokemon','$cp','$hour','$min','$ampm','$latitude','$longitude', '$address')";
if(!mysqli_query($conn,$sql))
{
    echo 'Not Inserted';
}
else
{
    echo 'Inserted';
}    
header('Location:index.php');
    
?>
