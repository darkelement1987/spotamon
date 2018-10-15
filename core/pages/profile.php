<?php
require_once 'initiate.php';


if (isset($_SESSION['Spotamon']['uname'])) {
    require_once 'config/config.php';
    $result = $conn->query("SELECT * FROM users,usergroup WHERE uname='" . $_SESSION['Spotamon']['uname'] . "' AND users.usergroup = usergroup.id LIMIT 1  ");
    $gcountquery = $conn->query("SELECT * FROM `gyms`");
    $gcountresult = mysqli_num_rows($gcountquery);
    $scountquery = $conn->query("SELECT * FROM `stops`");
    $scountresult = mysqli_num_rows($scountquery);
    $eggcountquery = $conn->query("SELECT * FROM `gyms` WHERE egg != 0");
    $eggcountresult = mysqli_num_rows($eggcountquery);
    $raidcountquery = $conn->query("SELECT * FROM `gyms` WHERE actraid != 0");
    $raidcountresult = mysqli_num_rows($raidcountquery);
    $teamcountquery = $conn->query("SELECT * FROM `gyms` WHERE gteam > 1");
    $teamcountresult = mysqli_num_rows($teamcountquery);
    $moncountquery = $conn->query("SELECT * FROM `spots`");
    $moncountresult = mysqli_num_rows($moncountquery);
    $questcountquery = $conn->query("SELECT * FROM `stops` WHERE quested != 0");
    $questcountresult = mysqli_num_rows($questcountquery);
    $totalspots = $eggcountresult + $raidcountresult + $teamcountresult + $moncountresult + $questcountresult;
    $id = $usergroup = "";?>
            <h3 style="text-align:center;"><strong>Your Profile:</strong></h3>
            <?php
$versionquery = "SELECT version FROM version";
    $versionresult = $conn->query($versionquery);
    $rowversion = $versionresult->fetch_array(MYSQLI_NUM);
    $version = $rowversion[0];?>
            <center>
                <table id="spotted" class="table table-bordered">
                    <tr>
                        <th>Pic</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Usergroup</th>
                    </tr>
                    <?php
while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $uname = $row['uname'];
        $email = $row['email'];
        $usergroup = $row['groupname'];
        $url = $row['url'];
        $offtrades = $row['offtrades'];
        $reqtrades = $row['reqtrades'];
        $tradetotal = $offtrades + $reqtrades;?>
                    <tr>
                        <td>
                            <?php if (!empty($url)) {
            if (substr($url, 0, 5) == 'https') {?>
                            <img src="<?=$url?>" height="50px" width="50px" alt="logo" style="border:1px solid black">
                            <?php } else {?>
                            <img src="<?=$url?>" height="50px" width="50px" alt="logo" style="border:1px solid black">
                            <?php }} else {?>
                            <img src="./core/assets/userpics/nopic.png" height="50px" width="50px" alt="logo" style="border:1px solid black">
                            <?php }?>
                        </td>
                        <td>
                            <?=$uname?>
                        </td>
                        <td>
                            <?=$email?>
                        </td>
                        <td>
                            <?=$usergroup?>
                        </td>
                    </tr>
                </table>
                <br />
                <center><a href='./edit-profile.php'>Edit Profile</a></center><br>
                <center>
                    <table id="spotted" class="table table-bordered">
                        <tr>
                            <th colspan="2"><strong>
                                    <center>Trades
                                </strong></th>
                        </tr>
                        <tr>
                            <td><strong>My Created Trades:</strong></td>
                            <td><a href="./my-trades.php">Created Trades</a></td>
                        </tr>
                        <tr>
                            <td><strong>My Accepted Trades:</strong></td>
                            <td><a href="./accepted-trades.php">Accepted Trades</a></td>
                        </tr>
                        <tr>
                            <td><strong>Offered:</strong></td>
                            <td>
                                <?=$offtrades?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Requested:</strong></td>
                            <td>
                                <?=$reqtrades?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td><strong>
                                    <?=$tradetotal?></strong></td>
                        </tr>
                    </table>
                </center>
                <?php if ("$usergroup" == 'admin') {?>
                <h3 style="text-align:center;"><strong>Admin Panel:</strong></h3>
                <center>
                    <a href="gymcsv.php">Upload Gym .CSV</a><br />
                    <a href="stopcsv.php">Upload Stop .CSV</a><br />
                    <h3 style="text-align:center;"><strong>Database overview</strong></h3>
                    <table id="spotted" class="table table-bordered">
                        <tbody>
                            <tr>
                                <th colspan="2"><strong>
                                        <center>Database
                                    </strong></th>
                            </tr>
                            <tr>
                                <td>Gyms</td>
                                <td>
                                    <?=$gcountresult?>
                                </td>
                            </tr>
                            <tr>
                                <td>Stops</td>
                                <td>
                                    <?=$scountresult?>
                                </td>
                            </tr>
                            <tr>
                                <td>Database version</td>
                                <td>
                                    <?=$version?>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2"><strong>
                                        <center>Spots
                                    </strong></th>
                            </tr>
                            <tr>
                                <td>Pokemon</td>
                                <td>
                                    <?=$moncountresult?>
                                </td>
                            </tr>
                            <tr>
                                <td>Raids</td>
                                <td>
                                    <?=$raidcountresult?>
                                </td>
                            </tr>
                            <tr>
                                <td>Eggs</td>
                                <td>
                                    <?=$eggcountresult?>
                                </td>
                            </tr>
                            <tr>
                                <td>Teams</td>
                                <td>
                                    <?=$teamcountresult?>
                                </td>
                            </tr>
                            <tr>
                                <td>Quests</td>
                                <td>
                                    <?=$questcountresult?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Total spots:</strong></td>
                                <td><strong>
                                        <?=$totalspots?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"><a href="./droptables.php" onclick="return confirm('Are you sure?');">
                                        <center>Drop database</center>
                                    </a></td>
                            </tr>
                        </tbody>
                    </table>
                </center>
                <?php }
    }
} else {?>
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
