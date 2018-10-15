<?php
require_once 'initiate.php';

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
require_once './config/config.php';
    $result = $conn->query("SELECT * FROM pokedex");
    $id = $pokemon = $cp = $iv = $hour = $min = $ampm = $monster = $latitude = $longitude = $fulladdress = $spotter = "";
    if (isset($_SESSION['Spotamon']['uname'])) {?>
<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h3 style="text-align:center;"><strong>Add Pokémon:</strong></h3>
<form id="usersubmit" method="post" action="./spotpokemon.php">
    <center>
        <table id="added" class="table table-bordered">
            <tbody>
                <!--///////////////////// GENERATE MONSTER LIST \\\\\\\\\\\\\\\\\\\\\-->
                <tr>
                    <td style="width: 5%;">Pokemon</td>
                    <td style="width: 10%;">
                        <select id='pokesearch' name='pokemon'>
        <?php
while ($row = $result->fetch_assoc()) {
        unset($id, $monster);
        $id = $row['id'];
        $monster = $row['monster'];
        ?>
                            <option value="<?=$id?>">
                                <?=$id?>-<?=$monster?>
                            </option>
                            <?php }?>
                        </select>
        <?php
mysqli_close($conn);
        ?>
                    </td>
                </tr>
                <!--//////////////////php/// Cp enter \\\\\\\\\\\\\\\\\\\\\-->
                <tr>
                    <td style="width: 5%;">CP</td>
                    <td style="width: 10%;">
                        <input type="number" name="cp" min="10" max="4760" value="10" class="cpinput"><span id="cpoutput"></span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">IV in %</td>
                    <td style="width: 10%;">
                        <input type="number" name="moniv" min="1" max="100" value="1" class="cpinput"><span id="cpoutput"></span>
                    </td>
                </tr>
                <!--///////////////////// ADDRESS \\\\\\\\\\\\\\\\\\\\\-->
                <tr>
                    <td style="width: 5%;">Location</td>
                    <td style="width: 10%;">
                    <div id="ScanLocation"><p>Click the button to get your coordinates.</p>
                        <button type="button" onclick="getLocation();enablespotbutton()">Get Location</button>
                    </div>
                    </td>
                </tr>
                <!--///////////////////// fORM SUBMIT BUTTON \\\\\\\\\\\\\\\\\\\\\-->
                <center>
                    <td style="width:10%;"><input type="submit" id="spotbutton" value="SPOT!" disabled /></td>
                </center>
            </tbody>
        </table>
    </center>
</form>
<?php } else {?>
<center>
    <div style='margin-top:10px;'>
        Login to spot a pokemon
        <br />
        <br />
        <a href="#" id="login-link" data-toggle="modal" data-target="#auth-modal">
            <i class="fas fa-sign-in-alt"></i> Login/Register Here</a>
    </div>
</center>

<?php } ?>
