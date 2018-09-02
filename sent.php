<?php
require_once 'initiate.php';

include_once S_FUNCTIONS . 'menu.php';;
$error='';

	if(isset($_POST["markread"]))
{
			$clear = "UPDATE messages SET unread=0 WHERE unread=1 AND to_user='".$_SESSION['uname']."'";
			if(!mysqli_query($conn,$clear))
			{
				$error .= '<p><label class="text-danger">SQL ERROR</label></p>';
				} else {
					echo '<center><p><label class="text-success">Message marked as "read"</label></p></center>';
					echo "<meta http-equiv=\"refresh\" content=\"1;url='./inbox.php'\"/>";
				}
}

else if(isset($_SESSION["uname"], $_GET['del'], $_SERVER['HTTP_REFERER'])){
	
	    $delquery = "UPDATE messages SET del_out='1' WHERE id='".$_GET['del']."' AND to_user='".$_SESSION["uname"]."'";

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

	

	
	    $query = "SELECT * from messages WHERE from_user='".$_SESSION["uname"]."' AND id='".$_GET['id']."'";
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
					<td>To</td>
					<td>
						<?php echo $to;?>
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
							<a href="sent.php?del=<?php echo $_GET['id'];?>">Delete message</a>
						</td>
					</tr>
				</tbody>
			</table>
			<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
				<?php if($unread=='1'){ ?>
				<input type="submit" name="markread" value="Mark as read">
				<?php }?>
			</form>

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
