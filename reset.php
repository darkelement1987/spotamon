<?php
include 'frontend/menu.php';
include_once 'config/config.php';
?>

<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<?php
menu();

$error = '';
$name = '';
$email = '';
$subject = '';
$message = '';

function password_generate($chars) 
{
  $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
  return substr(str_shuffle($data), 0, $chars);
}

function clean_text($string)
{
	$string = trim($string);
	$string = stripslashes($string);
	$string = htmlspecialchars($string);
	return $string;
}

if(isset($_POST["submit"]))
{

    $checkquery = "SELECT * from users WHERE email='".$_POST["email"]."'";
    if(!mysqli_query($conn,$checkquery))
    	{
    		$error .= '<p><label class="text-danger">SQL ERROR</label></p>';
    	}
    		else
    		{}
    
    $checkmail = $conn->query($checkquery);
    
    $row = $checkmail->fetch_array(MYSQLI_NUM);
    $useremail = $row[1];
	$username = $row[2];

if (!$useremail) {
	$error .= '<p><label class="text-danger">User with this email does not exist</label></p>';
}
	
	if(empty($_POST["email"]))
	{
		$error .= '<p><label class="text-danger">Please Enter your Email</label></p>';
	}
	else
	{
		$email = clean_text($_POST["email"]);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$error .= '<p><label class="text-danger">Invalid email format</label></p>';
		}
	}
	if($error == '')
	{
		require 'static/scripts/class.phpmailer.php';
		$token = password_generate(7);
		
		    $storetoken = "INSERT IGNORE INTO reset (uname, email, token) VALUES ('$username','$useremail','$token') ON DUPLICATE KEY UPDATE uname='$username', email='$useremail', token='$token';";
    if(!mysqli_query($conn,$storetoken))
    	{}
    		else
    		{}
		
		$mail = new PHPMailer;
		$mail->IsSMTP();								//Sets Mailer to send message using SMTP
		$mail->Host = $mailhost;		//Sets the SMTP hosts of your Email hosting, this for Godaddy
		$mail->Port = $mailport;								//Sets the default SMTP server port
		$mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
		$mail->Username = $mailuser;					//Sets SMTP username
		$mail->Password = $mailpass;					//Sets SMTP password
		$mail->SMTPSecure = $mailauthtype;							//Sets connection prefix. Options are "", "ssl" or "tls"
		$mail->From = $_POST["email"];					//Sets the From email address for the message
		$mail->FromName = '';				//Sets the From name of the message
		$mail->AddAddress($mailemail, 'Name');		//Adds a "To" address
		$mail->AddCC($_POST["email"], '');	//Adds a "Cc" address
		$mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
		$mail->IsHTML(true);							//Sets message type to HTML				
		$mail->Subject = 'Password reset';				//Sets the Subject of the message
		$mail->Body = 'You requested a password reset<br>Reset password using this link: '.$viewurl.'/reset.php?token='.$token;				//An HTML or plain text message body
		if($mail->Send())								//Send an Email. Return true on success or false on error
		{
			$error = '<label class="text-success">A mail has been sent. use the reset-link that was sent to you</label>';
		}
		else
		{
			$error = '<label class="text-danger">There is an Error</label>';
		}
		$name = '';
		$email = '';
		$subject = '';
		$message = '';
	}
}?>
<?php
if (!isset($_GET['token'])) {?>
					<!DOCTYPE html>
					<center>
					<h3 align="center">Reset password</h3>
					<br />
					<form method="post" id="feedback">
						<div class="form-group">
							<label>Email</label>
							<input type="text" name="email" class="form-control" placeholder="Enter Email" value="<?php echo $email; ?>" />
						</div>
						<div class="form-group" align="center">
							<input type="submit" name="submit" value="Reset" class="btn btn-info" />
						</div>
						<?php echo $error; ?>
					</form>
</center><?php } else {
	// Get user/email from token
    $fetchtoken = "SELECT uname,email from reset WHERE token='".$_GET['token']."'";
    if(!mysqli_query($conn,$fetchtoken))
    	{
    		$error .= '<p><label class="text-danger">SQL ERROR</label></p>';
    	}
    		else
    		{}
    
    $fetched = $conn->query($fetchtoken);
    
    $row = $fetched->fetch_array(MYSQLI_NUM);
    $fetchedname = $row[0];
	$fetchedmail = $row[1];	
	
	// Insert new pass using token
	$newpass = password_generate(10);
    $resetpass = "UPDATE users SET upass='".md5($newpass)."' WHERE uname='".$fetchedname."'";
    if(!mysqli_query($conn,$resetpass))
    	{
    		echo '<center><p><label class="text-danger">SQL ERROR</label></p></center>';
    	}
    		else
    		{}
		
		if (!$fetchedname){echo "<center><p><label class=\"text-danger\">INVALID TOKEN</label></p></center>";} else {
			echo "<center><p><label class=\"text-danger\">Password reset. New password '".$newpass."'</label></p></center>";
			mysqli_query($conn,"DELETE FROM reset WHERE token='".$_GET['token']."'");
			}	

	
}?>

<footer></footer>

