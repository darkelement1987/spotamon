<?php
require 'config/config.php';
include 'frontend/functions.php';
include("login/auth.php");
if (isset($_GET['oid'])) {
	
$oid = $_GET['oid'];
$selectquery = "SELECT accepted FROM offers WHERE oid='$oid'";
$selectresult = $conn->query($selectquery);
$row = $selectresult->fetch_array(MYSQLI_NUM);
$accepted = $row[0];
	
} else {
	
$oid = $conn->real_escape_string($_POST['oid']);
$accepted = $conn->real_escape_string($_POST['accepted']);
}

$sql = "SELECT accepted FROM offers WHERE oid='$oid'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not correct';
		}
			else
			{
				$accepted = "1";
			}

$sql1 = "UPDATE offers SET accepted='$accepted' WHERE oid='$oid'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }

$sql2 = "SELECT * FROM offers WHERE oid='$oid'";
$result = mysqli_query($conn,$sql2)or die(mysqli_error($conn));
while($row = mysqli_fetch_array($result)) {			
	
	$offmon = $row['offmon'];
	$tradeloc = $row['tradeloc'];	
	$reqmon = $row['reqmon'];
	$offerby = $row['tname'];
	$rname = $_SESSION["uname"];
}

$sql3 = "INSERT INTO trades (tradeloc, tname, rname, offmon, reqmon) VALUES ('$tradeloc','$offerby','$rname','$offmon','$reqmon')";
if(!mysqli_query($conn,$sql3))
{
    echo 'Not Inserted';
}
else
{
    header('Location:active-trades.php');
}		

$sql4 = "SELECT * FROM users WHERE uname='".$_SESSION['uname']."'";
$result = mysqli_query($conn,$sql4)or die(mysqli_error($conn));	
				while($row = mysqli_fetch_array($result)) {
					$reqtrades = $row['reqtrades'];					
				}	
			$reqtrades = ++$reqtrades;

$sql5 = "UPDATE users SET reqtrades='$reqtrades' WHERE uname='".$_SESSION['uname']."'";
    if(!mysqli_query($conn,$sql5))
        {
            echo 'Not Inserted';
        }


?>