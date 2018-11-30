<?php
require_once 'initiate.php';

?>

	<script>
		$(document).ready(function() {
			$("#pokesearch2").select2({
				templateResult: formatState,
				width: '100%'
			});
		});

		function formatState(state) {
			if (!state.id) {
				return state.text;
			}
			var $state = $(
				'<span ><img style="display: inline-block;" src="<?=W_ASSETS?>icons/' +
				state.element.value.toLowerCase() + '.png" heigth="24" width="24"/> ' + state.text + '</span>'
			);
			return $state;
		}

	</script>

<?php
if (isset($_GET['oid'])) {
    $oid = $_GET['oid'];
    $selectquery = "SELECT * FROM offers WHERE oid='$oid'";
    $selectresult = $conn->query($selectquery);
    $row = $selectresult->fetch_array(MYSQLI_NUM);
} else {
    $oid = $conn->real_escape_string($_POST['oid']);
}
$sql2 = "SELECT * FROM offers WHERE oid='$oid'";
$result = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($result)) {
    $oid = $row['oid'];
    $offmon = $row['offmon'];
    $tradeloc = $row['tradeloc'];
    $cp = $row['cp'];
    $iv = $row['iv'];
    $offerby = $row['tname'];
    $shiny = $row['shiny'];
    $alolan = $row['alolan'];
    $notes = $row['notes'];
    $rname = $_SESSION['Spotamon']['uname'];
    $complete = $row['complete'];
    $cloc = $row['cloc'];
}
?>
                                        <h3 style="text-align:center;"><strong>Offered Pokemon:</strong></h3>
                                        <form id="usersubmit" method="post" action="make-offer.php">
                                            <center>
                                                <table id="added" class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width: 5%; text-align:center;">ID:</td>
                                                            <td style="width: 10%; text-align:center;">
                                                                <?=$oid?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 5%; text-align:center;">Offered By:</td>
                                                            <td style="width: 10%; text-align:center;">
                                                                User:&nbsp;
                                                                <a href='./compose.php?user=<?=$offerby?>&subject=Trade Number <?=$oid?>'>
                                                                    <?=$offerby?></a>&nbsp;(
                                                                <?=$tradeloc?>)
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 5%; text-align:center;vertical-align:middle;">Offered
                                                                Pokemon:</td>
                                                            <td style="width: 10%; text-align:center;vertical-align:middle;">
                                                                <img src="<?=W_ASSETS?>icons/<?=$offmon?>.png" height=50;
                                                                    width=55;>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 5%; text-align:center;vertical-align:middle;">Stats:</td>
                                                            <td style="width: 10%; text-align:center;vertical-align:middle;">
                                                                CP:
                                                                <?=$cp?>&nbsp;&nbsp;&nbsp;
                                                                IV:
                                                                <?=$iv?> %
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 5%; text-align:center;vertical-align:middle;">Variance:</td>
                                                            <td style="width: 10%; text-align:center;vertical-align:middle;">
                                                                Shiny:
                                                                <?php if ($shiny == 1) {
    echo " Yes";
} else {
    echo " No";
}?>&nbsp;&nbsp;&nbsp;
                                                                Alolan:
                                                                <?php if ($alolan == 1) {
    echo " Yes";
} else {
    echo " No";
}?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 5%; text-align:center;vertical-align:middle;">Catch
                                                                Location:</td>
                                                            <td style="width: 10%; text-align:center;vertical-align:middle;">
                                                                <?=$cloc?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 5%; text-align:center;">Notes:</td>
                                                            <td style="width: 10%; text-align:center;">
                                                                <?php
if (empty($notes)) {
    echo "No Notes";
} else {
    echo $notes;
}
?>
                                                            </td>
                                                        </tr>
                                                        <?php if ($offerby == ($_SESSION['Spotamon']['uname'])) {
} else {?>
                                                        <tr>
                                                            <td></td>
                                                            <td style="width:10%;">
                                                                <center><input type='hidden' name='oid' value='<?=$oid?>' />
                                                                    <input type="submit" id="coffer" value="OFFER!"></center>
                                                            </td>
                                                        </tr>
                                                        <?php }?>
                                                    </tbody>
                                                </table>
                                            </center>
                                        </form>
                                        </tbody>
                                        </table>
                                        <?php

$results_per_page = 10;
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM tradeoffers WHERE oid='$oid' ORDER BY complete DESC, accepted DESC, date DESC LIMIT $start_from," . $results_per_page;
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$sqlcnt = "SELECT COUNT(OID) AS total FROM tradeoffers";
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>
                                        <h3 style="text-align:center;"><strong>Counter Offers:</strong></h3>
                                        <center>
                                            <!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
                                            <table id="spotted" class="table table-bordered">
                                                <?php
