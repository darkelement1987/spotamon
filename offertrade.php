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
$uname = $conn->real_escape_string($_SESSION['uname']);
$accepted = "0";

if(isset($_POST['shiny'])){
	$shiny = $conn->real_escape_string($_POST['shiny']);
	$shiny = 1;
} else {
	$shiny = 0;	
}

if(isset($_POST['alolan'])){
	$alolan = $conn->real_escape_string($_POST['alolan']);
	$alolan = 1;
} else {
	$alolan = 0;	
}

if(isset($_POST['opentrade'])){
	$opentrade = $conn->real_escape_string($_POST['opentrade']);
	$opentrade = 1;
	$reqmon = 0;
} else {
	$opentrade = 0;
}


// Start queries
$sql = "INSERT INTO offers (offmon, cp, iv, tradeloc, reqmon, tname, accepted, opentrade, shiny, alolan) VALUES ('$offmon','$cp','$iv','$tradeloc','$reqmon','$uname','$accepted','$opentrade','$shiny','$alolan')";
if(!mysqli_query($conn,$sql))
{
    echo 'Not Inserted';
}

$sql1 = "SELECT * FROM users WHERE uname='".$_SESSION['uname']."'";
$result = mysqli_query($conn,$sql1)or die(mysqli_error($conn));	
				while($row = mysqli_fetch_array($result)) {
					$offtrades = $row['offtrades'];					
				}	
			$offtrades = ++$offtrades;

$sql2 = "UPDATE users SET offtrades='$offtrades' WHERE uname='".$_SESSION['uname']."'";
    if(!mysqli_query($conn,$sql2))
        {
            echo 'Not Inserted';
        }
		
header('Location:active-trades.php');
?>
