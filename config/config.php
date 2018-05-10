<?php

// Connect to Database
$servername = "localhost";
$username = "username";
$password = "password";
$database = "database";

//24HR-Clock (default = false = 12HR) 
$clock = "false";



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
monid INT(3) NOT NULL,
cp INT(6) NOT NULL,
hour INT(2) NOT NULL,
min INT(2) NOT NULL,
ampm VARCHAR(5) NOT NULL,
latitude DECIMAL(10,6) NOT NULL,
longitude DECIMAL(10,6) NOT NULL,
address VARCHAR(60) NOT NULL,
date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

// sql to create the pokedex table
$dex = "CREATE TABLE IF NOT EXISTS `pokedex` (
dexentry INT(4) NOT NULL,
monster VARCHAR(25) NOT NULL
)";

$tables = [$spot, $dex];

foreach($tables as $k => $sql){
    $query = @$conn->query($sql);
}

?>
