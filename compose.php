<?php
include 'frontend/menu.php';
include_once 'config/config.php';
?>

<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>


<?php
menu();

$error='';
$usrerror='';
$msgerror='';
$unread='';
$suberror='';

if(isset($_POST["submit"]))
{
    $checkquery = "SELECT * from users WHERE uname='".$_POST["to_user"]."'";
    if(!mysqli_query($conn,$checkquery))
    	{
    		$error .= '<p><label class="text-danger">SQL ERROR</label></p>';
    	}
    		else
    		{}
    
    $checkuser = $conn->query($checkquery);
    
    $row = $checkuser->fetch_array(MYSQLI_NUM);
    $touser = $row[0];

if (!$touser) {
	$error .= '<p><label class="text-danger">User does not exist</label></p>';
}

	  $to_user = $_POST['to_user'];
  $from_user = $_POST['from_user'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];
  $unread = $unread + 1;
//Check empty fields
	if ($_POST['to_user'] == '') {$usrerror = "<label class=\"text-danger\">\"To user\" cannot be empty</label>";$error = "<label class=\"text-danger\">An error occured, please check input.</label>";}
	if ($_POST['message'] == '') {$msgerror = "<label class=\"text-danger\">\"Message\" cannot be empty</label>";$error = "<label class=\"text-danger\">An error occured, please check input.</label>";}
	if ($_POST['subject'] == '') {$suberror = "<label class=\"text-danger\">\"Subject\" cannot be empty</label>";$error = "<label class=\"text-danger\">An error occured, please check input.</label>";}
	if (!$error)
	{
			$error .= '<p><label class="text-success">Message sent</label></p>';
			$query = "INSERT INTO messages (subject, to_user, message, from_user, unread) VALUES ('$subject', '$to_user', '$message', '$from_user', '$unread')";
			if(!mysqli_query($conn,$query))
			{
				$error .= '<p><label class="text-danger">SQL ERROR</label></p>';
				}
				}
				}
?>
<center>
<?php if(isset($_SESSION["uname"])){?><h3>Messages:</h3><form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" style="width:30%;">
<table class="table table-bordered" style="background-color: rgba(255, 255, 255, 0.4);">

<tr><td colspan=2><h3>Send PM:</h3></td></tr>
<input type="hidden" name="from_user" maxlength="32" value = <?php echo $_SESSION['uname']; ?>>
</td></tr>

<tr><td>To User: </td><td>
<input type="text" name="to_user" maxlength="32" value="" style="width:100%;">
<p><?php if (isset($usrerror)){echo $usrerror;}?></p>
</td></tr>

<tr><td>Subject: </td><td>
<input type="text" name="subject" maxlength="255" value="" style="width:100%;">
<p><?php if (isset($suberror)){echo $suberror;}?></p>
</td></tr>

<tr><td>Message: </td><td>
<TEXTAREA NAME="message" COLS=50 ROWS=10 WRAP=SOFT style="width: 100%;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;resize: none;"></TEXTAREA>
<p><?php if (isset($msgerror)){echo $msgerror;}?></p>
</td></tr>

<tr><td colspan="2" align="right">
<center><input type="submit" name="submit" value="Send Message"><p><?php echo $error;?></center></p>
</td></tr>
</table>
</form>

<?php } else {
	echo "<div style='margin-top:10px;'>";
	echo "Login to read your messages";
		?><br /><br /><a href="./login/login.php">Login Here</a><?php
echo "</div>";}?>

</center>
