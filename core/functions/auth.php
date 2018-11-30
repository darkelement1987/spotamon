<?php
require_once 'initiate.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $Validate->getPost('formtype');
    if ($form !== null) {
        $sess->set('form', $form);
        unset($form);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $form = $Validate->getGet('formtype');
    if ($form !== null) {
        $sess->set('form', $form);
        unset($form);
    }
}
$form = $sess->get('form');

if ($form == 'discordlogin' || $form == 'discordregister') {
    $Oauth2 = new \Spotamon\Oauth2;
}

$authenticated = new \Spotamon\Authentication;
if ($authenticated->result === 'discord-register' || $authenticated->result === 'discord-login') {
    $result = $authenticated->result;
    $form = W_PAGES . 'temppass.php';
    $csrftoken = csrf();
    $quname = $sess->get('uname');
    $passcheck = $conn->prepare("SELECT upass FROM users WHERE uname = ?;");
    $passcheck->bind_param("s", $quname);
    $passcheck->execute();
    $passcheck->bind_result($upass);
    $passcheck->fetch();
    $passcheck->close();
    $pass = password_verify('1', $upass);


?>
<!doctype html>
<html lang="en">

<head>
    <title>Discord Oauth</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B"
        crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?=versionFile(W_CSS . 'style.css')?>">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em"
        crossorigin="anonymous">
    </script>

</head>

<body class="discord-dark d-flex flex-column align-align-items-center">
    <div class="container-fluid h-100">
        <div class="row align-items-center justify-content-center flex-column h-100">
            <div class="col-sm-1 col-md-3 ">
            </div>
            <div class="col-sm-10 col-md-6">
                <div class="card card-block text-center mx-auto border-0 w-75">
                    <div class="card-header discord-blue">
                        <div class="discord-logo-text-white mx-auto"></div>
                    </div>
                    <div class="card-body discord">
                        <?php if ($pass) {?>
                        <p class="card-text">Thank you for Registering with Discord<br>For compatability please set a
                            password now</p>
                        <form id="passset" method="post" action="<?=W_PAGES?>temppass.php">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text login-fields">
                                        <i class="fa fa-key"></i>
                                        <label for="password" class="sr-only">Password</label>
                                    </span>
                                </div>
                                <input type="password" autocomplete="off" name="password" placeholder="Password" class="form-control login-fields"
                                    pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{8,20}$" oninvalid="this.setCustomValidity('Password must contain: \n1 Capital, 1 Lowercase\n1 Number, and be 8-20 characters long.')"
                                    maxlength="18" id="regpass" minlength="8" required />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text login-fields">
                                        <i class="fa fa-key"></i>
                                        <label for="confirmpassword" class="sr-only">Confirm Password</label>
                                    </span>
                                </div>
                                <input type="password" name="confirmpassword" autocomplete="off" placeholder="Confirm Password" class="form-control login-fields"
                                    pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{8,20}$' oninvalid="this.setCustomValidity('Password must contain: \n1 Capital, 1 Lowercase\n1 Number, and be 8-20 characters long.')"
                                    id="regconfirmpass" maxlength="20" minlength="8" required />
                            </div>
                            <?=$csrftoken?>
                            <button class="btn btn-primary btn-block btn-sm" id="passwordsubmit" type="submit">Register</button>
                        </form>
                        <?php } else {?>
                        <p class="card-text">You have sucessfully logged in with Discord.</p>
                        <p class="card-text"> You May now close this window </p>
                        <?php }?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-1 col-md-3">
        </div>
    </div>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script>
        $(document).ready(function() {
            $("#regconfirmpass").focus(function() {
                $('#regconfirmpass').prop('pattern', $('#regpass').val());
                $('#regconfirmpass').attr('oninvalid', 'this.setCustomValidity("Passwords must Match")');
        });
            $('#return-btn').hide();
        });

        $("#passsset").submit(function(event) {
            event.preventDefault();
            var data = $("#passset").serialize();
            var auth = $('#passset').attr('action');
            $.post(auth, data, function(data) {
                if (data != 'false') {
                    $('#passset').html('<p> Thank you <br>You may now close this window</p>');
                } else {
                    ('#passset').html(
                        '<p> there seems to have been an error, please contact your system administrator</p>'
                    );
                }
            });
        });
        </script>
</body>

</html>

<?php
}

if ($authenticated->result == True) {

    echo 'true';
} else {
    foreach ($authenticated->error as $error) {
        echo $error . '<br>';
        }
}
?>
