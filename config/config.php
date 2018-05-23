<?php

// Connect to Database
$servername = "servername";
$username = "username";
$password = "password";
$database = "database";

// Set maps default location example: 
// Example:
// $mapcenter = "51.9720526, 6.7202572";

$mapcenter = "";

//24HR-Clock (default = false = 12HR) 
$clock = "";

//Google Maps key
$gmaps= "";

//Webhook Setup
$webhook_url = ""; // <-- Webhook URL of your Discord channel
$viewurl = ""; // <-- Enter your FULL Spotamon url here including http:// or https:// example: https://www.mysite.com < NO BACKSLASH AT THE END!
$viewtitle = ""; // <-- Text showing for the MAP-link @ Discord

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
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
fulladdress VARCHAR(128) NOT NULL
)";

// sql to create the pokedex table
$dex = "CREATE TABLE IF NOT EXISTS `pokedex` (
id INT(6) PRIMARY KEY NOT NULL,
monster VARCHAR(25) NOT NULL)";

$spotraid = "CREATE TABLE IF NOT EXISTS `spotraid` (
rid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
rboss VARCHAR(30) NOT NULL,
rhour INT(2) NOT NULL,
rmin INT(2) NOT NULL,
rampm VARCHAR(5) NOT NULL,
rdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$raidbosses = "CREATE TABLE IF NOT EXISTS `raidbosses` (
rid INT(6) PRIMARY KEY NOT NULL,
rcp INT(6) NOT NULL,
rlvl INT(1) NOT NULL,
rboss VARCHAR(25) NOT NULL)";

$gyms = "CREATE TABLE IF NOT EXISTS `gyms` (
gid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
gname VARCHAR(255) NOT NULL,
glatitude DECIMAL(10,6) NOT NULL,
glongitude DECIMAL(10,6) NOT NULL,
gteam INT(2) NOT NULL,
actraid VARCHAR(255) NOT NULL,
actboss VARCHAR(25) NULL,
hour INT(2) NOT NULL,
min INT(2) NOT NULL,
ampm VARCHAR(5) NOT NULL,
egg INT(1) NOT NULL,
type VARCHAR(25) NOT NULL,
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";

$teams = "CREATE TABLE IF NOT EXISTS `teams` (
tid INT(6) PRIMARY KEY NOT NULL,
tname VARCHAR(15) NOT NULL)";

$stops = "CREATE TABLE IF NOT EXISTS `stops` (
sid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
slatitude DECIMAL(10,6) NOT NULL,
slongitude DECIMAL(10,6) NOT NULL,
quested INT(1) NULL,
quest VARCHAR(255) NULL,
reward VARCHAR(255) NULL,
lured INT(1) NULL,
type VARCHAR(25) NULL,
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";

$quests = "CREATE TABLE IF NOT EXISTS `quests` (
qid INT(6) PRIMARY KEY NOT NULL,
quest VARCHAR(255) NOT NULL,
type VARCHAR(5) NULL)";

$rewards = "CREATE TABLE IF NOT EXISTS `rewards` (
reid INT(4) PRIMARY KEY NOT NULL,
reward VARCHAR(255) NOT NULL,
type VARCHAR(6) NULL)";

$users = "CREATE TABLE IF NOT EXISTS `users` (
id INT(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
email VARCHAR(100) NOT NULL,
uname VARCHAR(100) NOT NULL,
upass VARCHAR(100) NOT NULL,
usergroup VARCHAR(1) NOT NULL,
trn_date datetime NOT NULL)";

$usergroup = "CREATE TABLE IF NOT EXISTS `usergroup` (
id INT(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
groupname VARCHAR(20) NOT NULL)";

$tables = [$spot, $dex, $spotraid, $raidbosses, $gyms, $teams, $stops, $quests, $rewards, $users, $usergroup];

foreach($tables as $k => $sql){
    $query = @$conn->query($sql);
}

?>
