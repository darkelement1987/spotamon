<?php
require_once 'initiate.php';



?>

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


<?php

$result = $conn->query("SELECT * FROM gyms,teams WHERE gyms.gteam = teams.tid");
    $gid = $gname = $gteam = $eggby = "";
    if (isset($_SESSION['Spotamon']['uname'])) {
        ?>
                <!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
                <h3 style="text-align:center;"><strong>Spot Egg:</strong></h3>
                <form id="usersubmit" method="post" action="./spotegg.php">
                    <center>
                        <table id="added" class="table table-bordered">
                            <tbody>
                                <!--///////////////////// GENERATE MONSTER LIST \\\\\\\\\\\\\\\\\\\\\-->
                                <tr>
                                    <td style="width: 5%;">Gym</td>
                                    <td style="width: 10%;">
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
                                <tr>
                                    <td style="width: 5%;">Hatches in</td>
                                    <td style="width: 10%;">
                                        <style>
                                            .slideregg
{
    width: 100% !important;
}
</style>
                                        <input type="range" name="etime" min="0" max="60" value="0" id="etimerange"
                                            class="slideregg"><span id="etimeoutput"></span>
                                        <script>
                                            var slideregg = document.getElementById("etimerange");
var output = document.getElementById("etimeoutput");
output.innerHTML = "<br>Egg hatches in: " + slideregg.value + " minutes</center>";
slideregg.oninput = function() {
  output.innerHTML = "<br>Egg hatches in: " + this.value + " minutes</center>";
}
</script>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%;">Egg Lvl</td>
                                    <td style="width: 10%;">
                                        <select id='eggsearch' name='egg'>
                                            <option value="1">LVL 1</option>
                                            <option value="2">LVL 2</option>
                                            <option value="3">LVL 3</option>
                                            <option value="4">LVL 4</option>
                                            <option value="5">LVL 5</option>
                                            <option value="6">EX RAID</option>
                                        </select>
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
                    <div style='margin-top:10px;'>
                        Login to spot an Egg
                        <br /><br /><a href="#" id="login-link" data-toggle="modal" data-target="#auth-modal">
                            <i class="fas fa-sign-in-alt"></i> Login or Register Here</a>
                    </div>
                </center>
                <?php }
?>


