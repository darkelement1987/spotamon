<?php
///////////////////// FORM SUBMISSION DATA \\\\\\\\\\\\\\\\\\\\\
function pokesubmission(){
require('./config/config.php');

$result = $conn->query("SELECT * FROM pokedex");

$id = $pokemon = $cp = $iv = $hour = $min = $ampm = $monster = $latitude = $longitude = $fulladdress = $spotter ="";
if(isset($_SESSION["uname"])){ ?>
<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h3 style="text-align:center;"><strong>Add Pokémon:</strong></h3>
<form id="usersubmit" method="post" action="./spotpokemon.php">
<center><table id="added" class="table table-bordered">
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
    x.innerHTML = "<input name='latitude' value='" + position.coords.latitude + "' style='width:100%' readonly></input><input name='longitude' value='" + position.coords.longitude + "' style='width:100%' readonly></input><input name='addressinput' id='addressinput' value='' style='width:100%'></input> ";


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
	
	echo "<center><div style='margin-top:10px;'>";
	echo "Login to spot a pokemon";
		?><br /><br /><a href="./login/login.php">Login Here</a><?php
	echo "</div></center>";
	}
} 

///////////////////// SPOTTED MONSTER TABLE \\\\\\\\\\\\\\\\\\\\\
function spottedpokemon(){
require('./config/config.php');
$results_per_page = 10;

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql = "SELECT * FROM spots,pokedex WHERE spots.pokemon = pokedex.id ORDER BY spotid DESC LIMIT $start_from,".$results_per_page;
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));


$sqlcnt = "SELECT COUNT(SPOTID) AS total FROM spots"; 
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>


<h3 style="text-align:center;"><strong>Spotted Pokemon:</strong></h3>

<center>

<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php

echo "<table id=\"spotted\" class=\"table table-bordered\">";
if(isset($_SESSION["uname"])){
echo "<tr><th>#</th><th>ID</th><th>POKEMON</th><th>CP</th><th>IV</th><th>FOUND</th><th>LOCATION</th><th>VOTING</th></tr>";
} else {
echo "<tr><th>#</th><th>ID</th><th>POKEMON</th><th>CP</th><th>IV</th><th>FOUND</th><th>LOCATION</th></tr>";
}

