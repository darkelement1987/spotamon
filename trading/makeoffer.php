<?php

require '../config/config.php';
include '../frontend/functions.php';
include("../login/auth.php");

if (isset($_POST['oid'])) {
$oid = $conn->real_escape_string($_POST['oid']);
$coffmon = $conn->real_escape_string($_POST['coffmon']);
$ccp = $conn->real_escape_string($_POST['ccp']);
$civ = $conn->real_escape_string($_POST['civ']);
$offerby = $conn->real_escape_string($_POST['offerby']);
$cofferby = $conn->real_escape_string($_SESSION['uname']);

if(isset($_POST['cshiny'])){
	$cshiny = $conn->real_escape_string($_POST['cshiny']);
	$cshiny = 1;
} else {
	$cshiny = 0;	
}

if(isset($_POST['calolan'])){
	$calolan = $conn->real_escape_string($_POST['alolan']);
	$calolan = 1;
} else {
	$calolan = 0;	
}

$sql = "SELECT * FROM offers WHERE oid='$oid'";
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));
while($row = mysqli_fetch_array($result)) {			
	$oid = $row['oid'];
	$offmon = $row['offmon'];
	$offerby = $row['tname'];
}

$sql1 = "INSERT INTO tradeoffers (oid, coffer, offerby, cofferby, ccp, civ, cshiny, calolan) VALUES ('$oid','$coffmon','$offerby','$cofferby','$ccp','$civ','$cshiny','$calolan')";
if(!mysqli_query($conn,$sql1)){
    echo 'Not Inserted';
}
header('Location:../active-offers.php?oid='.$oid.'');
}
?>