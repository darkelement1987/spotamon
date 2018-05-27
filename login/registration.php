<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Registration</title>
<meta content="True" name="HandheldFriendly">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="./css/style.css" />
</head>
<body>
<?php
	require('../config/config.php');
    // If form submitted, insert values into the database.
    if (isset($_REQUEST['uname'])){
		$uname = stripslashes($_REQUEST['uname']); // removes backslashes
		$uname = mysqli_real_escape_string($conn,$uname); //escapes special characters in a string
		$email = stripslashes($_REQUEST['email']);
		$email = mysqli_real_escape_string($conn,$email);
		$upass = stripslashes($_REQUEST['upass']);
		$upass = mysqli_real_escape_string($conn,$upass);
		$usergroup = 1;
		$trn_date = date("Y-m-d H:i:s");
        $query = "INSERT into `users` (uname, upass, email, usergroup, trn_date) VALUES ('$uname', '".md5($upass)."', '$email', '$usergroup', '$trn_date')";
        $result = mysqli_query($conn,$query);
        if($result){
            echo "<div class='form'><h3>Registration was Successful.</h3><br/>Click here to <a href='login.php'>Login</a></div>";
        }
    }else{
?>
<center><div class="form">
<h1>Registration</h1>
<form name="registration" action="" method="post"></p>
<p><input type="text" name="uname" placeholder="Username" required /></p>
<p><input type="email" name="email" placeholder="Email" required /></p>
<p><input type="password" name="upass" placeholder="Password" required /></p>
<p><input type="submit" name="submit" value="Register" /></p>
</form>
<br />
<p>Already registered? <a href='login.php'>Login Here</a></p>
<br />
<p>Back to <a href='../'>home</a></p>
</div></center>
<?php } ?>
</body>
</html>
