<?php

require_once 'initiate.php';


?>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

	<script>
		$(document).ready(function() {
			$("#pokesearch2").select2({
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

	</script>

<?php
if (isset($_GET['oid'])) {
	$oid = $_GET['oid'];
	$selectquery = "SELECT * FROM offers WHERE oid='$oid'";
	$selectresult = $conn->query($selectquery);
	$row = $selectresult->fetch_array(MYSQLI_NUM);
} else {
	$oid = $conn->real_escape_string($_POST['oid']);
}
$sql2 = "SELECT * FROM offers WHERE oid='$oid'";
$result = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($result)) {
	$oid = $row['oid'];
	$offmon = $row['offmon'];
	$tradeloc = $row['tradeloc'];
	$cp = $row['cp'];
	$iv = $row['iv'];
	$offerby = $row['tname'];
	$shiny = $row['shiny'];
	$alolan = $row['alolan'];
	$rname = $_SESSION['Spotamon']['uname'];
}
?>
									<h3 style="text-align:center;"><strong>Make an Offer:</strong></h3>
									<form id="usersubmit" method="post" action="<?=S_ROOT?>core/functions/trading/makeoffer.php">
										<center>
											<table id="added" class="table table-bordered">
												<tbody>
													<tr>
														<td style="width: 5%; text-align:center;">ID:</td>
														<td style="width: 10%; text-align:center;">
															<?=$oid?>
														</td>
													</tr>
													<tr>
														<td style="width: 5%; text-align:center;">Offered By:</td>
														<td style="width: 10%; text-align:center;">
															<?=$offerby?>
														</td>
													</tr>
													<tr>
														<td style="width: 5%; text-align:center;">Location
															Preference :</td>
														<td style="width: 10%; text-align:center;">
															<?=$tradeloc?>
														</td>
													</tr>
													<tr>
														<td style="width: 5%; text-align:center;vertical-align:middle;">Offered
															Pokemon:</td>
														<td style="width: 10%; text-align:center;vertical-align:middle;">
															<img src="<?=W_ASSETS?>.png" height=50; width=55;>
														</td>
													</tr>
													<tr>
														<td style="width: 5%; text-align:center;vertical-align:middle;">Stats:</td>
														<td style="width: 10%; text-align:center;vertical-align:middle;">
															CP:
															<?=$cp?>&nbsp;&nbsp;&nbsp;
															IV:
															<?=$iv?> %
														</td>
													</tr>
													<tr>
														<td style="width: 5%; text-align:center;vertical-align:middle;">Variance:</td>
														<td style="width: 10%; text-align:center;vertical-align:middle;">
															Shiny:
															<?php if ($shiny == 1) {
	echo " Yes";
} else {
	echo " No";
}?>&nbsp;&nbsp;&nbsp;
															Alolan:
															<?php if ($alolan == 1) {
	echo " Yes";
} else {
	echo " No";
}?>
														</td>
													</tr>
													<tr>
														<td style="width: 5%; text-align:center;vertical-align:middle;">Counter
															offer:</td>
														<td style="width: 10%; text-align:center;vertical-align:middle;">
															<?php

$result1 = $conn->query("SELECT * FROM pokedex");
?>
															<select id='pokesearch2' name='coffmon'>
																<?php
while ($row = $result1->fetch_assoc()) {
	unset($id, $monster);
	$id = $row['id'];
	$monster = $row['monster'];?>
																<option value="<?=$id?>"><img src="<?=W_ASSETS?>icons/<?=$id?>.png">'
																	<?=$id?>-
																	<?=$monster?>
																</option>
																<?php }?>
															</select>
															<?php

?>
														</td>
													</tr>
													<tr>
														<td style="width: 5%;text-align:center;">CP</td>
														<td style="width: 10%;text-align:center;">
															<input type="number" name="ccp" min="10" max="4760"
																value="10" class="cpinput"><span id="cpoutput"></span>
														</td>
													</tr>
													<tr>
														<td style="width: 5%;text-align:center;">IV in %</td>
														<td style="width: 10%;text-align:center;">
															<input type="number" name="civ" min="1" max="100" value="1"
																class="cpinput"><span id="cpoutput"></span>
														</td>
													</tr>
													<tr>
														<td style="width: 5%; text-align:center;">Variance:</td>
														<td style="width: 10%; text-align:center;">
															Shiny: <input type="checkbox" id="cshiny" name="cshiny"><span
																id="cshiny"></span>
															Alolan: <input type="checkbox" id="calolan" name="calolan"><span
																id="calolan"></span>
														</td>
													</tr>
													<tr>
														<td></td>
														<td style="width:10%;">
															<center><input type='hidden' name='offerby' value='<?=$offerby?>' /><input
																	type='hidden' name='oid' value='<?=$oid?>' /><input
																	type="submit" id="coffer" value="OFFER!"></center>
														</td>
													</tr>
												</tbody>
											</table>
										</center>
									</form>

