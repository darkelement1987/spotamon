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
    $usergroup = 1;
    $trn_date = date("Y-m-d H:i:s");
    $query = "INSERT into `users` (uname, upass, email, usergroup, trn_date, url, lastUpload) VALUES ('$uname', '".md5($upass)."', '$email', '$usergroup', '$trn_date', '', '')";
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
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                <input type="password" name="password" id="password" placeholder="password" />
                <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm password" />
                <br><span id='message'></span>
            <br><a href="../policy.php">Read our privacy policy</a>
            <p>I've read the privacy policy and agree to share my personal information</p>
            <p>By registering an account I agree with the privacy policy</p>
            <input type="submit" name="submit"  value="registration"  id="submit"/>

            <script>
                $('input[type="submit"]').attr('disabled','disabled');
                $('#password, #confirm_password').on('keyup', function () {
                    if ($('#password').val() == $('#confirm_password').val()) {
                        $('#message').html('<br>Matching<br>').css('color', 'green');
                        $('input[type="submit"]').removeAttr('disabled');
                    } else
                        $('#message').html('<br>Not Matching<br>').css('color', 'red');
                });
            </script>

<br />
<p>Already registered? <a href='login.php'>Login Here</a></p>
<br />
<p>Back to <a href='../'>home</a></p>
</div></center>
<?php } ?>
</body>
</html>