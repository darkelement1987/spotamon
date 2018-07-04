<?php
include 'frontend/menu.php';
include_once 'config/config.php';?>

<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<?php
menu();
if(isset($_SESSION["uname"])){
$sql = "SELECT * FROM messages WHERE from_user = '".$_SESSION["uname"]."' and del_out='0'";
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));?>
<center>
<div id="pm">
<h3>
<?php
$error='';
echo $_SESSION["uname"].'\'s outbox';
?></h3>
<script>
$(document).ready(function() {
    $('#inbox').DataTable({
        "order": [[ 3, "desc" ]],
		"language": {
    "emptyTable": "No messages in outbox",
	"lengthMenu":     "Show _MENU_ messages",
	"info":           "Showing _START_ to _END_ of _TOTAL_ messages",
	"zeroRecords":    "No messages sent",
	"infoEmpty":      "Showing 0 to 0 of 0 messages"
  }
    });
} );
</script>
<table id="inbox" class="table table-bordered" style="background-color: rgba(255, 255, 255, 0.4);">
        <thead>
            <tr>
                <th>To</th>				
                <th>Subject</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
		<?php while($row = mysqli_fetch_array($result)) {
	$id = $row['id'];
	$subject = $row['subject'];
	$to = $row['to_user'];
	$unread = $row['unread'];
	$message = $row['message'];
	$date = $row['date'];
		
	echo "
            <tr>
                <td><a href=\"sent.php?id=$id\">".$to."</a></td>				
                <td><a href=\"sent.php?id=$id\">".$subject."</a></td>
                <td><a href=\"sent.php?id=$id\">".$date."</a></td>
            </tr>
	";	
		
		

}?>
        </tbody>
    </table>
	
	</div>

</center>
<?php } else {
	echo "<div style='margin-top:10px;'>";
	echo "Login to read your messages";
		?><br /><br /><a href="./login/login.php">Login Here</a><?php
echo "</div>";}?>
