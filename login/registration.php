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
if (isset($_REQUEST['username'])){
    $uname = stripslashes($_REQUEST['username']); // removes backslashes
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
        <script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>

        <style>
            #form label{float:left; width:140px;}
            #error_msg{color:red; font-weight:bold;}
        </style>

        <script>
            $(document).ready(function(){
                var $submitBtn = $("#form input[type='submit']");
                var $passwordBox = $("#password");
                var $confirmBox = $("#confirm_password");
                var $errorMsg =  $('<span id="error_msg"><br>Passwords do not match.<br></span>');

                // This is incase the user hits refresh - some browsers will maintain the disabled state of the button.
                $submitBtn.removeAttr("disabled");

                function checkMatchingPasswords(){
                    if($confirmBox.val() != "" && $passwordBox.val != ""){
                        if( $confirmBox.val() != $passwordBox.val() ){
                            $submitBtn.attr("disabled", "disabled");
                            $errorMsg.insertAfter($confirmBox);
                        }
                    }
                }
                function resetPasswordError(){
                    $submitBtn.removeAttr("disabled");
                    var $errorCont = $("#error_msg");
                    if($errorCont.length > 0){
                        $errorCont.remove();
                    }
                }
                $("#confirm_password, #password")
                    .on("keydown", function(e){
                        /* only check when the tab or enter keys are pressed
                         * to prevent the method from being called needlessly  */
                        if(e.keyCode == 13 || e.keyCode == 9) {
                            checkMatchingPasswords();
                        }
                    })
                    .on("blur", function(){
                        // also check when the element looses focus (clicks somewhere else)
                        checkMatchingPasswords();
                    })
                    .on("focus", function(){
                        // reset the error message when they go to make a change
                        resetPasswordError();
                    })
            });
        </script>

        <form id="form" name="registration" method="post" action="registration.php">
            <label for="username"></label>
            <input name="username" id="username" type="text" placeholder="username" required /></label><br/>

            <label for="email"></label>
            <input name="email" id="email" type="email" placeholder="email" required /></label><br/>

            <label for="password"></label>
            <input name="password" id="password" type="password" placeholder="password" required /><br/>

            <label for="confirm_password"></label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm password" required /><br/>

            <a href="../policy.php">Read our privacy policy</a>
            <p>I've read the privacy policy and agree to share my personal information</p>
            <p>By making an account you agree with this privacy policy</p>

        <p><input type="submit" name="submit" value="Register" id="regbutton" disabled="disabled"/></p>
        </form>
<br />
<p>Already registered? <a href='login.php'>Login Here</a></p>
<br />
<p>Back to <a href='../'>home</a></p>
</div></center>
<?php } ?>
</body>
</html>