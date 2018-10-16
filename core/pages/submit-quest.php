<?php
require_once 'initiate.php';



?>

	<script>
		$(document).ready(function() {
			$("#questsearch").select2({
				templateResult: formatState1,
				width: '100%'
			});
		});

		function formatState1(state) {
			if (!state.id) {
				return state.text;
			}
			var $state = $(
				'<span ><img style="display: inline-block;" src="<?=W_ASSETS?>quests/' +
				state.element.label.toLowerCase() + '.png" heigth="24" width="24"/> ' + state.text + '</span>'
			);
			return $state;
		}
		$(document).ready(function() {
			$("#pokestopsearch").select2({
				templateResult: formatState2,
				width: '100%'
			});
		});

		function formatState2(state) {
			if (!state.id) {
				return state.text;
			}
			var $state = $(
				'<span ><img style="display: inline-block;" src="<?=W_ASSETS?>stops/stops.png" heigth="24" width="24"/> ' +
				state.text + '</span>'
			);
			return $state;
		}
		$(document).ready(function() {
			$("#rewardsearch").select2({
				templateResult: formatState3,
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

		function formatState3(state) {
			if (!state.id) {
				return state.text;
			}
			var $state = $(
				'<span ><img style="display: inline-block;" src="<?=W_ASSETS?>rewards/' +
				state.element.label.toLowerCase() + '.png" heigth="24" width="24"/> ' + state.text + '</span>'
			);
			return $state;
		}

	</script>
</head>

<?php
$result = $conn->query("SELECT * FROM quests");
$qid = $qname = $spotter = "";
if (isset($_SESSION['Spotamon']['uname'])) {
	?>
	<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
	<h3 style="text-align:center;"><strong>Submit a Quest:</strong></h3>
	<form id="usersubmit" method="post" action="./spotquest.php">
		<center>
			<table id="added" class="table table-bordered">
				<tbody>
					<!--///////////////////// GENERATE QUEST & REWARD LIST \\\\\\\\\\\\\\\\\\\\\-->
					<tr>
						<td style="width: 5%;">Quests</td>
						<td style="width: 10%;">
							<select id='questsearch' name='quest'>
				<?php
while ($row = $result->fetch_assoc()) {
		unset($qid, $qname);
		$qid = $row['qid'];
		$qname = $row['qname'];
		$array[$row['type']][] = $row;
	}
	// loop the array to create optgroup
	foreach ($array as $key => $value) {
		// check if its an array
		if (is_array($value)) {
			// create optgroup for each groupname ?>
								<optgroup label="<?=$key?>">
				<?php
foreach ($value as $k => $v) {?>
									<option label="<?=$v['type']?>" value="<?=$v['qid']?>">
										<?=$v['qname']?>
									</option>
									<?php }?>
								</optgroup>
								<?php }
	}
	?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="width: 5%;">Rewards</td>
						<td style="width: 10%;">
							<?php

	$result2 = $conn->query("SELECT * FROM rewards");
	$reid = $rname = "";?>
							<select id='rewardsearch' name='reward'>
								<?php
while ($row2 = $result2->fetch_assoc()) {
		unset($reid, $rname);
		$reid = $row2['reid'];
		$rname = $row2['rname'];
		$array2[$row2['type']][] = $row2;
	}
	// loop the array to create optgroup
	foreach ($array2 as $key => $value) {
		// check if its an array
		if (is_array($value)) {
			// create optgroup for each groupname
			echo "<optgroup label='" . $key . "'>";
			foreach ($value as $k => $v) {
				echo "<option label='" . $v['type'] . "' value='" . $v['reid'] . "'>'" . $v['rname'] . "'</option>";
			}?>
								</optgroup>
								<?php }
	}?>
							</select>
						</td>
					</tr>
					<!--///////////////////// ADDRESS \\\\\\\\\\\\\\\\\\\\\-->
					<tr>
						<td style="width: 5%;">At Pokestop</td>
						<td style="width: 10%;">
							<?php
$result = $conn->query("SELECT * FROM stops");
	$sid = $sname = $sname = "";?>
							<select id='pokestopsearch' name='sname'>
								<?php
while ($row = $result->fetch_assoc()) {
		unset($sid, $sname);
		$sid = $row['sid'];
		$sname = $row['sname'];
		echo '<option value="' . $sid . '">' . $sname . '</option>';
	}?>
							</select>
						</td>
					</tr>
					<!--///////////////////// fORM SUBMIT BUTTON \\\\\\\\\\\\\\\\\\\\\-->
					<center>
						<td style="width:10%;">
							<input type="submit" value="SPOT!" />
						</td>
					</center>
				</tbody>
			</table>
		</center>
	</form>
	<?php } else {?>
	<center>
		<div style='margin-top:10px;'>
			Login to spot a Quest
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
