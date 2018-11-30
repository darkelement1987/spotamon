<?php

function directory() {
    function directory1() {
        $url =  substr(str_replace('\\', '/', realpath(dirname(__FILE__))), strlen(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))) + 1);
        $url = preg_replace("/\/?(core\/functions|functions)\/?/i", '', $url);
        return $url;
    }
    if (directory1() != '') {
        $wroot = '/' . directory1() . '/';
    } else {
        $wroot = '/';
    }
    return $wroot;
}

function columnExists($table, $column) {
    global $conn;
    $sql = "SHOW COLUMNS FROM `" . $table . "` LIKE '" . $column . "';";
    $exists = $conn->query($sql);
    if ($exists->num_rows){
        return true;
    }
    else {
        return false;
    }
}


//=============================
//    AUTO VERSION CSS/JS
//=============================

function versionFile($url) {
    $dir = S_ROOT . $url;
    if (file_exists($dir)) {
	    $path = pathinfo($url);
        $ver = '.'.filemtime($dir).'.';
        $ver = $path['dirname'].'/'. $path['basename'] . $ver . $path['extension'];
        $ver = preg_replace('/\.(css|js)/i', '', $ver, 1);
        return $ver;
    } else {
        return $url;
    }
}
//=========AND Images==========
function versionImage($url) {
    $dir = S_ROOT . $url;
    if (file_exists($dir)) {
	    $path = pathinfo($url);
	    $ver = $url. '?lastmod=' .filemtime($dir);
        return $ver;
    }
}

function isMultiArray( $arr ) {
    rsort( $arr );
    return isset( $arr[0] ) && is_array( $arr[0] );
}

function csrf($echo = null) {
    global $session;
    $csrf = $session->getCsrfToken()->getValue();
    $input = '<input type="hidden" value="' . $csrf . '" name="CSRF" />';
    if ($echo) {
        echo $input;
    } else {
        return $input;
    }
}

function verifyCsrf() {
    global $Validate;
    global $session;
    $token = $_POST['CSRF'];
    $csrf_token = $session->getCsrfToken();
    if ($csrf_token->isValid($token)) {
        return true;
    } else {
        return false;
    }
}

//=============================

//=============================
//     MY OPEN OFFERS
//=============================
function mynatrades()
{

    $results_per_page = 10;
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    } else {
        $page = 1;
    }
    $start_from = ($page - 1) * $results_per_page;
    $sql = "SELECT * FROM offers WHERE offers.tname = '" . $_SESSION['Spotamon']['uname'] . "' AND accepted = 0 ORDER BY oid DESC LIMIT $start_from," . $results_per_page;
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $sqlcnt = "SELECT COUNT(OID) AS total FROM offers";
    $resultcnt = $conn->query($sqlcnt);
    $row = $resultcnt->fetch_assoc();
    $total_pages = ceil($row["total"] / $results_per_page);
    ?>
                                <h3 style="text-align:center;"><strong>My Available Trades:</strong></h3>
                                <center>
                                    <!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
                                    <table id="spotted" class="table table-bordered">
                                        <?php
if ($sess->get('uname',null)) {?>
                                        <tr>
                                            <th>#</th>
                                            <th>OFFERED POKEMON</th>
                                            <th>REQUESTED POKEMON</th>
                                            <th>CITY TO TRADE</th>
                                            <th>STATUS</th>
                                            <th>DATE</th>
                                        </tr>
                                        <?php } else {?>
                                        <tr>
                                            <th>#</th>
                                            <th>OFFERED POKEMON</th>
                                            <th>REQUESTED POKEMON</th>
                                            <th>CITY TO TRADE</th>
                                            <th>STATUS</th>
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
        if ($sess->get('uname',null) != null) {?>
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
                                            <td><img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$reqmon?>.png"
                                                    title="<?=$reqmon?> (#<?=$reqmon?>)" height="24" width="28">
                                                <p style="padding-top:6%;">
                                                    <?=$reqmon?>
                                                </p>
                                            </td>
                                            <td>
                                                <?=$tradeloc?>
                                            </td>
                                            <td style='text-align:center; color:green;'>AVAILABLE</td>
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
<?php }