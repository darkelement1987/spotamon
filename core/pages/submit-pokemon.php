<?php
?>

<script>
		$(document).ready(function() {
			$("#pokesearch").select2({
				templateResult: formatState,
				width: '100%'
			});
		});

		function formatState(state) {
			if (!state.id) {
				return state.text;
			}
			var $state = $(
				'<span ><img style="display: inline-block;" src="<?=W_ASSETS?>icons/' +
				state.element.value.toLowerCase() + '.png" heigth="24" width="24"/> ' + state.text + '</span>'
			);
			return $state;
		}

		function enablespotbutton() {
			document.getElementById("spotbutton").disabled = false;
		}

	</script>


<?php

pokesubmission();
?>

</body>

<footer></footer>
