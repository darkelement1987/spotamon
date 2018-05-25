<?php
require './config/config.php';
include'frontend/functions.php';
include'login/auth.php';
$upass = $conn->real_escape_string($_POST['upass']);
// attempt insert query execution
if(!empty($upass)){
$sql = "UPDATE users SET upass='".md5($upass)."' WHERE uname='".$_SESSION['uname']."'";
if(mysqli_query($conn, $sql)){
    echo "Records added successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
}
 
// close connection
mysqli_close($conn);
echo "<meta http-equiv=\"refresh\" content=\"0;URL=./profile.php\">";
} else {
echo "<br /><center><img src='/static/img/oops2.png'></center>";
echo "<br /><center>Can not insert a blank Password</center>";
echo "<br /><center>You will be redirected back to <a href='/edit-profile.php'>Edit Profile</a></center>";
echo "<meta http-equiv='refresh' content='3;url=/edit-profile.php'>";	
}
?>
