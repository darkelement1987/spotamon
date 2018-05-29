<?php
require '../config/config.php';
$sql = "DELETE FROM spotraid WHERE rdate < (NOW() - INTERVAL 45 MINUTE)";
if(!mysqli_query($conn,$sql))
{
    echo 'Not Deleted';
}
else
{
    echo 'Deleted';
}

$sql2 = "UPDATE gyms SET actraid='0',actboss='0',hour='0',min='0',ampm='0',raidby='' WHERE date < (NOW() - INTERVAL 45 MINUTE)";
if(!mysqli_query($conn,$sql2))
{
    echo 'Not Deleted';
}
else
{
    echo 'Deleted';
}        
 // ends *_query() call
?>
