<?php
require_once 'initiate.php';
include  S_FUNCTIONS . 'functions.php';


if (isset($_GET['exid'])) {
	
$exidr = $_GET['exidr'];
$selectquery = "SELECT * FROM exraids WHERE exidr='$exid'";
	
$selectresult = $conn->query($selectquery);
$row = $selectresult->fetch_array(MYSQLI_NUM);
$att = $row[0];
	
} else {
	
$exidr = $conn->real_escape_string($_POST['exidr']);
$att = $conn->real_escape_string($_POST['att']);

}

$sql = "SELECT * FROM exraids WHERE exid='$exidr'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not selected';
		}
			
$sql = "SELECT * FROM users WHERE uname = '$att'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not selected';
		}
			else
			{
				echo '<center>See you there!</center><br>';
			}

$sql1 = "INSERT INTO exraidatt (exid, uid) VALUES ('$exidr', '$att')";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted<br>';
        }
            else
            {
			
				if (isset($_GET['exid'])) {
					echo "<meta http-equiv=\"refresh\" content=\"0;URL=./index.php?loc=$loc&zoom=19\">";
				} else {
					echo "<meta http-equiv=\"refresh\" content=\"0;URL=./exraids.php\">";
				}
			   
			   
			   
            }


?>