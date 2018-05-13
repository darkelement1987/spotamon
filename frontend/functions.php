<?php
///////////////////// FORM SUBMISSION DATA \\\\\\\\\\\\\\\\\\\\\
function pokesubmission(){
require('config/config.php');
$result = $conn->query("SELECT * FROM pokedex");
$id = $pokemon = $cp = $hour = $min = $ampm = $monster = $latitude = $longitude ="";
?>


<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h2 style="text-align:center;"><strong>Add Pokémon:</strong></h2>
<form id="usersubmit" method="post" action="spot.php">
<center><table style="width: 25%; height: auto;" id="t01">
<tbody>


<!--///////////////////// GENERATE MONSTER LIST \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">Pokemon</td>
<td style="width: 10%;">
<?php
echo "<select id='pokesearch' name='pokemon'>";
while ($row = $result->fetch_assoc()) {
    unset($id, $monster);
        $id = $row['id'];
            $monster= $row['monster'];
				echo '<option value="'.$id.'">'.$id.' - '.$monster.'</option>';
					}					
						echo "</select>";
							mysqli_close($conn);
?>
</td>
</tr>

<!--///////////////////// Cp enter \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">CP</td>
<td style="width: 10%;">
	<input type="text" name="cp" value="2500">
</td>
</tr>

<!--///////////////////// TIME OF FIND \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">Time Found</td>
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
		<option value="AM/PM" selected>AM/PM</option>
		<option value="AM">AM</option>
		<option value="PM">PM</option>
	</select>
	
	<?php } else { ?>
	
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

<!--///////////////////// ADDRESS \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">Location</td>
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
    x.innerHTML = "<input name='latitude' value='" + position.coords.latitude + "' readonly></input><input name='longitude' value='" + position.coords.longitude + "' readonly></input>";


}
</script>

<button type="button" onclick="getLocation()">Get Location</button>

</td>
</tr>
<!--///////////////////// fORM SUBMIT BUTTON \\\\\\\\\\\\\\\\\\\\\-->
<center><td style="width:100%;"><input type="submit" value="SPOT!"/></td></center>

</tbody>
</table></center>
</form>

<?php } 

///////////////////// SPOTTED MONSTER TABLE \\\\\\\\\\\\\\\\\\\\\
function spottedpokemon(){
require('config/config.php');
$results_per_page = 10;

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql = "SELECT * FROM spots,pokedex WHERE spots.pokemon = pokedex.id ORDER BY date DESC LIMIT $start_from,".$results_per_page;
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));


$sqlcnt = "SELECT COUNT(ID) AS total FROM spots"; 
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>


<h2 style="text-align:center;"><strong>Spotted Pokemon:</strong></h2>

<center>

<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php

echo "<table id=\"t02\" class=\"spotted\">";
echo "<tr><th>ID</th><th>POKEMON</th><th>CP</th><th>TIME FOUND</th><th>LOCATION</th></tr>";
while($row = mysqli_fetch_array($result)) {
	$id = $row['monster'];
    $pokemon = $row['pokemon'];
    $cp = $row['cp'];
	$hour = $row['hour'];
	$min = $row['min'];
	$ampm = $row['ampm'];
	$latitude = $row['latitude'];
	$longitude = $row['longitude'];
	$minutes = $min;
	$hr = $hour;
	
	
	///////////////////// ADDS "0" TO SIGNLE DIGIT MINUTE TIMES \\\\\\\\\\\\\\\\\\\\\
	if ($min < 10) {
    $minutes = str_pad($min, 2, "0", STR_PAD_LEFT);	
	}
	
	///////////////////// 12 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
	if ($clock=="false"){
		
	///////////////////// 12 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td>".$pokemon."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="static/icons/<?php echo $pokemon?>.png" title="<?php echo $id; ?> (#<?php echo $pokemon?>)" height="24" width="24"><p style="padding-top:6%;"><?php echo $id; ?></p><?php echo "</td>
	<td>".$cp."</td>
	<td>".$hour.":".$minutes." ".$ampm."</td>
	<td>"?><a href="http://maps.google.com/maps?q=<?php echo "".$latitude,",".$longitude.""?>"><?php "</td>
	</tr>";
	
	///////////////////// GOOGLE DECODER \\\\\\\\\\\\\\\\\\\\\
	$url  = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=false";
	$json = @file_get_contents($url);
	$data = json_decode($json);
	$status = $data->status;
	$address = '';
		if($status == "OK")
		{
			echo $address = $data->results[0]->formatted_address;?></a><?php
		}
		else
		{
			echo "Cannot retrieve address";
		}
		
	} else {
	///////////////////// 24 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\

	///////////////////// ADDS "0" TO SIGNLE DIGIT HOUR TIMES \\\\\\\\\\\\\\\\\\\\\
	if ($hour < 10) {
    $hr = str_pad($hour, 2, "0", STR_PAD_LEFT);	
	}
	
	///////////////////// 24 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td>".$pokemon."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="static/icons/<?php echo $pokemon?>.png" title="<?php echo $id; ?> (#<?php echo $pokemon?>)" height="24" width="24"><p style="padding-top:6%;"><?php echo $id; ?></p><?php echo "</td>
	<td>".$cp."</td>
	<td>".$hr.":".$minutes."</td>
	<td>"?><a href="http://maps.google.com/maps?q=<?php echo "".$latitude,",".$longitude.""?>"><?php "</td>
	</tr>";
	
	///////////////////// GOOGLE DECODER \\\\\\\\\\\\\\\\\\\\\
	$url  = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=false";
	$json = @file_get_contents($url);
	$data = json_decode($json);
	$status = $data->status;
	$address = '';
		if($status == "OK")
		{
			echo $address = $data->results[0]->formatted_address;?></a><?php
		}
		else
		{
			echo "No Data Found Try Again";
		}
	
}}
echo "</table></center>";
?><center><?php

