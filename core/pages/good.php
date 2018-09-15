<?php




if (isset($_GET['spotid'])) {
	
	$spotid = $_GET['spotid'];
	$loc = $_GET['loc'];
	$selectquery = "SELECT good FROM spots WHERE spotid='$spotid'";


$selectresult = $conn->query($selectquery);
$row = $selectresult->fetch_array(MYSQLI_NUM);
$good = $row[0];
	
} else {
	
$spotid = $conn->real_escape_string($_POST['spotid']);
$good = $conn->real_escape_string($_POST['good']);
}

$sql = "SELECT good FROM spots WHERE spotid='$spotid'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not correct';
		}
			else
			{
				$good = ++$good;
			}

$sql1 = "UPDATE spots SET good='$good' WHERE spotid='$spotid'";
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