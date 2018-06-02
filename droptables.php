<?php
ob_start();
require './config/config.php';
include 'frontend/functions.php';
include("login/auth.php");

if (isset($_SESSION["uname"])) {
    
    $result = $conn->query("SELECT * FROM users,usergroup WHERE uname='" . $_SESSION['uname'] . "' AND users.usergroup = usergroup.id LIMIT 1  ");
    $id     = $usergroup = "";
    while ($row = $result->fetch_assoc()) {
        $id        = $row['id'];
        $uname     = $row['uname'];
        $email     = $row['email'];
        $usergroup = $row['groupname'];
        if ("$usergroup" == 'admin') {
            $sql = "DROP TABLE `gyms`, `pokedex`, `quests`, `raidbosses`, `rewards`, `spotraid`, `spots`, `stops`, `teams`, `usergroup`, `users`";
            mysqli_query($conn, $sql);
            
?>
<html>
<head>
<meta charset="utf-8">
<title>Database Cleared</title>
<meta content="True" name="HandheldFriendly">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="viewport" content="width=device-width">
<meta http-equiv="refresh" content="1; url=./index.php">
<link rel="stylesheet" href="./login/css/style.css" />
</head>
<body>
<center>
<div class='form'>
<h1>Succes</h1>
<h3>Database is cleared</h3>
</div>
</center>
</body>
</html>
<?php
        } else {
?>
<html>
<head>
<meta charset="utf-8">
<title>Database Clear Failed</title>
<meta content="True" name="HandheldFriendly">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="./login/css/style.css" />
</head>
<body>
<center>
<div class='form'>
<h1>Error</h1>
<h3>Must be admin to perform database clear</h3><br>
Click here to <a href='./login/login.php'>Login</a>
</div>
</center>
</body>
</html>
<?php
        }
    }
} else {
?>
<html>
<head>
<meta charset="utf-8">
<title>Not logged in</title>
<meta content="True" name="HandheldFriendly">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="./login/css/style.css" />
</head>
<body>
<center>
<div class='form'>
<h1>Error</h1>
<h3>Please login to perform this action</h3><br>
Click here to <a href='./login/login.php'>Login</a>
</div>
</center>
</body>
</html>
<?php
}
?>
