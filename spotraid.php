<?php

ob_start();
require 'config/config.php';
include'functions.php';
$rboss = $conn->real_escape_string($_POST['rboss']);
$rhour = $conn->real_escape_string($_POST['rhour']);
$rmin = $conn->real_escape_string($_POST['rmin']);
$rampm = $conn->real_escape_string($_POST['rampm']);
$gname = $conn->real_escape_string($_POST['gname']);

$sql = "INSERT INTO spotraid (rboss, rhour, rmin, rampm) VALUES ('$rboss','$rhour','$rmin','$rampm')";
    if(!mysqli_query($conn,$sql))
        {
            echo 'Not Inserted';
        }
            else
            {
                echo 'Inserted';
            }
$sql1 = "UPDATE gyms SET actraid='1',actboss='$rboss',hour='$rhour',min='$rmin',ampm='$rampm' WHERE gid='$gname'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }
            else
            {
                echo 'Inserted';
            }                

    header('Location:/');
    
?>
