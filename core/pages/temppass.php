<?php
require_once 'initiate.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $upass = $Validate->getPost('password', 'password');
    if ($upass === false) {
        echo $Validate->data;
    } else {
        $upass = password_hash($upass, PASSWORD_DEFAULT);
// attempt insert query execution
        if (!empty($upass)) {
            $sql = "UPDATE users SET upass='" . $upass . "' WHERE uname='" . $_SESSION['uname'] . "'";
            if (mysqli_query($conn, $sql)) {
            } else {
                echo "ERROR: Was not able to execute $sql. " . mysqli_error($conn);
            }

// close connection
            mysqli_close($conn);
            echo "<br /><center>Thank you, your password was successfully updated</center>";
            echo "<meta http-equiv=\"refresh\" content=\"3;URL=" . W_ROOT . "index.php\">";
        }
    }} else {
    ?>
<h3 style="text-align:center;">
    <strong>Set Your Password</strong>
</h3>

<center>
    <table id=\ "spotted\" class=\ "table table-bordered\">
        <tr>
            <form action="#" method="post">
                <th style='background-color:#fff;color:#000;'>
                    <center>Password: </center>
                    <br>
                    <br>
                </th>
                <td>
                    <center>
                        <span id='temppass-error' style='font-size:10px;float:left;'></span>
                        <br>
                        <input type="text" pattern="/^\S*(?=\S{8,})(?=\S*[a-z])(?=[\S\W]*)(?=\S*[A-Z])(?=\S*[\d])\S*$/"
                            maxlength="18" minlength="8" required name="password" class="form-control" placeholder="Password" />
                        <br>
                        <br>
                        <input type='submit' name='submit' value='Submit' id='submit_pass' style='float:left;'>
                </td>
            </form>
        </tr>

    </table>

    <?php }?>
