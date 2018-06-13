<?php
include './frontend/functions.php';
include './frontend/menu.php';
include './config/dbbuilding.php';
require './config/config.php';
?>

<head onload="rndqu(n)">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.klokantech.com/maptilerlayer/v1/index.js"></script>
    <meta property="og:title" content="<?php
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

    echo $titles[array_rand($titles)]; // Pick a random item from the array and output it
    ?> ">
    <meta property="og:description" content="spot a pokemon, raid, pokestop quest or more all in your local area! Dont forget to give us feedback to improve your experience and please let us know if you encounter any bugs. Welcome to spotamon.">
    <meta property="og:image" content="https://www.rocketmapdrenthe.nl/spotamon/static/img/ultra-ball.png">

    <script>

        function submitInstinct(){
            document.postInstinct.submit();
        }
        function submitValor(){
            document.postValor.submit();
        }
        function submitMystic(){
            document.postMystic.submit();
        }
    </script>

    <?php
    menu();

    maps();

    ?>

    <?php
    // Update map if needed
    require './config/version.php';
    $versionquery = "SELECT version FROM version";
    $versionresult = $conn->query($versionquery);
    $rowversion = $versionresult->fetch_array(MYSQLI_NUM);
    $version = $rowversion[0];

    if ($version =='') {
        $conn->query("INSERT IGNORE INTO `version` (`version`) VALUES ('1')");
    } else if ($version < $lastversion) {
        $conn->query("UPDATE version SET version='".$lastversion."'");
        echo "<meta http-equiv='refresh' content='1;url=update.php'>";
    }
    ?>

    <footer></footer>