if (isset($_SESSION['Spotamon']['uname'])) {
    if ($offerby == ($_SESSION['Spotamon']['uname'])) {
        if ($complete == 0) {?>
                                                <tr>
                                                    <th>#</th>
                                                    <th>OFFERED BY</th>
                                                    <th>OFFERED POKEMON</th>
                                                    <th>CP</th>
                                                    <th>IV</th>
                                                    <th>SHINY</th>
                                                    <th>ALOLAN</th>
                                                    <th>DATE</th>
                                                    <th>ACCEPT</th>
                                                    <th>DECLINE</th>
                                                </tr>
                                                <?php } else {?>
                                                <tr>
                                                    <th>#</th>
                                                    <th>OFFERED BY</th>
                                                    <th>OFFERED POKEMON</th>
                                                    <th>CP</th>
                                                    <th>IV</th>
                                                    <th>SHINY</th>
                                                    <th>ALOLAN</th>
                                                    <th>DATE</th>
                                                    <th>STATUS</th>
                                                </tr>
                                                <?php }
    } elseif ($offerby != ($_SESSION['Spotamon']['uname'])) {?>
                                                <tr>
                                                    <th>#</th>
                                                    <th>OFFERED BY</th>
                                                    <th>OFFERED POKEMON</th>
                                                    <th>CP</th>
                                                    <th>IV</th>
                                                    <th>SHINY</th>
                                                    <th>ALOLAN</th>
                                                    <th>DATE</th>
                                                </tr>
                                                <?php } else {?>
                                                <tr>
                                                    <th>#</th>
                                                    <th>OFFERED BY</th>
                                                    <th>OFFERED POKEMON</th>
                                                    <th>CP</th>
                                                    <th>IV</th>
                                                    <th>SHINY</th>
                                                    <th>ALOLAN</th>
                                                    <th>DATE</th>
                                                </tr>
                                                <?php }
}
while ($row = mysqli_fetch_array($result)) {
    $toid = $row['toid'];
    $oid = $row['oid'];
    $coffer = $row['coffer'];
    $cofferby = $row['cofferby'];
    $ccp = $row['ccp'];
    $civ = $row['civ'];
    $cshiny = $row['cshiny'];
    $calolan = $row['calolan'];
    $accepted = $row['accepted'];
    $complete = $row['complete'];
    $date = $row['date'];
    if (isset($_SESSION['Spotamon']['uname'])) {
        if ($offerby == ($_SESSION['Spotamon']['uname'])) {?>
                                                <tr>
                                                    <td style='text-align:center;'>
                                                        <?=$toid?>
                                                    </td>
                                                    <td style='text-align:center;'><a href='./compose.php?user=<?=$cofferby?>&subject=Counter Offer Number <?=$toid?>'>
                                                            <?=$cofferby?>
                                                    </td>
                                                    <td style='text-align:center;'>
                                                        <center><img style="padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$coffer?>.png"
                                                                title="<?=$coffer?> (#<?=$coffer?>)" height="50" width="55"></center>
                                                    </td>
                                                    <td style='text-align:center;'>
                                                        <?=$ccp?>
                                                    </td>
                                                    <td style='text-align:center;'>
                                                        <?=$civ?>
                                                    </td>
                                                    <td style='text-align:center;'>
                                                        <?php if ($cshiny == 1) {?>
                                                        <center><span><img style='padding-right:3px;' src='<?=W_ASSETS?>/img/star.png'
                                                                    title='star' height='24' width='28'></span>
                                                            <center>
                                                                <?php }?>
                                                    </td>
                                                    <td style='text-align:center;'>
                                                        <?php if ($calolan == 1) {?>
                                                        <center><span><img style='padding-right:3px;' src='<?=W_ASSETS?>/img/alolan.png'
                                                                    title='star' height='24' width='28'></span>
                                                            <center>
                                                                <?php }?>
                                                    </td>
                                                    <td style='text-align:center;'>
                                                        <?=$date?>
                                                    </td>
                                                    <?php if ($complete == 0) {?>
                                                    <td style='text-align:center;'>
                                                        <?php if ($accepted == 1) {?>
                                                        <p style='color:green;font-weight:700;'>Accepted</p><span style='display:inline-block;'>
                                                            <form action='<?=S_ROOT?>core/functions/trading/complete.php' method='post'>
                                                                <input type='hidden' name='toid' value='$toid' />
                                                                <input type='hidden' name='oid' value='$oid' />
                                                                <input type='submit' name='complete' style='color:blue;font-size:9px'
                                                                    value='Complete' />
                                                            </form>
                                                        </span>
                                                        <?php } else {?>
                                                        <span style='display:inline-block;'>
                                                            <form action='<?=S_ROOT?>core/functions/trading/accept.php' method='post'>
                                                                <input type='hidden' name='toid' value='$toid' />
                                                                <input type='hidden' name='oid' value='$oid' />
                                                                <input type='submit' name='accepted' style='color:green;font-size:9px'
                                                                    value='Accept' />
                                                            </form>
                                                        </span>
                                                        <?php }?>
                                                    </td>
                                                    <td style='text-align:center;'>
                                                        <?php if ($accepted == 2) {?>
                                                        <p style='color:red;font-weight:700;'>Declined</p>
                                                        <?php } else {?>
                                                        <span style='display:inline-block;'>
                                                            <form action='<?=S_ROOT?>core/functions/trading/decline.php' method='post'>
                                                                <input type='hidden' name='toid' value='$toid' />
                                                                <input type='hidden' name='oid' value='$oid' />
                                                                <input type='submit' name='declined' style='color:red;font-size:9px'
                                                                    value='Decline' />
                                                            </form>
                                                        </span>
                                                        <?php }?>
                                                    </td>
                                                    <?php } elseif ($complete == 1) {?>
                                                    <td style='text-align:center;'>
                                                        <?php if ($accepted == 1) {?>
                                                        <p style='color:green;font-weight:700;'>Complete</p>
                                                        <?php }
        }?>
                                                    </td>
                                                </tr>
                                                <?php } else {?>
                                                <tr>
                                                    <td style='text-align:center;'>
                                                        <?=$toid?>
                                                    </td>
                                                    <td style='text-align:center;'><a href='./compose.php?user=<?=$cofferby?>&subject=Trade Number <?=$oid?>'>
                                                            <?=$cofferby?>
                                                    </td>
                                                    <td>
                                                        <center><img style="padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$coffer?>.png"
                                                                title="<?=$coffer?> (#<?=$coffer?>)" height="50" width="55"></center>
                                                    </td>
                                                    <td>
                                                        <?=$ccp?>
                                                    </td>
                                                    <td>
                                                        <?=$civ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($cshiny == 1) {?>
                                                        <center><span><img style='padding-right:3px;' src='<?=W_ASSETS?>/img/star.png'
                                                                    title='star' height='24' width='28'></span>
                                                            <center>
                                                                <?php }?>
                                                    </td>
                                                    <td>
                                                        <?php if ($calolan == 1) {?>
                                                        <center><span><img style='padding-right:3px;' src='<?=W_ASSETS?>/img/alolan.png'
                                                                    title='star' height='24' width='28'></span>
                                                            <center>
                                                                <?php }?>
                                                    </td>
                                                    <td>
                                                        <?=$date?>
                                                    </td>
                                                </tr>
                                                <?php }
    } else {?>
                                                <tr>
                                                    <td style='text-align:center;'>
                                                        <?=$toid?>
                                                    </td>
                                                    <td style='text-align:center;'>
                                                        <?=$cofferby?>
                                                    </td>
                                                    <td>
                                                        <center><img style="padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$coffer?>.png"
                                                                title="<?=$coffer?> (#<?=$coffer?>)" height="50" width="55"></center>
                                                    </td>
                                                    <td>
                                                        <?=$ccp?>
                                                    </td>
                                                    <td>
                                                        <?=$civ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($cshiny == 1) {?>
                                                        <center><span><img style='padding-right:3px;' src='<?=W_ASSETS?>/img/star.png'
                                                                    title='star' height='24' width='28'></span>
                                                            <center>
                                                                <?php }?>
                                                    </td>
                                                    <td>
                                                        <?php if ($calolan == 1) {?>
                                                        <center><span><img style='padding-right:3px;' src='<?=W_ASSETS?>/img/alolan.png'
                                                                    title='star' height='24' width='28'></span>
                                                            <center>
                                                                <?php }?>
                                                    </td>
                                                    <td>
                                                        <?=$date?>
                                                    </td>
                                                </tr>
                                                <?php }
}?>
                                            </table>
                                        </center>
                                        <p id='pages'>
                                            <center>
                                                <?php for ($i = 1; $i <= $total_pages; $i++) {?>
                                                <a href="<?=basename($_SERVER['PHP_SELF'])?>?oid=<?=$oid?>?page=<?=$i?>">
                                                    <?=$i?></a>
                                                <?php }?>
                                            </center>
                                            </form>
