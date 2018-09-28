<?php
require_once 'initiate.php';

if ($Validate->getSession('uname') === null) {
    include S_PAGES . 'parts/authmodal.php';
}?>
    <!--end auth container -->
    <!-- Mobile Header -->
    <div class="wsmobileheader clearfix">
        <a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>
        <span class="smllogo"><img src="<?=W_ASSETS?>img/spotamon.png" width="98" alt="" /></span>
    </div>
    <!-- Mobile Header -->

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.6-rc.1/dist/js/select2.min.js" integrity="sha256-190Fv8aJAduyyIOnvWVpjCmzkX1h8OEtGWbcoU1QVsA="
        crossorigin="anonymous">
    </script>


    </script>
    <?php if ($disablemotd != true) {
    if ($motdalways == true) {?>
    <script>
        swal({
            title: '<?=$motdtitle?>',
            html: '<?=$motdtext?>',
            imageUrl: '<?=$motdimage?>',
            imageHeight: '<?=$imageheight?>',
            imageWidth: '<?=$imagewidth?>',
            width: '<?=$motdwidth?>'
        })

    </script>
    <?php } else if ($motdalways == false) {?>

    <?php if (!isset($_SESSION["uname"])) {?>
    <script>
        swal({
            title: '<?=$motdtitle?>',
            html: '<?=$motdtext?>',
            imageUrl: '<?=$motdimage?>',
            imageHeight: '<?=$imageheight?>',
            imageWidth: '<?=$imagewidth?>',
            width: '<?=$motdwidth?>'
        })

    </script>
    <?php }
    }
}?>


    <!--conatiner start -->
    <div class="container-fluid p-0" id="menu-container">
        <?php
include S_PAGES . 'parts/nav.php';
?>
    </div>
    <!--container end -->
    <script>
        var w_root = '<?=W_DOMAIN?>';
    </script>



    </script>
    <p class="bodyspacer"></p>
