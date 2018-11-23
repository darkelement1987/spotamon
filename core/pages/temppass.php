<?php
require_once "initiate.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf()) {
        $result = 'Validation Error';
        echo $result;
        exit();
    }

    $upass = $Validate->getPost('password', 'password');
    $cpass = $Validate->getPost('confirmpassword', 'password', true, $upass);
    $user = $sess->get('uname');
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
    <title>Passwords and Stuff</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
        crossorigin="anonymous">
    <link rel="stylesheet" href="<?=versionFile(W_CSS . 'style.css')?>">
    <?php include_once S_PAGES . 'parts/js.php'; ?>
</head>

<body>


    <h3 style="text-align:center;">
        <strong>Set Your Password</strong>
    </h3>

    <center>
        <table id="spotted" class="table table-bordered">
            <tr>
                <form action="#" method="post">

                    <td class="discord-light opc50">
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
                                <input type="password" autocomplete="off" name="password" placeholder="Password" class="form-control login-fields form-password"
                                    pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{5,20}$' oninvalid="this.setCustomValidity('Password must contain: \n1 Capital, 1 Lowercase\n1 Number, and be 8-20 characters long.')"
                                    maxlength="20" id="regpass" minlength="8" required />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text login-fields">
                                        <i class="fa fa-key"></i>
                                        <label for="confirmpassword" class="sr-only">Confirm Password</label>
                                    </span>
                                </div>
                                <input type="password" autocomplete="off" name="confirmpassword" placeholder="Confirm Password" class="form-control login-fields form-password"
                                    pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{5,20}$' oninvalid="this.setCustomValidity('Password must contain: \n1 Capital, 1 Lowercase\n1 Number, and be 8-20 characters long.')"
                                    id="regconfirmpass" maxlength="20" minlength="8" required />
                            </div><br>
                            <input class="btn btn-dark my-2 mx-auto" type='submit' name='submit' value='Submit' id='submit_pass'
                                style='float:left;'>
                    </td>
                </form>
            </tr>

        </table>

        <script src="<?=versionFile(W_JS . 'spotamon.js')?>">
        </script>
</body>

</html>

<?php }?>
