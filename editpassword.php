<?php
require './config/config.php';
include'frontend/functions.php';
include'login/auth.php';
$upass = $conn->real_escape_string($_POST['upass']);
// attempt insert query execution
$sql = "UPDATE users SET upass='".md5($upass)."' WHERE uname='".$_SESSION['uname']."'";
if(mysqli_query($conn, $sql)){
    echo "Records added successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
}
 
// close connection
mysqli_close($conn);
echo "<meta http-equiv=\"refresh\" content=\"0;URL=./profile.php\">";
?>