///////////////////// PAGENATION \\\\\\\\\\\\\\\\\\\\\
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."'>".$i."</a> "; 
}; 
?></center><?php
}

function maps(){
	require('config/config.php');
?>

<div id="map"></div>

<script>
var customLabel = {
  monster: {
    label: 'pokemon'
  }
};

  function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    center: new google.maps.LatLng(<?php echo $mapcenter;?>),
    zoom: 15,
    gestureHandling: 'greedy',
    fullscreenControl: true,
    streetViewControl: false,
    mapTypeControl: false,
    clickableIcons: false
  });
  var infoWindow = new google.maps.InfoWindow;

    // Change this depending on the name of your PHP or XML file
    downloadUrl('frontend/xml.php', function(data) {
      var xml = data.responseXML;
      var markers = xml.documentElement.getElementsByTagName('marker');
      Array.prototype.forEach.call(markers, function(markerElem) {
        var id = markerElem.getAttribute('id');
        var pokemon = markerElem.getAttribute('pokemon');
        var cp = markerElem.getAttribute('cp');
		var hour = markerElem.getAttribute('hour');
		var min = markerElem.getAttribute('min');
		var ampm = markerElem.getAttribute('ampm');
        var type = markerElem.getAttribute('id');
        var point = new google.maps.LatLng(
            parseFloat(markerElem.getAttribute('latitude')),
            parseFloat(markerElem.getAttribute('longitude')));

        var infowincontent = document.createElement('div');
        var strong = document.createElement('strong');
        strong.textContent = pokemon + ' (#' + id + ')'
        infowincontent.appendChild(strong);
        infowincontent.appendChild(document.createElement('br'));
		var text = document.createElement('text');
        text.textContent = 'Expires: ' + hour + ':' + min + ' ' + ampm  
        infowincontent.appendChild(text);
		infowincontent.appendChild(document.createElement('br'));
        var text = document.createElement('text');
        text.textContent = cp + ' CP'
        infowincontent.appendChild(text);
        var icon = customLabel[type] || {};
        var image = {
            url: 'static/icons/' + id + '.png',
            scaledSize: new google.maps.Size(32, 32)
        };
		
        var marker = new google.maps.Marker({
          map: map,
          position: point,
          label: icon.label,
          icon: image
        });
        marker.addListener('click', function() {
          infoWindow.setContent(infowincontent);
          infoWindow.open(map, marker);
        });
      });
    });
	
	    downloadUrl('frontend/rxml.php', function(data) {
      var xml = data.responseXML;
      var markers = xml.documentElement.getElementsByTagName('marker');
      Array.prototype.forEach.call(markers, function(markerElem) {
        var rid = markerElem.getAttribute('rid');
        var rboss = markerElem.getAttribute('rboss');
		var rlvl = markerElem.getAttribute('rlvl');
        var rcp = markerElem.getAttribute('rcp');
		var rhour = markerElem.getAttribute('rhour');
		var rmin = markerElem.getAttribute('rmin');
		var rampm = markerElem.getAttribute('rampm');
        var type = markerElem.getAttribute('rlvl');
        var point = new google.maps.LatLng(
            parseFloat(markerElem.getAttribute('rlatitude')),
            parseFloat(markerElem.getAttribute('rlongitude')));
		
		var infowincontent = document.createElement('div');
        var strong = document.createElement('strong');
        strong.textContent = 'RAID VS ' + rboss + ' (#' + rid + ')'
        infowincontent.appendChild(strong);
        infowincontent.appendChild(document.createElement('br'));
		var text = document.createElement('text');
        text.textContent = 'LVL: ' +rlvl
        infowincontent.appendChild(text);
		infowincontent.appendChild(document.createElement('br'));
		var text = document.createElement('text');
        text.textContent = 'CP: ' + rcp 
        infowincontent.appendChild(text);
		infowincontent.appendChild(document.createElement('br'));
        var text = document.createElement('text');
        text.textContent = 'Expires: ' + rhour + ':' + rmin + ' ' + rampm
        infowincontent.appendChild(text);
        var icon = customLabel[type] || {};
        var image = {
            url: 'static/icons/' + rid + '.png',
            scaledSize: new google.maps.Size(32, 32)
        };
		
        var marker = new google.maps.Marker({
          map: map,
          position: point,
          label: icon.label,
          icon: image
        });
        marker.addListener('click', function() {
          infoWindow.setContent(infowincontent);
          infoWindow.open(map, marker);
        });
      });
    });
  }



