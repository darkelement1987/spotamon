<?php
$results_per_page = 8;
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    } else {
        $page = 1;
    }
    $start_from = ($page - 1) * $results_per_page;
    $sql = "SELECT * FROM offers WHERE offers.tname = '" . $sess->get('uname') . "' ORDER BY oid DESC LIMIT $start_from," . $results_per_page;
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $sqlcnt = "SELECT COUNT(TID) AS total FROM trades";
    $resultcnt = $conn->query($sqlcnt);
    $row = $resultcnt->fetch_assoc();
    $total_pages = ceil($row["total"] / $results_per_page);
    ?>
                            <h3 style="text-align:center;"><strong>My Active Trades:</strong></h3>
                            <center>
                                <!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
                                <table id="spotted" class="table table-bordered">
                                    <?php if (isset($_SESSION["uname"])) {?>
                                    <tr>
                                        <th>#</th>
                                        <th>OFFERED POKEMON</th>
                                        <th>REQUESTED POKEMON</th>
                                        <th>CITY TO TRADE</th>
                                        <th>DATE</th>
                                    </tr>
                                    <?php } else {?>
                                    <tr>
                                        <th>#</th>
                                        <th>OFFERED POKEMON</th>
                                        <th>REQUESTED POKEMON</th>
                                        <th>CITY TO TRADE</th>
                                        <th>DATE</th>
                                    </tr>
                                    <?php }
    while ($row = mysqli_fetch_array($result)) {
        $oid = $row['oid'];
        $offmon = $row['offmon'];
        $reqmon = $row['reqmon'];
        $tradeloc = $row['tradeloc'];
        $date = $row['date'];
        $tname = $row['tname'];
        if (isset($_SESSION["uname"])) {?>
                                    <tr>
                                        <td style='text-align:center;'>
                                            <?=$oid?>
                                        </td>
                                        <td>
                                            <center>
                                                <form action='active-offers.php' method='post'>
                                                    <input type='hidden' name='oid' value='<?=$oid?>' />
                                                    <input type='image' name='offmon' style='width:45px;height:auto;display:inline;'
                                                        src="<?=W_ASSETS?>icons/<?=$offmon?>" value="<?=$offmon?>" />
                                                </form>
                                                <center>
                                        </td>
                                        <td>
                                            <center>
                                                <img style=" padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$reqmon?>.png"
                                                    title="<?=$reqmon?> (#<?=$reqmon?>)" height="50" width="55">
                                            </center>
                                        </td>
                                        <td>
                                            <?=$tradeloc?>
                                        </td>
                                        <td>
                                            <?=$date?>
                                        </td>
                                    </tr>
                                    <?php } else {?>
                                    <tr>
                                        <td style='text-align:center;'>
                                            <?=$oid?>
                                        </td>
                                        <td>
                                            <img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$offmon?>.png"
                                                title="<?=$offmon?> (#<?=$offmon?>)" height="24" width="28">
                                            <p style="padding-top:6%;">
                                                <?=$offmon?>
                                            </p>
                                        </td>
                                        <td>
                                            <img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$reqmon?>.png"
                                                title="<?=$reqmon?> (#<?=$reqmon?>)" height="24" width="28">
                                            <p style="padding-top:6%;">
                                                <?=$reqmon?>
                                            </p>
                                        </td>
                                        <td>
                                            <?=$tradeloc?>
                                        </td>
                                        <td>
                                            <?=$rname?>
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
