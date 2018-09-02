<?php
require_once 'initiate.php';
include S_FUNCTIONS . 'menu.php';
?>
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

    maps();

    ?>

	
    
    <script>
    $( document ).ready(function() {
        initMap()
    })
    </script>
    </body>
	<footer></footer>
