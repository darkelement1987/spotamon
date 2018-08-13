<?php
require_once 'initiate.php';
include S_FUNCTIONS . 'menu.php';
?>

<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<?php
include_once S_FUNCTIONS . 'menu.php';;
$error='';

	if(isset($_POST["markread"],$_GET['id']))
{
			$clear = "UPDATE messages SET unread=0 WHERE unread=1 AND to_user='".$_SESSION['uname']."' AND id='".$_GET['id']."'";
			if(!mysqli_query($conn,$clear))
			{
				$error .= '<p><label class="text-danger">SQL ERROR</label></p>';
				} else {
					echo '<center><p><label class="text-success">Message marked as "read"</label></p></center>';
					echo "<meta http-equiv=\"refresh\" content=\"1;url='./inbox.php'\"/>";
				}
}

else if(isset($_SESSION["uname"], $_GET['del'], $_SERVER['HTTP_REFERER'])){
	
	    $delquery = "UPDATE messages SET del_in='1' WHERE id='".$_GET['del']."' AND to_user='".$_SESSION["uname"]."' AND del_in='0'";

    if(!mysqli_query($conn,$delquery))
    	{
    		echo '<center><p><label class="text-danger">MESSAGE NOT AVAILABLE</label></p></center>';
    	} 
		else 
		{ 
	echo '<center><p><label class="text-success">MESSAGE DELETED</label></p></center>';
	echo "<meta http-equiv=\"refresh\" content=\"1;url='./inbox.php'\"/>";
	}
}

else if(isset($_SESSION["uname"], $_GET['id'])){

	

	
	    $query = "SELECT * from messages WHERE to_user='".$_SESSION["uname"]."' AND id='".$_GET['id']."'";
    if(!mysqli_query($conn,$query))
    	{
    		$error .= '<p><label class="text-danger">SQL ERROR</label></p>';
    	}
    		else
    		{}
    
    $result = $conn->query($query);
    
    $row = $result->fetch_array(MYSQLI_NUM);
    $msgid = $row[0];
	$subject = $row[1];
	$to = $row[2];
	$from = $row[3];
	$unread = $row[4];
	$message = $row[5];
	$date = $row[6];
	
		// Begin of reply
	if (isset($_POST['submit'])){ 
	
	
	
	$reply = "INSERT INTO messages (subject, to_user, message, from_user, unread) VALUES ('RE: ".$subject."', '".$from."', '".$_POST['message']."', '".$_SESSION["uname"]."', '1')";
			
			if ($_POST['message'] == ''){ $error .=  'Message cannot be empty';}
			
			else if(mysqli_query($conn,$reply))
			{
				$error .= '<p><label class="text-succes">Reply sent to '.$from.'</label></p>';
				}
}
// End of reply
	
if ($row){
	?>
	<center>
		<div id="pm">
			<h3>Message from "
				<?php echo $from;?>"</h3>
			<table id="readpm" class="table-bordered" style="background-color: rgba(255, 255, 255, 0.4);">
				<tbody>
					<tr>
						<td>Subject</td>
						<td>
							<?php echo $subject;?>
						</td>
					</tr>
					<tr>
						<td>Date</td>
						<td>
							<?php echo $date;?>
						</td>
					</tr>
					<tr>
						<td>From</td>
						<td>
							<?php echo $from;?>
						</td>
					</tr>
					<tr>
						<td>Message</td>
						<td style="word-break:break-word;">
							<?php echo $message;?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<a href="read.php?del=<?php echo $_GET['id'];?>">Delete message</a>
						</td>
					</tr>
				</tbody>
			</table>
			<form action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $_GET['id'];?>"
			 method="post">
				<?php if($unread=='1'){ ?>
				<input type="submit" name="markread" value="Mark as read">
				<?php }?>
			</form>

			<h3>Reply:</h3>
			<form action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $msgid;?>"
			 method="post" style="width:100%;">
				<table id="readpm" class="table-bordered" style="background-color: rgba(255, 255, 255, 0.4);">

					<tr>
						<td colspan=2>
							<h3>Send PM:</h3>
						</td>
					</tr>
					<input type="hidden" name="from_user" maxlength="32" value=< !--%fhip-comment-start#<?php echo $_SESSION[ 'uname']; ?>>
					</td>
					</tr>

					<tr>
						<td>To User: </td>
						<td>
							<input type="text" name="to_user" maxlength="32" value="<?php echo $from;?>"
							 style="width:100%;" disabled>
							<p>
								<?php if (isset($usrerror)){echo $usrerror;}?>
							</p>
						</td>
					</tr>

					<tr>
						<td>Subject: </td>
						<td>
							<input type="text" name="subject" maxlength="255" value="RE: <?php echo $subject;?>"
							 style="width:100%;" disabled>
							<p>
								<?php if (isset($suberror)){echo $suberror;}?>
							</p>
						</td>
					</tr>

					<tr>
						<td>Message: </td>
						<td>
							<TEXTAREA NAME="message" COLS=50 ROWS=10 WRAP=SOFT style="width: 100%;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;resize: none;"></TEXTAREA>
							<p>
								<?php if (isset($msgerror)){echo $msgerror;}?>
							</p>
						</td>
					</tr>

					<tr>
						<td colspan="2" align="right">
							<center>
								<input type="submit" name="submit" value="Send Message">
								<?php if(isset($error)){ echo '<p><label class="text-danger">'.$error.'</label></p>';}?>
							</center>
						</td>
					</tr>
				</table>
			</form>

		</div>
	</center>
	</body>
	<?php // else for 'if row'
 } else { echo "<center><p><label class=\"text-danger\">Invalid ID or not allowed to view</label></p></center>";}?>

	<?php // else for 'if(isset($_SESSION["uname"], $_GET['id'])){'
} else  { echo "<center><p><label class=\"text-danger\">You are not allowed to view this page</label></p></center>";} 
