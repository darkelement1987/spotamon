<?php
require_once 'initiate.php';

?>
<h3 style="text-align:center;"><strong>EX Raid Attendance:</strong></h3>
                    <center>
                        <!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
                        <table id="spotted" class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <th>Gym</th>
                                <th>Date and Time</th>
                                <th>Attending</th>
                            </tr>
                            <?php
$results_per_page = 15;
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    } else {
        $page = 1;
    }
    $start_from = ($page - 1) * $results_per_page;
    $sql1 = "SELECT * FROM exraidatt,exraids,gyms WHERE exraidatt.exid = exraids.exid AND exraids.gname = gyms.gid ORDER BY exraids.exid ASC LIMIT $start_from," . $results_per_page;
    $result = mysqli_query($conn, $sql1) or die(mysqli_error($conn));
    $sqlcnt = "SELECT COUNT(EXID) AS total FROM exraidatt";
    $resultcnt = $conn->query($sqlcnt);
    $row = $resultcnt->fetch_assoc();
    $total_pages = ceil($row["total"] / $results_per_page);
    while ($row = mysqli_fetch_array($result)) {
        $exid = $row['exid'];
        $uid = $row['uid'];
        $gname = $row['gname'];
        $exraiddate = $row['exraiddate'];
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
                                <td><a href="./?loc=<?php echo $glatitude, " ," . $glongitude; ?>&zoom=19">
                                        <?=$gname?></a></td>
                                <td>
                                    <?=$exraiddate?>
                                </td>
                                <td>
                                    <?=$uid?>
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
                                    <?=$uid?>
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
