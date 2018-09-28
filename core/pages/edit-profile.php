<?php
if (isset($_SESSION["uname"])) {
    require 'config/config.php';
    $result = $conn->query("SELECT * FROM users,usergroup WHERE uname='" . $_SESSION['uname'] . "' AND users.usergroup = usergroup.id LIMIT 1  ");
    $id = $usergroup = "";?>
            <h3 style="text-align:center;"><strong>Edit Your Profile:</strong></h3>
            <center>
                <table id="spotted" class="table table-bordered">
                    <?php
while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $uname = $row['uname'];
        $email = $row['email'];
        $usergroup = $row['groupname'];
        $url = $row['url'];?>
                    <tr>
                        <form action="editusername.php" method="post">
                            <th style='background-color:#fff;color:#000;width:10%;'>
                                <center>Username: </center>
                            </th>
                            <td>
                                <center>
                                    <input type='text' name='uname' id='uname' style='float:left;'>
                                    <br>
                                    <br>
                                    <input type='submit' value='Submit' id='submit_name' style='float:left;'>
                                </center>
                            </td>
                        </form>
                    </tr>
                    <tr>
                        <form action="editemail.php" method="post">
                            <th style='background-color:#f9f9f9;color:#000;'>
                                <center>Email: </center>
                            </th>
                            <td>
                                <center>
                                    <input type='email' name='email' id='email' style='float:left;'>
                                    <br>
                                    <br>
                                    <input type='submit' value='Submit' id='submit_email' style='float:left;'>
                                </center>
                            </td>
                        </form>
                    </tr>
                    <tr>
                        <form action="editpassword.php" method="post">
                            <th style='background-color:#fff;color:#000;'>
                                <center>Password: </center>
                                <br>
                                <br>
                            </th>
                            <td>
                                <center>
                                    <span id='error-nwl' style='font-size:10px;float:left;'></span>
                                    <br>
                                    <input type='password' minlength='6' name='password' id='password' placeholder='password'
                                        onkeyup='checkPass(); return false;' style='float:left;'>
                                    <br>
                                    <br>
                                    <input type='password' minlength='6' name='confirm_password' id='confirm_password'
                                        placeholder='confirm password' onkeyup='checkPass(); return false;' style='float:left;'>
                                    <br>
                                    <br>
                                    <input type='submit' name='submit' value='Submit' id='submit_pass' style='float:left;'>
                            </td>
                        </form>
                    </tr>
                    <script>
                        $('input[id="submit_pass"]').attr('disabled','disabled');
        function checkPass()
        {
            var pass1 = document.getElementById('password');
            var pass2 = document.getElementById('confirm_password');
            var message = document.getElementById('error-nwl');
            var goodColor = "#66cc66";
            var badColor = "#ff6666";
            if(pass1.value.length > 5)
            {
                pass1.style.backgroundColor = goodColor;
                message.style.color = goodColor;
                $('input[type="submit"]').attr('disabled','disabled');
                message.innerHTML = "Character number ok!<br>"
            }
            else
            {
                pass1.style.backgroundColor = badColor;
                message.style.color = badColor;
                $('input[type="submit"]').attr('disabled','disabled');
                message.innerHTML = "You have to enter at least 6 digit!<br>"
                return;
            }
            if(pass1.value == pass2.value)
            {
                pass2.style.backgroundColor = goodColor;
                message.style.color = goodColor;
                $('input[type="submit"]').removeAttr('disabled');
                message.innerHTML = "Ready to go!<br>"
            }
            else
            {
                pass2.style.backgroundColor = badColor;
                message.style.color = badColor;
                $('input[type="submit"]').attr('disabled','disabled');
                message.innerHTML = "These passwords don't match!<br>"
            }
        }
    </script>
                </table>
                <h3 style="text-align:center;"><strong>Upload profile picture:</strong></h3>
                <?php }
    if ($conn->connect_errno) {
        $conn->connect_error;
    }
    $pull = "select * from users where uname='" . $_SESSION['uname'] . "'";
    // Lookup id for user
    $urlquery = "SELECT id FROM users WHERE uname = '" . $_SESSION['uname'] . "'";
    $resulturl = $conn->query($urlquery);
    $rowurl = $resulturl->fetch_array(MYSQLI_NUM);
    $userid = $rowurl[0];
    $allowedExts = array(
        "jpg",
        "jpeg",
        "gif",
        "png",
        "JPG",
    );
    $extension = @end(explode(".", $_FILES["file"]["name"]));
    if (isset($_POST['pupload'])) {
        if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/JPG") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 800000) && in_array($extension, $allowedExts)) {
            if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
            } else {?>
                <div class="plus">
                    Uploaded Successully
                </div>
                <br /><b><u>Image Details</u></b><br />
                Name:
                <?=$_FILES["file"]["name"]?><br />
                Type:
                <?=$_FILES["file"]["type"]?><br />
                Size:
                <?=ceil(($_FILES["file"]["size"] / 1024))?> KB <br>
                <?php
if (file_exists("./core/assets/userpics/" . $_FILES["file"]["name"])) {
                unlink("./core/assets/userpics/" . $_FILES["file"]["name"]);
            } else {
                $pic = $_FILES["file"]["name"];
                $conv = explode(".", $pic);
                $ext = $conv['1'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./core/assets/userpics/" . $userid . "." . $ext);
                echo "Stored in as: " . "./core/assets/userpics/" . $userid . "." . $ext;
                $urlpic = $userid . "." . $ext;
                $query = "update users set url='$urlpic', lastUpload=now() where uname='" . $_SESSION['uname'] . "'";
                if ($upl = $conn->query($query)) {?>
                <br />Saved to Database successfully
                <meta http-equiv='refresh' content='3;url=profile.php'>
                <?php }
            }
            }
        } else {?>
                <p>File Size exceeded 800kb limit or wrong extension, please upload .png/gif/jpg</p>
                <?php }
    }
    ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <?php
$res = $conn->query($pull);
    $pics = $res->fetch_assoc();
    ?>
                    <div class="imgLow">
                        <?php if ($url !== '') {?>
                        <img src="./core/assets/userpics/<?=$url?>" height="50px" width="50px" alt="logo" style="border:1px solid black">
                        <?php } else {?>
                        <img src="./core/assets/userpics/nopic.png" height="50px" width="50px" alt="logo" style="border:1px solid black">
                        <?php }?>
                    </div><br>
                    <input type="file" name="file" />
                    <input type="submit" name="pupload" class="button" value="Upload" />
                </form>
            </center>
            <?php } else {?>
            <center>
                <div style='margin-top:10px;'>
                    Login to view your profile
                    <br />
                    <br />
                    <a href="#" id="login-link" data-toggle="modal" data-target="#auth-modal">
                        <i class="fas fa-sign-in-alt"></i> Login or Register Here</a>
                </div>
            </center>
            </table>
            </center>
            <?php }
?>

