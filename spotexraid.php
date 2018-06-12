<?php
$curl = curl_init();
ob_start();
require './config/config.php';
include'frontend/functions.php';
include("login/auth.php");	

$exraiddate = $conn->real_escape_string($_POST['exraiddate']);
$gname = $conn->real_escape_string($_POST['gname']);

$sql1 = "UPDATE gyms SET exraid='1',exraiddate='$exraiddate' WHERE gid='$gname'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }
            else
            {
                echo 'Inserted';
            }
$sql = "SELECT * FROM gyms WHERE gid='$gname'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }
            else
            {
                echo 'Inserted';
            }
    header('Location:index.php?loc='.$gymlat.','.$gymlon.'&zoom=19');

?>
