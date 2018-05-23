<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Registration</title>
<link rel="stylesheet" href="css/style.css" />
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
<div class="form">
<h1>Registration</h1>
<form name="registration" action="" method="post">
<input type="text" name="uname" placeholder="Username" required />
<input type="email" name="email" placeholder="Email" required />
<input type="password" name="upass" placeholder="Password" required />
<input type="submit" name="submit" value="Register" />
</form>
<br />
<p>Already registered? <a href='login.php'>Login Here</a></p>
<br />
<p>Back to Home <a href='/'>Home</a></p>
</div>
<?php } ?>
</body>
</html>
