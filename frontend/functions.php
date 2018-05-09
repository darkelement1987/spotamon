<?php
function formsubmission(){
$pokemon = $cp = $hour = $min = $ampm = $monster = $dexentry = $latitude = $longitude = $address ="";?>
<h2 style="text-align:center;"><strong>Add spot:</strong></h2>
<form id="usersubmit" method="post" action="spot.php">
<center><table style="width: 20%; height: auto;" cellspacing="5" cellpadding="5">
<tbody>

<tr>
<td style="width: 5%;;">Pokemon</td>
<td style="width: 10%;">  
<?php
require('config/config.php');
$result = $conn->query("SELECT * FROM pokedex");

echo "<select name='pokemon'>";
    while ($row = $result->fetch_assoc()) {
        unset($id, $pokemon);
            $dexentry = $row['dexentry'];
                $monster= $row['monster']; 
					echo '<option value="#'.$dexentry.' - '.$monster.'">'.$dexentry.' - '.$monster.'</option>';
						
}					
	echo "</select>";
	mysqli_close($conn);
?>

</td>
</tr>

<tr>
<td style="width: 5%;;">CP</td>
<td style="width: 10%;">
	<input type="text" name="cp" placeholder="2500">
</td>
</tr>

<tr>
<td style="width: 5%;;">Time Found</td>
<td style="width: 10%;">

<?php 
	if ($clock=="false"){ ?>
	<select name="hour">
		<?php
			for($i=1; $i<=12; $i++){
			echo "<option value=".$i.">".$i."</option>";}
		?>
		<option name="hour"> </option>   
	</select> 
	
	<select name="min">
		<?php
			for($i=0; $i<=60; $i++){
				$value = str_pad($i,2,"0",STR_PAD_LEFT);
			echo "<option value=".$value.">".$value."</option>";}
		?>
		<option name="min"> </option>   
	</select> 
	
	<select name="ampm">
		<option value="AM/PM" disabled selected hidden>AM/PM</option>
		<option value="AM">AM</option>
		<option value="PM">PM</option>
	</select>
	<?php 
	} 
	else
	{
	?>
	<select name="hour">
		<?php
			for($i=0; $i<=24; $i++){
			echo "<option value=".$i.">".$i."</option>";}
		?>
		<option name="hour"> </option>   
	</select> 
	
	<select name="min">
		<?php
			for($i=0; $i<=60; $i++){
				$value = str_pad($i,2,"0",STR_PAD_LEFT);
			echo "<option value=".$value.">".$value."</option>";}
		?>
		<option name="min"> </option>   
	</select> 
	<?php } ?>
</td>
</tr>

<tr>
<td style="width: 5%;">Address or Park</td>
<td style="width: 10%;">

<p>Click the button to get your coordinates.</p>
<p id="ScanLocation"></p>

<script>
var x = document.getElementById("ScanLocation");

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    x.innerHTML = "<input name='latitude' value='" + position.coords.latitude + "'></input><input name='longitude' value='" + position.coords.longitude + "'></input>";
	
	
}
</script>

<button type="button" onclick="getLocation()">Get Location</button>

<p><input type="text" name="address" placeholder="Enter Address or Park"></p>
</td>
</tr>

<center><td style="width:100%;"><input type="submit" value="SPOT!"/></td></center>

</tbody>
</table></center>
</form>

<?php } 

function spottedpokemon(){?>

<h2 style="text-align:center;"><strong>Spotted Pokemon:</strong></h2>
<?php
require('config/config.php');
$sql = "SELECT * FROM spots ORDER BY id DESC";
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));
?>
<div class="table">
<center>
<?php

echo "<table>";
echo "<tr><th>POKEMON</th><th>CP</th><th>TIME FOUND</th><th>LOCATION</th></tr>";

while($row = mysqli_fetch_array($result)) {
    $pokemon = $row['pokemon'];
    $cp = $row['cp'];
	$hour = $row['hour'];
	$min = $row['min'];
	$ampm = $row['ampm'];
	$latitude = $row['latitude'];
	$longitude = $row['longitude'];
	$address = $row['address'];
	$minutes = $min;
	if ($min < 10) {
    $minutes = str_pad($min, 2, "0", STR_PAD_LEFT);
	}
	
	if ($clock=="false"){ 
	
    echo "
	<tr>
	<td>".$pokemon."</td>
	<td>".$cp."</td>
	<td>".$hour.":".$minutes." ".$ampm."</td>
	<td>"?><a href="http://maps.google.com/maps?q=<?php echo "".$latitude,",".$longitude.""?>"> <?php echo "".$address."" ?></a><?php "</td>
	</tr>
	";
	
	} else {
		
	echo "
	<tr>
	<td>".$pokemon."</td>
	<td>".$cp."</td>
	<td>".$hour."".$minutes."</td>
	<td>"?><a href="http://maps.google.com/maps?q=<?php echo "".$latitude,",".$longitude.""?>"> <?php echo "".$address."" ?></a><?php "</td>
	</tr>";
} }

echo "</table>";
?>
</center></div>
<?php
mysqli_close($conn);

}?>
