<?php
require_once 'initiate.php';



if (isset($_SESSION['Spotamon']['uname'])) {
    $sql = "SELECT * FROM messages WHERE to_user = '" . $_SESSION['Spotamon']['uname'] . "' AND del_in='0'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));?>
    <center>
        <div id="pm">
            <h3>
                <?php
$error = '';
    if (isset($_POST["markreadall"])) {
        $countunread = "SELECT * FROM messages WHERE unread='1' AND to_user='" . $sess->get('uname') . "'";
        $countresult = mysqli_query($conn, $countunread) or die(mysqli_error($conn));
        $unreadcount = mysqli_num_rows($countresult);
        if ($unreadcount !== 0) {
            $clear = "UPDATE messages SET unread=0 WHERE unread=1 AND to_user='" . $sess->get('uname') . "'";
            if (!mysqli_query($conn, $clear)) {
                $error .= '<p><label class="text-danger">SQL ERROR</label></p>';
            } else {
                $error .= '<p><label class="text-success">All messages marked as "read"</label></p>';
                echo "<meta http-equiv=\"refresh\" content=\"1;url='./inbox.php'\"/>";
            }
        } else { $error .= '<p><label class="text-danger">No new messages to mark</label></p>';}
    }

    if (isset($_POST["deleteall"])) {
        $delcountquery = "SELECT * FROM messages WHERE to_user='" . $sess->get('uname') . "'";
        $delcountresult = mysqli_query($conn, $delcountquery) or die(mysqli_error($conn));
        $delcount = mysqli_num_rows($delcountresult);
        if ($delcount !== 0) {
            $clear = "UPDATE messages SET del_in='1' WHERE to_user='" . $sess->get('uname') . "' AND del_in='0'";
            if (!mysqli_query($conn, $clear)) {
                $error .= '<p><label class="text-danger">SQL ERROR</label></p>';
            } else {
                $error .= '<p><label class="text-success">All messages deleted</label></p>';
                echo "<meta http-equiv=\"refresh\" content=\"1;url='./inbox.php'\"/>";
            }
        } else { $error .= '<p><label class="text-danger">No messages to delete</label></p>';}
    }

    echo $_SESSION['Spotamon']['uname'] . '\'s inbox';
    ?>
            </h3>
            <script>
                $(document).ready(function() {
					$('#inbox').DataTable({
						"order": [
							[2, "desc"]
						],
						"language": {
							"emptyTable": "No messages in inbox",
							"lengthMenu": "Show _MENU_ messages",
							"info": "Showing _START_ to _END_ of _TOTAL_ messages",
							"zeroRecords": "No messages found",
							"infoEmpty": "Showing 0 to 0 of 0 messages"
						}
					});
				});

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
                    <?php while ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $subject = $row['subject'];
        $to = $row['to_user'];
        $from = $row['from_user'];
        $unread = $row['unread'];
        $message = $row['message'];
        $date = $row['date'];

        if ($unread == 1) {
            echo "
            <tr>
                <td><b><a href=\"read.php?id=$id\">" . $from . "</a></b></td>
                <td><b><a href=\"read.php?id=$id\">" . $subject . "</a></b></td>
                <td><b><a href=\"read.php?id=$id\">" . $date . "</a></b></td>
            </tr>
	";
        } else {

            echo "
            <tr>
                <td><a href=\"read.php?id=$id\">" . $from . "</a></td>
                <td><a href=\"read.php?id=$id\">" . $subject . "</a></td>
                <td><a href=\"read.php?id=$id\">" . $date . "</a></td>
            </tr>
	";}

    }?>
                </tbody>
            </table>

            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <input type="submit" name="markreadall" value="Mark all as read"> /
                <input type="submit" name="deleteall" value="Delete all">
                <p>
                    <?php echo $error; ?>
                </p>
            </form>

        </div>

    </center>
    <?php } else {
    echo "<div style='margin-top:10px;'>";
    echo "Login to read your messages";
    ?>
    <br />
    <br />
    <a href="#" id="login-link" data-toggle="modal" data-target="#auth-modal">
        Login Here</a>
    <?php
echo "</div>";}
