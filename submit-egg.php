<?php
require_once 'initiate.php';
include S_FUNCTIONS . 'menu.php';

?>

<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.8.3.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<script>
		$(document).ready(function() {
			$("#gymsearch").select2({
				templateResult: formatState,
				sorter: sortresults,
				width: '100%'
			});
		});

		function formatState(state) {
			if (!state.id) {
				return state.text;
			}
			var $state = $(
				'<span ><img style="display: inline-block;" src="<?=W_ASSETS?>/gyms/' +
				state.element.label + '.png" heigth="24" width="24"/> ' + state.text + '</span>'
			);
			return $state;
		}

		$(document).ready(function() {
			$("#eggsearch").select2({
				templateResult: formatState2,
				width: '100%'
			});
		});

		function sortresults(state) {
			return state.sort(function(a, b) {
				if (a.text > b.text) {
					return 1;
				}
				if (a.text < b.text) {
					return -1;
				}
				return 0;
			});
		}

		function formatState2(state) {
			if (!state.id) {
				return state.text;
			}
			var $state = $(
				'<span ><img style="display: inline-block;" src="<?=W_ASSETS?>/eggs/' +
				state.element.value.toLowerCase() + '.png" heigth="24" width="24"/> ' + state.text + '</span>'
			);
			return $state;
		}

	</script>
</head>

<?php
include_once S_FUNCTIONS . 'menu.php';;

eggsubmission();
?>

</body>

<footer></footer>
