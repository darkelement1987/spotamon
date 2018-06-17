<?php
	require('../config/config.php');
	session_start();
    // If form submitted, insert values into the database.
    if (isset($_POST['uname'])){
		
		$uname = stripslashes($_REQUEST['uname']); // removes backslashes
		$uname = mysqli_real_escape_string($conn,$uname); //escapes special characters in a string
		$upass = stripslashes($_REQUEST['upass']);
		$upass = mysqli_real_escape_string($conn,$upass);
		
	//Checking is user existing in the database or not
        $query = "SELECT * FROM `users` WHERE uname='$uname' and upass='".md5($upass)."'";
		$result = mysqli_query($conn,$query) or die(mysql_error());
		$rows = mysqli_num_rows($result);
        if($rows==1){
			$_SESSION['uname'] = $uname;
			$_SESSION['upass'] = $upass;
			 header('Location:./');
            }else{
?>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<meta content="True" name="HandheldFriendly">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="./css/style.css" />
</head>
<body>
<center>
<div class='form'><h3>Username/password is incorrect.</h3><br/>Click here to <a href='login.php'>Login</a></div></center>
</body>
</html>
<?php }
    }else{
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<meta content="True" name="HandheldFriendly">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="./css/style.css" />
</head>
<body>
<center>
<div class="form">
<h1>Log In</h1>
<form action="" method="post" name="login">
<p><input type="text" name="uname" placeholder="Username" required /></p>
<p><input type="password" name="upass" placeholder="Password" required /></p>
<p><input name="submit" type="submit" value="Login" /></p>
<p><a href="../reset.php">Forgot password</a></p>
</form>
<br />
<b>Not registered yet?</b><p> <a href='registration.php'>Register Here</a> / <a href='../'>Back home</a></p>
</div></center>
</body>
</html>
<?php } ?>