while($row = mysqli_fetch_array($result)) {
	$spotid = $row['spotid'];
	$id = $row['monster'];
    $pokemon = $row['pokemon'];
    $cp = $row['cp'];
    $iv = $row['iv'];	
	$hour = $row['hour'];
	$min = $row['min'];
	$ampm = $row['ampm'];
	$latitude = $row['latitude'];
	$longitude = $row['longitude'];
	$minutes = $min;
	$hr = $hour;
    $fulladdress = $row['fulladdress'];
	if(isset($_SESSION["uname"])){
	$good = $row['good'];
	$bad = $row['bad'];
	}
	
	///////////////////// ADDS "0" TO SIGNLE DIGIT MINUTE TIMES \\\\\\\\\\\\\\\\\\\\\
	if ($min < 10) {
    $minutes = str_pad($min, 2, "0", STR_PAD_LEFT);	
	}
	
	///////////////////// 12 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
	if ($clock=="false"){
		
	///////////////////// 12 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	
	if(isset($_SESSION["uname"])){
	echo "
	<tr>
	<td style='text-align:center;'>".$spotid."</td>
	<td style='text-align:center;'>".$pokemon."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $pokemon?>.png" title="<?php echo $id; ?> (#<?php echo $pokemon?>)" height="24" width="24"><p style="padding-top:6%;"><?php echo $id; ?></p><?php echo "</td>
	<td>".$cp."</td>
	<td>".$iv."%</td>
	<td style='text-align:center;'>".$hour.":".$minutes." ".$ampm."</td>
	<td>"?><a href="./?loc=<?php echo "".$latitude,",".$longitude.""?>&zoom=19"><?php echo $fulladdress;?></a><?php echo "</td>
	<td style='text-align:center;'>
	<span style='display:inline-block;'><form action='good.php' method='post'><input type='hidden' name='spotid' value='$spotid' /><input type='image' name='good' style='width:25px;height:auto;display:inline;' src='static/voting/up.png' value='$good' /></form></span>".$good."<br>
	<span style='display:inline-block;'><form action='bad.php' method='post'><input type='hidden' name='spotid' value='$spotid' /><input type='image' name='bad' style='width:27px;height:auto;display:inline;' src='static/voting/down.png' value='$bad' /></form></span>".$bad."</td>
	</tr>";
	} else {
	echo "
	<tr>
	<td style='text-align:center;'>".$spotid."</td>
	<td style='text-align:center;'>".$pokemon."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $pokemon?>.png" title="<?php echo $id; ?> (#<?php echo $pokemon?>)" height="24" width="24"><p style="padding-top:6%;"><?php echo $id; ?></p><?php echo "</td>
	<td>".$cp."</td>
	<td>".$iv."%</td>
	<td style='text-align:center;'>".$hour.":".$minutes." ".$ampm."</td>
	<td>"?><a href="./?loc=<?php echo "".$latitude,",".$longitude.""?>&zoom=19"><?php echo $fulladdress;?></a><?php echo "</td>
	";
	}
		
		
	} else {
	///////////////////// 24 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\

	///////////////////// ADDS "0" TO SIGNLE DIGIT HOUR TIMES \\\\\\\\\\\\\\\\\\\\\
	if ($hour < 10) {
    $hr = str_pad($hour, 2, "0", STR_PAD_LEFT);	
	}
	
	///////////////////// 24 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	
	if(isset($_SESSION["uname"])){	
	echo "
	<tr>
	<td style='text-align:center;'>".$spotid."</td>
	<td>".$pokemon."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $pokemon?>.png" title="<?php echo $id; ?> (#<?php echo $pokemon?>)" height="24" width="24"><p style="padding-top:6%;"><?php echo $id; ?></p><?php echo "</td>
	<td>".$cp."</td>
	<td>".$iv."%</td>
	<td>".$hr.":".$minutes."</td>
	<td>"?><a href="./?loc=<?php echo "".$latitude,",".$longitude.""?>&zoom=19"><?php echo $fulladdress;?></a><?php echo "</td>
	<td style='text-align:center;'>
	<span style='display:inline-block;'><form action='good.php' method='post'><input type='hidden' name='spotid' value='$spotid' /><input type='image' name='good' style='width:25px;height:auto;display:inline;' src='static/voting/up.png' value='$good' /></form></span>".$good."<br>
	<span style='display:inline-block;'><form action='bad.php' method='post'><input type='hidden' name='spotid' value='$spotid' /><input type='image' name='bad' style='width:27px;height:auto;display:inline;' src='static/voting/down.png' value='$bad' /></form></span>".$bad."</td>
	</tr>";
	} else {
	echo "
	<tr>
	<td style='text-align:center;'>".$spotid."</td>
	<td>".$pokemon."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $pokemon?>.png" title="<?php echo $id; ?> (#<?php echo $pokemon?>)" height="24" width="24"><p style="padding-top:6%;"><?php echo $id; ?></p><?php echo "</td>
	<td>".$cp."</td>
	<td>".$iv."%</td>
	<td>".$hr.":".$minutes."</td>
	<td>"?><a href="./?loc=<?php echo "".$latitude,",".$longitude.""?>&zoom=19"><?php echo $fulladdress;?></a><?php echo "</td>
	";
	}

}}
echo "</table></center><p id='pages'>";
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
$('#myModal').on('show.bs.modal', function (e) {
    var loadurl = $(e.relatedTarget).data('load-url');
    $(this).find('.modal-body').load(loadurl);
});
</script>

<script>
 $(document).ready(function(){
  $("#questsearch").select2({
   templateResult: formatState1,
   width:'100%'
  });
 });
 
 function formatState1 (state) {
  if (!state.id) { return state.text; }
  var $state = $(
   '<span ><img style="display: inline-block;" src="static/quests/' + state.element.label.toLowerCase() + '.png" heigth="24" width="24"/> ' + state.text + '</span>'
  );
  return $state;
 }
 $(document).ready(function(){
  $("#pokestopsearch").select2({
   templateResult: formatState2,
   width:'100%'
  });
 });
 
 function formatState2 (state) {
  if (!state.id) { return state.text; }
  var $state = $(
   '<span ><img style="display: inline-block;" src="static/stops/stops.png" heigth="24" width="24"/> ' + state.text + '</span>'
  );
  return $state;
 }
  $(document).ready(function(){
  $("#rewardsearch").select2({
   templateResult: formatState3,
   sorter: sortresults,
   width:'100%'
  });
 });
 
   function sortresults (state) {
    return state.sort(function (a, b) {
        if (a.text > b.text) {
            return 1;
        }
        if (a.text < b.text) {
            return -1;
        }
        return 0;
    });
}
 
 function formatState3 (state) {
  if (!state.id) { return state.text; }
  var $state = $(
   '<span ><img style="display: inline-block;" src="static/rewards/' + state.element.label.toLowerCase() + '.png" heigth="24" width="24"/> ' + state.text + '</span>'
  );
  return $state;
 }
</script>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <iframe src="submit-quest.php?stopid=<?php echo $_GET['stopid'];?>"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
var customLabel = {
  monster: {
    label: 'pokemon'
  }
};

  function initMap() {
   map = L.map('map', {
        center: [

<?php
if (isset($_GET['loc'])) {
    echo $_GET['loc'];
} else {
echo $mapcenter;
}?>

],
        zoom:

<?php
if (isset($_GET['zoom'])) {
    echo $_GET['zoom'];
} else {
echo 15;
}?>,
        maxZoom: 18,
        zoomControl: false
    })

map.addLayer(L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'}))

    // Change this depending on the name of your PHP or XML file
    downloadUrl('./frontend/xml.php', function(data) {
      var xml = data.responseXML;
      var markers = xml.documentElement.getElementsByTagName('marker');
      Array.prototype.forEach.call(markers, function(markerElem) {
        var id = markerElem.getAttribute('id');
        var spotid = markerElem.getAttribute('spotid');
        var pokemon = markerElem.getAttribute('pokemon');
        var cp = markerElem.getAttribute('cp');
        var iv = markerElem.getAttribute('iv');
		var hour = markerElem.getAttribute('hour');
		var min = markerElem.getAttribute('min');
		var ampm = markerElem.getAttribute('ampm');
        var type = markerElem.getAttribute('id');
		var good = markerElem.getAttribute('good');
		var bad = markerElem.getAttribute('bad');
		var spotter = markerElem.getAttribute('spotter');
        var point = (
            parseFloat(markerElem.getAttribute('latitude')),
            parseFloat(markerElem.getAttribute('longitude')));


        var icon = customLabel[type] || {};
        var image = new L.Icon({
            iconUrl: './static/icons/' + id + '.png',
            iconSize: [32, 32]
        });
		
		var html = '<div class=\"maplabel\"><center><img src=\"./static/icons/' + id + '.png\" height=\"45\" width=\"45\"></img><p><b>' 
		+ pokemon + ' (#' + id + ')</b><br>CP: ' + cp + '<br>IV: '+ iv + '%<br>Found: ' + hour + ':' + min + ' ' + ampm +
		'<?php if(isset($_SESSION["uname"])){?><br><hr><a href =\"./good.php?spotid=' + spotid + '&loc=' + markerElem.getAttribute('latitude') + ',' + markerElem.getAttribute('longitude') + '\"><img src=\"./static/voting/up.png\" height=\"25\" width=\"25\"></img></a>' + good +
		' x Found<br><a href =\"./bad.php?spotid=' + spotid + '&loc=' + markerElem.getAttribute('latitude') + ',' + markerElem.getAttribute('longitude') + '\"><img src=\"./static/voting/down.png\" height=\"25\" width=\"25\"></img></a>' + bad + ' x Not found<?php }?><br><hr><a href=\"http://maps.google.com/maps?q=' + 
		markerElem.getAttribute('latitude') + ',' + markerElem.getAttribute('longitude') + '\">Google Maps</a><?php if(isset($_SESSION["uname"])){?><br><hr>Spotted by: <b>' + spotter + '</b><?php }?></center></div>';
		
        var marker = new L.marker([parseFloat(markerElem.getAttribute('latitude')), parseFloat(markerElem.getAttribute('longitude'))],{
          icon: image
        }).bindPopup(html);
        marker.on('click', function() {
          marker.openPopup();
        });
        map.addLayer(marker)
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
		var bossname = markerElem.getAttribute('bossname');
		var raidby = markerElem.getAttribute('raidby');
		var eggby = markerElem.getAttribute('eggby');
		var bosscp = markerElem.getAttribute('bosscp');
		var exraid = markerElem.getAttribute('exraid');
		var exraiddate = markerElem.getAttribute('exraiddate');
			

		if (actraid === "0" && egg === "0"){
			if (exraid === "1"){
			var html = '<div class=\"maplabel\"><center><img src=\"./static/gyms/' + gteam + 'ex.png\" height=\"45px\" width=\"45px\"></img><p><b>' + gname + '</b><br>Team: ' + tid + '<?php if(!isset($_SESSION["uname"])){?><hr><b><span class="text-danger">Login to change/add teams or raids.</span></b><?php }?><?php if(isset($_SESSION["uname"])){?><br><hr><strong>EX Raid On:</strong><br> ' + exraiddate + '<br><hr><b>Choose team:</b><br><form action=\"./gymteam.php\" name=\"postInstinct\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"2\"></form><form action=\"./gymteam.php\" name=\"postValor\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"3\"></form><form action=\"./gymteam.php\" name=\"postMystic\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"4\"></form><a href=\"javascript:submitInstinct();\"><img border="0" alt="W3Schools" src="./static/teams/2.png" width="25" height="25"></a> / <a href="javascript:submitValor();\"><img border="0" alt="W3Schools" src="./static/teams/3.png" width="25" height="25"></a> / <a href="javascript:submitMystic();\"><img border="0" alt="W3Schools" src="./static/teams/4.png" width="25" height="25"></a><?php };?><br><hr><a href=\"http://maps.google.com/maps?q=' + markerElem.getAttribute('glatitude') + ',' + markerElem.getAttribute('glongitude') + '\">Google Maps</a></center></div>';
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: './static/gyms/' + gteam + 'ex.png',
            iconSize: [55, 55]
			});
			} else if (exraid === "0"){
			var html = '<div class=\"maplabel\"><center><img src=\"./static/gyms/' + gteam + '.png\" height=\"45px\" width=\"45px\"></img><p><b>' + gname + '</b><br>Team: ' + tid + '<?php if(!isset($_SESSION["uname"])){?><hr><b><span class="text-danger">Login to change/add teams or raids.</span></b><?php }?><?php if(isset($_SESSION["uname"])){?><br><hr><b>Choose team:</b><br><form action=\"./gymteam.php\" name=\"postInstinct\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"2\"></form><form action=\"./gymteam.php\" name=\"postValor\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"3\"></form><form action=\"./gymteam.php\" name=\"postMystic\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"4\"></form><a href=\"javascript:submitInstinct();\"><img border="0" alt="W3Schools" src="./static/teams/2.png" width="25" height="25"></a> / <a href="javascript:submitValor();\"><img border="0" alt="W3Schools" src="./static/teams/3.png" width="25" height="25"></a> / <a href="javascript:submitMystic();\"><img border="0" alt="W3Schools" src="./static/teams/4.png" width="25" height="25"></a><?php };?><br><hr><a href=\"http://maps.google.com/maps?q=' + markerElem.getAttribute('glatitude') + ',' + markerElem.getAttribute('glongitude') + '\">Google Maps</a></center></div>';
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: './static/gyms/' + gteam + '.png',
            iconSize: [55, 55]
			});
			}
		} else if (actraid !== "0" && egg === "0"){
			if (exraid === "0"){
			var html = '<div class=\"maplabel\"><center><img src=\"./static/icons/' + actboss + '.png\" height=\"45px\" width=\"45px\"></img><p><b>' + gname + '</b><br>Boss: ' + bossname + '<br>CP: ' + bosscp + '<br>Team: ' + tid + '<br>Expires: ' + hour + ':' + min + ' ' + ampm + '<?php if(!isset($_SESSION["uname"])){?><hr><b><span class="text-danger">Login to change/add teams or raids.</span></b><?php }?><?php if(isset($_SESSION["uname"])){?><br><hr><b>Choose team:</b><br><form action=\"./gymteam.php\" name=\"postInstinct\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"2\"></form><form action=\"./gymteam.php\" name=\"postValor\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"3\"></form><form action=\"./gymteam.php\" name=\"postMystic\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"4\"></form><a href=\"javascript:submitInstinct();\"><img border="0" alt="W3Schools" src="./static/teams/2.png" width="25" height="25"></a> / <a href="javascript:submitValor();\"><img border="0" alt="W3Schools" src="./static/teams/3.png" width="25" height="25"></a> / <a href="javascript:submitMystic();\"><img border="0" alt="W3Schools" src="./static/teams/4.png" width="25" height="25"></a><?php };?><br><hr><a href=\"http://maps.google.com/maps?q=' + markerElem.getAttribute('glatitude') + ',' + markerElem.getAttribute('glongitude') + '\">Google Maps</a><?php if(isset($_SESSION["uname"])){?><br><hr><b>Spotted by: </b>' + raidby + '<?php }?></center></div>';
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: './static/raids/' + actboss + '.png',
            iconSize: [55, 55]
			});
			} else if (exraid === "1"){
			var html = '<div class=\"maplabel\"><center><img src=\"./static/icons/' + actboss + '.png\" height=\"45px\" width=\"45px\"></img><p><b>' + gname + '</b><br>Boss: ' + bossname + '<br>CP: ' + bosscp + '<br>Team: ' + tid + '<br>Expires: ' + hour + ':' + min + ' ' + ampm + '<?php if(!isset($_SESSION["uname"])){?><hr><b><span class="text-danger">Login to change/add teams or raids.</span></b><?php }?><?php if(isset($_SESSION["uname"])){?><br><hr><strong>EX Raid On:</strong><br> ' + exraiddate + '<br><hr><b>Choose team:</b><br><form action=\"./gymteam.php\" name=\"postInstinct\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"2\"></form><form action=\"./gymteam.php\" name=\"postValor\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"3\"></form><form action=\"./gymteam.php\" name=\"postMystic\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"4\"></form><a href=\"javascript:submitInstinct();\"><img border="0" alt="W3Schools" src="./static/teams/2.png" width="25" height="25"></a> / <a href="javascript:submitValor();\"><img border="0" alt="W3Schools" src="./static/teams/3.png" width="25" height="25"></a> / <a href="javascript:submitMystic();\"><img border="0" alt="W3Schools" src="./static/teams/4.png" width="25" height="25"></a><?php };?><br><hr><a href=\"http://maps.google.com/maps?q=' + markerElem.getAttribute('glatitude') + ',' + markerElem.getAttribute('glongitude') + '\">Google Maps</a><?php if(isset($_SESSION["uname"])){?><br><hr><b>Spotted by: </b>' + raidby + '<?php }?></center></div>';
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: './static/raids/' + actboss + '.png',
            iconSize: [75, 75]
			});
			}
		} else if (actraid === "0" && egg !== "0"){
			if (exraid === "0"){
			var html = '<div class=\"maplabel\"><center><img src=\"./static/eggs/' + egg + '.png\" height=\"45px\" width=\"45px\"></img><p><b>' + gname + '</b><br>Egg level: ' + egg + '<br>Team: ' + tid + '<br>Hatches at: ' + hour + ':' + min + ' ' + ampm + '<?php if(!isset($_SESSION["uname"])){?><hr><b><span class="text-danger">Login to change/add teams or raids.</span></b><?php }?><?php if(isset($_SESSION["uname"])){?><br><hr><b>Choose team:</b><br><form action=\"./gymteam.php\" name=\"postInstinct\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"2\"></form><form action=\"./gymteam.php\" name=\"postValor\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"3\"></form><form action=\"./gymteam.php\" name=\"postMystic\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"4\"></form><a href=\"javascript:submitInstinct();\"><img border="0" alt="W3Schools" src="./static/teams/2.png" width="25" height="25"></a> / <a href="javascript:submitValor();\"><img border="0" alt="W3Schools" src="./static/teams/3.png" width="25" height="25"></a> / <a href="javascript:submitMystic();\"><img border="0" alt="W3Schools" src="./static/teams/4.png" width="25" height="25"></a><?php };?><br><hr><a href=\"http://maps.google.com/maps?q=' + markerElem.getAttribute('glatitude') + ',' + markerElem.getAttribute('glongitude') + '\">Google Maps</a><?php if(isset($_SESSION["uname"])){?><br><hr><b>Spotted by: </b>' + eggby + '<?php }?></center></div>';		
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: './static/eggs/' + egg + '.png',
            iconSize: [55, 55]
			});
			} else if (exraid === "1"){
			var html = '<div class=\"maplabel\"><center><img src=\"./static/eggs/' + egg + '.png\" height=\"45px\" width=\"45px\"></img><p><b>' + gname + '</b><br>Egg level: ' + egg + '<br>Team: ' + tid + '<br>Hatches at: ' + hour + ':' + min + ' ' + ampm + '<?php if(!isset($_SESSION["uname"])){?><hr><b><span class="text-danger">Login to change/add teams or raids.</span></b><?php }?><?php if(isset($_SESSION["uname"])){?><br><hr><strong>EX Raid On:</strong><br> ' + exraiddate + '<br><hr><b>Choose team:</b><br><form action=\"./gymteam.php\" name=\"postInstinct\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"2\"></form><form action=\"./gymteam.php\" name=\"postValor\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"3\"></form><form action=\"./gymteam.php\" name=\"postMystic\" method=\"post\"\"><input type=\"hidden\" name=\"gname\" value=\"' + gid + '\"><input type=\"hidden\" name=\"tname\" value=\"4\"></form><a href=\"javascript:submitInstinct();\"><img border="0" alt="W3Schools" src="./static/teams/2.png" width="25" height="25"></a> / <a href="javascript:submitValor();\"><img border="0" alt="W3Schools" src="./static/teams/3.png" width="25" height="25"></a> / <a href="javascript:submitMystic();\"><img border="0" alt="W3Schools" src="./static/teams/4.png" width="25" height="25"></a><?php };?><br><hr><a href=\"http://maps.google.com/maps?q=' + markerElem.getAttribute('glatitude') + ',' + markerElem.getAttribute('glongitude') + '\">Google Maps</a><?php if(isset($_SESSION["uname"])){?><br><hr><b>Spotted by: </b>' + eggby + '<?php }?></center></div>';		
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: './static/eggs/' + egg + '.png',
            iconSize: [55, 55]			
			});
			}
		}
        var marker = new L.marker([parseFloat(markerElem.getAttribute('glatitude')), parseFloat(markerElem.getAttribute('glongitude'))],{
          icon: image
        }).bindPopup(html);
        marker.on('click', function() {
          marker.openPopup();
        });
        map.addLayer(marker)
      });
    });
	
	downloadUrl('./frontend/sxml.php', function(data) {
      var xml = data.responseXML;
      var markers = xml.documentElement.getElementsByTagName('marker');
      Array.prototype.forEach.call(markers, function(markerElem) {
        var sid = markerElem.getAttribute('sid');
        var sname = markerElem.getAttribute('sname');
		var quest = markerElem.getAttribute('quest');
		var quested = markerElem.getAttribute('quested');
        var reward = markerElem.getAttribute('reward');
		var type = markerElem.getAttribute('type');
        var questby = markerElem.getAttribute('questby');
		
		if (quested === "1"){
		
		var html = '<div class=\"maplabel\"><center><img src=\"./static/stops/queststop.png\" height=\"45\" width=\"45\"></img><p><b>' + sname + '</b><?php if(!isset($_SESSION["uname"])){?><br>(<b><span class="text-success">Quested</span></b>)<br><hr><b><span class="text-danger">Login to add/view quests.</span></b><?php }?><?php if(isset($_SESSION["uname"])){?></b><br><hr><b>Quest:</b><br> ' + quest + '<br><hr><b>Reward:</b><br>' + reward + '<?php };?><br><hr><a href=\"http://maps.google.com/maps?q=' + markerElem.getAttribute('slatitude') + ',' + markerElem.getAttribute('slongitude') + '\">Google Maps</a><?php if(isset($_SESSION["uname"])){?><br><hr><b>Spotted by: </b>' + questby + '<?php }?></center></div>';
        var icon = customLabel[type] || {};
        var image = new L.Icon({
            iconUrl: './static/stops/queststop.png',
            iconSize: [30, 30]
			});
		} else if (quested === ""){
		var html = '<div class=\"maplabel\"><center><img src=\"./static/stops/stops.png\" height=\"45\" width=\"45\"></img><p><b>' + sname + '</b><?php if(!isset($_SESSION["uname"])){?><br><hr><b><span class="text-danger">Login to add/view quests.</span></b><?php }?><?php if(isset($_SESSION["uname"])){?><br><hr><a href=\"./submit-quest.php?stopid=' + sid + '\" data-toggle=\"modal\" data-target=\"#myModal\">Add Quest</a><?php }?><br><hr><a href=\"http://maps.google.com/maps?q=' + markerElem.getAttribute('slatitude') + ',' + markerElem.getAttribute('slongitude') + '\">Google Maps</a></center></div>';
        var icon = customLabel[type] || {};
        var image = new L.Icon({
            iconUrl: './static/stops/stops.png',
            iconSize: [30, 30]
			});				
		}
        var marker = new L.marker([parseFloat(markerElem.getAttribute('slatitude')), parseFloat(markerElem.getAttribute('slongitude'))],{
          icon: image
        }).bindPopup(html);
        marker.on('click', function() {
          marker.openPopup();
        });
        map.addLayer(marker)
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

<?php
}

///////////////// SUBMIT RAIDS \\\\\\\\\\\\\\\\\

function raidsubmission(){
require('./config/config.php');
$result = $conn->query("SELECT * FROM raidbosses");
$rid = $rboss = $rlvl = $rhour = $rmin = $rampm = $spotter="";
if(isset($_SESSION["uname"])){ 
?>

<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h3 style="text-align:center;"><strong>Add Raid:</strong></h3>
<form id="usersubmit" method="post" action="./spotraid.php">
<center><table id="added" class="table table-bordered">
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
<td style="width: 5%;">Minutes until expire:</td>
<td style="width: 10%;">
<style>
.sliderraid
{
    width: 100% !important;
}
</style>
	<input type="range" name="rtime" min="0" max="45" value="0" id="rtimerange" class="sliderraid"><span id="rtimeoutput"></span>
	<script>
var sliderraid = document.getElementById("rtimerange");
var output = document.getElementById("rtimeoutput");
output.innerHTML = "<br>Raid ends in: " + sliderraid.value + " minutes</center>";

sliderraid.oninput = function() {
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
	
	echo "<center><div style='margin-top:10px;'>";
	echo "Login to spot a Raid";
		?><br /><br /><a href="./login/login.php">Login Here</a><?php
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


<h3 style="text-align:center;"><strong>Spotted Raids:</strong></h3>

<center>

<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php
echo "<table id=\"spotted\" class=\"table table-bordered\">";
echo "<tr><th>ID</th><th>BOSS</th><th>LVL / CP</th><th>EXPIRES</th><th>LOCATION</th></tr>";
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
	<td>"?><a href="./?loc=<?php echo "".$glatitude,",".$glongitude.""?>&zoom=19"><?php echo $gname;?></a><?php echo "</td>
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
	<td>"?><a href="./?loc=<?php echo "".$glatitude,",".$glongitude.""?>&zoom=19"><?php echo $gname;?></a><?php echo "</td>
	</tr>";
	
}}
echo "</table></center><p id='pages'>";
?><center><?php
///////////////////// PAGENATION \\\\\\\\\\\\\\\\\\\\\
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."'>".$i."</a> "; 
}; 
?></center><?php
}

///////////////// SUBMIT QUESTS \\\\\\\\\\\\\\\\\

function questsubmission(){
require('./config/config.php');
$result = $conn->query("SELECT * FROM quests");
$qid = $qname= $spotter="";
if(isset($_SESSION["uname"])){ 
?>

<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h3 style="text-align:center;"><strong>Submit a Quest:</strong></h3>
<form id="usersubmit" method="post" action="./spotquest.php">
<center><table id="added" class="table table-bordered">
<tbody>


<!--///////////////////// GENERATE QUEST & REWARD LIST \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">Quests</td>
<td style="width: 10%;">
<?php
echo "<select id='questsearch' name='quest'>";
while ($row = $result->fetch_assoc()) {
    unset($qid, $qname);
        $qid = $row['qid'];
            $qname= $row['qname'];
$array[$row['type']][] = $row;
					}					
// loop the array to create optgroup
foreach($array as $key=>$value){
    // check if its an array
    if(is_array($value)){
        // create optgroup for each groupname
        echo "<optgroup label='".$key."'>";
        foreach($value as $k=>$v){
            echo "<option label='".$v['type']."' value='".$v['qid']."'>'".$v['qname']."'</option>";
        }
        echo "</optgroup>";
    }
}

					echo "</select>";

?>
</td>
</tr>

<tr>
<td style="width: 5%;">Rewards</td>
<td style="width: 10%;">
<?php
require('./config/config.php');
$result2 = $conn->query("SELECT * FROM rewards");
$reid = $rname= "";
echo "<select id='rewardsearch' name='reward'>";
while ($row2 = $result2->fetch_assoc()) {
    unset($reid, $rname);
        $reid = $row2['reid'];
            $rname= $row2['rname'];
$array2[$row2['type']][] = $row2;
					}					
// loop the array to create optgroup
foreach($array2 as $key=>$value){
    // check if its an array
    if(is_array($value)){
        // create optgroup for each groupname
        echo "<optgroup label='".$key."'>";
        foreach($value as $k=>$v){
            echo "<option label='".$v['type']."' value='".$v['reid']."'>'".$v['rname']."'</option>";
        }
        echo "</optgroup>";
    }
}

					echo "</select>";
?>
</td>
</tr>
<!--///////////////////// ADDRESS \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">At Pokestop</td>
<td style="width: 10%;">
<?php
if(isset($_GET['stopid'])){
	$result = $conn->query("SELECT * FROM stops WHERE sid =" . $_GET['stopid']);
} else {
$result = $conn->query("SELECT * FROM stops");
}
$sid = $sname = $sname = "";
echo "<select id='pokestopsearch' name='sname'>";
while ($row = $result->fetch_assoc()) {
    unset($sid, $sname);
        $sid = $row['sid'];
            $sname= $row['sname'];
				echo '<option value="'.$sid.'">'.$sname.'</option>';
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
	
	echo "<center><div style='margin-top:10px;'>";
	echo "Login to spot a Quest";
		?><br /><br /><a href="./login/login.php">Login Here</a><?php
	echo "</div></center>";
	
} }

////////////////////// SPOTTED QUESTS \\\\\\\\\\\\\\\\\\\\\\\\\


function spottedquests(){
require('./config/config.php');
$results_per_page = 10;

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql = "SELECT * from stops,quests,rewards WHERE quested='1' AND stops.actquest = quests.qid AND stops.actreward = rewards.reid ORDER BY date DESC LIMIT $start_from,".$results_per_page;
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));


$sqlcnt = "SELECT COUNT(SID) AS total FROM stops WHERE quested='1'"; 
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>


<h3 style="text-align:center;"><strong>Spotted Quests:</strong></h3>

<center>

<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php

echo "<table id=\"spotted\" class=\"table table-bordered\">";
echo "<tr><th>ID</th><th>QUEST</th><th>REWARD</th><th>SPOTTED</th><th>LOCATION</th></tr>";
while($row = mysqli_fetch_array($result)) {
	$questname = $row['qname'];
    $sname = $row['sname'];
    $reward = $row['rname'];	
	$sid = $row['sid'];
	$slat = $row['slatitude'];
	$slon = $row['slongitude'];
	$hour = $row['hour'];
	$min = $row['min'];
	$ampm = $row['ampm'];
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
	<td>".$sid."</td>
	<td>".$questname."</td>
	<td>".$reward."</td>
	<td>".$hour.":".$minutes." ".$ampm."</td>
	<td>"?><a href="./?loc=<?php echo "".$slat,",".$slon.""?>&zoom=19"><?php echo $sname;?></a><?php echo "</td>
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
	<td>".$sid."</td>
	<td>".$questname."</td>
	<td>".$reward."</td>
	<td>".$hr.":".$minutes."</td>
	<td>"?><a href="./?loc=<?php echo "".$slat,",".$slon.""?>&zoom=19"><?php echo $sname;?></a><?php echo "</td>	
	</tr>";
	
}}
echo "</table></center><p id='pages'>";
?><center><?php

///////////////////// PAGENATION \\\\\\\\\\\\\\\\\\\\\
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."'>".$i."</a> "; 
}; 
?></center><?php
}

