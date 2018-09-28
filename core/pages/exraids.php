<?php


$results_per_page = 10;
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    } else {
        $page = 1;
    }
    $start_from = ($page - 1) * $results_per_page;
    if ($clock == "true") {
        $sql = "SELECT exid, DATE_FORMAT(`gyms`.`exraiddate`, '%d-%m-%Y %H:%m:%s') as exraiddate , gyms.gname, spotter, glatitude, glongitude FROM exraids, gyms WHERE exraids.gname = gyms.gid ORDER BY exraids.exraiddate ASC LIMIT $start_from," . $results_per_page;
    } else {
        $sql = "SELECT exid, DATE_FORMAT(`gyms`.`exraiddate`, '%Y-%m-%d %h:%m:%s %p') as exraiddate , gyms.gname, spotter, glatitude, glongitude FROM exraids, gyms WHERE exraids.gname = gyms.gid ORDER BY exraids.exraiddate ASC LIMIT $start_from," . $results_per_page;
    }
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $sqlcnt = "SELECT COUNT(EXID) AS total FROM exraids";
    $resultcnt = $conn->query($sqlcnt);
    $row = $resultcnt->fetch_assoc();
    $total_pages = ceil($row["total"] / $results_per_page);
    ?>
                <h3 style="text-align:center;"><strong>Spotted EX Raids:</strong></h3>
                <center>
                    <!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
                    <table id="spotted" class="table table-bordered">
                        <tr>
                            <th>EX ID</th>
                            <th>GYM</th>
                            <th>Date and Time</th>
                            <th>Spotter</th>
                            <th>Attendance</th>
                        </tr>
                        <?php
while ($row = mysqli_fetch_array($result)) {
        $exid = $row['exid'];
        $exraiddate = $row['exraiddate'];
        $gname = $row['gname'];
        $spotter = $row['spotter'];
        $glatitude = $row['glatitude'];
        $glongitude = $row['glongitude'];
        ///////////////////// 12 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
        if ($clock == "false") {
            ///////////////////// 12 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
            ?>
                        <tr>
                            <td>
                                <center>
                                    <?=$exid?>
                                    <center>
                            </td>
                            <td>
                                <a href="./?loc=<?php echo $glatitude, " ," . $glongitude; ?>&zoom=19">
                                    <?=$gname?></a></td>
                            <td>
                                <?=$exraiddate?>
                            </td>
                            <td>
                                <center>
                                    <?=$spotter?>
                                    <center>
                            </td>
                            <td>
                                <center>
                                    <form action='attendance.php' method='post'>
                                        <input type='hidden' name='exidr' value="<?=$exid?>" />
                                        <input type='image' name='att' style='width:25px;height:auto;align:middle;' src='<?=W_ASSETS?>voting/up.png'
                                            value="<?=$_SESSION['uname']?>" />
                                    </form><a href='./ex-attendance.php' style='display:inline;'>View</a>
                                </center>
                            </td>
                        </tr>
                        <?php } else {
            ///////////////////// 24 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
            ///////////////////// 24 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
            ?>
                        <tr>
                            <td>
                                <center>
                                    <?=$exid?>
                                    <center>
                            </td>
                            <td><a href="./?loc=<?php echo $glatitude, " ," . $glongitude; ?>&zoom=19">
                                    <?=$gname?></a></td>
                            <td>
                                <?=$exraiddate?>
                            </td>
                            <td>
                                <center>
                                    <?=$spotter?>
                                    <center>
                            </td>
                            <td>
                                <center>
                                    <form action='attendance.php' method='post'>
                                        <input type='hidden' name='exidr' value="<?=$exid?>" />
                                        <input type='image' name='att' style='width:25px;height:auto;align:middle;' src='<?=W_ASSETS?>voting/up.png'
                                            value="<?=$_SESSION['uname']?>" />
                                    </form>
                                    <a href='./ex-attendance.php' style='display:inline;'>View</a>
                                </center>
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
