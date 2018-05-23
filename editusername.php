<?php
require './config/config.php';
include'frontend/functions.php';
include'login/auth.php';
$uname = $conn->real_escape_string($_POST['uname']);
// attempt insert query execution
$sql = "UPDATE users SET uname='$uname' WHERE uname='".$_SESSION['uname']."'";
if(mysqli_query($conn, $sql)){
    echo "Records added successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
}
 
// close connection
mysqli_close($conn);
header('Location:login/logout.php');
?>