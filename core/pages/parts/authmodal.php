<?php
$csrftoken = csrf();
?>
<!-- Login/register Modal Start -->
<div class="container-fluid" id="auth-modal-container">
    <div class="modal" id="auth-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0" id="auth-modal-content">
                <div class="modal-body p-0">
                    <div class="lloader"></div>
                    <div class="login-row row login-modal">
                        <div class="col-md-5 d-none d-md-flex flex-column discord-modal" id="discordloginbody">
                            <a href="<?=W_FUNCTIONS?>auth.php?formtype=discordlogin" class="d-none d-md-flex align-content-center discord-link">
                                <img src="<?=W_ASSETS?>img/discord_logo.png" width="150px" alt="Discord" id="discord-logo-login"
                                    class="d-flex mx-auto" />
                            </a>
                            <h5 class="modal-title">or login with Discord!</h5>
                        </div>
                        <div class="col-12 col-md-7 d-flex flex-column align-content-around email-modal" id="emailmodalbody">
                            <h5 class="d-none d-md-inline-block modal-title">Login By Username or Email</h5>
                            <h5 class="d-inline-block d-md-none">Login to Spotamon</h5>
                            <form class="login-form my-4" id="loginform" action="<?=W_FUNCTIONS?>auth.php" method="post">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text login-fields">
                                                <label for="username" class="sr-only">Username/Email</label>
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="login_username" class="form-control login-fields" name="username"
                                            placeholder="Username/Email" required minlength="5" maxLength="20"
                                            oninvalid="this.setCustomValidity('This does not seem to be a valid username, sorry')"
                                            pattern='^[a-zA-Z0-9#]([._](?![._])|[a-zA-Z0-9#]){6,18}[a-zA-Z0-9]$|^[a-zA-Z0-9.!#$%&
                                            *+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$|^admin$' />
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text login-fields">
                                                <i class="fa fa-key"></i>
                                                <label class="sr-only">Password</label>
                                            </span>
                                        </div>
                                        <input type="password" id="login_password" name="password" pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{8,20}$|^admin$'
                                            maxlength="20" minlength="5" oninvalid="this.setCustomValidity('Password must contain: \n1 Capital, 1 Lowercase\n1 Number, and be 8-20 characters long.')"
                                            placeholder="Password" class="form-control login-fields" required />
                                    </div>
                                </div>
                                <input type="hidden" name="formtype" value="login">
                                <?=$csrftoken?>
                                <div class="form-row d-flex justify-content-around">
                                    <button class="btn btn-primary d-inline-block d-md-block  btn-sm" id="loginsubmit"
                                        type="submit">Login by Account</button>
                                    <a href="<?=W_FUNCTIONS?>auth.php?formtype=discordlogin" class="btn discord-link btn-discord btn-sm d-inline-block d-md-none"><span
                                            class="fab fa-discord"></span>
                                        Login by Discord</a>
                                </div>
                            </form>
                            <div id="login-error"></div>

                            <div class="row register-switch mt-auto">
                                <div class="col">
                                    <p class="small m-0 text-muted">Not a Member Yet??</p>
                                </div>
                            </div>
                            <div class="row form-switch register-switch">
                                <div class="col small">
                                    <a href=" #" id="registerswitch">Register
                                        Here</a>
                                    <strong>/</strong>
                                    <a href="#" class="close-modal" data-dismiss="modal">Go Home</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="register-row row login-modal">
                    <div class="col-md-5 d-none d-md-flex flex-column justify-content-center discord-modal" id="discordregisterbody">
                        <a href="<?=W_FUNCTIONS?>auth.php?formtype=discordregister" class="d-none d-md-flex justify-content-center discord-link">
                            <img src="<?=W_ASSETS?>img/discord_logo.png" alt="Discord" id="discord-logo-register" class="d-flex" />
                        </a>
                        <h5 class="modal-title">or Register
                            <br>with Discord!</h5>
                    </div>
                    <div class="col-md-7 order-1 order-md-12 email-modal">
                        <h5 class="modal-title">Register By Email</h5>
                        <div class="rloader"></div>
                        <form class="register-form" id="registerform" method="post" action="<?=W_FUNCTIONS?>auth.php">
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
                                        minlength="8" max-length="20" pattern='^[a-zA-Z0-9#]([._](?![._])|[a-zA-Z0-9#]){6,18}[a-zA-Z0-9]$'
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
                                        pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{8,20}$' oninvalid="this.setCustomValidity('Password must contain: \n1 Capital, 1 Lowercase\n1 Number, and be 8-20 characters long.')"
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
                                        pattern='^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{8,20}$' oninvalid="this.setCustomValidity('Password must contain: \n1 Capital, 1 Lowercase\n1 Number, and be 8-20 characters long.')"
                                        id="regconfirmpass" maxlength="18" minlength="8" required />
                                </div>
                            </div>
                            <input type="hidden" name="formtype" value="register">
                            <?=$csrftoken?>
                            <div class="form-row d-flex no-wrap justify-content-around">
                                <button class="btn btn-primary btn-md-block d-inline-block d-md-block btn-sm" id="registersubmit"
                                    type="submit">Register by Account</button>
                                <a href="<?=W_FUNCTIONS?>auth.php?formtype=discordregister" class="btn discord-link btn-discord btn-sm d-inline-block d-md-none"><span
                                        class="fab fa-discord"></span>
                                    Register by Discord</a>
                            </div>
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
                                <a href="#" id="login-switch">Login Here</a>
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
