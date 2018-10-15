<?php
require_once 'initiate.php';
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include_once S_PAGES . 'parts/meta.php';
    include_once S_PAGES . 'parts/js.php';
    ?>
</head>

<body>
    <div id="menu-wrapper">
    <?php include_once S_PAGES . 'parts/menu.php'; ?>
</div>
<?php
    if (!empty($Validate->get->pg)) {
        $pageurl = $Validate->get->pg;
        if (file_exists(S_PAGES . $pageurl . '.php')) { ?>
            <div id="content" data-page="<?=$pageurl?>">
            <?php
            include_once S_PAGES . $pageurl . '.php';
        } else if (function_exists($pageurl)) { ?>
            <div id="content" data-page="<?=$pageurl?>">
            <?php
            $pageurl();
        } else { ?>
            <div id="content" data-page="map">
            <?php
            include_once S_PAGES . 'map.php';
        }
    } else { ?>
        <div id="content" data-page="map">
        <?php
            include_once S_PAGES . 'map.php';
        } ?>
    </div>





    <script>
if (typeof initMap == 'function') {

    $( document ).ready(function() {
        initMap()
    });
}
    </script>
</body>
</html>
<?php
if($conn) {
$conn->close();
}
?>
