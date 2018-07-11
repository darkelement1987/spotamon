<?php
include './frontend/functions.php';
include './frontend/menu.php';
include './config/dbbuilding.php';
require './config/config.php';
?>

<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <script>
    $( document ).ready(function() {
        initMap()
    })
    </script>
    </body>
	<footer></footer>
