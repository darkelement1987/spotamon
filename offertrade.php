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

// Start queries

$sql = "INSERT INTO offers (offmon, cp, iv, tradeloc, reqmon, tname, accepted) VALUES ('$offmon','$cp','$iv','$tradeloc','$reqmon','$uname','$accepted')";
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
