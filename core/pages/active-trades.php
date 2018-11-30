<?php
require_once 'initiate.php';
$results_per_page = 7;
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM offers,pokedex WHERE offers.offmon = pokedex.id AND complete = 0 ORDER BY oid DESC LIMIT $start_from," . $results_per_page;
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
if (isset($_SESSION['Spotamon']['uname'])) {
    $sqlcnt = "SELECT COUNT(OID) AS total FROM offers";
    $resultcnt = $conn->query($sqlcnt);
    $row = $resultcnt->fetch_assoc();
    $total_pages = ceil($row["total"] / $results_per_page);
    ?>
                    <h3 style="text-align:center;"><strong>Available Trades:</strong></h3>
                    <!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
                    <?php
while ($row = mysqli_fetch_array($result)) {
        $oid = $row['oid'];
        $offmon = $row['offmon'];
        $cp = $row['cp'];
        $iv = $row['iv'];
        $tradeloc = $row['tradeloc'];
        $reqmon = $row['reqmon'];
        $accepted = $row['accepted'];
        $offerby = $row['tname'];
        $opentrade = $row['opentrade'];
        $shiny = $row['shiny'];
        $alolan = $row['alolan'];
        $cloc = $row['cloc'];
        ?>
                    <div id="<?=$oid?>" class="activelisting">
                        <div id="<?=$oid?>" class="actid">
                            <a href='./active-offers.php?oid=<?=$oid?>'>ID:
                                <?=$oid?></a>
                        </div>
                        <div class="offerby">
                            <?php
$urlquery = "SELECT url FROM users WHERE uname = '" . $offerby . "'";
        $resulturl = $conn->query($urlquery);
        $rowurl = $resulturl->fetch_array(MYSQLI_NUM);
        $url = $rowurl[0];
        ?>
                            <center><a href='./compose.php?user=<?=$offerby?>&subject=Trade Number <?=$oid?>'>
                                    <?=$offerby?></a><br>
                                <?php if ($url !== '') {?>
                                <a href='./compose.php?user=<?=$offerby?>&subject=Trade Number <?=$oid?>'>
                                    <img src="<?=$url?>" height="25px" width="25px" alt="logo" style="border:1px solid black"></a>
                                <?php } else {?>
                                <a href='./compose.php?user=<?=$offerby?>&subject=Trade Number <?=$oid?>'>
                                    <img src="./core/assets/userpics/nopic.png" height="25px" width="25px" alt="logo" style="border:1px solid black"></a>
                                <?php }?>
        <p style='font-weight:600;'><?=$tradeloc?></p>

                            </center>
                        </div>
                        <div class="offmon">
                            <a href='./active-offers.php?oid=<?=$oid?>'><img src='<?=W_ASSETS?>icons/<?=$offmon?>.png'></a>
    </div>
<div class="monvars">
                                <?php if ($shiny == 1) {?>
                                <img src='<?=W_ASSETS?>/img/star.png' title='shiny'></br>
                                <?php }
        if ($alolan == 1) {?>
                                <img src='<?W_ASSETS?>/img/alolan.png' title='alolan'>
                                <?php }?>
                        </div>
                        <div class="stats">
                            CP:&nbsp;
                            <?=$cp?><br>
                            IV:&nbsp;
                            <?=$iv?><br>
                            CAUGHT:&nbsp;
                            <?=$cloc?>
                        </div>
                        <a href='./active-offers.php?oid=<?=$oid?>'>
                            <img style="height:30px;width:30px;margin-left:10px;margin-bottom:40px;" src='<?=W_ASSETS?>/img/swap.png'
                                title='swap'>
                        </a>
                        <div class="reqmon">
                            <a href='./active-offers.php?oid=<?=$oid?>'>
                                <img src="<?=W_ASSETS?>icons/<?=$reqmon?>.png">
                            </a>
                        </div>
                        <div class="control">
                            <?php if ($offerby == $_SESSION['Spotamon']['uname']) {?>
                            <a href='./active-offers.php?oid=<?=$oid?>'>
                                <input type='button' name='makeoffer' class='btn3' value='Your Trade' />
                            </a>
                            <?php } else {
            if ($accepted == 0) {
                if ($opentrade == 0) {?>
                            <form action='./trading.php' method='post'>
                                <input type='hidden' name='oid' value='<?=$oid?>' />
                                <input type='hidden' name='accepted' value='<?=$accepted?>' />
                                <input type='submit' class='btn1' name='accepted' value='Lets Trade' />
                            </form>
                            <?php } else {?>
                            <form action='./make-offer.php' method='post'><input type='hidden' name='oid' value='<?=$oid?>' />
                                <input type='submit' name='makeoffer' class='btn' value='Make Offer' />
                            </form>
                            <?php }
            } else {?>
                            <a href='./active-offers.php?oid=<?=$oid?>'>
                                <input type='button' name='makeoffer' class='btn2' value='In Progress' /></a>
                            <?php }
        }?>
                        </div>
                    </div>
                    <?php }?>
                    <p id='pages'>
                        <center>
                            <?php
//-----------------------------
    //     pagenation
    //-----------------------------
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='" . basename($_SERVER['PHP_SELF']) . "?page=" . $i . "'>" . $i . "</a> ";
    }
} else {?>
                            <center>
                                <whoa wtdiv style='margin-top:10px;'>
                                    Login to spot a pokemon
                                    <br />
                                    <br />
                                    <a href="#" id="login-link" data-toggle="modal" data-target="#auth-modal">
                                        <i class="fas fa-sign-in-alt"></i> Login or Register Here</a>
                                    </div>
                            </center>
                            <?php }?>
                        </center>