////////////////////// SPOTTED EGGS \\\\\\\\\\\\\\\\\\\\\\\\\


function spottedeggs(){
require('./config/config.php');
$results_per_page = 10;

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql = "SELECT * FROM gyms WHERE gyms.egg != '0' ORDER BY date DESC LIMIT $start_from,".$results_per_page;
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));


$sqlcnt = "SELECT COUNT(eggby) AS total FROM gyms WHERE egg !='0'"; 
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>


<h3 style="text-align:center;"><strong>Spotted Eggs:</strong></h3>

<center>

<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php

echo "<table id=\"spotted\" class=\"table table-bordered\">";
echo "<tr><th>GYM ID</th><th>EGG LVL</th><th>HATCHES</th><th>LOCATION</th></tr>";
while($row = mysqli_fetch_array($result)) {
	$gid = $row['gid'];
	$hour = $row['hour'];
	$min = $row['min'];
	$ampm = $row['ampm'];
	$glatitude = $row['glatitude'];
	$glongitude = $row['glongitude'];
	$minutes = $min;
	$hr = $hour;
	$gname = $row['gname'];
	$egg = $row['egg'];
	
	
	///////////////////// ADDS "0" TO SIGNLE DIGIT MINUTE TIMES \\\\\\\\\\\\\\\\\\\\\
	if ($min < 10) {
    $minutes = str_pad($min, 2, "0", STR_PAD_LEFT);	
	}
	
	///////////////////// 12 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
	if ($clock=="false"){
		
	///////////////////// 12 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td>".$gid."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/eggs/<?php echo $egg?>.png" title="<?php echo $egg; ?>" height="24" width="24"><p style="padding-top:6%;"><?php echo $egg; ?></p><?php echo "</td>
	<td>".$hour.":".$minutes." ".$ampm."</td>
	<td>"?><a href="./?loc=<?php echo "".$glatitude,",".$glongitude.""?>&zoom=19"><?php echo $gname;?></a><?php echo "</td>
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
	<td>".$gid."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/eggs/<?php echo $egg?>.png" title="<?php echo $egg; ?>" height="24" width="24"><p style="padding-top:6%;"><?php echo $egg; ?></p><?php echo "</td>
	<td>".$hr.":".$minutes."</td>
	<td>"?><a href="./?loc=<?php echo "".$glatitude,",".$glongitude.""?>&zoom=19"><?php echo $gname;?></a><?php echo "</td>
	</tr>";
	
}}
echo "</table></center><p id='pages'>";
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
$gid = $gname = $gteam = $teamby="";
if(isset($_SESSION["uname"])){ 
?>

<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h3 style="text-align:center;"><strong>Gym team:</strong></h3>
<form id="usersubmit" method="post" action="./gymteam.php">
<center><table id="added" class="table table-bordered">
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
	
	echo "<center><div style='margin-top:10px;'>";
	echo "Login to spot a team";
		?><br /><br /><a href="./login/login.php">Login Here</a><?php
	echo "</div></center>";
}}


