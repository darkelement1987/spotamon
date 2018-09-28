<?php

$results_per_page = 10;
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    } else {
        $page = 1;
    }
    $start_from = ($page - 1) * $results_per_page;
    $sql = "SELECT * from stops,quests,rewards WHERE quested='1' AND stops.actquest = quests.qid AND stops.actreward = rewards.reid ORDER BY date DESC LIMIT $start_from," . $results_per_page;
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $sqlcnt = "SELECT COUNT(SID) AS total FROM stops WHERE quested='1'";
    $resultcnt = $conn->query($sqlcnt);
    $row = $resultcnt->fetch_assoc();
    $total_pages = ceil($row["total"] / $results_per_page);
    ?>
        <h3 style="text-align:center;"><strong>Spotted Quests:</strong></h3>
        <center>
            <!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
            <table id="spotted" class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <th>QUEST</th>
                    <th>REWARD</th>
                    <th>SPOTTED</th>
                    <th>LOCATION</th>
                </tr>
                <?php
while ($row = mysqli_fetch_array($result)) {
        $questname = $row['qname'];
        $sname = $row['sname'];
        $reward = $row['rname'];
        $sid = $row['sid'];
        $slat = $row['slatitude'];
        $slon = $row['slongitude'];
        $hour = $row['hour'];
        $min = $row['min'];
        $ampm = $row['ampm'];
        $minutes = $min;
        $hr = $hour;
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
                        <?=$sid?>
                    </td>
                    <td>
                        <?=$questname?>
                    </td>
                    <td>
                        <?=$reward?>
                    </td>
                    <td>
                        <?php echo $hour . ":" . $minutes . " " . $ampm; ?>
                    </td>
                    <td><a href="./?loc=<?php echo $slat, " ," . $slon; ?>&zoom=19">
                            <?=$sname?></a></td>
                </tr>
                <?php } else {
            ///////////////////// 24 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
            ///////////////////// ADDS "0" TO SIGNLE DIGIT HOUR TIMES \\\\\\\\\\\\\\\\\\\\\
            if ($hour < 10) {
                $hr = str_pad($hour, 2, "0", STR_PAD_LEFT);
            }
            ///////////////////// 24 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
            ?>
                <tr>
                    <td>
                        <?=$sid?>
                    </td>
                    <td>
                        <?=$questname?>
                    </td>
                    <td>
                        <?=$reward?>
                    </td>
                    <td>
                        <?php echo $hr . ":" . $minutes; ?>
                    </td>
                    <td>
                        <a href="./?loc=<?php echo $slat, " ," . $slon; ?>&zoom=19">
                            <?=$sname?></a>
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

