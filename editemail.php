<?php
require './config/config.php';
include'frontend/functions.php';
include'login/auth.php';
$email = $conn->real_escape_string($_POST['email']);
// attempt insert query execution
$sql = "UPDATE users SET email='$email' WHERE uname='".$_SESSION['uname']."'";
if(mysqli_query($conn, $sql)){
    echo "Records added successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
}
 
// close connection
mysqli_close($conn);
echo "<meta http-equiv=\"refresh\" content=\"0;URL=./profile.php\">";
?>