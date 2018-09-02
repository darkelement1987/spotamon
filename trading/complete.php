<?php
require_once 'initiate.php';
;

if (isset($_GET['oid'])) {
	$toid = $_GET['toid'];
	$oid = $_GET['oid'];
	$loc = $_GET['loc'];
	$selectquery = "SELECT complete FROM tradeoffers WHERE oid='$oid'";

$selectresult = $conn->query($selectquery);
$row = $selectresult->fetch_array(MYSQLI_NUM);
	
} else {
$toid = $conn->real_escape_string($_POST['toid']);
$oid = $conn->real_escape_string($_POST['oid']);
$complete = $conn->real_escape_string($_POST['complete']);
$complete = 1;
}

$sql2 = "UPDATE offers SET complete='$complete' WHERE oid='$oid'";
    if(!mysqli_query($conn,$sql2))
        {
            echo 'Not Inserted';
        }

$sql1 = "UPDATE tradeoffers SET complete='$complete' WHERE toid='$toid'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }
            else
            {
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=../active-offers.php?oid=".$oid."\">";
			}

$sql3 = "DELETE FROM tradeoffers WHERE oid='$oid' AND complete='0' ";
    if(!mysqli_query($conn,$sql3))
        {
            echo 'Not Inserted';
        }
            else
            {
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=../active-offers.php?oid=".$oid."\">";
			}

			
?>