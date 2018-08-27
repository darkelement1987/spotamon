<?php
require_once 'initiate.php';
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, shrink-to-fit=no">



    <?php if ($analytics !== '') {?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?=$analytics?>">


    </script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '<?=$analytics?>');

    </script>
    <?php }?>
    <title>
        <?php
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
srand((float) microtime() * 10000000); // Seed the random number generator

// Pick a random item from the array and output it
echo $titles[array_rand($titles)];?>
    </title>
    <meta property="og:title" content="<?php
echo $titles[array_rand($titles)]; // Pick a random item from the array and output it                   ?> ">
    <meta property="og:description" content="spot a pokemon, raid, pokestop quest or more all in your local area! Dont forget to give us feedback to improve your experience and please let us know if you encounter any bugs. Welcome to spotamon.">
    <meta property="og:image" content="<?=W_ASSETS?>img/ultra-ball.png">

    <link rel="icon" href="<?=W_ASSETS?>img/favicon.ico" type="image/ico" sizes="16x16">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js">


    </script>

    <!-- Notification and alert library -->
    <script type="text/javascript" language="javascript" src="<?=W_JS?>sweetalert2.min.js">


    </script>
    <link rel="stylesheet" href="<?=W_CSS?>sweetalert2.min.css">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
        crossorigin="anonymous">

    <!-- Jquery Plugin for Table data -->
    <script type="text/javascript" language="javascript" src="<?=W_JS?>dataTables.min.js">


    </script>
    <link rel="stylesheet" type="text/css" href="<?=W_CSS?>dataTables.css">


    <!-- Interactive Map Library -->
    <link rel="stylesheet" href="<?=W_CSS?>leaflet.css">
    <script src="<?=W_JS?>leaflet.js">


    </script>
    <!-- Fadedown animations for menus -->
    <link rel="stylesheet" type="text/css" media="all" href="<?=W_CSS?>fade-down.css" />

    <!-- Theming and JS for menu -->
    <link rel="stylesheet" type="text/css" media="all" href="<?=W_CSS?>menu.css" />
    <link rel="stylesheet" type="text/css" media="all" href="<?=W_CSS?>black-gry.css" />
    <script type="text/javascript" language="javascript" src="<?=W_JS?>menu.js">


    </script>
    <!-- JQuery and bootstrap.js -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">


    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em"
        crossorigin="anonymous"></script>

    <!-- Custom Style Sheet Last -->
    <link rel="stylesheet" type="text/css" href="<?=W_CSS?>style.css">
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">

</head>

<body>
    <!-- Container for authmodal -->
    <?php
if ($Validate->getSession('uname') === null) {
    include S_PAGES . 'parts/authmodal.php';
}?>
    <!--end auth container -->
    <!-- Mobile Header -->
    <div class="wsmobileheader clearfix">
        <a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>
        <span class="smllogo"><img src="<?=W_ASSETS?>img/spotamon.png" width="98" alt="" /></span>
    </div>
    <!-- Mobile Header -->

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.6-rc.1/dist/js/select2.min.js" integrity="sha256-190Fv8aJAduyyIOnvWVpjCmzkX1h8OEtGWbcoU1QVsA="
        crossorigin="anonymous">
    </script>


    </script>
    <?php if ($disablemotd != true) {
    if ($motdalways == true) {?>
    <script>
        swal({
            title: '<?=$motdtitle?>',
            html: '<?=$motdtext?>',
            imageUrl: '<?=$motdimage?>',
            imageHeight: '<?=$imageheight?>',
            imageWidth: '<?=$imagewidth?>',
            width: '<?=$motdwidth?>'
        })

    </script>
    <?php } else if ($motdalways == false) {?>

    <?php if (!isset($_SESSION["uname"])) {?>
    <script>
        swal({
            title: '<?=$motdtitle?>',
            html: '<?=$motdtext?>',
            imageUrl: '<?=$motdimage?>',
            imageHeight: '<?=$imageheight?>',
            imageWidth: '<?=$imagewidth?>',
            width: '<?=$motdwidth?>'
        })

    </script>
    <?php }
    }
}?>


    <!--conatiner start -->
    <div class="container-fluid p-0" id="menu-container">
        <?php
include S_PAGES . 'parts/nav.php';
?>
    </div>
    <!--container end -->
    <script>
        var w_root = '<?=W_ROOT?>';

    </script>
    <script src="<?=W_JS?>spotamon.js">
    </script>


    </script>
    <p class="bodyspacer"></p>
