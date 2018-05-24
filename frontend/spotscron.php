<?php
require '../config/config.php';

$sql = "DELETE FROM spots WHERE date < (NOW() - INTERVAL 15 MINUTE) OR bad>='5'";
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
