<?php

$pass = $Validate->getPost('password');
$upass = $Validate->getPost('password', 'password');
// attempt insert query execution
$upass = password_hash($upass, PASSWORD_DEFAULT);
$username = $sess->get('uname');
if (!empty($upass)) {
    $password = $conn->prepare("UPDATE users SET upass= ? WHERE uname= ?;");
    if (!$password) {
        die("error preparing statement");

    }
    $result = $password->bind_param("ss", $upass, $username);
    if (!$result) {
        die('error binding variables');
    }
    $result = $password->execute();
    if (!$result) {
        die('error executing statement');
    } else {
        echo "Records added successfully.";
    }

// close connection
    $password->close();
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=profile.php\">";
} else if (!empty($pass)) {?>
<br />
<center><img src='<?-W_ASSETS?>/img/oops2.png'></center>
<br />
<center>Can not insert a blank Password</center>
<br />
<center>You will be redirected back to <a href='edit-profile.php'>Edit Profile</a></center>
<meta http-equiv='refresh' content='3;url=edit-profile.php'>
<?php } else {?>
<br />
<center><img src='<?-W_ASSETS?>/img/oops2.png'></center>
<br />
<center>
    <?=$Validate->data?>
</center>
<br />
<center>You will be redirected back to <a href='edit-profile.php'>Edit Profile</a></center>
<meta http-equiv='refresh' content='3;url=edit-profile.php'>
<?php }?>