function eggsubmission(){
require('./config/config.php');
$result = $conn->query("SELECT * FROM gyms,teams WHERE gyms.gteam = teams.tid");
$gid = $gname = $gteam = $eggby="";
if(isset($_SESSION["uname"])){
?>

<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h3 style="text-align:center;"><strong>Spot Egg:</strong></h3>
<form id="usersubmit" method="post" action="./spotegg.php">
<center><table id="added" class="table table-bordered">
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
<td style="width: 5%;">Hatches in</td>
<td style="width: 10%;">
<style>
.slideregg
{
    width: 100% !important;
}
</style>
	<input type="range" name="etime" min="0" max="60" value="0" id="etimerange" class="slideregg"><span id="etimeoutput"></span>
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
<center><td style="width:10%;"><input type="submit" value="SPOT!"/></td></center>

</tbody>
</table></center>
</form>

<?php } else{
	echo "<center><div style='margin-top:10px;'>";
	echo "Login to spot an Egg";
		?><br /><br /><a href="./login/login.php">Login Here</a><?php
	echo "</div></center>";
	
} }

function profile(){ 
if(isset($_SESSION["uname"])){
require('config/config.php');
$result = $conn->query("SELECT * FROM users,usergroup WHERE uname='".$_SESSION['uname']."' AND users.usergroup = usergroup.id LIMIT 1  ");

$gcountquery = $conn->query("SELECT * FROM `gyms`");
$gcountresult = mysqli_num_rows($gcountquery);

$scountquery = $conn->query("SELECT * FROM `stops`");
$scountresult = mysqli_num_rows($scountquery);  

$eggcountquery = $conn->query("SELECT * FROM `gyms` WHERE egg != 0");
$eggcountresult = mysqli_num_rows($eggcountquery);  

$raidcountquery = $conn->query("SELECT * FROM `gyms` WHERE actraid != 0");
$raidcountresult = mysqli_num_rows($raidcountquery); 

$teamcountquery = $conn->query("SELECT * FROM `gyms` WHERE gteam > 1");
$teamcountresult = mysqli_num_rows($teamcountquery); 

$moncountquery = $conn->query("SELECT * FROM `spots`");
$moncountresult = mysqli_num_rows($moncountquery); 

$questcountquery = $conn->query("SELECT * FROM `stops` WHERE quested != 0");
$questcountresult = mysqli_num_rows($questcountquery); 

$totalspots = $eggcountresult + $raidcountresult + $teamcountresult + $moncountresult + $questcountresult;

$id = $usergroup = "";?>
<h3 style="text-align:center;"><strong>Your Profile:</strong></h3>
<?php
	$versionquery = "SELECT version FROM version";
	$versionresult = $conn->query($versionquery);
	$rowversion = $versionresult->fetch_array(MYSQLI_NUM);
	$version = $rowversion[0];
	echo "<center><table id=\"spotted\" class=\"table table-bordered\">";
	echo "<tr><th>Pic<th>User</th><th>Email</th><th>Usergroup</th></tr>";
	while ($row = $result->fetch_assoc()) {
	$id = $row['id'];
    $uname = $row['uname'];
    $email = $row['email'];
	$usergroup = $row['groupname'];
	$url = $row['url'];
	$offtrades = $row['offtrades'];
	$reqtrades = $row['reqtrades'];
	$tradetotal = $offtrades + $reqtrades;
	
	echo "<tr>"; ?>
	<td><?php if ($url !== ''){?><img src="./userpics/<?php echo $url; ?>" height="50px" width="50px" alt="logo"  style="border:1px solid black"><?php } else {?><img src="./userpics/nopic.png" height="50px" width="50px" alt="logo"  style="border:1px solid black"><?php }?></td>
	<td><?php echo $uname; ?></td>
	<td><?php echo $email; ?></td>
	<td><?php echo $usergroup; ?></td>
	</tr>
	</table>
	<?php echo "<br /><center><a href='./edit-profile.php'>Edit Profile</a></center><br>"; ?>
	<center><table id="spotted" class="table table-bordered">
	<tr>
        <th colspan="2"><strong><center>Trades</strong></th>
        </tr>
		<tr>
        <td><strong>My Created Trades:</strong></td>
        <td><a href="./my-trades.php">Created Trades</a></td>
        </tr>
		<tr>
        <td><strong>My Accepted Trades:</strong></td>
        <td><a href="./accepted-trades.php">Accepted Trades</a></td>
        </tr>
		<tr>
        <td><strong>Offered:</strong></td>
        <td><?php echo $offtrades?></td>
        </tr>
		<tr>
        <td><strong>Requested:</strong></td>
        <td><?php echo $reqtrades?></td>
        </tr>
		<tr>
        <td><strong>Total:</strong></td>
        <td><strong><?php echo $tradetotal?></strong></td>
        </tr>
<?php echo "</table></center>";
		
		if ("$usergroup" == 'admin'){
		?>
		
		<h3 style="text-align:center;"><strong>Admin Panel:</strong></h3>
		<center>
		<a href="gymcsv.php">Upload Gym .CSV</a><br />
		<a href="stopcsv.php">Upload Stop .CSV</a><br />
		
		<h3 style="text-align:center;"><strong>Database overview</strong></h3>
		
		<center><table id="spotted" class="table table-bordered">
        <tbody>
        <tr>
        <th colspan="2"><strong><center>Database</strong></th>
        </tr>	
        <tr>
        <td>Gyms</td>
        <td><?php echo $gcountresult?></td>
        </tr>
        <tr>
        <td>Stops</td>
        <td><?php echo $scountresult?></td>
        </tr>
        <tr>
        <td>Database version</td>
        <td><?php echo $version?></td>
        </tr>		
        <tr>
        <th colspan="2"><strong><center>Spots</strong></th>
        </tr>		
        <tr>
        <td>Pokemon</td>
        <td><?php echo $moncountresult?></td>
        </tr>			
        <tr>
        <td>Raids</td>
        <td><?php echo $raidcountresult?></td>
        </tr>
        <tr>
        <td>Eggs</td>
        <td><?php echo $eggcountresult?></td>
        </tr>
        <tr>
        <td>Teams</td>
        <td><?php echo $teamcountresult?></td>
        </tr>		
        <tr>
        <td>Quests</td>
        <td><?php echo $questcountresult?></td>
        </tr>			
        <tr>
        <td><strong>Total spots:</strong></td>
        <td><strong><?php echo $totalspots?></strong></td>
        </tr>			
		<tr>
		<td colspan="2"><a href="./droptables.php" onclick="return confirm('Are you sure?');"><center>Drop database</center></a></td>
		</tr>
        </tbody>
        </table></center>
		<?php
	}
	
	}
} else{
	echo "<center><div style='margin-top:10px;'>";
	echo "Login to view your profile";
		?><br /><br /><a href="/login/login.php">Login Here</a><?php
	echo "</div></center></table></center>";
} }


