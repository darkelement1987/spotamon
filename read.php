<?php
include 'frontend/menu.php';
include_once 'config/config.php';
menu();
$error='';
$back = $_SERVER['HTTP_REFERER'];

	if(isset($_POST["markread"]))
{
			$clear = "UPDATE messages SET unread=0 WHERE unread=1 AND to_user='".$_SESSION['uname']."'";
			if(!mysqli_query($conn,$clear))
			{
				$error .= '<p><label class="text-danger">SQL ERROR</label></p>';
				} else {
					$error .= '<p><label class="text-success">All messages marked as "read"</label></p>';
				}
}

else if(isset($_SESSION["uname"], $_GET['del'], $_SERVER['HTTP_REFERER'])){
	
	    $delquery = "DELETE FROM messages WHERE id='".$_GET['del']."' AND to_user='".$_SESSION["uname"]."'";

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
	$from = $row[3];
	$message = $row[7];
	$date = $row[8];
	
if ($row){
	?>
<center>
<h3>Message from "<?php echo $from;?>"</h3>
<div style="margin:10px;padding:10px;">
<table class="table-bordered" style="background-color: rgba(255, 255, 255, 0.4);">
<tbody>
<tr>
<td>Subject</td>
<td><?php echo $subject;?></td>
</tr>
<tr>
<td>Date</td>
<td><?php echo $date;?></td>
</tr>
<tr>
<td>From</td>
<td><?php echo $from;?></td>
</tr>
<tr>
<td>Message</td>
<td><?php echo $message;?></td>
</tr>
<tr>
<td></td>
<td><a href="read.php?del=<?php echo $_GET['id'];?>">Delete message</a></td>
</tr>
</tbody>
</table>
	<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
<input type="submit" name="markread" value="Mark as read">
<p><?php echo $error;?></p>
</form>
</div>
<?php if(isset($error)){ echo $error;}?>
</center>
</body>
<?php // else for 'if row'
 } else { echo "<center><p><label class=\"text-danger\">Invalid ID or not allowed to view</label></p></center>";}?>
 
<?php // else for 'if(isset($_SESSION["uname"], $_GET['id'])){'
} else  { echo "<center><p><label class=\"text-danger\">You are not allowed to view this page</label></p></center>";} ?>