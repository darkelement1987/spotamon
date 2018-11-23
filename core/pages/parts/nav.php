<?php
require_once 'initiate.php';
$gcountquery = $conn->query("SELECT * FROM `gyms`");
$gcountresult = mysqli_num_rows($gcountquery);

$scountquery = $conn->query("SELECT * FROM `stops`");
$scountresult = mysqli_num_rows($scountquery);
$uname = $sess->get('uname', null);
$mquery = $conn->prepare('Select count(a.message) as count, b.url from messages a join users b on a.to_user = b.uname where a.to_user = ? and a.unread = 1 and a.del_in = 0;');
$mquery->bind_param('s', $uname);
if ($mquery->execute()) {
    $results = $mquery->get_result();
    while ( $result = $results->fetch_assoc()) {
        $mcount = $result['count'];
        $url = $result['url'];
    }

}

$mquery->close();


?>

<nav class="wsmenu clearfix">
    <ul class="wsmenu-list">
        <li><a href="<?=W_ROOT?>index.php" class="active"><img id="mainlogo" src="<?=W_ASSETS?>img/spotamon.png" height="50px"></img><span
                    class="hometext"></span><h1 class="sr-only d-hidden">Spotamon</h1></a></li>
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
                    <div class=" col-md-8 align-self-center p-0">
                        <?php if (!empty($uname)) {?>
                        <h2 class="text-center" id="feedback-title">Feedback</h3>
                        <br />
                        <form method="post" class="mx-auto w-100 border-0 p-1" id="feedback-form">
                            <div class="form-group">
                                <label class="sr-only">Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Email:" title="optional"/>
                            </div>
                            <div class="form-group">
                                <label class="sr-only">Subject</label>
                                <input type="text" name="subject" class="form-control" placeholder="Subject:"/>
                            </div>
                            <div class="form-group">
                                <label class="sr-only">Message</label>
                                <textarea name="message" class="form-control" placeholder="Message:" rows="5"></textarea>
                            </div>
                            <div class="form-group" align="center">
                                <input type="submit" name="submit" value="Submit" class="btn btn-info" />
                            </div>
                            <?=$error?>
                            <script>
                            var csrf = '<?=$session->getCsrfToken()->getValue()?>';
                            </script>
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
if ( !empty($uname)) { ?>
        <li class="ml-md-auto"><a href="#"><i class="far fa-envelope-open"></i>Mail
                <span class="badge mcount my-auto">
                    <?=$mcount?>
                </span>
                <span class="wsarrow"></span></a>
            <ul class="sub-menu">
                <li>
                    <a href="/mail">Inbox</a>
                </li>
                <li>
                    <a href="/mail?box=outbox">Outbox</a>
                </li>
                <li>
                    <a href="/mail?compose=true">Send message</a>
                </li>
                <li>
                    <a href="/mail?box=trash" class="d-block d-md-none">Trash</a>
                </li>
            </ul>
        </li>
        <!--Dropdown for MAIL-TAB ends -->
        <!-- USERPROFILE begins -->
        <li>
            <a href="profile.php">
                <?php if (!empty($url)) { ?>
                <img class="pic my-auto" src="<?=$url?>" height="25px" width="25px" alt="logo" style="border:1px solid black">
                <?php } else {?>
                <img class="pic my-auto" src="<?=W_ASSETS?>userpics/nopic.png" height="25px" width="25px" alt="logo"
                    style="border:1px solid black">
                <?php }?>
                &nbsp
                <?=$uname?>
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