function editprofile(){ 
if(isset($_SESSION["uname"])){
require('config/config.php');
$result = $conn->query("SELECT * FROM users,usergroup WHERE uname='".$_SESSION['uname']."' AND users.usergroup = usergroup.id LIMIT 1  "); 
$id = $usergroup = "";?>
<h3 style="text-align:center;"><strong>Edit Your Profile:</strong></h3>
<?php
	echo "<center><table id=\"spotted\" class=\"table table-bordered\">";
	
	while ($row = $result->fetch_assoc()) {
	$id = $row['id'];
    $uname = $row['uname'];
    $email = $row['email'];
	$usergroup = $row['groupname'];
	$url = $row['url'];?>
	
	<tr>
	<form action="editusername.php" method="post">
	<?php echo "<th style='background-color:#fff;color:#000;width:10%;'><center>Username: </center></th>";?>
	<?php echo "<td><center><input type='text' name='uname' id='uname' style='float:left;'><br><br><input type='submit' value='Submit' id='submit_name' style='float:left;'></center></td></form>";?>	
	</tr>
	
	<tr>
	<form action="editemail.php" method="post">
	<?php echo "<th style='background-color:#f9f9f9;color:#000;'><center>Email: </center></th>";?>
	<?php echo "<td><center><input type='text' name='email' id='email' style='float:left;' pattern=\"[a-zA-Z0-9!#$%&amp;'*+\/=?^_`{|}~.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\"><br><br><input type='submit' value='Submit' id='submit_email' style='float:left;'></center></td></form>";?>
	</tr>

	<tr>
	<form action="editpassword.php" method="post">
	<?php echo "<th style='background-color:#fff;color:#000;'><center>Password: </center><br><br></th>";?>
    <?php echo "<td><center><span id='error-nwl' style='font-size:10px;float:left;'></span><br><input type='password' minlength='6' name='password' id='password' placeholder='password' onkeyup='checkPass(); return false;' style='float:left;'><br><br><input type='password' minlength='6' name='confirm_password' id='confirm_password' placeholder='confirm password' onkeyup='checkPass(); return false;' style='float:left;'><br><br><input type='submit' name='submit'  value='Submit' id='submit_pass' style='float:left;'></td></form>";?>
	</tr>
        <script>
            $('input[id="submit_pass"]').attr('disabled','disabled');
            function checkPass()
            {
                var pass1 = document.getElementById('password');
                var pass2 = document.getElementById('confirm_password');
                var message = document.getElementById('error-nwl');
                var goodColor = "#66cc66";
                var badColor = "#ff6666";

                if(pass1.value.length > 5)
                {
                    pass1.style.backgroundColor = goodColor;
                    message.style.color = goodColor;
                    $('input[type="submit"]').attr('disabled','disabled');
                    message.innerHTML = "Character number ok!<br>"
                }
                else
                {
                    pass1.style.backgroundColor = badColor;
                    message.style.color = badColor;
                    $('input[type="submit"]').attr('disabled','disabled');
                    message.innerHTML = "You have to enter at least 6 digit!<br>"
                    return;
                }

                if(pass1.value == pass2.value)
                {
                    pass2.style.backgroundColor = goodColor;
                    message.style.color = goodColor;
                    $('input[type="submit"]').removeAttr('disabled');
					message.innerHTML = "Ready to go!<br>"
                }
                else
                {
                    pass2.style.backgroundColor = badColor;
                    message.style.color = badColor;
                    $('input[type="submit"]').attr('disabled','disabled');
                    message.innerHTML = "These passwords don't match!<br>"
                }
            }
        </script>
        <?php echo "</table>";?>
	<h3 style="text-align:center;"><strong>Upload profile picture:</strong></h3>
	<?php
	}
	
if($conn->connect_errno){
echo $conn->connect_error;
}
$pull="select * from users where uname='".$_SESSION['uname']."'";

// Lookup id for user
$urlquery = "SELECT id FROM users WHERE uname = '".$_SESSION['uname']."'";
$resulturl = $conn->query($urlquery);
$rowurl = $resulturl->fetch_array(MYSQLI_NUM);
$userid = $rowurl[0];
$allowedExts = array("jpg", "jpeg", "gif", "png","JPG");
$extension = @end(explode(".", $_FILES["file"]["name"]));
if(isset($_POST['pupload'])){
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/JPG")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "image/pjpeg"))
&& ($_FILES["file"]["size"] < 800000)
&& in_array($extension, $allowedExts))
{
	if ($_FILES["file"]["error"] > 0)
	{
	echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
	}
	else
	{
		echo '<div class="plus">';
		echo "Uploaded Successully";
		echo '</div>';echo"<br/><b><u>Image Details</u></b><br/>";

		echo "Name: " . $_FILES["file"]["name"] . "<br/>";
		echo "Type: " . $_FILES["file"]["type"] . "<br/>";
		echo "Size: " . ceil(($_FILES["file"]["size"] / 1024)) . " KB";

		if (file_exists("./userpics/" . $_FILES["file"]["name"]))
		{
		unlink("./userpics/" . $_FILES["file"]["name"]);
		}
		else{
			$pic=$_FILES["file"]["name"];
			$conv=explode(".",$pic);
			$ext=$conv['1'];
			move_uploaded_file($_FILES["file"]["tmp_name"],"./userpics/".$userid.".".$ext);
			echo "Stored in as: " . "./userpics/".$userid.".".$ext;
			$urlpic=$userid.".".$ext;

			$query="update users set url='$urlpic', lastUpload=now() where uname='".$_SESSION['uname']."'";
			if($upl=$conn->query($query)){
			echo "<br/>Saved to Database successfully";
			echo "<meta http-equiv='refresh' content='3;url=profile.php'>";
			}
		 }
	}
}else{
 echo "File Size exceeded 800kb limit or wrong extension, please upload .png/gif/jpg";
}
}
?>
<form action="" method="post" enctype="multipart/form-data">
<?php
$res=$conn->query($pull);
$pics=$res->fetch_assoc();
echo '<div class="imgLow">';
?>
<?php if ($url!=='') {?><img src="./userpics/<?php echo $url; ?>" height="50px" width="50px" alt="logo"  style="border:1px solid black"><?php } else {?><img src="./userpics/nopic.png" height="50px" width="50px" alt="logo"  style="border:1px solid black"><?php }?>
<?php echo "</div><br>";
?>
<input type="file" name="file" />
<input type="submit" name="pupload" class="button" value="Upload"/>
</form></center><?php

	} else{
	echo "<center><div style='margin-top:10px;'>";
	echo "Login to view your profile";
		?><br /><br /><a href="/login/login.php">Login Here</a><?php
	echo "</div></center></table></center>";
} }

///////////////// SUBMIT EX RAIDS \\\\\\\\\\\\\\\\\

function exraidsubmission(){
require('./config/config.php');
if(isset($_SESSION["uname"])){ 
?>

<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h3 style="text-align:center;"><strong>Add EX Raid:</strong></h3>
<form id="usersubmit" method="post" action="./spotexraid.php">
<center><table id="added" class="table table-bordered">
<tbody>

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

<!--///////////////////// DATE & TIME \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">Date & Time:</td>
<td style="width: 10%;">

<input type="datetime-local" name="exraiddate">

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
		?><br /><br /><a href="./login/login.php">Login Here</a><?php
	echo "</div></center>";
	
} }

////////////////////// SPOTTED RAIDS \\\\\\\\\\\\\\\\\\\\\\\\\
function spottedexraids(){
require('./config/config.php');
$results_per_page = 10;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;

if ($clock=="true"){
$sql = "SELECT exid, DATE_FORMAT(`gyms`.`exraiddate`, '%d-%m-%Y %H:%m:%s') as exraiddate , gyms.gname, spotter, glatitude, glongitude FROM exraids, gyms WHERE exraids.gname = gyms.gid ORDER BY exraids.exraiddate ASC LIMIT $start_from,".$results_per_page;
} else {
$sql = "SELECT exid, DATE_FORMAT(`gyms`.`exraiddate`, '%Y-%m-%d %h:%m:%s %p') as exraiddate , gyms.gname, spotter, glatitude, glongitude FROM exraids, gyms WHERE exraids.gname = gyms.gid ORDER BY exraids.exraiddate ASC LIMIT $start_from,".$results_per_page;
}

$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));
$sqlcnt = "SELECT COUNT(EXID) AS total FROM exraids";
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>


<h3 style="text-align:center;"><strong>Spotted EX Raids:</strong></h3>

<center>

<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php
echo "<table id=\"spotted\" class=\"table table-bordered\">";
echo "<tr><th>EX ID</th><th>GYM</th><th>Date and Time</th><th>Spotter</th><th>Attendance</th></tr>";
while($row = mysqli_fetch_array($result)) {
	$exid = $row['exid'];
	$exraiddate = $row['exraiddate'];
	$gname = $row['gname'];
	$spotter = $row['spotter'];
	$glatitude = $row['glatitude'];
	$glongitude = $row['glongitude'];
	
	///////////////////// 12 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
	if ($clock=="false"){
		
	///////////////////// 12 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td>"?><center><?php echo $exid;?><center><?php echo"</td>
	<td>"?><a href="./?loc=<?php echo "".$glatitude,",".$glongitude.""?>&zoom=19"><?php echo $gname;?></a><?php echo "</td>
	<td>".$exraiddate."</td>
	<td>"?><center><?php echo $spotter;?><center><?php echo "</td>
	<td>"?><center><form action='attendance.php' method='post'><input type='hidden' name='exidr' value="<?php echo $exid; ?>" /><input type='image' name='att' style='width:25px;height:auto;align:middle;' src='static/voting/up.png' value="<?php echo $_SESSION['uname']?>" /></form><a href='./ex-attendance.php' style='display:inline;'>View</a></center><?php echo "</td>
	</tr>";
		
	} else {
	///////////////////// 24 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
	
	///////////////////// 24 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td>"?><center><?php echo $exid;?><center><?php echo"</td>
	<td>"?><a href="./?loc=<?php echo "".$glatitude,",".$glongitude.""?>&zoom=19"><?php echo $gname;?></a><?php echo "</td>
	<td>".$exraiddate."</td>
	<td>"?><center><?php echo $spotter;?><center><?php echo "</td>
	<td>"?><center><form action='attendance.php' method='post'><input type='hidden' name='exidr' value="<?php echo $exid; ?>" /><input type='image' name='att' style='width:25px;height:auto;align:middle;' src='static/voting/up.png' value="<?php echo $_SESSION['uname']?>" /></form><a href='./ex-attendance.php' style='display:inline;'>View</a></center><?php echo "</td>
	</tr>";
	
}}
echo "</table></center><p id='pages'>";
?><center><?php
///////////////////// PAGENATION \\\\\\\\\\\\\\\\\\\\\
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."'>".$i."</a> "; 
}; 
?></center><?php
}

