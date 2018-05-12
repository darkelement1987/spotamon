<?php
include 'frontend/functions.php';
include 'config/dbbuilding.php';
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
</head>
<body>
<div class="topnav" id="myTopnav">
  <div class="dropdown">
    <button class="dropbtn">Add spots 
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
    <a href="index.php">Pokemon</a>
    <a href="#">Raid</a>
    <a href="#">Gym</a>
    <a href="#">Stop</a>
    <a href="#">Raid</a>
    </div>
  </div> 
  <div class="dropdown">
    <button class="dropbtn">Spots 
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
    <a href="pokemon.php">Pokemon</a>
    <a href="#">Raid</a>
    <a href="#">Gym</a>
    <a href="#">Stop</a>
    <a href="#">Raid</a>
    </div>
  </div> 
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
