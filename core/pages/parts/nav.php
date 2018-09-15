<?php
require_once 'initiate.php';
$gcountquery = $conn->query("SELECT * FROM `gyms`");
$gcountresult = mysqli_num_rows($gcountquery);

$scountquery = $conn->query("SELECT * FROM `stops`");
$scountresult = mysqli_num_rows($scountquery);

?>

<nav class="wsmenu clearfix">
    <ul class="wsmenu-list">
        <li><a href="<?=W_ROOT?>index.php" class="active"><img id="mainlogo" src="<?=W_ASSETS?>img/spotamon.png" height="50px"></img><span
                    class="hometext"></span></a></li>
        <!--Dropdown for SPOTS-TAB starts -->

        <li><a href="#"><i class="fa fa-plus"></i>Add Spot<span class="wsarrow"></span></a>
            <ul class="sub-menu">
                <li>
                    <a href="./submit-pokemon.php">Pokémon</a>
                </li>
                <?php if (empty($gcountresult)) {} else {?>
                <li>
                    <a href="./submit-raid.php">Raid</a>
                </li>
                <li>
                    <a href="./submit-ex-raid.php">EX Raid</a>
                </li>
                <li>
                    <a href="./submit-team.php">Team</a>
                </li>
                <li>
                    <a href="./submit-egg.php">Egg</a>
                </li>
                <?php }?>
                <?php if (empty($scountresult)) {} else {?>
                <li>
                    <a href="./submit-quest.php">Quest</a>
                </li>
                <?php }?>
            </ul>
        </li>
        <!--Dropdown for SPOTS-TAB ends -->
        <!--Dropdown for VIEW-TAB starts -->
        <li><a href="#"><i class="fa fa-search"></i>View Spots<span class="wsarrow"></span></a>

            <ul class="sub-menu">
                <li>
                    <a href="./pokemon.php">Pokémon</a>
                </li>
                <?php if (empty($gcountresult)) {} else {?>
                <li>
                    <a href="./raids.php">Raid</a>
                </li>
                <li>
                    <a href="./exraids.php">EX Raids</a>
                </li>
                <li>
                    <a href="./eggs.php">Egg</a>
                </li>
                <?php }?>
                <?php if (empty($scountresult)) {} else {?>
                <li>
                    <a href="./quests.php">Quest</a>
                </li>
                <?php }?>
            </ul>
        </li>
        <!--Dropdown for VIEW-TAB ends -->
        <!--Dropdown for TRADE-TAB starts -->
        <li><a href="#"><i class="far fa-handshake"></i>Trades<span class="wsarrow"></span></a>

            <ul class="sub-menu">
                <li>
                    <a href="./offer-trade.php">Create Trade</a>
                </li>
                <li>
                    <a href="./active-trades.php">Open Trades</a>
                </li>
            </ul>
        </li>

        <!--Dropdown for TRADES-TAB ends -->

        <!--Dropdown for FEEDBACK-TAB starts -->
        <?php if ($showformlink === true) {?>
        <li><a href="#"><i class="far fa-comment"></i>Feedback<span class="wsarrow"></span></a>
            <?php $variables = $name = $email = $feedbacksubject = $message = $error = ' ';
    ?>
            <ul class="sub-menu">
                <div class="row m-0 py-3 contact-tab">
                    <div class="col-md-4 img-fluid align-self-center py-3">
                        <img src="<?=W_ASSETS?>img/feedback.png" class="img-fluid" />
                    </div>
                    <div class=" col-md-8 align-self-center p-0 ">
                        <?php if (isset($_SESSION['uname'])) {?>
                        <h3 class="text-center ">Feedback</h3>
                        <br />
                        <form method="post" class="p-2 mx-auto w-100 border-0 " id="feedback ">
                            <div class="form-group ">
                                <label>Name</label>
                                <input type="text" name="name" placeholder="Enter Name " class="form-control " value="<?=$name?>" />
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Enter Email" value="<?=$email?>" />
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="subject" class="form-control" placeholder="Enter Subject"
                                    value="<?=$feedbacksubject?>" />
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea name="message" class="form-control" placeholder="Enter Message" rows="5">
                                        <?=$message?>
                                    </textarea>
                            </div>
                            <div class="form-group" align="center">
                                <input type="submit" name="submit" value="Submit" class="btn btn-info" />
                            </div>
                            <?=$error?>
                        </form>
                        <?php } else {?>
                        <div>
                            Login to use the feedback form
                            <br />
                            <br />
                            <a href="#" data-toggle="modal" data-target="#auth-modal">Login Here</a>
                        </div>6
                        <?php }?>
                    </div>
                </div>
            </ul>
        </li>
        <!--Dropdown for FEEDBACK-TAB ends -->
        <?php }?>

        <!--Dropdown for MAIL-TAB starts -->
        <?php
if (isset($_SESSION["uname"])) {

    // Lookup usrpic filename for user
    $urlquery = "SELECT url FROM users WHERE uname = '" . $_SESSION['uname'] . "'";
    $resulturl = $conn->query($urlquery);
    $rowurl = $resulturl->fetch_array(MYSQLI_NUM);
    $url = $rowurl[0];
    $countquery = $conn->query("SELECT * FROM `messages` WHERE unread=1 AND to_user = '" . $_SESSION["uname"] . "' AND del_in='0'");
    $msgcount = $countquery->num_rows;
    ?>
        <li class="ml-md-auto"><a href="#"><i class="far fa-envelope-open"></i>Mail
                <span class="badge my-auto">
                    <?=$msgcount?>
                </span>
                <span class="wsarrow"></span></a>
            <ul class="sub-menu">
                <li>
                    <a href="./inbox.php">Inbox</a>
                </li>
                <li>
                    <a href="./outbox.php">Outbox</a>
                </li>
                <li>
                    <a href="./compose.php">Send message</a>
                </li>
            </ul>
        </li>
        <!--Dropdown for MAIL-TAB ends -->
        <!-- USERPROFILE begins -->
        <li>
            <a href="profile.php">
                <?php if (!empty($url)) {
        if (substr($url, 0, 5) == 'https') {?>
                <img class="pic my-auto" src="<?=$url?>" height="19px" width="19px" alt="logo" style="border:1px solid black">
                <?php } else {?>
                <img class="pic my-auto" src="<?=W_ASSETS?>userpics/<?=$url?>" height="20px" width="20px" alt="logo"
                    style="border:1px solid black">
                <?php }} else {?>
                <img class="pic my-auto" src="<?=W_ASSETS?>userpics/nopic.png" height="20px" width="20px" alt="logo"
                    style="border:1px solid black">
                <?php }?>
                &nbsp
                <?=$_SESSION['uname']?>
            </a>
        </li>
        <!-- USERPROFILE ends -->
        <li>
            <a href="#" target="<?=W_ROOT?>core/functions/auth.php?formtype=logout" class="nav-link" id="logout-link">
                <i class="fa fa-sign-out"></i> Logout</a>
            <script>
                $('#logout-link').click(function(event) {
                    event.preventDefault();
                    $(this).text(function() {
                        return $(this).text().replace("Logout", "Logging out");
                    });
                    auth = $('#logout-link').attr('target');
                    data = {
                        'formtype': "logout"
                    };
                    $.post(auth, data, function() {
                        location.reload();
                    });
                });

            </script>
        </li>
        <?php } else {?>
        <li class="ml-md-auto">
            <a href="#" id="login-link" data-toggle="modal" data-target="#auth-modal">
                <i class="fas fa-sign-in-alt"></i> Login/Register</a>
        </li>
        <?php }?>
    </ul>
    </div>
</nav>