function exatt(){
require('./config/config.php');


?>


<h3 style="text-align:center;"><strong>EX Raid Attendance:</strong></h3>

<center>

<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php
echo "<table id=\"spotted\" class=\"table table-bordered\">";
echo "<tr><th>ID</th><th>Gym</th><th>Date and Time</th><th>Attending</th></tr>";
$results_per_page = 15;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql1 = "SELECT * FROM exraidatt,exraids,gyms WHERE exraidatt.exid = exraids.exid AND exraids.gname = gyms.gid ORDER BY exraids.exid ASC LIMIT $start_from,".$results_per_page;
$result = mysqli_query($conn,$sql1)or die(mysqli_error($conn));
$sqlcnt = "SELECT COUNT(EXID) AS total FROM exraidatt";
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
while($row = mysqli_fetch_array($result)) {
	
	$exid = $row['exid'];
	$uid = $row['uid'];
	$gname = $row['gname'];
	$exraiddate = $row['exraiddate'];
	$glatitude = $row['glatitude'];
	$glongitude = $row['glongitude'];
	///////////////////// 12 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\

	if ($clock=="false"){
		
	///////////////////// 12 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td>"?><center><?php echo $exid;?><center><?php echo"</td>
	<td>"?><a href="./?loc=<?php echo "".$glatitude,",".$glongitude.""?>&zoom=19"><?php echo $gname;?></a><?php echo "</td>
	<td>".$exraiddate."</td>
	<td>".$uid."</td>
	</tr>";
		
	} else {
	///////////////////// 24 HOUR FORMAT \\\\\\\\\\\\\\\\\\\\\
	
	///////////////////// 24 HOUR TABLE LAYOUT \\\\\\\\\\\\\\\\\\\\\
	echo "
	<tr>
	<td>"?><center><?php echo $exid;?><center><?php echo"</td>
	<td>"?><a href="./?loc=<?php echo "".$glatitude,",".$glongitude.""?>&zoom=19"><?php echo $gname;?></a><?php echo "</td>
	<td>".$exraiddate."</td>
	<td>".$uid."</td>
	</tr>";
	
}}
echo "</table></center><p id='pages'>";
?><center><?php
///////////////////// PAGENATION \\\\\\\\\\\\\\\\\\\\\
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."'>".$i."</a> "; 
}; 
?></center><?php
}

///////////////////// CREATE TRADE \\\\\\\\\\\\\\\\\\\\\

function offertrade(){
require('./config/config.php');
$result = $conn->query("SELECT * FROM pokedex");
$id = $pokemon = $cp = $iv = $monster = $spotter = $opentrade = "";

if(isset($_SESSION["uname"])){ ?>
<!--///////////////////// SUBMIT FORM \\\\\\\\\\\\\\\\\\\\\-->
<h3 style="text-align:center;"><strong>Offer a Trade:</strong></h3>
<form id="usersubmit" method="post" action="./offertrade.php">
<center><table id="added" class="table table-bordered">
<tbody>

<!--///////////////////// GENERATE MONSTER LIST \\\\\\\\\\\\\\\\\\\\\-->
<tr>
<td style="width: 5%;">Offer Pokemon</td>
<td style="width: 10%;">
<?php
echo "<select id='pokesearch' name='offmon'>";
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
	<input type="number" name="cp" min="10" max="4760" value="10" class="cpinput"><span id="cpoutput"></span>
</td>
</tr>
<tr>
<td style="width: 5%;">IV in %</td>
<td style="width: 10%;">
<input type="number" name="iv" min="1" max="100" value="1" class="cpinput"><span id="cpoutput"></span>
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
	<input type="text" name="tradeloc" placeholder="City" class="tradeloc"><span id="tradeloc"></span>
</td>
</tr>

<tr>
<td style="width: 5%;">Request Pokemon</td>
<td style="width: 10%;">

<center>

Open to Trades: <input type="checkbox" id="myCheck" name="opentrade" onclick="myFunction()">

<div id="textbox">
<small>** Lets users make offers **</small>
</div>

</center>

<div id="opendefine">
<?php
require('./config/config.php');
$result1 = $conn->query("SELECT * FROM pokedex");
echo "<select id='pokesearch2' name='reqmon'>";
while ($row = $result1->fetch_assoc()) {
    unset($id, $monster);
        $id = $row['id'];
            $monster= $row['monster'];
				echo '<option value="'.$id.'">'.$id.' - '.$monster.'</option>';
					}					
						echo "</select>";
							mysqli_close($conn);
							?>
</div>

</td>
</tr>
<tr>
<td style="width: 5%;">Notes</td>
<td style="width: 10%;">
	<input type="text" name="notes" placeholder="My Extra Shiny" class="notes"><span id="notes"></span>
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
<center><td style="width:10%;"><input type="submit" id="offtrade" value="OFFER!"></td></center>

</tbody>
</table></center>
</form>

<?php } else {
	
	echo "<center><whoa wtdiv style='margin-top:10px;'>";
	echo "Login to spot a pokemon";
		?><br /><br /><a href="./login/login.php">Login Here</a><?php
	echo "</div></center>";
	}
} 

///////////////////// ACTIVE TRADE LIST \\\\\\\\\\\\\\\\\\\\\

function activetrades(){
require('./config/config.php');
$results_per_page = 7;

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql = "SELECT * FROM offers,pokedex WHERE offers.offmon = pokedex.id AND complete = 0 ORDER BY oid DESC LIMIT $start_from,".$results_per_page;
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));

if(isset($_SESSION["uname"])){ 
$sqlcnt = "SELECT COUNT(OID) AS total FROM offers"; 
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>

<h3 style="text-align:center;"><strong>Available Trades:</strong></h3>



<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php

while($row = mysqli_fetch_array($result)) {
	$oid = $row['oid'];
	$offmon = $row['offmon'];
    $cp = $row['cp'];
    $iv = $row['iv'];
	$tradeloc = $row['tradeloc'];	
	$reqmon = $row['reqmon'];
	$accepted = $row['accepted'];
	$offerby = $row['tname'];
	$opentrade = $row['opentrade'];
	$shiny = $row['shiny'];
	$alolan = $row['alolan'];
	$cloc = $row['cloc'];
	
?>
	
	<div id="<?php echo $oid;?>" class="activelisting">
	
	<div id="<?php echo $oid;?>" class="actid">
	
		<a href='./active-offers.php?oid=<?php echo $oid;?>'>ID:<?php echo $oid;?></a>
	
		</div>
	
	<div class="offerby">
	<?php
		$urlquery = "SELECT url FROM users WHERE uname = '".$offerby."'";
        $resulturl = $conn->query($urlquery);
        $rowurl = $resulturl->fetch_array(MYSQLI_NUM);
        $url = $rowurl[0];

	?>

		<center><a href='./compose.php?user=<?php echo $offerby;?>&subject=Trade Number <?php echo $oid;?>'><?php echo $offerby;?></a><br>
	<?php if ($url !=='') {?><a href='./compose.php?user=<?php echo $offerby;?>&subject=Trade Number <?php echo $oid;?>'><img src="./userpics/<?php echo $url; ?>" height="25px" width="25px" alt="logo"  style="border:1px solid black"></a><?php } else {?><a href='./compose.php?user=<?php echo $offerby;?>&subject=Trade Number <?php echo $oid;?>'><img src="./userpics/nopic.png" height="25px" width="25px" alt="logo"  style="border:1px solid black"></a><?php }?>
	
		<?php echo "<p style='font-weight:600;'>$tradeloc</p>";?></center>
		
		</div>
		
	<div class="offmon">
	
		<a href='./active-offers.php?oid=<?php echo $oid;?>'><img src='static/icons/<?php echo $offmon;?>.png'></a>
			
		</div>
	
	<div class="monvars">
			
			<?php if($shiny == 1){ ?><img src='./static/img/star.png' title='shiny'></br><?php }
	
				if($alolan == 1){?><img src='./static/img/alolan.png' title='alolan'><?php }?>
				
		</div>
	
	<div class="stats">
	
		CP:&nbsp;<?php echo $cp;?><br>
		IV:&nbsp;<?php echo $iv;?><br>
		CAUGHT:&nbsp;<?php echo $cloc;?>
		
		</div>
	
	<a href='./active-offers.php?oid=<?php echo $oid;?>'><img style="height:30px;width:30px;margin-left:10px;margin-bottom:40px;" src='./static/img/swap.png' title='swap'></a>
	
	<div class="reqmon">
	
	<a href='./active-offers.php?oid=<?php echo $oid;?>'><img src='static/icons/<?php echo $reqmon;?>.png'></a>
	
		</div>
	
	<div class="control">
	
	<?php
		
	if($offerby == $_SESSION["uname"]) {?><a href='./active-offers.php?oid=<?php echo $oid;?>'><?php echo "<input type='button' name='makeoffer' class='btn3' value='Your Trade' />"; ?></a><?php } else {
	
	if($accepted == 0) { 
	
	if($opentrade == 0) { 
	
	?>
	
	<form action='./trading.php' method='post'><input type='hidden' name='oid' value='<?php echo $oid;?>' /><input type='hidden' name='accepted' value='<?php echo $accepted;?>' /><input type='submit' class='btn1' name='accepted' value='Lets Trade' /></form>
	
	<?php } else { ?>

	<form action='./make-offer.php' method='post'><input type='hidden' name='oid' value='<?php echo $oid;?>' /><input type='submit' name='makeoffer' class='btn' value='Make Offer' /></form>
	
	<?php }} else { ?>
		<a href='./active-offers.php?oid=<?php echo $oid;?>'>
	<?php
	
	echo "<input type='button' name='makeoffer' class='btn2' value='In Progress' />"; 
	?></a><?php	
	}}
	?>
		</div>
</div>
<?php

}

echo "<p id='pages'>";

?><center><?php
 
///////////////////// PAGENATION \\\\\\\\\\\\\\\\\\\\\

for ($i=1; $i<=$total_pages; $i++) { 

    echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."'>".$i."</a> "; 
	
}; 
} else {
	
	echo "<center><whoa wtdiv style='margin-top:10px;'>";
	echo "Login to spot a pokemon";
		?><br /><br /><a href="./login/login.php">Login Here</a><?php
	echo "</div></center>";
	}
?></center><?php

}

///////////////////// MY TRADES \\\\\\\\\\\\\\\\\\\\\

function mytrades(){
require('./config/config.php');
$results_per_page = 8;

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql = "SELECT * FROM offers WHERE offers.tname = '".$_SESSION['uname']."' ORDER BY oid DESC LIMIT $start_from,".$results_per_page;
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));

$sqlcnt = "SELECT COUNT(TID) AS total FROM trades"; 
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>

<h3 style="text-align:center;"><strong>My Active Trades:</strong></h3>

<center>

<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php

echo "<table id=\"spotted\" class=\"table table-bordered\">";
if(isset($_SESSION["uname"])){
echo "<tr><th>#</th><th>OFFERED POKEMON</th><th>REQUESTED POKEMON</th><th>CITY TO TRADE</th><th>DATE</th></tr>";
} else {
echo "<tr><th>#</th><th>OFFERED POKEMON</th><th>REQUESTED POKEMON</th><th>CITY TO TRADE</th><th>DATE</th></tr>";
}

