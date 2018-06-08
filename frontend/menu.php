<?php function menu(){ 
include("login/auth.php");
require 'config/config.php';
?>

	<title>Spotamon</title>
	<link rel="icon" href="https://raw.githubusercontent.com/darkelement1987/spotamon/main/static/img/favicon.ico" type="image/ico" sizes="16x16">

<style>
body {
	margin:0;
	font-family:Arial;
	height:100%;

	}

.topnav {
  overflow: hidden;
  background-color: #333;
  margin-left: 100px;
}

.topnav a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.active {
  background-color: inherit;
  color: white;
}

.topnav .icon {
  display: none;
}

.dropdown {
    float: left;
    overflow: hidden;
}

.dropdown .dropbtn {
    font-size: 17px;    
    border: none;
    outline: none;
    color: white;
    padding: 14px 16px;
    background-color: inherit;
    font-family: inherit;
    margin: 0;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #e0e0e0;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    float: none;
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    text-align: left;
}

.topnav a:hover, .dropdown:hover .dropbtn {
  background-color: inherit;
  color: white;
}

.dropdown-content a:hover {
    background-color: #959595;
    color: black;
}

.dropdown:hover .dropdown-content {
    display: block;
}

@media screen and (max-width: 480px) {
  .topnav a:not(:first-child), .dropdown .dropbtn {
    display: none;
  }
  .topnav a.icon {
    float: left;
    display: block;
  }
}

@media screen and (max-width: 480px) {
  .topnav.responsive {position: relative; padding-top:3em;}
  .topnav.responsive .icon {
    position: absolute;
    left: 0;
    top: 0;
  }
  .topnav.responsive a {
    float: none;
    display: block;
    text-align: left;
  }
  .topnav.responsive .dropdown {float: none;}
  .topnav.responsive .dropdown-content {position: relative;}
  .topnav.responsive .dropdown .dropbtn {
    display: block;
    width: 100%;
    text-align: left;
  }
}
</style>
<script src="./static/scripts/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="./static/scripts/sweetalert2.min.css">
</head>
<body>

<?php if ($disablemotd == true) {} elseif ($motdalways == true) {?>

<script>swal({
  title: '<?php echo $motdtitle;?>',
  text: '<?php echo $motdtext;?>',
  imageUrl: '<?php echo $motdimage;?>'
})</script><?php } elseif ($motdalways == false){?>

<?php if(!isset($_SESSION["uname"])){?>
<script>swal({
  title: '<?php echo $motdtitle;?>',
  text: '<?php echo $motdtext;?>',
  imageUrl: '<?php echo $motdimage;?>'
})</script><?php }?><?php }?>

<a href="./"><img src="header.png" alt="logo" style="padding-top:7px; float:left; background-color:#333; position:absolute; z-index:100;"></a>
<div class="topnav" id="myTopnav">

	<div class="dropdown">
    <button class="dropbtn"><a href="./" style="padding:0px !important;">Home</a></button>
	</div>

   <div class="dropdown">
    <button class="dropbtn">Add spot <i class="fa fa-caret-down"></i></button>
    <div class="dropdown-content">
      <a href="./submit-pokemon.php" class="active">Pokémon</a>
      <a href="./submit-raid.php">Raid</a>
      <a href="./submit-team.php">Team</a>
	  <a href="./submit-egg.php">Egg</a>
	  <a href="./submit-quest.php">Quest</a>
    </div>
  </div>
  <div class="dropdown">
    <button class="dropbtn">View spots <i class="fa fa-caret-down"></i></button>
    <div class="dropdown-content">
      <a href="./pokemon.php">Pokémon</a>
      <a href="./raids.php">Raids</a>
	  <a href="./eggs.php">Eggs</a>
	  <a href="./quests.php">Quests</a>
    </div>
  </div>
  
	<?php
	if(isset($_SESSION["uname"])){
	?>
	<div class="dropdown">
    <button class="dropbtn">Welcome <?php echo $_SESSION['uname']; ?> <i class="fa fa-caret-down"></i></button>
	 <div class="dropdown-content">
	 <a href="profile.php">Profile</a>
	 <a href="login/logout.php">Logout</a>
	</div>
	</div>
	<?php } 
	else{?>
	<div class="dropdown">
    <button class="dropbtn">Registration<i class="fa fa-caret-down"></i></button>
	 <div class="dropdown-content">
	 <a href="login/login.php">Login</a>
	 <a href="login/registration.php">Register</a>
	</div>
	</div>
	<?php }?>
  
  
  <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
</div>

<script>
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script></body>
<?php } ?>
