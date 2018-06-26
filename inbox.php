<?php
include 'frontend/menu.php';
include_once 'config/config.php';?>

<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<?php
menu();
	
	if(isset($_POST["deleteall"]))
{
			$delcountquery = "SELECT * FROM messages WHERE from_user='".$_SESSION['uname']."'";
			$delcountresult = mysqli_query($conn,$delcountquery)or die(mysqli_error($conn));
			$delcount=mysqli_num_rows($delcountresult);
			if ($delcount !==0){
			$clear = "DELETE FROM messages WHERE from_user='".$_SESSION['uname']."'";
			if(!mysqli_query($conn,$clear))
			{
				$error .= '<p><label class="text-danger">SQL ERROR</label></p>';
				} else {
					$error .= '<p><label class="text-success">All messages deleted</label></p>';
					echo "<meta http-equiv=\"refresh\" content=\"1;url='./inbox.php'\"/>";
				}
			} else { $error .= '<p><label class="text-danger">No messages to delete</label></p>'; }
}
	
if(isset($_SESSION["uname"])){
	
$setgroup = "SELECT groupname FROM users,usergroup WHERE uname='".$_SESSION['uname']."' AND users.usergroup = usergroup.id LIMIT 1";
	if(!mysqli_query($conn,$setgroup))
		{
			echo 'Not Inserted';
		}

$groupresult = $conn->query($setgroup);

$grouprow = $groupresult->fetch_array(MYSQLI_NUM);
$usergroup = $grouprow[0];
$reportlink='';

if(isset($_GET['viewreports'])){
	
if ($usergroup=='admin'){ // makes reports show
$sql = "SELECT * FROM messages WHERE report='1'";} else {
$sql = "SELECT * FROM messages WHERE to_user = '".$_SESSION["uname"]."' AND report=0";
$reportlink='';
}

} else if($usergroup=='admin'){ $reportlink='<p><a href=\'inbox.php?viewreports\'>View reports</a></p>'; }

if(!isset($_GET['viewreports'])){
$sql = "SELECT * FROM messages WHERE to_user = '".$_SESSION["uname"]."' AND report=0";	
}

$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));?>
<center>
<div id="pm">
<h3>
<?php
$error='';
	if(isset($_POST["markreadall"]))
{
			$clear = "UPDATE messages SET unread=0 WHERE unread=1 AND to_user='".$_SESSION['uname']."'";
			if(!mysqli_query($conn,$clear))
			{
				$error .= '<p><label class="text-danger">SQL ERROR</label></p>';
				} else {
					$error .= '<p><label class="text-success">All messages marked as "read"</label></p>';
					echo "<meta http-equiv=\"refresh\" content=\"1;url='./inbox.php'\"/>";
				}
}
echo $_SESSION["uname"].'\'s inbox';
?></h3>
<?php echo $reportlink;?>
<?php if(isset($_GET['viewreports'])){?><h4>Reports <small>(<a href='./inbox.php'>Back</a>)</small></h4><?php }?>
<script>
$(document).ready(function() {
    $('#inbox').DataTable({
        "order": [[ 2, "desc" ]],
		"language": {
    "emptyTable": "No messages in inbox",
	"lengthMenu":     "Show _MENU_ messages",
	"info":           "Showing _START_ to _END_ of _TOTAL_ messages",
	"zeroRecords":    "No messages found",
	"infoEmpty":      "Showing 0 to 0 of 0 messages"
  }
    });
} );
</script>
<table id="inbox" class="table table-bordered" style="background-color: rgba(255, 255, 255, 0.4);">
        <thead>
            <tr>
                <th>From</th>
                <th>Subject</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
		<?php while($row = mysqli_fetch_array($result)) {
	$id = $row['id'];
	$subject = $row['subject'];
	$to = $row['to_user'];
	$from = $row['from_user'];
	$unread = $row['unread'];
	$message = $row['message'];
	$date = $row['date'];

	if ($unread==1){
	echo "
            <tr>
                <td><b><a href=\"read.php?id=$id\">".$from."</a></b></td>
                <td><b><a href=\"read.php?id=$id\">".$subject."</a></b></td>
                <td><b><a href=\"read.php?id=$id\">".$date."</a></b></td>
            </tr>
	";
	} else {
		
	echo "
            <tr>
                <td><a href=\"read.php?id=$id\">".$from."</a></td>
                <td><a href=\"read.php?id=$id\">".$subject."</a></td>
                <td><a href=\"read.php?id=$id\">".$date."</a></td>
            </tr>
	";}	
		
		

}?>
        </tbody>
    </table>
	
	<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
<input type="submit" name="markreadall" value="Mark all as read"> / <input type="submit" name="deleteall" value="Delete all">
<p><?php echo $error;?></p>
</form>
	
	</div>

</center>
<?php } else {
	echo "<div style='margin-top:10px;'>";
	echo "Login to read your messages";
		?><br /><br /><a href="./login/login.php">Login Here</a><?php
echo "</div>";}?>