while($row = mysqli_fetch_array($result)) {
	$oid = $row['oid'];
	$offmon = $row['offmon'];
	$reqmon = $row['reqmon'];
	$tradeloc = $row['tradeloc'];
	$date = $row['date'];
	$tname = $row['tname'];
	if(isset($_SESSION["uname"])){
	echo "
	<tr>
	<td style='text-align:center;'>".$oid."</td>
	<td>"?><center><form action='active-offers.php' method='post'><input type='hidden' name='oid' value='<?php echo $oid;?>' /><input type='image' name='offmon' style='width:45px;height:auto;display:inline;' src='static/icons/<?php echo $offmon;?>' value='<?php echo $offmon;?>' /></form><center><?php echo "</td>
	<td>"?><center><img style=" padding-right:5px;" src="./static/icons/<?php echo $reqmon?>.png" title="<?php echo $reqmon; ?> (#<?php echo $reqmon?>)" height="50" width="55"></center><?php echo "</td>
	<td>".$tradeloc."</td>

	<td>".$date."</td>
	</tr>";
	} else {
	echo "
	<tr>
	<td style='text-align:center;'>".$oid."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $offmon?>.png" title="<?php echo $offmon; ?> (#<?php echo $offmon?>)" height="24" width="28"><p style="padding-top:6%;"><?php echo $offmon; ?></p><?php echo "</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $reqmon?>.png" title="<?php echo $reqmon; ?> (#<?php echo $reqmon?>)" height="24" width="28"><p style="padding-top:6%;"><?php echo $reqmon; ?></p><?php echo "</td>
	<td>".$tradeloc."</td>

	<td>".$rname."</td>
	<td>".$date."</td>
	</tr>";
	}
}
echo "</table></center><p id='pages'>";
?><center><?php

///////////////////// PAGENATION \\\\\\\\\\\\\\\\\\\\\
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."'>".$i."</a> "; 
}; 
?></center><?php
}

///////////////////// MY OPEN OFFERS \\\\\\\\\\\\\\\\\\\\\

function mynatrades(){
require('./config/config.php');
$results_per_page = 10;

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql = "SELECT * FROM offers WHERE offers.tname = '".$_SESSION['uname']."' AND accepted = 0 ORDER BY oid DESC LIMIT $start_from,".$results_per_page;
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));

$sqlcnt = "SELECT COUNT(OID) AS total FROM offers"; 
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>

<h3 style="text-align:center;"><strong>My Available Trades:</strong></h3>

<center>

<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php

echo "<table id=\"spotted\" class=\"table table-bordered\">";
if(isset($_SESSION["uname"])){
echo "<tr><th>#</th><th>OFFERED POKEMON</th><th>REQUESTED POKEMON</th><th>CITY TO TRADE</th><th>STATUS</th><th>DATE</th></tr>";
} else {
echo "<tr><th>#</th><th>OFFERED POKEMON</th><th>REQUESTED POKEMON</th><th>CITY TO TRADE</th><th>STATUS</th><th>DATE</th></tr>";
}

while($row = mysqli_fetch_array($result)) {
	$oid = $row['oid'];
	$offmon = $row['offmon'];
	$reqmon = $row['reqmon'];
	$tradeloc = $row['tradeloc'];	
	$date = $row['date'];
	$tname = $row['tname'];
	if(isset($_SESSION["uname"])){
	echo "
	<tr>
	<td style='text-align:center;'>".$oid."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $offmon?>.png" title="<?php echo $offmon; ?> (#<?php echo $offmon?>)" height="24" width="28"><p style="padding-top:6%;"><?php echo $offmon; ?></p><?php echo "</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $reqmon?>.png" title="<?php echo $reqmon; ?> (#<?php echo $reqmon?>)" height="24" width="28"><p style="padding-top:6%;"><?php echo $reqmon; ?></p><?php echo "</td>
	<td>".$tradeloc."</td>
	<td style='text-align:center; color:green;'>AVAILABLE</td>
	<td>".$date."</td>
	</tr>";
	} else {
	echo "
	<tr>
	<td style='text-align:center;'>".$tid."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $offmon?>.png" title="<?php echo $offmon; ?> (#<?php echo $offmon?>)" height="24" width="28"><p style="padding-top:6%;"><?php echo $offmon; ?></p><?php echo "</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $reqmon?>.png" title="<?php echo $reqmon; ?> (#<?php echo $reqmon?>)" height="24" width="28"><p style="padding-top:6%;"><?php echo $reqmon; ?></p><?php echo "</td>
	<td>".$tradeloc."</td>
	<td style='text-align:center; color:orange;'> ACCEPTED / IN PROGRESS</td>
	<td>".$rname."</td>
	<td>".$date."</td>
	</tr>";
	}
}
echo "</table></center><p id='pages'>";
?><center><?php

///////////////////// PAGENATION \\\\\\\\\\\\\\\\\\\\\
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."'>".$i."</a> "; 
}; 
?></center><?php
}

///////////////////// ACCEPTED TRADES \\\\\\\\\\\\\\\\\\\\\

function acceptedtrades(){
require('./config/config.php');
$results_per_page = 10;

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql = "SELECT * FROM trades WHERE trades.rname = '".$_SESSION['uname']."' ORDER BY tid DESC LIMIT $start_from,".$results_per_page;
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));
$sqlcnt = "SELECT COUNT(TID) AS total FROM trades"; 
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>

<h3 style="text-align:center;"><strong>My Accepted Trades:</strong></h3>

<center>

<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php

echo "<table id=\"spotted\" class=\"table table-bordered\">";
if(isset($_SESSION["uname"])){
echo "<tr><th>#</th><th>OFFERED POKEMON</th><th>CITY TO TRADE</th><th>STATUS</th><th>OFFERED BY</th><th>DATE</th></tr>";
} else {
echo "<tr><th>#</th><th>OFFERED POKEMON</th><th>CITY TO TRADE</th><th>STATUS</th><th>OFFERED BY</th><th>DATE</th></tr>";
}

