<?php function menu(){
    include("login/auth.php");
    require 'config/config.php';
    ?>
    <title><?php
        $titles = array();
        $titles[] = "Spotamon";
        $titles[] = "Spotamon, spotting made easy";
        $titles[] = "Spotamon, insert cheesy slogan";
        $titles[] = "Spotamon, we want YOU to spot";
        $titles[] = "Spotamon, easy spotting for YOU";
        $titles[] = "Spotamon, spotting is for all of us";
        $titles[] = "Spotamon, sometimes all we need is pokemon go";
        $titles[] = "Spotamon, will not require a soul";
        $titles[] = "Spotamon, help us become even better!";
        $titles[] = "Spotamon, with love and care";
        $titles[] = "Spotamon, sometimes we just need a life too";
        $titles[] = "Spotamon, for all your pokemon pleasure";
        $titles[] = "Spotamon, will need to think about this one";
        $titles[] = "Spotamon, thank you for visiting us";
        $titles[] = "Spotamon, now for your local community!";
        $titles[] = "Spotamon, does not cost money!";
        $titles[] = "Spotamon, feedback is always appreciated";
        $titles[] = "Spotamon, DarkElement1987 approved";
        $titles[] = "Spotamon, <3";
        $titles[] = "Spotamon, PokemonGO enthausiast";
        $titles[] = "Spotamon, ask for the admin!";
        $titles[] = "Spotamon, we love you too <3";
        srand ((float) microtime() * 10000000); // Seed the random number generator
    
// Pick a random item from the array and output it
        echo $titles[array_rand($titles)];?>
        </title>

        <meta property="og:title" content="<?php
            echo $titles[array_rand($titles)]; // Pick a random item from the array and output it
    ?> ">
        <meta property="og:description" content="spot a pokemon, raid, pokestop quest or more all in your local area! Dont forget to give us feedback to improve your experience and please let us know if you encounter any bugs. Welcome to spotamon.">
        <meta property="og:image" content="<?php echo $viewurl;?>/static/img/ultra-ball.png">

    <link rel="icon" href="static/img/favicon.ico" type="image/ico" sizes="16x16">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script src="./static/scripts/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="./static/scripts/sweetalert2.min.css">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css"><link rel="stylesheet" type="text/css" href="style.css">

    </head>
    <body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <?php if ($disablemotd == true) {} elseif ($motdalways == true) {?>
        <script>swal({
                title: '<?php echo $motdtitle;?>',
                text: '<?php echo $motdtext;?>',
                imageUrl: '<?php echo $motdimage;?>'
            })</script><?php } elseif ($motdalways == false){?>

    <?php if(!isset($_SESSION["uname"])){?>
        <script>swal({
                title: '<?php echo $motdtitle;?>',
                text: '<?php echo $motdtext;?>',
                imageUrl: '<?php echo $motdimage;?>'
            })</script><?php }?><?php }?>
    <nav class="navbar navbar-inverse" style="margin-bottom:0px;">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" style="float:left; margin-left:10px;"class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="spotlogo"><a href="./"><img src="header.png" alt="logo"></a></div>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="./">Home</a></li>
                    <li class="divider"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-plus"></span> Add spot
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="./submit-pokemon.php">Pokémon</a></li>
                            <li><a href="./submit-raid.php">Raid</a></li>
                            <li><a href="./submit-ex-raid.php">EX Raid</a></li>
                            <li><a href="./submit-team.php">Team</a></li>
                            <li><a href="./submit-egg.php">Egg</a></li>
                            <li><a href="./submit-quest.php">Quest</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-search"></span> View spots
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="./pokemon.php">Pokémon</a></li>
                            <li><a href="./raids.php">Raid</a></li>
                            <li><a href="./exraids.php">EX Raids</a></li>
                            <li><a href="./eggs.php">Egg</a></li>
                            <li><a href="./quests.php">Quest</a></li>
                        </ul>
                    </li>
					<?php if($showformlink==true){echo "<li><a href=\"./feedback.php\"><span class=\"glyphicon glyphicon-envelope\"></span> Feedback</a></li>";} else {}?>
                    <li class="divider"></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if(isset($_SESSION["uname"])){

                        // Lookup usrpic filename for user
                        $urlquery = "SELECT url FROM users WHERE uname = '".$_SESSION['uname']."'";
                        $resulturl = $conn->query($urlquery);
                        $rowurl = $resulturl->fetch_array(MYSQLI_NUM);
                        $url = $rowurl[0];

                        ?>
                        <li><a href="profile.php"><?php if ($url !=='') {?><img src="./userpics/<?php echo $url; ?>" height="25px" width="25px" alt="logo"  style="border:1px solid black"><?php } else {?><img src="./userpics/nopic.png" height="25px" width="25px" alt="logo"  style="border:1px solid black"><?php }?> Welcome <?php echo $_SESSION['uname']; ?></a></li>
                        <li><a href="login/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                    <?php } else {?>
                        <li><a href="login/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                        <li><a href="login/registration.php"><span class="glyphicon glyphicon-user"></span> Register</a></li>
                    <?php }?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    </body>
<?php } ?>
