<?php
require_once 'initiate.php';
;

if (isset($_GET['oid'])) {
	$toid = $_GET['toid'];
	$oid = $_GET['oid'];
	$loc = $_GET['loc'];
	$selectquery = "SELECT accepted FROM tradeoffers WHERE oid='$oid'";

$selectresult = $conn->query($selectquery);
$row = $selectresult->fetch_array(MYSQLI_NUM);
	
} else {
$toid = $conn->real_escape_string($_POST['toid']);
$oid = $conn->real_escape_string($_POST['oid']);

$accepted = 2;
}

$sql1 = "UPDATE tradeoffers SET accepted='$accepted' WHERE toid='$toid'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }
            else
            {
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=../active-offers.php?oid=".$oid."\">";
			}
			
?>