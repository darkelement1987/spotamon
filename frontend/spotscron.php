<?php
require '../config/config.php';

if (empty($delmon)) {
	$delvalue = 5;
} else {
	$delvalue = $delmon;
}

$sql = "DELETE FROM spots WHERE date < (NOW() - INTERVAL 15 MINUTE) OR bad>='$delvalue'";
if(!mysqli_query($conn,$sql))
{
    echo 'Not Deleted';
}
else
{
    echo 'Deleted';
}    
 // ends *_query() call
?>
