<?php



?>

	<script>
		$(document).ready(function() {
			$("#raidsearch").select2({
				templateResult: formatState1,
				width: '100%'
			});
		});

		function formatState1(state) {
			if (!state.id) {
				return state.text;
			}
			var $state = $(
				'<span ><img style="display: inline-block;" src="<?=W_ASSETS?>icons/' +
				state.element.value.toLowerCase() + '.png" heigth="24" width="24"/> ' + state.text + '</span>'
			);
			return $state;
		}
		$(document).ready(function() {
			$("#gymsearch").select2({
				templateResult: formatState2,
				sorter: sortresults,
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
				'<span ><img style="display: inline-block;" src="<?=W_ASSETS?>/gyms/' +
				state.element.label + '.png" heigth="24" width="24"/> ' + state.text + '</span>'
			);
			return $state;
		}

	</script>

<?php
if (isset($_SESSION["uname"])) {
	?>
			<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
			<h3 style="text-align:center;"><strong>Add EX Raid:</strong></h3>
			<form id="usersubmit" method="post" action="./spotexraid.php">
				<center>
					<table id="added" class="table table-bordered">
						<tbody>
							<tr>
								<td style="width: 5%;">At Gym</td>
								<td style="width: 10%;">
									<?php
require './config/config.php';
	$result = $conn->query("SELECT * FROM gyms,teams WHERE gyms.gteam = teams.tid");
	$gid = $gname = $gteam = "";?>
									<select id='gymsearch' name='gname'>
										<?php
while ($row = $result->fetch_assoc()) {
		unset($gid, $gname);
		$gid = $row['gid'];
		$tid = $row['tname'];
		$gname = $row['gname'];
		$gteam = $row['gteam'];
		echo '<option value="' . $gid . '" label="' . $gteam . '">' . $gname . '</option>';
	}?>
									</select>
								</td>
							</tr>
							<!--///////////////////// DATE & TIME \\\\\\\\\\\\\\\\\\\\\-->
							<tr>
								<td style="width: 5%;">Date & Time:</td>
								<td style="width: 10%;">
									<input type="datetime-local" name="exraiddate">
								</td>
							</tr>
							<!--///////////////////// fORM SUBMIT BUTTON \\\\\\\\\\\\\\\\\\\\\-->
							<center>
								<td style="width:10%;"><input type="submit" value="SPOT!" /></td>
							</center>
						</tbody>
					</table>
				</center>
			</form>
			<?php } else {?>
			<center>
				<div style='margin-top:5%;'>
					Login to spot a Raid
					<br />
					<br />
					<a href="#" id="login-link" data-toggle="modal" data-target="#auth-modal">
						<i class="fas fa-sign-in-alt"></i> Login or Register Here</a>
				</div>
			</center>
			<?php }
?>
</body>

<footer></footer>
