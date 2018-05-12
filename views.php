<?php
include 'frontend/functions.php';
include 'config/dbbuilding.php';
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    }
</style>
</head>
<body>
<div class="topnav" id="myTopnav">
  <a href="index.php">Add Spot</a>
  <a href="views.php">View Spots</a>
  <a href="map.php">Map</a>
</div>
<div>
<?php 
spottedpokemon();
?>
</div>
</body>
<footer></footer>
</html>