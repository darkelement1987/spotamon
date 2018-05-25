<?php
///////////////////// FORM SUBMISSION DATA \\\\\\\\\\\\\\\\\\\\\
function pokesubmission(){
require('./config/config.php');
$result = $conn->query("SELECT * FROM pokedex");
$id = $pokemon = $cp = $hour = $min = $ampm = $monster = $latitude = $longitude = $fulladdress="";
if(isset($_SESSION["uname"])){ ?>
<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h2 style="text-align:center;"><strong>Add Pokémon:</strong></h2>
<form id="usersubmit" method="post" action="./spotpokemon.php">
<center><table id="t01">
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
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=<?php echo $gmaps;?>"></script>
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
	            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            var latlng = new google.maps.LatLng(lat, lng);
            var geocoder = geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        document.getElementById("addressinput").value = "" + results[0].formatted_address + "";
                    }
                }
            });
    x.innerHTML = "<input name='latitude' value='" + position.coords.latitude + "' style='width:100%' readonly></input><input name='longitude' value='" + position.coords.longitude + "' style='width:100%' readonly></input><input id='addressinput' value='' style='width:100%'></input> ";


}
</script>

<button type="button" onclick="getLocation();enablespotbutton()">Get Location</button>

</td>
</tr>
<!--///////////////////// fORM SUBMIT BUTTON \\\\\\\\\\\\\\\\\\\\\-->
<center><td style="width:10%;"><input type="submit" id="spotbutton" value="SPOT!" disabled/></td></center>

</tbody>
</table></center>
</form>

<?php } else {
	
	echo "<center><div style='margin-top:5%;'>";
	echo "Login to spot a pokemon";
		?><br /><br /><a href="/login/login.php">Login Here</a><?php
	echo "</div></center>";
	}
} 

///////////////////// SPOTTED MONSTER TABLE \\\\\\\\\\\\\\\\\\\\\
function spottedpokemon(){
require('./config/config.php');
$results_per_page = 10;

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql = "SELECT * FROM spots,pokedex WHERE spots.pokemon = pokedex.id ORDER BY date DESC LIMIT $start_from,".$results_per_page;
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));


$sqlcnt = "SELECT COUNT(SPOTID) AS total FROM spots"; 
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>


<h2 style="text-align:center;"><strong>Spotted Pokemon:</strong></h2>

<center>

<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php

