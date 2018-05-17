<?php
require '../config/config.php';
$sql = "DELETE FROM spots WHERE date < (NOW() - INTERVAL 20 MINUTE)";
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
