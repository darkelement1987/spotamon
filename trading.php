<?php
require_once 'initiate.php';
include  S_FUNCTIONS . 'functions.php';
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
            echo 'Not Inserted4';
        }

$sql2 = "SELECT * FROM offers WHERE oid='$oid'";
$result = mysqli_query($conn,$sql2)or die(mysqli_error($conn));
while($row = mysqli_fetch_array($result)) {			
	$oid = $row['oid'];
	$offmon = $row['offmon'];
	$tradeloc = $row['tradeloc'];
	$offerby = $row['tname'];
	$rname = $_SESSION["uname"];
	$coffer = $row['reqmon'];
	$ccp = 0;
    $civ = 0;
	$shiny = 0;
	$alolan = 0;
	$complete =0;
	}

$sql3 = "INSERT INTO trades (oid, tradeloc, tname, rname, offmon) VALUES ('$oid','$tradeloc','$offerby','$rname','$offmon')";
if(!mysqli_query($conn,$sql3))
{
    echo 'Not Inserted1';
}		

$sql6 = "INSERT INTO tradeoffers (oid, coffer, offerby, cofferby, ccp, civ, cshiny, calolan, accepted, complete) VALUES ('$oid','$coffer','$offerby','$rname','$ccp','$civ','$shiny','$alolan','$accepted','$complete')";
if(!mysqli_query($conn,$sql6)){
    echo 'Not Inserted2';
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
            echo 'Not Inserted3';
        }

header('Location:../active-offers.php?oid='.$oid.'');

?>