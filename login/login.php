<?php
	require('../config/config.php');
	session_start();
	$post_data = http_build_query(
    array(
        'secret' => $captcha_secret_key,
        'response' => $_POST['g-recaptcha-response'],
        'remoteip' => $_SERVER['REMOTE_ADDR']
    )
);
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $post_data
    )
);
$context  = stream_context_create($opts);
$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
$result = json_decode($response);
    // If form submitted, insert values into the database.
	
	if (!empty($_POST)){
if ($result->success==true) {} else {$error .= '<label class="text-danger">Captcha wrong</label>';}
}
	
    if ($result->success && isset($_POST['uname'])){
		
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
<script src='https://www.google.com/recaptcha/api.js'></script>
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
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<center>
<div class="form">
<h1>Log In</h1>
<form action="" method="post" name="login">
<p><input type="text" name="uname" placeholder="Username" required /></p>
<p><input type="password" name="upass" placeholder="Password" required /></p>
<p><div class="g-recaptcha" data-sitekey=<?php echo $captcha_site_key; ?>></div></p>
<p><input name="submit" type="submit" value="Login" /></p>
<p><label style="color:red;"><b><?php echo $error;?></b></label></p>
<p><a href="../reset.php">Forgot password</a></p>
</form>
<br />
<b>Not registered yet?</b><p> <a href='registration.php'>Register Here</a> / <a href='../'>Back home</a></p>
</div></center>
</body>
</html>
<?php } ?>
