<?php
require_once 'initiate.php';


?>

	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
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

	</script>
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
$result = $conn->query("SELECT * FROM pokedex");
$id = $pokemon = $cp = $iv = $monster = $spotter = $opentrade = "";
if (isset($_SESSION['Spotamon']['uname'])) {?>
					<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
					<h3 style="text-align:center;"><strong>Offer a Trade:</strong></h3>
					<form id="usersubmit" method="post" action="<?=W_FUNCTIONS?>offertrade.php">
						<center>
							<table id="added" class="table table-bordered">
								<tbody>
									<!--///////////////////// GENERATE MONSTER LIST \\\\\\\\\\\\\\\\\\\\\-->
									<tr>
										<td style="width: 5%;">Offer Pokemon</td>
										<td style="width: 10%;">
											<select id='pokesearch' name='offmon'>
												<?php
while ($row = $result->fetch_assoc()) {
	unset($id, $monster);
	$id = $row['id'];
	$monster = $row['monster'];
	echo '<option value="' . $id . '">' . $id . ' - ' . $monster . '</option>';
}?>
											</select>
											<?php

	?>
										</td>
									</tr>
									<!--///////////////////// Cp enter \\\\\\\\\\\\\\\\\\\\\-->
									<tr>
										<td style="width: 5%;">CP</td>
										<td style="width: 10%;">
											<input type="number" name="cp" min="10" max="4760" value="10" class="cpinput"><span
												id="cpoutput"></span>
										</td>
									</tr>
									<tr>
										<td style="width: 5%;">IV in %</td>
										<td style="width: 10%;">
											<input type="number" name="iv" min="1" max="100" value="1" class="cpinput"><span
												id="cpoutput"></span>
										</td>
									</tr>
									<tr>
										<td style="width: 5%;">Pokemon Catch Location</td>
										<td style="width: 10%;">
											<input type="text" name="cloc" placeholder="City" class="cloc"><span id="cloc"></span>
										</td>
									</tr>
									<tr>
										<td style="width: 5%;">Variance:</td>
										<td style="width: 10%;">
											Shiny: <input type="checkbox" id="shiny" name="shiny"><span id="shiny"></span>
											Alolan: <input type="checkbox" id="alolan" name="alolan"><span id="alolan"></span>
										</td>
									</tr>
									</tr>
									<tr>
										<td style="width: 5%;">Preferred Trade City</td>
										<td style="width: 10%;">
											<input type="text" name="tradeloc" placeholder="City" class="tradeloc"><span
												id="tradeloc"></span>
										</td>
									</tr>
									<tr>
										<td style="width: 5%;">Request Pokemon</td>
										<td style="width: 10%;">
											<center>
												Open to Trades: <input type="checkbox" id="myCheck" name="opentrade"
													onclick="myFunction()">
												<div id="textbox">
													<small>** Lets users make offers **</small>
												</div>
											</center>
											<div id="opendefine">
												<?php

	$result1 = $conn->query("SELECT * FROM pokedex");?>
												<select id='pokesearch2' name='reqmon'>
													<?php
while ($row = $result1->fetch_assoc()) {
		unset($id, $monster);
		$id = $row['id'];
		$monster = $row['monster'];
		echo '<option value="' . $id . '">' . $id . ' - ' . $monster . '</option>';
	}?>
												</select>
												<?php

	?>
											</div>
										</td>
									</tr>
									<tr>
										<td style="width: 5%;">Notes</td>
										<td style="width: 10%;">
											<input type="text" name="notes" placeholder="My Extra Shiny" class="notes"><span
												id="notes"></span>
										</td>
									</tr>
									<script>
										function myFunction() {
// Get the checkbox
var checkBox = document.getElementById("myCheck");
// Get the output text
var text = document.getElementById("opendefine");
// If the checkbox is checked, display the output text
if (checkBox.checked == true){
text.style.display = "none";
} else {
text.style.display = "block";
}
var text = document.getElementById("textbox");
// If the checkbox is checked, display the output text
if (checkBox.checked == true){
text.style.display = "block";
} else {
text.style.display = "none";
}
}
</script>
									<!--///////////////////// fORM SUBMIT BUTTON \\\\\\\\\\\\\\\\\\\\\-->
									<center>
										<td style="width:10%;"><input type="submit" id="offtrade" value="OFFER!"></td>
									</center>
								</tbody>
							</table>
						</center>
					</form>
					<?php } else {?>
					<center>
						<whoa wtdiv style='margin-top:10px;'>
							Login to spot a pokemon
							<br /><br /><a href="#" id="login-link" data-toggle="modal" data-target="#auth-modal">
								<i class="fas fa-sign-in-alt"></i> Login or Register Here</a>
							</div>
					</center>
					<?php }
?>
