<?php
include './frontend/functions.php';
include './frontend/menu.php';
include './config/dbbuilding.php';
?>

<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://cdn.klokantech.com/maptilerlayer/v1/index.js"></script>
<script>

function submitInstinct(){
	document.postInstinct.submit();
	}
	function submitValor(){
		document.postValor.submit();
		}
		function submitMystic(){
			document.postMystic.submit();
			}
			</script>

<?php
menu();

maps();
?>

<footer></footer>

