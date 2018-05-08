<?php
$servername = "localhost";
$username = "username";
$password = "password";
$database = "databasename";


// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
?>
