<?php
require_once 'initiate.php';




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
</head>
<?php
$result = $conn->query("SELECT * FROM raidbosses");
    $rid = $rboss = $rlvl = $rhour = $rmin = $rampm = $spotter = "";
    if (isset($_SESSION['Spotamon']['uname'])) {
        ?>
    <!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
    <h3 style="text-align:center;"><strong>Add Raid:</strong></h3>
    <form id="usersubmit" method="post" action="./spotraid.php">
        <center>
            <table id="added" class="table table-bordered">
                <tbody>
                    <!--///////////////////// GENERATE BOSS LIST \\\\\\\\\\\\\\\\\\\\\-->
                    <tr>
                        <td style="width: 5%;">Raid Boss</td>
                        <td style="width: 10%;">
                            <select id='raidsearch' name='rboss'>
                    <?php
while ($row = $result->fetch_assoc()) {
            unset($rid, $rboss);
            $rid = $row['rid'];
            $rboss = $row['rboss'];
            echo '<option value="' . $rid . '">' . $rid . ' - ' . $rboss . '</option>';
        }
        echo "</select>";

        ?>
                        </td>
                    </tr>
                    <!--///////////////////// TIME OF FIND \\\\\\\\\\\\\\\\\\\\\-->
                    <tr>
                        <td style="width: 5%;">Minutes until expire:</td>
                        <td style="width: 10%;">
                            <style>
                                .sliderraid {
                                    width: 100% !important;
                                }
                            </style>
                            <input type="range" name="rtime" min="0" max="45" value="0" id="rtimerange" class="sliderraid"><span
                                id="rtimeoutput"></span>
                            <script>
                                var sliderraid = document.getElementById("rtimerange");
                                var output = document.getElementById("rtimeoutput");
                                output.innerHTML = "<br>Raid ends in: " + sliderraid.value + " minutes</center>";
                                sliderraid.oninput = function () {
                                    output.innerHTML = "<br>Raid ends in: " + this.value + " minutes</center>";
                                }
                            </script>
                        </td>
                    </tr>
                    <!--///////////////////// ADDRESS \\\\\\\\\\\\\\\\\\\\\-->
                    <tr>
                        <td style="width: 5%;">At Gym</td>
                        <td style="width: 10%;">
                <?php

        $result = $conn->query("SELECT * FROM gyms,teams WHERE gyms.gteam = teams.tid");
        $gid = $gname = $gteam = "";
        echo "<select id='gymsearch' name='gname'>";
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
        <div style='margin-top:10px;'>Login to spot a Raid
            <br />
            <br />
            <a href="#" id="login-link" data-toggle="modal" data-target="#auth-modal">
                <i class="fas fa-sign-in-alt"></i> Login or Register Here</a>
        </div>
    </center>
    <?php } ?>

