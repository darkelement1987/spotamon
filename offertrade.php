<?php
$curl = curl_init();
ob_start();
require './config/config.php';
include'frontend/functions.php';
include("login/auth.php");

$offmon = $conn->real_escape_string($_POST['offmon']);
$cp = $conn->real_escape_string($_POST['cp']);
$iv = $conn->real_escape_string($_POST['iv']);
$tradeloc = $conn->real_escape_string($_POST['tradeloc']);
$reqmon = $conn->real_escape_string($_POST['reqmon']);
$accepted = $conn->real_escape_string($_POST['accepted']);
$uname = $conn->real_escape_string($_SESSION['uname']);

$accepted = "0";

// Start queries

$sql = "INSERT INTO offers (offmon, cp, iv, tradeloc, reqmon, tname, accepted) VALUES ('$offmon','$cp','$iv','$tradeloc','$reqmon','$uname','$accepted')";
if(!mysqli_query($conn,$sql))
{
    echo 'Not Inserted';
}
else
{
    echo 'Inserted';
}

header('Location:/active-trades.php');
    
?>
