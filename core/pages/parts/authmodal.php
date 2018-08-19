<?php
$form = W_FUNCTIONS . 'auth.php';
$csrftoken = $csrf->insertToken($form, false);
?>
<!-- Login/register Modal Start -->
<div class="container-fluid" id="auth-modal-container">
    <div class="modal fade" id="auth-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" id="auth-modal-content">
                <div class="modal-body p-0">
                    <div class="login-row row login-modal">
                        <div class="col-md-5 justify-content-center discord-modal" id="discordloginbody">
                            <a href="<?=W_FUNCTIONS?>auth.php?formtype=discordlogin" class="d-flex justify-content-center">
                                <img src="<?=W_ASSETS?>img/Discord_Logo.png" width="150px" alt="Discord" id="discord-logo-login"
                                    class="d-flex" />
                            </a>
                            <h5>or login with Discord!</h5>
                        </div>
                        <div class="col-md-7 email-modal" id="emailmodalbody">
                            <h5 class="modal-title">Login By Username or Email</h5>
                            <form class="login-form" id="loginform" action="<?=W_FUNCTIONS?>auth.php" method="post">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text login-fields">
                                                <label for="username" class="sr-only">Username/Email</label>
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="login_username" class="form-control login-fields" name="username"
                                            placeholder="Username/Email" required minlength="5" maxLength="20" oninvalid="this.setCustomValidity('This does not seem to be a valid username, sorry')"
                                            maxlength="20" pattern='^[a-zA-Z0-9#_-]{8,20}|(?i)admin(?-i)' />
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text login-fields">
                                                <i class="fa fa-key"></i>
                                                <label class="sr-only">Password</label>
                                            </span>
                                        </div>
                                        <input type="password" id="login_password" name="password" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])([^\s]).{8,20}$|admin"
                                            maxlength="20" oninvalid="this.setCustomValidity('Password must contain: \n1 Capital, 1 Lowercase\n1 Number, and be 8-20 characters long.')" placeholder="Password" class="form-control login-fields"
                                            required />
                                    </div>
                                </div>
                                <input type="hidden" name="formtype" value="login">
                                <?=$csrftoken?>
                                <button class="btn btn-primary btn-block btn-sm" id="loginsubmit" type="submit">Login</button>
                            </form>
                            <div id="login-error"></div>

                            <div class="row">
                                <div class="col">
                                    <p>
                                        <strong class="login-fields" style="  font-size:14px; margin-top:7px;">Not a
                                            Member
                                            Yet??
                                        </strong>
                                    </p>
                                </div>
                            </div>
                            <div class="row form-switch">
                                <div class="col p-0" style="  font-size:14px;">
                                    <a href="#" id="registerswitch">Register Here</a>
                                    <strong>/</strong>
                                    <a href="#" class="close-modal" data-dismiss="modal">Go Home</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="register-row row login-modal">
                    <div class="col-md-5 justify-content-center discord-modal">
                        <a href="<?=W_FUNCTIONS?>auth.php?formtype=discordregister" class="d-flex justify-content-center">
                            <img src="<?=W_ASSETS?>img/discord_logo.png" alt="Discord" id="discord-logo-register" class="d-flex" />
                        </a>
                        <h5>or Register
                            <br>with Discord!</h5>
                    </div>
                    <div class="col-md-7 email-modal">
                        <h5 class="modal-title">Register By Email</h5>
                        <form class="register-form" method="post" action="<?=W_FUNCTIONS?>auth.php">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text login-fields">
                                            <label for="email" class="sr-only">Email</label>
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                    </div>
                                    <input type="email" class="form-control login-fields" name="email" placeholder="Email"
                                        required />
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text login-fields">
                                            <label for="username" class="sr-only">Username</label>
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control login-fields" name="username" placeholder="Username"
                                        minlength="8" max-length="20" pattern='^[a-zA-Z0-9#]([._](?![._])|[a-zA-Z0-9#]){8,20}[a-zA-Z0-9]$'
                                        required />
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text login-fields">
                                            <i class="fa fa-key"></i>
                                            <label for="password" class="sr-only">Password</label>
                                        </span>
                                    </div>
                                    <input type="password" name="password" placeholder="Password" class="form-control login-fields"
                                        pattern="^\S*(?=\S{8,})(?=\S*[a-z])(?=[\S\W]*)(?=\S*[A-Z])(?=\S*[\d])\S*$"
                                        oninvalid="this.setCustomValidity('Password must contain: \n1 Capital, 1 Lowercase\n1 Number, and be 8-20 characters long.')"
                                        maxlength="18" id="regpass" minlength=required />
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text login-fields">
                                            <i class="fa fa-key"></i>
                                            <label for="confirmpassword" class="sr-only">Confirm Password</label>
                                        </span>
                                    </div>
                                    <input type="password" name="confirmpassword" placeholder="Confirm Password" class="form-control login-fields"
                                        pattern='^\S*(?=\S{8,})(?=\S*[a-z])(?=[\S\W]*)(?=\S*[A-Z])(?=\S*[\d])\S*$'
                                        oninvalid="this.setCustomValidity('Password must contain: \n1 Capital, 1 Lowercase\n1 Number, and be 8-20 characters long.')"
                                        id="regconfirmpass" maxlength="18" minlength="8" required />
                                </div>
                            </div>
                            <input type="hidden" name="formtype" value="register">
                            <?=$csrftoken?>
                            <button class="btn btn-primary btn-block btn-sm" id="registersubmit" type="submit">Register</button>
                        </form>
                        <div id="register-error"></div>
                        <div class="row">
                            <div class="col p-0">
                                <strong class="login-fields" style="font-size:14px; margin-top:7px;">Already a
                                    Member???</strong>
                            </div>
                        </div>
                        <div class="row form-switch">
                            <div class="col p-0" style="  font-size:14px;">
                                <a href="#" id="loginswitch">Login Here</a>
                                <strong>/</strong>
                                <a href="#" data-dismiss="modal" class="close-modal">Go Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end login/Register modal -->