while($row = mysqli_fetch_array($result)) {
	$tid = $row['tid'];
	$offmon = $row['offmon'];
	$tradeloc = $row['tradeloc'];	
	$rname = $row['rname'];
	$tname = $row['tname'];
	$date = $row['date'];
	if(isset($_SESSION["uname"])){
	echo "
	<tr>
	<td style='text-align:center;'>".$tid."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $offmon?>.png" title="<?php echo $offmon; ?> (#<?php echo $offmon?>)" height="24" width="28"><p style="padding-top:6%;"><?php echo $offmon; ?></p><?php echo "</td>
	<td>".$tradeloc."</td>
	<td style='text-align:center; color:orange;'> ACCEPTED / IN PROGRESS</td>
	<td style='text-align:center;'><a href='./compose.php?user=".$tname."&subject=Counter Offer Number ".$tid."'>".$tname."</td>
	<td>".$date."</td>
	</tr>";
	} else {
	echo "
	<tr>
	<td style='text-align:center;'>".$tid."</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $offmon?>.png" title="<?php echo $offmon; ?> (#<?php echo $offmon?>)" height="24" width="28"><p style="padding-top:6%;"><?php echo $offmon; ?></p><?php echo "</td>
	<td>"?><img style="float:left; padding-right:5px;" src="./static/icons/<?php echo $reqmon?>.png" title="<?php echo $reqmon; ?> (#<?php echo $reqmon?>)" height="24" width="28"><p style="padding-top:6%;"><?php echo $reqmon; ?></p><?php echo "</td>
	<td>".$tradeloc."</td>
	<td style='text-align:center; color:orange;'> ACCEPTED / IN PROGRESS</td>
	<td>".$tname."</td>
	<td>".$date."</td>
	</tr>";
	}
}
echo "</table></center><p id='pages'>";
?><center><?php

///////////////////// PAGENATION \\\\\\\\\\\\\\\\\\\\\
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='".basename($_SERVER['PHP_SELF'])."?page=".$i."'>".$i."</a> "; 
}; 
?></center><?php
}

///////////////////// COUNTER OFFER TRADE \\\\\\\\\\\\\\\\\\\\\

function counteroffer(){
require('./config/config.php');
if (isset($_GET['oid'])) {
$oid = $_GET['oid'];
$selectquery = "SELECT * FROM offers WHERE oid='$oid'";
$selectresult = $conn->query($selectquery);
$row = $selectresult->fetch_array(MYSQLI_NUM);	
} else {
$oid = $conn->real_escape_string($_POST['oid']);
}

$sql2 = "SELECT * FROM offers WHERE oid='$oid'";
$result = mysqli_query($conn,$sql2)or die(mysqli_error($conn));

while($row = mysqli_fetch_array($result)) {			
	$oid = $row['oid'];
	$offmon = $row['offmon'];
	$tradeloc = $row['tradeloc'];
	$cp = $row['cp'];
	$iv = $row['iv'];
	$offerby = $row['tname'];
	$shiny = $row['shiny'];
	$alolan = $row['alolan'];
	$rname = $_SESSION["uname"];
}
?> 

<h3 style="text-align:center;"><strong>Make an Offer:</strong></h3>
<form id="usersubmit" method="post" action="./trading/makeoffer.php">
<center><table id="added" class="table table-bordered">
<tbody>

<tr>
<td style="width: 5%; text-align:center;">ID:</td>
<td style="width: 10%; text-align:center;">
<?php
echo $oid;
?>
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;">Offered By:</td>
<td style="width: 10%; text-align:center;">
<?php
echo $offerby;
?>
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;">Location Preference :</td>
<td style="width: 10%; text-align:center;">
<?php
echo $tradeloc;
?>
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;vertical-align:middle;">Offered Pokemon:</td>
<td style="width: 10%; text-align:center;vertical-align:middle;">
<img src="./static/icons/<?php echo $offmon;?>.png" height=50; width=55;>
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;vertical-align:middle;">Stats:</td>
<td style="width: 10%; text-align:center;vertical-align:middle;">
CP:<?php echo " $cp";?>&nbsp;&nbsp;&nbsp;
IV:<?php echo " $iv";?> %
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;vertical-align:middle;">Variance:</td>
<td style="width: 10%; text-align:center;vertical-align:middle;">
Shiny:<?php if($shiny == 1 ){ echo " Yes"; } else { echo " No"; }?>&nbsp;&nbsp;&nbsp;
Alolan:<?php if($alolan == 1 ){ echo " Yes"; } else { echo " No"; }?>
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;vertical-align:middle;">Counter offer:</td>
<td style="width: 10%; text-align:center;vertical-align:middle;">

<?php
require('./config/config.php');
$result1 = $conn->query("SELECT * FROM pokedex");
echo "<select id='pokesearch2' name='coffmon'>";
while ($row = $result1->fetch_assoc()) {
    unset($id, $monster);
        $id = $row['id'];
            $monster= $row['monster'];
				echo '<option value="'.$id.'"><img src="./static/icons/'.$id.'.png">'.$id.' - '.$monster.'</option>';
					}					
						echo "</select>";
							mysqli_close($conn);
?>

</td>
</tr>

<tr>
<td style="width: 5%;text-align:center;">CP</td>
<td style="width: 10%;text-align:center;">
<input type="number" name="ccp" min="10" max="4760" value="10" class="cpinput"><span id="cpoutput"></span>
</td>
</tr>

<tr>
<td style="width: 5%;text-align:center;">IV in %</td>
<td style="width: 10%;text-align:center;">
<input type="number" name="civ" min="1" max="100" value="1" class="cpinput"><span id="cpoutput"></span>
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;">Variance:</td>
<td style="width: 10%; text-align:center;">
Shiny: <input type="checkbox" id="cshiny" name="cshiny"><span id="cshiny"></span>
Alolan: <input type="checkbox" id="calolan" name="calolan"><span id="calolan"></span>
</td>
</tr>

<tr>
<td></td>
<td style="width:10%;"><center><input type='hidden' name='offerby' value='<?php echo $offerby;?>' /><input type='hidden' name='oid' value='<?php echo $oid;?>' /><input type="submit" id="coffer" value="OFFER!"></center></td>
</tr>
</tbody>
</table></center>
</form>

<?php 
} 

///////////////////// ACTIVE OFFERS \\\\\\\\\\\\\\\\\\\\\

function actoffer(){
require('./config/config.php');
if (isset($_GET['oid'])) {
$oid = $_GET['oid'];
$selectquery = "SELECT * FROM offers WHERE oid='$oid'";
$selectresult = $conn->query($selectquery);
$row = $selectresult->fetch_array(MYSQLI_NUM);	
} else {
$oid = $conn->real_escape_string($_POST['oid']);
}

$sql2 = "SELECT * FROM offers WHERE oid='$oid'";
$result = mysqli_query($conn,$sql2)or die(mysqli_error($conn));

while($row = mysqli_fetch_array($result)) {			
	$oid = $row['oid'];
	$offmon = $row['offmon'];
	$tradeloc = $row['tradeloc'];
	$cp = $row['cp'];
	$iv = $row['iv'];
	$offerby = $row['tname'];
	$shiny = $row['shiny'];
	$alolan = $row['alolan'];
	$notes= $row['notes'];
	$rname = $_SESSION["uname"];
	$complete = $row['complete'];
	$cloc = $row['cloc'];
}
?> 

<h3 style="text-align:center;"><strong>Offered Pokemon:</strong></h3>
<form id="usersubmit" method="post" action="make-offer.php">
<center><table id="added" class="table table-bordered">
<tbody>

<tr>
<td style="width: 5%; text-align:center;">ID:</td>
<td style="width: 10%; text-align:center;">
<?php
echo $oid;
?>
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;">Offered By:</td>
<td style="width: 10%; text-align:center;">
<?php
echo "User:&nbsp;<a href='./compose.php?user=".$offerby."&subject=Trade Number ".$oid."'>".$offerby."</a>&nbsp;(".$tradeloc.")"
?>
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;vertical-align:middle;">Offered Pokemon:</td>
<td style="width: 10%; text-align:center;vertical-align:middle;">
<img src="./static/icons/<?php echo $offmon;?>.png" height=50; width=55;>
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;vertical-align:middle;">Stats:</td>
<td style="width: 10%; text-align:center;vertical-align:middle;">
CP:<?php echo " $cp";?>&nbsp;&nbsp;&nbsp;
IV:<?php echo " $iv";?> %
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;vertical-align:middle;">Variance:</td>
<td style="width: 10%; text-align:center;vertical-align:middle;">
Shiny:<?php if($shiny == 1 ){ echo " Yes"; } else { echo " No"; }?>&nbsp;&nbsp;&nbsp;
Alolan:<?php if($alolan == 1 ){ echo " Yes"; } else { echo " No"; }?>
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;vertical-align:middle;">Catch Location:</td>
<td style="width: 10%; text-align:center;vertical-align:middle;">
<?php echo " $cloc"; ?> 
</td>
</tr>

<tr>
<td style="width: 5%; text-align:center;">Notes:</td>
<td style="width: 10%; text-align:center;">
<?php
if(empty($notes)){ echo "No Notes"; } else {
echo $notes; }

?>
</td>
</tr>

<?php if($offerby==($_SESSION["uname"])){}else{?>
<tr>
<td></td>
<td style="width:10%;"><center><input type='hidden' name='oid' value='<?php echo $oid;?>' /><input type="submit" id="coffer" value="OFFER!"></center></td>
</tr>
<?php } ?>
</tbody>
</table></center>
</form>
</tbody>
</table>

<?php
require('./config/config.php');
$results_per_page = 10;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$sql = "SELECT * FROM tradeoffers WHERE oid='$oid' ORDER BY complete DESC, accepted DESC, date DESC LIMIT $start_from,".$results_per_page;
$result = mysqli_query($conn,$sql)or die(mysqli_error($conn));
$sqlcnt = "SELECT COUNT(OID) AS total FROM tradeoffers"; 
$resultcnt = $conn->query($sqlcnt);
$row = $resultcnt->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);
?>

<h3 style="text-align:center;"><strong>Counter Offers:</strong></h3>

<center>
<!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
<?php

echo "<table id=\"spotted\" class=\"table table-bordered\">";

if(isset($_SESSION["uname"])){
	
if($offerby == ($_SESSION["uname"])){
	if($complete == 0){
echo "<tr><th>#</th><th>OFFERED BY</th><th>OFFERED POKEMON</th><th>CP</th><th>IV</th><th>SHINY</th><th>ALOLAN</th><th>DATE</th><th>ACCEPT</th><th>DECLINE</th></tr>";
	}else{
echo "<tr><th>#</th><th>OFFERED BY</th><th>OFFERED POKEMON</th><th>CP</th><th>IV</th><th>SHINY</th><th>ALOLAN</th><th>DATE</th><th>STATUS</th></tr>";
	}
} else if($offerby != ($_SESSION["uname"])){

echo "<tr><th>#</th><th>OFFERED BY</th><th>OFFERED POKEMON</th><th>CP</th><th>IV</th><th>SHINY</th><th>ALOLAN</th><th>DATE</th></tr>";

} else {

echo "<tr><th>#</th><th>OFFERED BY</th><th>OFFERED POKEMON</th><th>CP</th><th>IV</th><th>SHINY</th><th>ALOLAN</th><th>DATE</th></tr>";

}
}

while($row = mysqli_fetch_array($result)) {
	$toid = $row['toid'];
	$oid = $row['oid'];
	$coffer = $row['coffer'];
	$cofferby = $row['cofferby'];
	$ccp = $row['ccp'];	
	$civ = $row['civ'];
	$cshiny = $row['cshiny'];
	$calolan = $row['calolan'];
	$accepted = $row['accepted'];
	$complete = $row['complete'];
	$date = $row['date'];
	
	
		
	if(isset($_SESSION["uname"])){
		if($offerby == ($_SESSION["uname"])){
			
			
	echo "
	<tr>
	<td style='text-align:center;'>".$toid."</td>
	<td style='text-align:center;'><a href='./compose.php?user=".$cofferby."&subject=Counter Offer Number ".$toid."'>".$cofferby."</td>
	<td style='text-align:center;'>"?><center><img style="padding-right:5px;" src="./static/icons/<?php echo $coffer?>.png" title="<?php echo $coffer; ?> (#<?php echo $coffer?>)" height="50" width="55"></center><?php echo "</td>
	<td style='text-align:center;'>".$ccp."</td>
	<td style='text-align:center;'>".$civ."</td>
	<td style='text-align:center;'>"; if($cshiny == 1){echo "<center><span><img style='padding-right:3px;' src='./static/img/star.png' title='star' height='24' width='28'></span><center>";} echo "</td>	
	<td style='text-align:center;'>"; if($calolan == 1){echo "<center><span><img style='padding-right:3px;' src='./static/img/alolan.png' title='star' height='24' width='28'></span><center>";} echo "</td>
	<td style='text-align:center;'>".$date."</td>
	
	"; if ($complete == 0){ echo "
	
	
	<td style='text-align:center;'>
	"; if($accepted == 1){echo "<p style='color:green;font-weight:700;'>Accepted</p><span style='display:inline-block;'><form action='./trading/complete.php' method='post'><input type='hidden' name='toid' value='$toid' /><input type='hidden' name='oid' value='$oid' /><input type='submit' name='complete' style='color:blue;font-size:9px' value='Complete' /></form></span>";} else { echo "<span style='display:inline-block;'><form action='./trading/accept.php' method='post'><input type='hidden' name='toid' value='$toid' /><input type='hidden' name='oid' value='$oid' /><input type='submit' name='accepted' style='color:green;font-size:9px' value='Accept' /></form></span>"; } echo "</td>
	
	<td style='text-align:center;'>
	"; if($accepted == 2){echo "<p style='color:red;font-weight:700;'>Declined</p>";} else { echo "<span style='display:inline-block;'><form action='./trading/decline.php' method='post'><input type='hidden' name='toid' value='$toid' /><input type='hidden' name='oid' value='$oid' /><input type='submit' name='declined' style='color:red;font-size:9px' value='Decline' /></form></span>"; } echo "</td>

	";} elseif ($complete == 1) { echo " 
	
	<td style='text-align:center;'>
	"; if($accepted == 1){echo "<p style='color:green;font-weight:700;'>Complete</p>";}} echo "</td>
	
	</tr>";
	

	

	
	} else {
			
	echo "
	<tr>
	<td style='text-align:center;'>".$toid."</td>
	<td style='text-align:center;'><a href='./compose.php?user=".$cofferby."&subject=Trade Number ".$oid."'>".$cofferby."</td>
	<td>"?><center><img style="padding-right:5px;" src="./static/icons/<?php echo $coffer?>.png" title="<?php echo $coffer; ?> (#<?php echo $coffer?>)" height="50" width="55"></center><?php echo "</td>
	<td>".$ccp."</td>
	<td>".$civ."</td>
	<td>"; if($cshiny == 1){echo "<center><span><img style='padding-right:3px;' src='./static/img/star.png' title='star' height='24' width='28'></span><center>";} echo "</td>	
	<td>"; if($calolan == 1){echo "<center><span><img style='padding-right:3px;' src='./static/img/alolan.png' title='star' height='24' width='28'></span><center>";} echo "</td>
	<td>".$date."</td>
	</tr>";
	
		} } else {
		
	echo "
	<tr>
	<td style='text-align:center;'>".$toid."</td>
	<td style='text-align:center;'>".$cofferby."</td>
	<td>"?><center><img style="padding-right:5px;" src="./static/icons/<?php echo $coffer?>.png" title="<?php echo $coffer; ?> (#<?php echo $coffer?>)" height="50" width="55"></center><?php echo "</td>
	<td>".$ccp."</td>
	<td>".$civ."</td>
	<td>"; if($cshiny == 1){echo "<center><span><img style='padding-right:3px;' src='./static/img/star.png' title='star' height='24' width='28'></span><center>";} echo "</td>	
	<td>"; if($calolan == 1){echo "<center><span><img style='padding-right:3px;' src='./static/img/alolan.png' title='star' height='24' width='28'></span><center>";} echo "</td>
	<td>".$date."</td>
	</tr>";
	}
	
}

echo "</table></center><p id='pages'><center>";

for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='".basename($_SERVER['PHP_SELF'])."?oid=".$oid."?page=".$i."'>".$i."</a> "; 
}; 

?>

</center>
</form>

<?php } ?>
