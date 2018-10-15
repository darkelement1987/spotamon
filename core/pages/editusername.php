<?php
require_once 'initiate.php';

$uname = $conn->real_escape_string($_POST['uname']);
// attempt insert query execution
if(!empty($uname)){
$sql = "UPDATE users SET uname='$uname' WHERE uname='".$sess->get('uname')."'";
if(mysqli_query($conn, $sql)){
    echo "Username changed, logging out....";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
}

// close connection
mysqli_close($conn);
echo "<meta http-equiv=\"refresh\" content=\"3;URL=./login/logout.php\">";

} else {
    echo "<br /><center><img src='<?-W_ASSETS?>/img/oops2.png'></center>";
	echo "<br /><center>Can not insert a blank user name</center>";
	echo "<br /><center>You will be redirected back to <a href='edit-profile.php'>Edit Profile</a></center>";
	echo "<meta http-equiv='refresh' content='3;url=edit-profile.php'>";
}

?>
