<?php
include("auth.php"); //include auth.php file on all secure pages ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Welcome Home</title>
<meta content="True" name="HandheldFriendly">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="./css/style.css" />
</head>
<body>
<center><div class="form">
<p>Welcome <?php echo $_SESSION['uname']; ?>!</p>
<p>Secure Login.</p>
<meta http-equiv="refresh" content="1; url=../">
</div></center>
</body>
</html>