echo "<table id=\"t02\" class=\"spotted\">";
echo "<tr><th>#</th><th>ID</th><th>POKEMON</th><th>CP</th><th>TIME FOUND</th><th>LOCATION</th><th>MAP</th><th>Up Votes</th><th>Down votes</th></tr>";
while($row = mysqli_fetch_array($result)) {
	$spotid = $row['spotid'];
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
    $fulladdress = $row['fulladdress'];
	$good = $row['good'];
	$bad = $row['bad'];
	
	///////////////////// ADDS "0" TO SIGNLE DIGIT MINUTE TIMES \\\\\\\\\\\\\\\\\\\\\
	if ($min < 10) {
    $minutes = str_pad($min, 2, "0", STR_PAD_LEFT);	
	}
	
	///////////////////// 12 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
	if ($clock=="false"){
		
	///////////////////// 12 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td style='text-align:center;'>".$spotid."</td>
	<td style='text-align:center;'>".$pokemon."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $pokemon?>.png" title="<?php echo $id; ?> (#<?php echo $pokemon?>)" height="24" width="24"><p style="padding-top:6%;"><?php echo $id; ?></p><?php echo "</td>
	<td>".$cp."</td>
	<td style='text-align:center;'>".$hour.":".$minutes." ".$ampm."</td>
	<td>"?><a href="http://maps.google.com/maps?q=<?php echo "".$latitude,",".$longitude.""?>"><?php echo $fulladdress;?></a><?php echo "</td>
	<td style='text-align:center;'>"?><a href="./?loc=<?php echo "".$latitude,",".$longitude.""?>&zoom=19">Map</a><?php echo "</td>
	<td style='text-align:center;'>".$good." <span style='display:inline-block;'><form action='good.php' method='post'><input type='hidden' name='spotid' value='$spotid' /><input type='image' name='good' style='width:25px;height:auto;display:inline;' src='static/voting/up.png' value='$good' /></form></span></td>
	<td style='text-align:center;'>".$bad." <span style='display:inline-block;'><form action='bad.php' method='post'><input type='hidden' name='spotid' value='$spotid' /><input type='image' name='bad' style='width:27px;height:auto;display:inline;' src='static/voting/down.png' value='$bad' /></form></span></td>
	</tr>";
		
	} else {
	///////////////////// 24 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\

	///////////////////// ADDS "0" TO SIGNLE DIGIT HOUR TIMES \\\\\\\\\\\\\\\\\\\\\
	if ($hour < 10) {
    $hr = str_pad($hour, 2, "0", STR_PAD_LEFT);	
	}
	
	///////////////////// 24 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td style='text-align:center;'>".$spotid."</td>
	<td>".$pokemon."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $pokemon?>.png" title="<?php echo $id; ?> (#<?php echo $pokemon?>)" height="24" width="24"><p style="padding-top:6%;"><?php echo $id; ?></p><?php echo "</td>
	<td>".$cp."</td>
	<td>".$hr.":".$minutes."</td>
	<td>"?><a href="http://maps.google.com/maps?q=<?php echo "".$latitude,",".$longitude.""?>"><?php echo $fulladdress;?></a><?php echo "</td>
	<td>"?><a href="./?loc=<?php echo "".$latitude,",".$longitude.""?>&zoom=19">Map</a><?php echo "</td>
	<td style='text-align:center;'>".$good." <span style='display:inline-block;'><form action='good.php' method='post'><input type='hidden' name='spotid' value='$spotid' /><input type='image' name='good' style='width:25px;height:auto;display:inline;' src='static/voting/up.png' value='$good' /></form></span></td>
	<td style='text-align:center;'>".$bad." <span style='display:inline-block;'><form action='bad.php' method='post'><input type='hidden' name='spotid' value='$spotid' /><input type='image' name='bad' style='width:27px;height:auto;display:inline;' src='static/voting/down.png' value='$bad' /></form></span></td>
	</tr>";

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
	require('./config/config.php');
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
    center: new google.maps.LatLng(<?php
if (isset($_GET['loc'])) {
    echo $_GET['loc'];
} else {
echo $mapcenter;
}?>),
    zoom: <?php
if (isset($_GET['zoom'])) {
    echo $_GET['zoom'];
} else {
echo 15;
}?>,
    gestureHandling: 'greedy',
    fullscreenControl: true,
    streetViewControl: false,
    mapTypeControl: false,
    clickableIcons: false
  });
  var infoWindow = new google.maps.InfoWindow;

    // Change this depending on the name of your PHP or XML file
    downloadUrl('./frontend/xml.php', function(data) {
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
		var good = markerElem.getAttribute('good');
		var bad = markerElem.getAttribute('bad');
        var point = new google.maps.LatLng(
            parseFloat(markerElem.getAttribute('latitude')),
            parseFloat(markerElem.getAttribute('longitude')));

        var infowincontent = document.createElement('div');
        var strong = document.createElement('strong');
        strong.textContent = pokemon + ' (#' + id + ')'
        infowincontent.appendChild(strong);
        infowincontent.appendChild(document.createElement('br'));
		if (min < 10){
		var text = document.createElement('text');
        text.textContent = 'Found: ' + hour + ':' + '0' + min + ' ' + ampm  
        infowincontent.appendChild(text);
		infowincontent.appendChild(document.createElement('br'));
		} else {
		var text = document.createElement('text');
        text.textContent = 'Found: ' + hour + ':' + min + ' ' + ampm  
        infowincontent.appendChild(text);
		infowincontent.appendChild(document.createElement('br'));	
		}
        var text = document.createElement('text');
        text.textContent = cp + ' CP'
        infowincontent.appendChild(text);
		infowincontent.appendChild(document.createElement('br'));
		var text = document.createElement('text');
        text.textContent = good + ' Verified Find'
        infowincontent.appendChild(text);
		infowincontent.appendChild(document.createElement('br'));
		var text = document.createElement('text');
        text.textContent = bad + ' Not found'
        infowincontent.appendChild(text);
		infowincontent.appendChild(document.createElement('br'));
        var icon = customLabel[type] || {};
        var image = {
            url: './static/icons/' + id + '.png',
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
	
	downloadUrl('./frontend/gxml.php', function(data) {
      var xml = data.responseXML;
      var markers = xml.documentElement.getElementsByTagName('marker');
      Array.prototype.forEach.call(markers, function(markerElem) {
        var gid = markerElem.getAttribute('gid');
        var gname = markerElem.getAttribute('gname');
		var gteam = markerElem.getAttribute('gteam');
        var type = markerElem.getAttribute('type');
        var tid = markerElem.getAttribute('tid');
		var actraid = markerElem.getAttribute('actraid');
		var actboss = markerElem.getAttribute('actboss');
		var hour = markerElem.getAttribute('hour');
		var min = markerElem.getAttribute('min');
		var ampm = markerElem.getAttribute('ampm');
		var egg = markerElem.getAttribute('egg');
		var point = new google.maps.LatLng(
            parseFloat(markerElem.getAttribute('glatitude')),
            parseFloat(markerElem.getAttribute('glongitude')));
		if (actraid === "0" && egg === "0"){
		var infowincontent = document.createElement('div');
        var strong = document.createElement('strong');
        strong.textContent = gname
        infowincontent.appendChild(strong);
        infowincontent.appendChild(document.createElement('br'));
		var text = document.createElement('text');
        text.textContent = 'Team: ' + tid
        infowincontent.appendChild(text);
		infowincontent.appendChild(document.createElement('br'));
        var icon = customLabel[type] || {};
			var image = {
            url: './static/gyms/' + gteam + '.png',
            scaledSize: new google.maps.Size(50, 50)
			};
		} else if (actraid !== "0" && egg === "0"){
			var infowincontent = document.createElement('div');
			var strong = document.createElement('strong');
			strong.textContent = 'Raid At: ' +gname
			infowincontent.appendChild(strong);
			infowincontent.appendChild(document.createElement('br'));
			var text = document.createElement('text');
			text.textContent = 'Team: ' + tid
			infowincontent.appendChild(text);
			infowincontent.appendChild(document.createElement('br'));
			if (min < 10){
			var text = document.createElement('text');
			text.textContent = 'Expires: ' + hour + ':' + '0' + min + ' ' + ampm  
			infowincontent.appendChild(text);
			infowincontent.appendChild(document.createElement('br'));
			} else {
			var text = document.createElement('text');
			text.textContent = 'Expires: ' + hour + ':' + min + ' ' + ampm  
			infowincontent.appendChild(text);
			infowincontent.appendChild(document.createElement('br'));	
			}
			var icon = customLabel[type] || {};
			var image = {
            url: './static/raids/' + actboss + '.png',
            scaledSize: new google.maps.Size(75, 75)
			};		
		} else if (actraid !== "0" && egg !== "0"){
			var infowincontent = document.createElement('div');
			var strong = document.createElement('strong');
			strong.textContent = 'Raid At: ' + gname
			infowincontent.appendChild(strong);
			infowincontent.appendChild(document.createElement('br'));
			var text = document.createElement('text');
			text.textContent = 'Raid Lvl: ' + egg
			infowincontent.appendChild(text);
			infowincontent.appendChild(document.createElement('br'));
			var text = document.createElement('text');
			text.textContent = 'Team: ' + tid
			infowincontent.appendChild(text);
			infowincontent.appendChild(document.createElement('br'));
			if (min < 10){
			var text = document.createElement('text');
			text.textContent = 'Expires: ' + hour + ':' + '0' + min + ' ' + ampm  
			infowincontent.appendChild(text);
			infowincontent.appendChild(document.createElement('br'));
			} else {
			var text = document.createElement('text');
			text.textContent = 'Hatches: ' + hour + ':' + min + ' ' + ampm  
			infowincontent.appendChild(text);
			infowincontent.appendChild(document.createElement('br'));	
			}
			var icon = customLabel[type] || {};
			var image = {
            url: './static/raids/' + actboss + '.png',
            scaledSize: new google.maps.Size(75, 75)
			};		
		} else if (actraid === "0" && egg !== "0"){
			var infowincontent = document.createElement('div');
			var strong = document.createElement('strong');
			strong.textContent = 'Egg At: ' + gname
			infowincontent.appendChild(strong);
			infowincontent.appendChild(document.createElement('br'));
			var text = document.createElement('text');
			text.textContent = 'Egg Lvl: ' + egg
			infowincontent.appendChild(text);
			infowincontent.appendChild(document.createElement('br'));
			var text = document.createElement('text');
			text.textContent = 'Team: ' + tid
			infowincontent.appendChild(text);
			infowincontent.appendChild(document.createElement('br'));
			if (min < 10){
			var text = document.createElement('text');
			text.textContent = 'Expires: ' + hour + ':' + '0' + min + ' ' + ampm  
			infowincontent.appendChild(text);
			infowincontent.appendChild(document.createElement('br'));
			} else {
			var text = document.createElement('text');
			text.textContent = 'Hatches: ' + hour + ':' + min + ' ' + ampm  
			infowincontent.appendChild(text);
			infowincontent.appendChild(document.createElement('br'));	
			}
			var icon = customLabel[type] || {};
			var image = {
            url: './static/eggs/' + egg + '.png',
            scaledSize: new google.maps.Size(55, 55)
			};		
		} 
		
        var marker = new google.maps.Marker({
          map: map,
          position: point,
          label: icon.label,
          icon: image,
		  title: gname + ' held by ' + tid
        });
        marker.addListener('click', function() {
          infoWindow.setContent(infowincontent);
          infoWindow.open(map, marker);
        });
      });
    });
	
	downloadUrl('./frontend/sxml.php', function(data) {
      var xml = data.responseXML;
      var markers = xml.documentElement.getElementsByTagName('marker');
      Array.prototype.forEach.call(markers, function(markerElem) {
        var sid = markerElem.getAttribute('sid');
		var quest = markerElem.getAttribute('quest');
        var reward = markerElem.getAttribute('reward');
		var type = markerElem.getAttribute('type');
		var point = new google.maps.LatLng(
            parseFloat(markerElem.getAttribute('slatitude')),
            parseFloat(markerElem.getAttribute('slongitude')));
		
		var infowincontent = document.createElement('div');
		var strong = document.createElement('strong');
        strong.textContent = 'Stop ID: ' + sid
        infowincontent.appendChild(strong);
        infowincontent.appendChild(document.createElement('br'));
		var text = document.createElement('text');
        text.textContent = 'Quest: ' + quest
        infowincontent.appendChild(text);
		infowincontent.appendChild(document.createElement('br'));
		var text = document.createElement('text');
        text.textContent = 'Reward: ' + reward
        infowincontent.appendChild(text);
		infowincontent.appendChild(document.createElement('br'));
        var icon = customLabel[type] || {};
        var image = {
            url: './static/stops/stops.png',
            scaledSize: new google.maps.Size(30, 30)
        };
        var marker = new google.maps.Marker({
          map: map,
          position: point,
          label: icon.label,
          icon: image,
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
require('./config/config.php');
$result = $conn->query("SELECT * FROM raidbosses");
$rid = $rboss = $rlvl = $rhour = $rmin = $rampm = "";
if(isset($_SESSION["uname"])){ 
?>

<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h2 style="text-align:center;"><strong>Add Raid:</strong></h2>
<form id="usersubmit" method="post" action="./spotraid.php">
<center><table id="t03">
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
<td style="width: 5%;">At Gym</td>
<td style="width: 10%;">
<?php
require('./config/config.php');
$result = $conn->query("SELECT * FROM gyms,teams WHERE gyms.gteam = teams.tid");
$gid = $gname = $gteam = "";
echo "<select id='gymsearch' name='gname'>";
while ($row = $result->fetch_assoc()) {
    unset($gid, $gname);
        $gid = $row['gid'];
		$tid = $row['tname'];
            $gname= $row['gname'];
				$gteam= $row['gteam'];
					echo '<option value="'.$gid.'" label="'.$gteam.'">'.$gname.'</option>';
						}					
							echo "</select>";
						
?>

</td>
</tr>
<!--///////////////////// fORM SUBMIT BUTTON \\\\\\\\\\\\\\\\\\\\\-->
<center><td style="width:10%;"><input type="submit" value="SPOT!"/></td></center>

</tbody>
</table></center>
</form>

<?php } else{
	
	echo "<center><div style='margin-top:5%;'>";
	echo "Login to spot a Raid";
		?><br /><br /><a href="/login/login.php">Login Here</a><?php
	echo "</div></center>";
	
} }


////////////////////// SPOTTED RAIDS \\\\\\\\\\\\\\\\\\\\\\\\\


function spottedraids(){
require('./config/config.php');
$results_per_page = 10;

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql = "SELECT * FROM raidbosses,gyms WHERE gyms.actraid = '1' AND gyms.actboss = raidbosses.rid  AND gyms.glatitude AND gyms.glongitude ORDER BY date DESC LIMIT $start_from,".$results_per_page;
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
echo "<tr><th>ID</th><th>BOSS</th><th>LVL / CP</th><th>EXPIRES</th><th>LOCATION</th><th>MAP</th></tr>";
while($row = mysqli_fetch_array($result)) {
	$rid = $row['rid'];
    $rboss = $row['rboss'];
    $rlvl = $row['rlvl'];
	$rcp = $row['rcp'];
	$hour = $row['hour'];
	$min = $row['min'];
	$ampm = $row['ampm'];
	$glatitude = $row['glatitude'];
	$glongitude = $row['glongitude'];
	$minutes = $min;
	$hr = $hour;
	$gname = $row['gname'];
	
	
	///////////////////// ADDS "0" TO SIGNLE DIGIT MINUTE TIMES \\\\\\\\\\\\\\\\\\\\\
	if ($min < 10) {
    $minutes = str_pad($min, 2, "0", STR_PAD_LEFT);	
	}
	
	///////////////////// 12 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
	if ($clock=="false"){
		
	///////////////////// 12 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td>".$rid."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $rid?>.png" title="<?php echo $rid; ?> (#<?php echo $rboss?>)" height="24" width="24"><p style="padding-top:6%;"><?php echo $rboss; ?></p><?php echo "</td>
	<td>".$rlvl." / ".$rcp."</td>
	<td>".$hour.":".$minutes." ".$ampm."</td>
	<td>"?><a href="http://maps.google.com/maps?q=<?php echo "".$glatitude,",".$glongitude.""?>"><?php echo $gname;?></a><?php echo "</td>
	<td>"?><a href="./?loc=<?php echo "".$glatitude,",".$glongitude.""?>&zoom=19">Map</a><?php echo "</td>
	</tr>";
		
	} else {
	///////////////////// 24 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\

	///////////////////// ADDS "0" TO SIGNLE DIGIT HOUR TIMES \\\\\\\\\\\\\\\\\\\\\
	if ($hour < 10) {
    $hr = str_pad($hour, 2, "0", STR_PAD_LEFT);	
	}
	
	///////////////////// 24 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td>".$rid."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $rid?>.png" title="<?php echo $rid; ?> (#<?php echo $rboss?>)" height="24" width="24"><p style="padding-top:6%;"><?php echo $rboss; ?></p><?php echo "</td>
	<td>".$rlvl." / ".$rcp."</td>
	<td>".$hr.":".$minutes."</td>
	<td>"?><a href="http://maps.google.com/maps?q=<?php echo "".$glatitude,",".$glongitude.""?>"><?php echo $gname;?></a><?php echo "</td>
	<td>"?><a href="./?loc=<?php echo "".$glatitude,",".$glongitude.""?>&zoom=19">Map</a><?php echo "</td>
	</tr>";
	
}}
echo "</table></center>";
?><center><?php

///////////////////// PAGENATION \\\\\\\\\\\\\\\\\\\\\
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."'>".$i."</a> "; 
}; 
?></center><?php
}

///////////////////// FORM SUBMISSION DATA \\\\\\\\\\\\\\\\\\\\\
function gymsubmission(){
require('./config/config.php');
$result = $conn->query("SELECT * FROM gyms,teams WHERE gyms.gteam = teams.tid");
$gid = $gname = $gteam = "";
if(isset($_SESSION["uname"])){ 
?>

<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h2 style="text-align:center;"><strong>Gym team:</strong></h2>
<form id="usersubmit" method="post" action="./gymteam.php">
<center><table id="t04">
<tbody>

<!--///////////////////// GENERATE MONSTER LIST \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">Gym</td>
<td style="width: 10%;">
<?php
echo "<select id='gymsearch' name='gname'>";
while ($row = $result->fetch_assoc()) {
    unset($gid, $gname);
        $gid = $row['gid'];
		$tid = $row['tname'];
            $gname= $row['gname'];
				$gteam= $row['gteam'];
					echo '<option value="'.$gid.'" label="'.$gteam.'">'.$gname.'</option>';
						}					
							echo "</select>";
						
?>
</td>
</tr>

<tr>
<td style="width: 5%;">Team</td>
<td style="width: 10%;">
<select id='teamsearch' name='tname'>
<option value="2">Instinct</option>
<option value="4">Mystic</option>
<option value="3">Valor</option>
</select>
</td>
</tr>

<!--///////////////////// fORM SUBMIT BUTTON \\\\\\\\\\\\\\\\\\\\\-->
<center><td style="width:10%;"><input type="submit" value="SPOT!"/></td></center>

</tbody>
</table></center>
</form>

<?php } else{
	
	echo "<center><div style='margin-top:5%;'>";
	echo "Login to spot a team";
		?><br /><br /><a href="/login/login.php">Login Here</a><?php
	echo "</div></center>";
}}


function eggsubmission(){
require('./config/config.php');
$result = $conn->query("SELECT * FROM gyms,teams WHERE gyms.gteam = teams.tid");
$gid = $gname = $gteam = "";
if(isset($_SESSION["uname"])){
?>

<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h2 style="text-align:center;"><strong>Spot Egg:</strong></h2>
<form id="usersubmit" method="post" action="./spotegg.php">
<center><table id="t04">
<tbody>

<!--///////////////////// GENERATE MONSTER LIST \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">Gym</td>
<td style="width: 10%;">
<?php
echo "<select id='gymsearch' name='gname'>";
while ($row = $result->fetch_assoc()) {
    unset($gid, $gname);
        $gid = $row['gid'];
		$tid = $row['tname'];
            $gname= $row['gname'];
				$gteam= $row['gteam'];
					echo '<option value="'.$gid.'" label="'.$gteam.'">'.$gname.'</option>';
						}					
							echo "</select>";
						
?>
</td>
</tr>

<tr>
<td style="width: 5%;">Hatches At</td>
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
<center><td style="width:10%;"><input type="submit" value="SPOT!"/></td></center>

</tbody>
</table></center>
</form>

<?php } else{
	echo "<center><div style='margin-top:5%;'>";
	echo "Login to spot an Egg";
		?><br /><br /><a href="/login/login.php">Login Here</a><?php
	echo "</div></center>";
	
} }

function profile(){ 
if(isset($_SESSION["uname"])){
require('config/config.php');
$result = $conn->query("SELECT * FROM users,usergroup WHERE uname='".$_SESSION['uname']."' AND users.usergroup = usergroup.id LIMIT 1  "); 
$id = $usergroup = "";?>
<h2 style="text-align:center;"><strong>Your Profile:</strong></h2>
<?php
	echo "<center><table id=\"t02\" class=\"spotted\">";
	echo "<tr><th>user</th><th>Email</th><th>LVL</th></tr>";
	while ($row = $result->fetch_assoc()) {
	$id = $row['id'];
    $uname = $row['uname'];
    $email = $row['email'];
	$usergroup = $row['groupname'];
	echo "<tr>"; ?>
	<td><?php echo $uname; ?></td>
	<td><?php echo $email; ?></td>
	<td><?php echo $usergroup; ?></td>
	<?php echo "</tr>";
	echo "</table></center>";
	echo "<br /><center><a href='./edit-profile.php'>Edit Profile</a></center>";
	if ("$usergroup" == 'admin'){
		?>
		<h2 style="text-align:center;"><strong>Admin Panel:</strong></h2>
		<center>
		<a href="gymcsv.php">Upload Gym .CSV</a><br />
		<a href="stopcsv.php">Upload Stop .CSV</a>
		<?php
	}
	
	}
} else{
	echo "<center><div style='margin-top:5%;'>";
	echo "Login to view your profile";
		?><br /><br /><a href="/login/login.php">Login Here</a><?php
	echo "</div></center></table></center>";
} }


function editprofile(){ 
if(isset($_SESSION["uname"])){
require('config/config.php');
$result = $conn->query("SELECT * FROM users,usergroup WHERE uname='".$_SESSION['uname']."' AND users.usergroup = usergroup.id LIMIT 1  "); 
$id = $usergroup = "";?>
<h2 style="text-align:center;"><strong>Edit Your Profile:</strong></h2>
<?php
	echo "<center><table style='width:20%;' id=\"t05\" class=\"profile\">";
	
	while ($row = $result->fetch_assoc()) {
	$id = $row['id'];
    $uname = $row['uname'];
    $email = $row['email'];
	$usergroup = $row['groupname'];?>
	
	<tr>
	<form action="editusername.php" method="post">
	<?php echo "<th style='background-color:#fff;color:#000;width:10%;'><center>Username: </center></th>";?>
	<?php echo "<td><center><input type='text' name='uname' id='uname'><input type='submit' value='Submit'></center></td></form>";?>	
	</tr>
	
	<tr>
	<form action="editemail.php" method="post">
	<?php echo "<th style='background-color:#f9f9f9;color:#000;'><center>Email: </center></th>";?>
	<?php echo "<td><center><input type='text' name='email' id='email' pattern=\"[a-zA-Z0-9!#$%&amp;'*+\/=?^_`{|}~.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\"><input type='submit' value='Submit'></center></td></form>";?>
	</tr>
	
	<tr>
	<form action="editpassword.php" method="post">
	<?php echo "<th style='background-color:#fff;color:#000;'><center>Password: </center></th>";?>
	<?php echo "<td><center><input type='password' name='upass' id='upass'><input type='submit' value='Submit'></center></td></form>";?>
	</tr>
	<?php echo "</table></center>";?>
	<?php
	}

	} else{
	echo "<center><div style='margin-top:5%;'>";
	echo "Login to view your profile";
		?><br /><br /><a href="/login/login.php">Login Here</a><?php
	echo "</div></center></table></center>";
} }




?>


