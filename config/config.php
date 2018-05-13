<?php

// Connect to Database
$servername = "localhost";
$username = "username";
$password = "password";
$database = "database";

// Set maps default location example: 
// Example:
// $mapcenter = "51.9720526, 6.7202572";

$mapcenter = "43.894386, -78.863105";

//24HR-Clock (default = false = 12HR) 
$clock = "false";

//Google Maps key
$gmaps= "key goes here";

///////////////////// DO NOT TOUCH \\\\\\\\\\\\\\\\\\\\\
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql to create spoting table
$spot = "CREATE TABLE IF NOT EXISTS `spots` (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
pokemon VARCHAR(30) NOT NULL,
cp INT(6) NOT NULL,
hour INT(2) NOT NULL,
min INT(2) NOT NULL,
ampm VARCHAR(5) NOT NULL,
latitude DECIMAL(10,6) NOT NULL,
longitude DECIMAL(10,6) NOT NULL,
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

// sql to create the pokedex table
$dex = "CREATE TABLE IF NOT EXISTS `pokedex` (
id INT(6) PRIMARY KEY NOT NULL,
monster VARCHAR(25) NOT NULL)";

$spotraid = "CREATE TABLE IF NOT EXISTS `spotraid` (
rid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
rboss VARCHAR(30) NOT NULL,
rlvl INT(6) NOT NULL,
rhour INT(2) NOT NULL,
rmin INT(2) NOT NULL,
rampm VARCHAR(5) NOT NULL,
rlatitude DECIMAL(10,6) NOT NULL,
rlongitude DECIMAL(10,6) NOT NULL,
rdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$raidbosses = "CREATE TABLE IF NOT EXISTS `raidbosses` (
rid INT(6) PRIMARY KEY NOT NULL,
rcp INT(6) NOT NULL,
rboss VARCHAR(25) NOT NULL)";


$tables = [$spot, $dex, $spotraid, $raidbosses];

foreach($tables as $k => $sql){
    $query = @$conn->query($sql);
}

?>