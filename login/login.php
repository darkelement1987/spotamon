<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
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
			$_SESSION['email'] = $email;
			 echo "<meta http-equiv=\"refresh\" content=\"0;URL=/login/index.php\">";
            }else{
				echo "<div class='form'><h3>Username/password is incorrect.</h3><br/>Click here to <a href='login.php'>Login</a></div>";
				}
    }else{
?>

<div class="form">
<h1>Log In</h1>
<form action="" method="post" name="login">
<input type="text" name="uname" placeholder="Username" required />
<input type="password" name="upass" placeholder="Password" required />
<input name="submit" type="submit" value="Login" />
</form>
<br />
<p>Not registered yet? <a href='registration.php'>Register Here</a></p>
<br />
<p>Back to Home <a href='/'>Home</a></p>
</div>
<?php } ?>


</body>
</html>
