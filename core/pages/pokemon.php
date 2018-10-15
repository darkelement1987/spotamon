<?php
require_once 'initiate.php';

$results_per_page = 10;
$page = $Validate->getGet('page', 'int', true, null, 1);
if ($page === 1 ){
    $start_from = $page * $results_per_page;
} else {
    $start_from = ($page - 1) * $results_per_page;
}
$sql = "SELECT * FROM spots join pokedex ON spots.pokemon = pokedex.id ORDER BY spotid DESC LIMIT " . $start_from . ";";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$sqlcnt = "SELECT COUNT(SPOTID) AS total FROM spots;";
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>
<h3 style="text-align:center;"><strong>Spotted Pokemon:</strong></h3>
<center>
<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
    <table id="spotted" class="table table-bordered">
    <?php if (isset($_SESSION['Spotamon']['uname'])) {?>
    <tr>
        <th>#</th>
        <th>ID</th>
        <th>POKEMON</th>
        <th>CP</th <th>IV</th>
        <th>FOUND</th>
        <th>LOCATION</th>
        <th>VOTING</th>
    </tr>
    <?php } else {?>
    <tr>
        <th>#</th>
        <th>ID</th>
        <th>POKEMON</th>
        <th>CP</th>
        <th>IV</th>
        <th>FOUND</th>
        <th>LOCATION</th>
    </tr>
    <?php }
while ($row = mysqli_fetch_array($result)) {
    $spotid = $row['spotid'];
    $id = $row['monster'];
    $pokemon = $row['pokemon'];
    $cp = $row['cp'];
    $iv = $row['iv'];
    $hour = $row['hour'];
    $min = $row['min'];
    $ampm = $row['ampm'];
    $latitude = $row['latitude'];
    $longitude = $row['longitude'];
    $minutes = $min;
    $hr = $hour;
    $fulladdress = $row['fulladdress'];
    if (isset($_SESSION['Spotamon']['uname'])) {
        $good = $row['good'];
        $bad = $row['bad'];
    }
    ///////////////////// ADDS "0" TO SIGNLE DIGIT MINUTE TIMES \\\\\\\\\\\\\\\\\\\\\
    if ($min < 10) {
        $minutes = str_pad($min, 2, "0", STR_PAD_LEFT);
    }
    ///////////////////// 12 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
    if ($clock == "false") {
        ///////////////////// 12 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
        if (isset($_SESSION['Spotamon']['uname'])) {?>
    <tr>
        <td style='text-align:center;'>
            <?=$spotid?>
        </td>
        <td style='text-align:center;'>
            <?=$pokemon?>
        </td>
        <td><img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$pokemon?>.png" title="<?=$id?> (#<?=$pokemon?>)"
                height="24" width="24">
            <p style="padding-top:6%;">
                <?=$id?>
            </p>
        </td>
        <td>
            <?=$cp?>
        </td>
        <td>
            <?=$iv?>%</td>
        <td style='text-align:center;'>
            <?php echo $hour . ":" . $minutes . " " . $ampm; ?>
        </td>
        <td><a href="./?loc=<?php echo $latitude, " ," . $longitude; ?>&zoom=19">
                <?=$fulladdress?></a></td>
        <td style='text-align:center;'>
            <span style='display:inline-block;'>
                <form action='good.php' method='post'>
                    <input type='hidden' name='spotid' value="<?=$spotid?>" />
                    <input type='image' name='good' style='width:25px;height:auto;display:inline;' src='<?=W_ASSETS?>voting/up.png'
                        value="<?=$good?>" />
                </form>
            </span>
            <?=$good?><br>
            <span style='display:inline-block;'>
                <form action='bad.php' method='post'>
                    <input type='hidden' name='spotid' value='<?=$spotid?>' />
                    <input type='image' name='bad' style='width:27px;height:auto;display:inline;' src='<?=W_ASSETS?>voting/down.png'
                        value='<?=$bad?>' />
                </form>
            </span>
            <?=$bad?>
        </td>
    </tr>
    <?php } else {?>
    <tr>
        <td style='text-align:center;'>
            <?=$spotid?>
        </td>
        <td style='text-align:center;'>
            <?=$pokemon?>
        </td>
        <td>
            <img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$pokemon?>.png" title="<?=$id?> (#<?=$pokemon?>)"
                height="24" width="24">
            <p style="padding-top:6%;">
                <?=$id?>
            </p>
        </td>
        <td>
            <?=$cp?>
        </td>
        <td>
            <?=$iv?>%</td>
        <td style='text-align:center;'>
            <?php echo $hour . ":" . $minutes . " " . $ampm; ?>
        </td>
        <td><a href="./?loc=<?php echo $latitude . " ," . $longitude; ?>&zoom=19">
                <?=$fulladdress?></a></td>
        <?php }
    } else {
        ///////////////////// 24 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
        ///////////////////// ADDS "0" TO SIGNLE DIGIT HOUR TIMES \\\\\\\\\\\\\\\\\\\\\
        if ($hour < 10) {
            $hr = str_pad($hour, 2, "0", STR_PAD_LEFT);
        }
        ///////////////////// 24 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
        if (isset($_SESSION['Spotamon']['uname'])) {
            ?>
    <tr>
        <td style='text-align:center;'>
            <?=$spotid?>
        </td>
        <td>
            <?=$pokemon?>
        </td>
        <td><img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$pokemon?>.png" title="<?=$id?> (#<?=$pokemon?>)"
                height="24" width="24">
            <p style="padding-top:6%;">
                <?=$id?>
            </p>
        </td>
        <td>
            <?=$cp?>
        </td>
        <td>
            <?=$iv?>%</td>
        <td>
            <?php echo $hr . ":" . $minutes; ?>
        </td>
        <td><a href="./?loc=<?php echo $latitude, " ," . $longitude; ?>&zoom=19">
                <?=$fulladdress?></a></td>
        <td style='text-align:center;'>
            <span style='display:inline-block;'>
                <form action='good.php' method='post'>
                    <input type='hidden' name='spotid' value='<?=$spotid?>' />
                    <input type='image' name='good' style='width:25px;height:auto;display:inline;' src='<?=W_ASSETS?>voting/up.png'
                        value='<?=$good?>' />
                </form>
            </span>
            <?=$good?><br>
            <span style='display:inline-block;'>
                <form action='bad.php' method='post'>
                    <input type='hidden' name='spotid' value='<?=$spotid?>' />
                    <input type='image' name='bad' style='width:27px;height:auto;display:inline;' src='<?=W_ASSETS?>voting/down.png'
                        value='<?=$bad?>' />
                </form>
            </span>
            <?=$bad?>
        </td>
    </tr>
    <?php } else {?>
    <tr>
        <td style='text-align:center;'>
            <?=$spotid?>
        </td>
        <td>
            <?=$pokemon?>
        </td>
        <td>
            <img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$pokemon?>.png" title="<?=$id?> (#<?=$pokemon?>)"
                height="24" width="24">
            <p style="padding-top:6%;">
                <?=$id?>
            </p>
        </td>
        <td>
            <?=$cp?>
        </td>
        <td>
            <?=$iv?>"%</td>
        <td>
            <?php echo $hr . ":" . $minutes; ?>
        </td>
        <td><a href="./?loc=<?php echo $latitude, " ," . $longitude; ?>&zoom=19">
                <?=$fulladdress?></a></td>
        <?php }
    }
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
