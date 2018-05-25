<?php
require './config/config.php';
include'frontend/functions.php';
include'login/auth.php';
$email = $conn->real_escape_string($_POST['email']);
// attempt insert query execution
if(!empty($email)){
$sql = "UPDATE users SET email='$email' WHERE uname='".$_SESSION['uname']."'";
if(mysqli_query($conn, $sql)){
    echo "Records added successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
}
 
// close connection
mysqli_close($conn);
echo "<meta http-equiv=\"refresh\" content=\"0;URL=profile.php\">";
} else {
echo "<br /><center><img src='./static/img/oops2.png'></center>";
echo "<br /><center>Can not insert a blank email</center>";
echo "<br /><center>You will be redirected back to <a href='edit-profile.php'>Edit Profile</a></center>";
	echo "<meta http-equiv='refresh' content='3;url=edit-profile.php'>";	
}
?>
