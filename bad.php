<?php
require_once 'initiate.php';



if (isset($_GET['spotid'])) {
	
	$spotid = $_GET['spotid'];
	$loc = $_GET['loc'];
	$selectquery = "SELECT bad FROM spots WHERE spotid='$spotid'";


$selectresult = $conn->query($selectquery);
$row = $selectresult->fetch_array(MYSQLI_NUM);
$bad = $row[0];
	
} else {
	
$spotid = $conn->real_escape_string($_POST['spotid']);
$bad = $conn->real_escape_string($_POST['bad']);
}

$sql = "SELECT bad FROM spots WHERE spotid='$spotid'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not correct';
		}
			else
			{
				$bad = ++$bad;
			}

$sql1 = "UPDATE spots SET bad='$bad' WHERE spotid='$spotid'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }
            else
            {
			
				if (isset($_GET['spotid'])) {
					echo "<meta http-equiv=\"refresh\" content=\"0;URL=./index.php?loc=$loc&zoom=19\">";
				} else {
					echo "<meta http-equiv=\"refresh\" content=\"0;URL=./pokemon.php\">";
				}
			   
			   
			   
            }


?>