function downloadUrl(url, callback) {
  var request = window.ActiveXObject ?
      new ActiveXObject('Microsoft.XMLHTTP') :
      new XMLHttpRequest;

  request.onreadystatechange = function() {
    if (request.readyState == 4) {
      request.onreadystatechange = doNothing;
      callback(request, request.status);
    }
  };

  request.open('GET', url, true);
  request.send(null);
}

function doNothing() {}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $gmaps;?>&callback=initMap"></script>

<?php
}

///////////////// SUBMIT RAIDS \\\\\\\\\\\\\\\\\

function raidsubmission(){
require('config/config.php');
$result = $conn->query("SELECT * FROM raidbosses");
$rid = $rboss = $rlvl = $rhour = $rmin = $rampm = $rlatitude = $rlongitude ="";
?>


<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h2 style="text-align:center;"><strong>Add Raid:</strong></h2>
<form id="usersubmit" method="post" action="spotraid.php">
<center><table style="width: 25%; height: auto;" id="t01">
<tbody>


<!--///////////////////// GENERATE BOSS LIST \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">Raid Boss</td>
<td style="width: 10%;">
<?php
echo "<select id='raidsearch' name='rboss'>";
while ($row = $result->fetch_assoc()) {
    unset($rid, $rboss);
        $rid = $row['rid'];
            $rboss= $row['rboss'];
				echo '<option value="'.$rid.'">'.$rid.' - '.$rboss.'</option>';
					}					
						echo "</select>";
							mysqli_close($conn);
?>
</td>
</tr>

<!--///////////////////// TIME OF FIND \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">Time of Expire</td>
<td style="width: 10%;">

<?php 
	if ($clock=="false"){ ?>
	<select name="rhour">
		<?php
			for($i=1; $i<=12; $i++){
			echo "<option value=".$i.">".$i."</option>";}
		?>
		<option name="hour"> </option>   
	</select> 
	
	<select name="rmin">
		<?php
			for($i=0; $i<=60; $i++){
				$value = str_pad($i,2,"0",STR_PAD_LEFT);
			echo "<option value=".$value.">".$value."</option>";}
		?>
		<option name="rmin"> </option>   
	</select> 
	
	<select name="rampm">
		<option value="AM/PM" selected>AM/PM</option>
		<option value="AM">AM</option>
		<option value="PM">PM</option>
	</select>
	
	<?php } else { ?>
	
	<select name="rhour">
		<?php
			for($i=0; $i<=24; $i++){
			echo "<option value=".$i.">".$i."</option>";}
		?>
		<option name="rhour"> </option>   
	</select> 
	
	<select name="rmin">
		<?php
			for($i=0; $i<=60; $i++){
				$value = str_pad($i,2,"0",STR_PAD_LEFT);
			echo "<option value=".$value.">".$value."</option>";}
		?>
		<option name="rmin"> </option>   
	</select> 
	<?php } ?>
</td>
</tr>

<!--///////////////////// ADDRESS \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">Location</td>
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
    x.innerHTML = "<input name='rlatitude' value='" + position.coords.latitude + "' readonly></input><input name='rlongitude' value='" + position.coords.longitude + "' readonly></input>";


}
</script>

