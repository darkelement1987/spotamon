<?php
require_once 'initiate.php';
?>

<!DOCTYPE html>
<html>

<head>
    <?php include_once S_PAGES . 'parts/meta.php'; ?>
</head>

<body>
    <?php include_once S_PAGES . 'parts/menu.php'; ?>

    <script>
        function submitInstinct() {
            document.postInstinct.submit();
        }

        function submitValor() {
            document.postValor.submit();
        }

        function submitMystic() {
            document.postMystic.submit();
        }

    </script>

    <?php
    if (!empty($Validate->get->pg)) {
        $pageurl = $Validate->get->pg;
        if (file_exists(S_PAGES . $pageurl . '.php')) {
            include_once S_PAGES . $pageurl . '.php';
        } else if (function_exists($pageurl)) {
            $pageurl();
        } else {
            maps();
        }
    } else {
        maps();
    }


    ?>


<?php include_once S_PAGES . 'parts/js.php'; ?>
    <script>
if (typeof initMap == 'function') {

    $( document ).ready(function() {
        initMap()
    });
}
    </script>
</body>
</html>
