<?php
require_once 'initiate.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$csrf->validateRequest()) {
        $result = 'Validation Error';
        echo $result;
        exit();
    }

    $upass = $Validate->getPost('password', 'password');
    $cpass = $Validate->getPost('confirmpassword', 'password', true, ['hash', $upass]);
    var_dump($cpass);
    $user = $Validate->getSession('uname');
    if ($cpass === false) {
        echo $Validate->data;
    } else {
        $cpass = password_hash($cpass, PASSWORD_DEFAULT);
// attempt insert query execution
        if (!empty($cpass)) {
            $passupdate = $conn->prepare("UPDATE users SET upass=? WHERE uname=?;");
            $passupdate->bind_param('ss', $cpass, $user);
            $passupdate->execute();
            $passupdate->close();
            ?>
<br />
<center>Thank you, your password was successfully updated</center>
<meta http-equiv=\"refresh\" content=\"3;URL=" . W_ROOT . " index.php\">
<?php
}
    }
} else {
    $form = W_PAGES . 'temppass.php';
    $csrftoken = $csrf->insertToken($form, false);

    ?>
<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
        crossorigin="anonymous">
    <link rel="stylesheet" href="<?=W_CSS?>style.css">
</head>

<body>


    <h3 style="text-align:center;">
        <strong>Set Your Password</strong>
    </h3>

    <center>
        <table id="spotted" class="table table-bordered">
            <tr>
                <form action="#" method="post">

                    <td>
                        <center>
                            <span id='temppass-error' style='font-size:10px;float:left;'></span>
                            <br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text login-fields">
                                        <i class="fa fa-key"></i>
                                        <label for="password" class="sr-only">Password</label>
                                    </span>
                                </div>
                                <input type="password" name="password" placeholder="Password" class="form-control login-fields"
                                    pattern='^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,20}$' oninvalid="this.setCustomValidity('Password must contain: \n1 Capital, 1 Lowercase\n1 Number, and be 8-20 characters long.')"
                                    maxlength="20" id="regpass" minlength="8" required />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text login-fields">
                                        <i class="fa fa-key"></i>
                                        <label for="confirmpassword" class="sr-only">Confirm Password</label>
                                    </span>
                                </div>
                                <input type="password" name="confirmpassword" placeholder="Confirm Password" class="form-control login-fields"
                                    pattern='^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,20}$' oninvalid="this.setCustomValidity('Password must contain: \n1 Capital, 1 Lowercase\n1 Number, and be 8-20 characters long.')"
                                    id="regconfirmpass" maxlength="20" minlength="8" required />
                            </div>
                            <input class="btn btn-primary mx-auto" type='submit' name='submit' value='Submit' id='submit_pass'
                                style='float:left;'>
                    </td>
                </form>
            </tr>

        </table>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
            crossorigin="anonymous">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em"
            crossorigin="anonymous">
        </script>
        <script src="<?=W_JS?>spotamon.js">
        </script>
</body>

</html>

<?php }?>
