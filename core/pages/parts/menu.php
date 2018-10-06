<?php
require_once 'initiate.php';

if ($sess->get('uname', null) === null) {
    include_once S_PAGES . 'parts/authmodal.php';
}?>
    <!--end auth container -->
    <!-- Mobile Header -->
    <div class="wsmobileheader clearfix">
        <a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>
        <span class="smllogo"><img src="<?=W_ASSETS?>img/spotamon.png" width="98" alt="" /></span>
    </div>
    <!-- Mobile Header -->




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
        var w_root = '<?=W_ROOT?>';
    </script>



    </script>
    <p class="bodyspacer"></p>
