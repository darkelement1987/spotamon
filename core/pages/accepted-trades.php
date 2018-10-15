<?php
require_once 'initiate.php';

$results_per_page = 10;
    $page = $validate->getGet('page', int, true, null, 1);
    $start_from = ($page - 1) * $results_per_page;
    $sql = "SELECT * FROM trades WHERE trades.rname = '" . $sess->get('uname') . "' ORDER BY tid DESC LIMIT $start_from," . $results_per_page;
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $sqlcnt = "SELECT COUNT(TID) AS total FROM trades";
    $resultcnt = $conn->query($sqlcnt);
    $row = $resultcnt->fetch_assoc();
    $total_pages = ceil($row["total"] / $results_per_page);
    ?>
                                    <h3 style="text-align:center;"><strong>My Accepted Trades:</strong></h3>
                                    <center>
                                        <!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
                                        <table id="spotted" class="table table-bordered">
                                            <?php if (isset($_SESSION['Spotamon']['uname'])) {?>
                                            <tr>
                                                <th>#</th>
                                                <th>OFFERED POKEMON</th>
                                                <th>CITY TO TRADE</th>
                                                <th>STATUS</th>
                                                <th>OFFERED BY</th>
                                                <th>DATE</th>
                                            </tr>
                                            <?php } else {?>
                                            <tr>
                                                <th>#</th>
                                                <th>OFFERED POKEMON</th>
                                                <th>CITY TO TRADE</th>
                                                <th>STATUS</th>
                                                <th>OFFERED BY</th>
                                                <th>DATE</th>
                                            </tr>
                                            <?php }
    while ($row = mysqli_fetch_array($result)) {
        $tid = $row['tid'];
        $offmon = $row['offmon'];
        $tradeloc = $row['tradeloc'];
        $rname = $row['rname'];
        $tname = $row['tname'];
        $date = $row['date'];
        if (isset($_SESSION['Spotamon']['uname'])) {?>
                                            <tr>
                                                <td style='text-align:center;'>
                                                    <?=$tid?>
                                                </td>
                                                <td><img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$offmon?>.png"
                                                        title="<?=$offmon?> (#<?=$offmon?>)" height="24" width="28">
                                                    <p style="padding-top:6%;">
                                                        <?=$offmon?>
                                                    </p>
                                                </td>
                                                <td>
                                                    <?=$tradeloc?>
                                                </td>
                                                <td style='text-align:center; color:orange;'> ACCEPTED / IN PROGRESS</td>
                                                <td style='text-align:center;'><a href='./compose.php?user=<?=$tname?>&subject=Counter Offer Number <?=$tid?>'>
                                                        <?=$tname?>
                                                </td>
                                                <td>
                                                    <?=$date?>
                                                </td>
                                            </tr>
                                            <?php } else {?>
                                            <tr>
                                                <td style='text-align:center;'>
                                                    <?=$tid?>
                                                </td>
                                                <td><img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$offmon?>.png"
                                                        title="<?=$offmon?> (#<?=$offmon?>)" height="24" width="28">
                                                    <p style="padding-top:6%;">
                                                        <?=$offmon?>
                                                    </p>
                                                </td>
                                                <td><img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$reqmon?>.png"
                                                        title="<?=$reqmon?> (#<?=$reqmon?>)" height="24" width="28">
                                                    <p style="padding-top:6%;">
                                                        <?=$reqmon?>
                                                    </p>
                                                </td>
                                                <td>
                                                    <?=$tradeloc?>
                                                </td>
                                                <td style='text-align:center; color:orange;'> ACCEPTED / IN PROGRESS</td>
                                                <td>
                                                    <?=$tname?>
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
                                            <?php
//-----------------------------
    //     pagenation
    //-----------------------------
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='" . basename($_SERVER['PHP_SELF']) . "?page=" . $i . "'>" . $i . "</a> ";
    }
    ?>
                                        </center>
