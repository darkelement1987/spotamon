

<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <style>
    #login-modal-content {
        background-color: rgba(35, 39, 32, 0.00);
        
    }
.modal-title {
    margin-top:15px;
    margin-bottom:5px;
    color:white;
}
    .login-row {
        margin: 0px;
        padding: 0px;
        font-family: average Sans, sans-serif; 
        }

         #discord-modal{ 
             padding:0px; 
             text-align :center; 
             background-color:rgba(249, 242, 242, 0.63); 
             } 
             #discord-logo-login
            {
            margin:0px;
            max-height: 150px;
            margin-top: 23px;
            max-width: 150px;
        }
        #discord-logo-register {
            margin: 0px;
            max-height: 150px;
            margin-top: 55px;
            max-width: 150px;
        }
        #email-modal {
            background-color: rgba(35, 39, 32, 0.63);
            text-align: center;
        }

        #login-form * {
            color: white;
        }
        .login-fields {
            background-color: rgba(233, 236, 239, 0);
            color: rgb(255, 255, 255);
            width: 40px;
        }
        .login-button {
            margin-bottom: 14px;
            margin-right: 0px;
        }
        #form-switch {
            margin-right: -15px;
            margin-bottom: 9px;
        }
        .modals {
            top: 50%;
            transform: translateY(-50%);
        }
        
        </style>
  </head>
  <body>
      
   

<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content" id="login-modal-content">
        <div class="modal-body p-0">
            <div class="login-row row login-modal">
                <div class="col-md-5 justify-content-center" id="discord-modal">
                    <a href="#" class="d-flex justify-content-center">
                        <img src="Untitled.png" width="150px" alt="Discord" id="discord-logo-login" class="d-flex" />
                    </a>
                    <h5>or login with Discord!</h5>
                </div>
                <div class="col-md-7" id="email-modal">
                    <h5 class="modal-title">Login to Spotamon</h4>
                        <form class="login-form">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text login-fields">
                                            <label for="username" class="sr-only">Username</label>
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control login-fields" name="username" placeholder="Username" required />
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text login-fields">
                                            <i class="fa fa-key"></i>
                                            <label class="sr-only">Password</label>
                                        </span>
                                    </div>
                                    <input type="password" name="password" placeholder="Password" class="form-control login-fields" required/>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-block btn-sm login=button" type="button">Login</button>
                        </form>
                        <div class="row">
                            <div class="col p-0">
                                <strong class="login-fields" style="  font-size:14px; margin-top:7px;">Not a Member Yet??</strong>
                            </div>
                        </div>
                        <div class="row" id="form-switch">
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
                <div class="col-md-5 justify-content-center" id="discord-modal">            
                    <a href="#" class="d-flex justify-content-center">
                        <img src="Untitled.png" alt="Discord" id="discord-logo-register" class="d-flex" />
                    </a>
                    <h5>or Register<br>with Discord!</h5>
                </div>
                <div class="col-md-7" id="email-modal">
                    <h5 class="modal-title">Register for Spotamon</h4>
                        <form class="register-form">
                            <div class="form-group">
                            <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text login-fields">
                                            <label for="email" class="sr-only">Email</label>
                                            <i class="fas fa-envelope"></i>            
                                        </span>
                                    </div>
                                    <input type="email" class="form-control login-fields" name="email" placeholder="Email" required />
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text login-fields">
                                            <label for="username" class="sr-only">Username</label>
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control login-fields" name="username" placeholder="Username" required />
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text login-fields">
                                            <i class="fa fa-key"></i>
                                            <label for="password" class="sr-only">Password</label>
                                        </span>
                                    </div>
                                    <input type="password" name="password" placeholder="Password" class="form-control login-fields" required/>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text login-fields">
                                            <i class="fa fa-key"></i>
                                            <label for="confirmpassword" class="sr-only">Confirm Password</label>
                                        </span>
                                    </div>
                                    <input type="password" name="confirmpassword" placeholder="Confirm Password" class="form-control login-fields" required/>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-block btn-sm login=button" type="button">Register</button>
                        </form>
                        <div class="row">
                            <div class="col p-0">
                                <strong class="login-fields" style="  font-size:14px; margin-top:7px;">Already a Member??</strong>
                            </div>
                        </div>
                        <div class="row" id="form-switch">
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

<div class="container-fluid" style="background-image: url(https://initiate.alphacoders.com/images/717/cropped-1366-768-717083.jpg?7698); height:1000px;">
<button type="button" class="btn btn-alert" data-toggle="modal" data-target="#login-modal">click</button>
<img>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script
			  src="https://code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em"
    crossorigin="anonymous"></script>
    <script>
    $(document).ready(function() {
        $('.register-row').hide();
    });
        $('#registerswitch').on('click', function() {
            $('.login-row').fadeOut("fast", function(){
                $('.register-row').fadeIn("fast");
                return false;   
            });    
            });
            

            $('#loginswitch').on('click', function() {
            $('.register-row').fadeOut("fast", function() {
                $('.login-row').fadeIn("fast");
                return false;
            });
            });
        </script>
</body>

</html>