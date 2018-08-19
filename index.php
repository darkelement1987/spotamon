<?php
require_once 'initiate.php';
include  S_FUNCTIONS . 'functions.php';
include S_FUNCTIONS . 'menu.php';
?>
<!doctype html>
<html lang="en">


<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    include_once S_FUNCTIONS . 'menu.php';;

    maps();

    ?>

	
    
    <script>
    $( document ).ready(function() {
        initMap()
    })
    </script>
    </body>
	<footer></footer>
