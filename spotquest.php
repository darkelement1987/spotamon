<?php
session_start(); // <-------- add session_start here
ob_start();
require './config/config.php';
include'frontend/functions.php';
include("login/auth.php");

$quest = $conn->real_escape_string($_POST['quest']);
$reward = $conn->real_escape_string($_POST['reward']);
$sname = $conn->real_escape_string($_POST['sname']);             
$sid = $conn->real_escape_string($_POST['sid']); 

$sql1 = "UPDATE stops SET quested='1',quest='$quest',reward='$reward' WHERE sid='$sname'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }
            else
            {
                echo 'Inserted';
            }


$rewardquery = "SELECT reward FROM rewards WHERE reid='$reward'";
	if(!mysqli_query($conn,$rewardquery))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
				}
				
$pokestopquery = "SELECT sname,slatitude,slongitude FROM stops WHERE sid = '$sname'";
	if(!mysqli_query($conn,$pokestopquery))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}
			

$resultstop = $conn->query($pokestopquery);

$row = $resultstop->fetch_array(MYSQLI_NUM);
$stopname = $row[0];
$stoplat = $row[1];
$stoplon = $row[2];
			
header('Location:index.php?loc='.$stoplat.','.$stoplon.'&zoom=19');

?>
