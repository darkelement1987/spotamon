<?php

$results_per_page = 10;
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    } else {
        $page = 1;
    }
    $start_from = ($page - 1) * $results_per_page;
    $sql = "SELECT * FROM raidbosses,gyms WHERE gyms.actraid = '1' AND gyms.actboss = raidbosses.rid  AND gyms.glatitude AND gyms.glongitude ORDER BY date DESC LIMIT $start_from," . $results_per_page;
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $sqlcnt = "SELECT COUNT(RID) AS total FROM spotraid";
    $resultcnt = $conn->query($sqlcnt);
    $row = $resultcnt->fetch_assoc();
    $total_pages = ceil($row["total"] / $results_per_page);
    ?>
    <h3 style="text-align:center;"><strong>Spotted Raids:</strong></h3>
    <center>
        <!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
        <table id="spotted" class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>BOSS</th>
                <th>LVL / CP</th>
                <th>EXPIRES</th>
                <th>LOCATION</th>
            </tr>
    <?php
while ($row = mysqli_fetch_array($result)) {
        $rid = $row['rid'];
        $rboss = $row['rboss'];
        $rlvl = $row['rlvl'];
        $rcp = $row['rcp'];
        $hour = $row['hour'];
        $min = $row['min'];
        $ampm = $row['ampm'];
        $glatitude = $row['glatitude'];
        $glongitude = $row['glongitude'];
        $minutes = $min;
        $hr = $hour;
        $gname = $row['gname'];
        ///////////////////// ADDS "0" TO SIGNLE DIGIT MINUTE TIMES \\\\\\\\\\\\\\\\\\\\\
        if ($min < 10) {
            $minutes = str_pad($min, 2, "0", STR_PAD_LEFT);
        }
        ///////////////////// 12 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
        if ($clock == "false") {
            ///////////////////// 12 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
            ?>
            <tr>
                <td>
                    <?=$rid?>
                </td>
                <td>
                    <img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$rid?>.png" title="<?=$rid?> (#<?=$rboss?>)"
                        height="24" width="24">
                    <p style="padding-top:6%;">
                        <?=$rboss?>
                    </p>
                </td>
                <td>
                    <?php echo $rlvl . " / " . $rcp; ?>
                </td>
                <td>
                    <?php echo $hour . ":" . $minutes . " " . $ampm ?>
                </td>
                <td><a href="./?loc=<?php echo "" . $glatitude, " ," . $glongitude; ?>&zoom=19">
                        <?=$gname?></a></td>
            </tr>
            <?php } else {
            ///////////////////// 24 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
            ///////////////////// ADDS "0" TO SIGNLE DIGIT HOUR TIMES \\\\\\\\\\\\\\\\\\\\\
            if ($hour < 10) {
                $hr = str_pad($hour, 2, "0", STR_PAD_LEFT);
            }?>
            ///////////////////// 24 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
            <tr>
                <td>
                    <?=$rid?>
                </td>
                <td>
                    <img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$rid?>.png" title="<?=$rid?> (#<?=$rboss?>)"
                        height="24" width="24">
                    <p style="padding-top:6%;">
                        <?=$rboss?>
                    </p>
                </td>
                <td>
                    <?php echo $rlvl . " / " . $rcp; ?>
                </td>
                <td>
                    <?php echo $hr . ":" . $minutes; ?>
                </td>
                <td><a href="./?loc=<?php echo "" . $glatitude, " ," . $glongitude; ?>&zoom=19">
                        <?=$gname?></a></td>
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
</body>

<footer></footer>