<button type="button" onclick="getLocation()">Get Location</button>

</td>
</tr>
<!--///////////////////// fORM SUBMIT BUTTON \\\\\\\\\\\\\\\\\\\\\-->
<center><td style="width:100%;"><input type="submit" value="SPOT!"/></td></center>

</tbody>
</table></center>
</form>

<?php }


////////////////////// SPOTTED RAIDS \\\\\\\\\\\\\\\\\\\\\\\\\


function spottedraids(){
require('config/config.php');
$results_per_page = 10;

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql = "SELECT * FROM spotraid,raidbosses WHERE spotraid.rboss = raidbosses.rid ORDER BY rdate DESC LIMIT $start_from,".$results_per_page;
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));


$sqlcnt = "SELECT COUNT(RID) AS total FROM spotraid"; 
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>


<h2 style="text-align:center;"><strong>Spotted Raids:</strong></h2>

<center>

<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php

echo "<table id=\"t02\" class=\"spotted\">";
echo "<tr><th>ID</th><th>BOSS</th><th>LVL / CP</th><th>EXPIRES</th><th>LOCATION</th></tr>";
while($row = mysqli_fetch_array($result)) {
	$rid = $row['rid'];
    $rboss = $row['rboss'];
    $rlvl = $row['rlvl'];
	$rcp = $row['rcp'];
	$rhour = $row['rhour'];
	$rmin = $row['rmin'];
	$rampm = $row['rampm'];
	$rlatitude = $row['rlatitude'];
	$rlongitude = $row['rlongitude'];
	$minutes = $rmin;
	$hr = $rhour;
	
	
	///////////////////// ADDS "0" TO SIGNLE DIGIT MINUTE TIMES \\\\\\\\\\\\\\\\\\\\\
	if ($rmin < 10) {
    $minutes = str_pad($rmin, 2, "0", STR_PAD_LEFT);	
	}
	
	///////////////////// 12 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
	if ($clock=="false"){
		
	///////////////////// 12 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td>".$rid."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="static/icons/<?php echo $rid?>.png" title="<?php echo $rid; ?> (#<?php echo $rboss?>)" height="24" width="24"><p style="padding-top:6%;"><?php echo $rboss; ?></p><?php echo "</td>
	<td>".$rlvl." / ".$rcp."</td>
	<td>".$rhour.":".$minutes." ".$rampm."</td>
	<td>"?><a href="http://maps.google.com/maps?q=<?php echo "".$rlatitude,",".$rlongitude.""?>"><?php "</td>
	</tr>";
	
	///////////////////// GOOGLE DECODER \\\\\\\\\\\\\\\\\\\\\
	$url  = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$rlatitude.",".$rlongitude."&sensor=false";
	$json = @file_get_contents($url);
	$data = json_decode($json);
	$status = $data->status;
	$address = '';
		if($status == "OK")
		{
			echo $address = $data->results[0]->formatted_address;?></a><?php
		}
		else
		{
			echo "Cannot retrieve address";
		}
		
	} else {
	///////////////////// 24 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\

	///////////////////// ADDS "0" TO SIGNLE DIGIT HOUR TIMES \\\\\\\\\\\\\\\\\\\\\
	if ($rhour < 10) {
    $hr = str_pad($rhour, 2, "0", STR_PAD_LEFT);	
	}
	
	///////////////////// 24 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td>".$rid."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="static/icons/<?php echo $rid?>.png" title="<?php echo $rid; ?> (#<?php echo $rboss?>)" height="24" width="24"><p style="padding-top:6%;"><?php echo $rboss; ?></p><?php echo "</td>
	<td>".$rlvl." / ".$rcp."</td>
	<td>".$hr.":".$minutes."</td>
	<td>"?><a href="http://maps.google.com/maps?q=<?php echo "".$rlatitude,",".$rlongitude.""?>"><?php "</td>
	</tr>";
	
	///////////////////// GOOGLE DECODER \\\\\\\\\\\\\\\\\\\\\
	$url  = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$rlatitude.",".$rlongitude."&sensor=false";
	$json = @file_get_contents($url);
	$data = json_decode($json);
	$status = $data->status;
	$address = '';
		if($status == "OK")
		{
			echo $address = $data->results[0]->formatted_address;?></a><?php
		}
		else
		{
			echo "No Data Found Try Again";
		}
	
}}
echo "</table></center>";
?><center><?php

///////////////////// PAGENATION \\\\\\\\\\\\\\\\\\\\\
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."'>".$i."</a> "; 
}; 
?></center><?php
}
?>
