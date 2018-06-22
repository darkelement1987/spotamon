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
    $upass = stripslashes($_REQUEST['password']);
    $upass = mysqli_real_escape_string($conn,$upass);
	$offtrades = 0;
	$reqtrades = 0;
    $usergroup = 1;
		$trn_date  = date("Y-m-d H:i:s");
		
		$checkuserquery = "SELECT * FROM users WHERE uname='$uname'";
		$userresult     = $conn->query($checkuserquery);
		$row_cnt        = $userresult->num_rows;
		if ($row_cnt == 1) {
			echo "<div class='form'><h3>Username already taken</h3><br/>You will be brought back to the registration page...</div>";
			echo "<meta http-equiv=\"refresh\" content=\"3;url=".$_SERVER['HTTP_REFERER']."\"/>";
		} else {
			
			$query  = "INSERT into `users` (uname, upass, email, usergroup, trn_date, url, lastUpload, offtrades, reqtrades) VALUES ('$uname', '" . md5($upass) . "', '$email', '$usergroup', '$trn_date', '', '', '$offtrades', '$reqtrades')";
			$result = mysqli_query($conn, $query);
			if ($result) {
				echo "<div class='form'><h3>Registration was Successful.</h3><br/>Click here to <a href='login.php'>Login</a></div>";
			}
		}
	} else {
    ?>
<center><div class="form">
<h1>Registration</h1>


        <form name="registration" action="" method="post"></p>
            <p><input type="text" name="uname" placeholder="Username" required /></p>
            <p><input type="email" name="email" placeholder="Email" required /></p>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                <input type="password" minlength="6" name="password" id="password" placeholder="password" onkeydown="" onkeyup="checkPass(); return false;"/><br><br>
                <input type="password" minlength="6" name="confirm_password" id="confirm_password" placeholder="confirm password" onkeydown="" onkeyup="checkPass(); return false;" />
                <br><span id='message'></span>
                <div id="error-nwl"></div>
            <br><a href="../policy.php">Read our privacy policy</a>
            <p>I've read the privacy policy and agree to share my personal information</p>
            <p>By registering an account I agree with the privacy policy</p>
            <input type="submit" name="submit"  value="registration"  id="submit"/>

            <script>
                $('input[type="submit"]').attr('disabled','disabled');
                function checkPass()
                {
                    var pass1 = document.getElementById('password');
                    var pass2 = document.getElementById('confirm_password');
                    var message = document.getElementById('error-nwl');
                    var goodColor = "#66cc66";
                    var badColor = "#ff6666";

                    if(pass1.value.length > 5)
                    {
                        pass1.style.backgroundColor = goodColor;
                        message.style.color = goodColor;
                        $('input[type="submit"]').attr('disabled','disabled');
                        message.innerHTML = "character number ok!"
                    }
                    else
                    {
                        pass1.style.backgroundColor = badColor;
                        message.style.color = badColor;
                        $('input[type="submit"]').attr('disabled','disabled');
                        message.innerHTML = "<br>You have to enter at least 6 digit!"
                        return;
                    }

                    if(pass1.value == pass2.value)
                    {
                        pass2.style.backgroundColor = goodColor;
                        message.style.color = goodColor;
                        message.innerHTML = "<br>Ready to go!"
                        $('input[type="submit"]').removeAttr('disabled');
                    }
                    else
                    {
                        pass2.style.backgroundColor = badColor;
                        message.style.color = badColor;
                        $('input[type="submit"]').attr('disabled','disabled');
                        message.innerHTML = "<br>These passwords don't match!"
                    }
                }
            </script>
            <script>
                event: { keydown: function(data, event) { keypressdown(); return true; } }
            </script>
<br />
<p>Already registered? <a href='login.php'>Login Here</a></p>
<br />
<p>Back to <a href='../'>home</a></p>
</div></center>
<?php } ?>
</body>
</html>
