<?php
    require_once 'initiate.php';
?>

<div id="map"></div>

<script>
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
} ?>
],
        zoom:
<?php
if (isset($_GET['zoom'])) {
    echo $_GET['zoom'];
} else {
    echo 13;
} ?>,
        maxZoom: 18,
        zoomControl: false
    });
map.addLayer(L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'}));
    // Change this depending on the name of your PHP or XML file
function getPokemon(){
    downloadUrl('/core/functions/frontend/xml.php', function(data) {
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
            iconUrl: '<?= W_ASSETS ?>icons/' + id + '.png',
            iconSize: [32, 32]
        });
        var html = `
        <div class="maplabel">
            <center>
                <img src="<?=W_ASSETS?>icons/` + id + `.png" height="45" width="45"></img>
                <p><b>` + pokemon + ` (#` + id + `)</b>
                <br>CP: ` + cp + `
                <br>IV: `+ iv + `%
                <br>Found: ` + hour + `:` + min + ` ` + ampm + `
        <?php if ($sess->get('uname',null) != null) { ?>
                <br>
                <hr>
                <a href ="./good.php?spotid=` + spotid + `&loc=` + markerElem.getAttribute('latitude') + `,` + markerElem.getAttribute('longitude') + `">
                    <img src="<?= W_ASSETS ?>voting/up.png" height="25" width="25"></img>
                </a>` + good + ` x Found
                <br>
                <a href ="./bad.php?spotid=` + spotid + `&loc=` + markerElem.getAttribute('latitude') + `,` + markerElem.getAttribute('longitude') + `">
                    <img src="<?= W_ASSETS ?>voting/down.png" height="25" width="25"></img>
                </a>` + bad + ` x Not found
                <?php } ?>
                <br><hr>
                <a href="http://maps.google.com/maps?q=` +	markerElem.getAttribute('latitude') + `,` + markerElem.getAttribute('longitude') + `">Google Maps</a>
                <?php if ($sess->get('uname',null) != null) { ?>
                <br><hr>
                Spotted by: <b>` + spotter + `</b>
                <?php } ?></center></div>`;
        var marker = new L.marker([parseFloat(markerElem.getAttribute('latitude')), parseFloat(markerElem.getAttribute('longitude'))],{ icon: image }).bindPopup(html);
        marker.on('click', function() {
          marker.openPopup();
        });
        map.addLayer(marker)
      });
    });
}
function getGyms() {
	downloadUrl('/core/functions/frontend/gxml.php', function(data) {
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
            var html = `
                <div class="maplabel">
                    <center>
                        <img src="<?= W_ASSETS ?>gyms/` + gteam + `ex.png" height="45px" width="45px"></img>
                        <h6>` + gname + `</h6><br><p>Team: ` + tid + `</p>
                        <?php if ($sess->get('uname',null) === null) { ?>
                        <hr>
                        <b><span class="text-danger">Login to change/add teams or raids.</span></b>
                        <?php } else { ?>
                        <br>
                        <hr><strong>EX Raid On:</strong><br> ` + exraiddate + `
                        <br><hr><b>Choose team:</b>
                        <br>
                        <a href="#" onclick="pickInstinct(` + gid + `);return false;" id="pickInstinct" title="Click to choose Instinct">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="#" onclick="pickValor(` + gid + `);return false;" id="pickValor" title="Click to choose Valor">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="#" onclick="pickMystic(` + gid + `);return false;" id="pickMystic" title="Click to choose Mystic">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                        <?php } ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                    </center>
                </div>`;
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: '<?=W_ASSETS?>gyms/' + gteam + 'ex.png',
            iconSize: [55, 55],
            className: 'marker' + gid
			});
			} else if (exraid === "0"){
            var html = `
                <div class="maplabel" id="` + gid + `">
                    <center>
                        <img src="<?=W_ASSETS?>gyms/` + gteam + `.png" height="45px" width="45px"></img>
                        <h6>` + gname + `</h6><br><p>Team: ` + tid + `</p>
                    <?php if ($sess->get('uname',null) === null) { ?>
                        <hr><b><span class="text-danger">Login to change/add teams or raids.</span></b>
                    <?php } else { ?>
                        <br>
                        <hr><b>Choose team:</b><br>
                        <a href="#" onclick="pickInstinct(` + gid + `);return false;" id="pickInstinct" title="Click to choose Instinct">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="#" onclick="pickValor(` + gid + `);return false;" id="pickValor" title="Click to choose Valor">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="#" onclick="pickMystic(` + gid + `);return false;" id="pickMystic" title="Click to choose Mystic">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                    <?php } ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                    </center>
                </div>`;
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>gyms/' + gteam + '.png',
            iconSize: [55, 55],
            className: 'marker' + gid
			});
			}
		} else if (actraid !== "0" && egg === "0"){
			if (exraid === "0"){
            var html = `
                <div class="maplabel">
                    <center>
                        <img src="<?= W_ASSETS ?>icons/` + actboss + `.png" height="45px" width="45px"></img>
                        <p><b>` + gname + `</b>
                        <br>Boss: ` + bossname + `
                        <br>CP: ` + bosscp + `
                        <br>Team: ` + tid + `
                        <br>Expires: ` + hour + `:` + min + ` ` + ampm + `
                    <?php if ($sess->get('uname',null) === null) { ?>
                        <hr><b><span class="text-danger">Login to change/add teams or raids.</span></b>
                    <?php } else if ($sess->get('uname',null) != null) { ?>
                        <br><hr>
                        <b>Choose team:</b>66
                        <a href="#" onclick="pickInstinct(` + gid + `);return false;" id="pickInstinct" title="Click to choose Instinct">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="#" onclick="pickValor(` + gid + `);return false;" id="pickValor" title="Click to choose Valor">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="#" onclick="pickMystic(` + gid + `);return false;" id="pickMystic" title="Click to choose Mystic">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                    <?php } ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                    <?php if ($sess->get('uname',null) != null) { ?>
                        <br><hr><b>Spotted by: </b>` + raidby + `
                    <?php } ?>
                    </center>
                </div>`;
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>raids/' + actboss + '.png',
            iconSize: [55, 55],
            className: 'marker' + gid
			});
			} else if (exraid === "1"){
            var html = `
                <div class="maplabel">
                    <center>
                        <img src="<?= W_ASSETS ?>icons/` + actboss + `.png" height="45px" width="45px"></img>
                        <p><b>` + gname + `</b>
                        <br>Boss: ` + bossname + `
                        <br>CP: ` + bosscp + `
                        <br>Team: ` + tid + `
                        <br>Expires: ` + hour + `:` + min + ` ` + ampm + `
                    <?php if ($sess->get('uname',null) === null) { ?>
                        <hr><b><span class="text-danger">Login to change/add teams or raids.</span></b>
                    <?php } else if ($sess->get('uname',null) != null) { ?>
                        <br><hr><strong>EX Raid On:</strong>
                        <br> ` + exraiddate + `
                        <br><hr><b>Choose team:</b>
                        <br>
                        <a href="#" onclick="pickInstinct(` + gid + `);return false;" id="pickInstinct" title="Click to choose Instinct">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="#" onclick="pickValor(` + gid + `);return false;" id="pickValor" title="Click to choose Valor">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="#" onclick="pickMystic(` + gid + `);return false;" id="pickMystic" title="Click to choose Mystic">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                    <?php } ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                    <?php if ($sess->get('uname',null) != null) { ?>
                        <br><hr><b>Spotted by: </b>` + raidby + `
                    <?php } ?>
                    </center>
                </div>`;
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>raids/' + actboss + '.png',
            iconSize: [75, 75],
            className: 'marker' + gid
			});
			}
		} else if (actraid === "0" && egg !== "0"){
			if (exraid === "0"){
            var html = `
                <div class="maplabel">
                    <center>
                        <img src="<?= W_ASSETS ?>eggs/` + egg + `.png" height="45px" width="45px"></img>
                        <p><b>` + gname + `</b>
                        <br>Egg level: ` + egg + `
                        <br>Team: ` + tid + `
                        <br>Hatches at: ` + hour + `:` + min + ` ` + ampm + `
                    <?php if ($sess->get('uname',null) === null) { ?>
                        <hr><b><span class="text-danger">Login to change/add teams or raids.</span></b>
                    <?php } else if ($sess->get('uname',null) != null) { ?>
                    <br><hr>
                    <b>Choose team:</b>
                    <br>
                    <a href="#" onclick="pickInstinct(` + gid + `);return false;" id="pickInstinct" title="Click to choose Instinct">
                        <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                    </a> / <a href="#" onclick="pickValor(` + gid + `);return false;" id="pickValor" title="Click to choose Valor">
                        <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                    </a> / <a href="#" onclick="pickMystic(` + gid + `);return false;" id="pickMystic" title="Click to choose Mystic">
                        <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                    </a>
                <?php } ?>
                    <br><hr>
                    <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                <?php if ($sess->get('uname',null) != null) { ?>
                    <br><hr>
                    <b>Spotted by: </b>` + eggby + `
                <?php } ?>
                </center>
            </div>`;
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>eggs/' + egg + '.png',
            iconSize: [55, 55],
            className: 'marker' + gid
			});
			} else if (exraid === "1"){
            var html = `
                <div class="maplabel">
                    <center>
                        <img src="<?= W_ASSETS ?>eggs/` + egg + `.png" height="45px" width="45px"></img>
                        <p><b>` + gname + `</b>
                        <br>Egg level: ` + egg + `
                        <br>Team: ` + tid + `
                        <br>Hatches at: ` + hour + `:` + min + ` ` + ampm + `
                    <?php if ($sess->get('uname',null) === null) { ?>
                        <hr><b><span class="text-danger">Login to change/add teams or raids.</span></b>
                    <?php } else if ($sess->get('uname',null) != null) { ?>
                        <br><hr>
                        <strong>EX Raid On:</strong>
                        <br> ` + exraiddate + `
                        <br><hr>
                        <b>Choose team:</b>
                        <br>
                        <a href="#" onclick="pickInstinct(` + gid + `);return false;" id="pickInstinct" title="Click to choose Instinct">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="#" onclick="pickValor(` + gid + `);return false;" id="pickValor" title="Click to choose Valor">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="#" onclick="pickMystic(` + gid + `);return false;" id="pickMystic" title="Click to choose Mystic">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                    <?php }; ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                    <?php if ($sess->get('uname',null) != null) { ?>
                        <br>
                        <hr><b>Spotted by: </b>` + eggby + `
                    <?php } ?>
                    </center>
                </div>`;
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>eggs/' + egg + '.png',
            iconSize: [55, 55],
            className: 'marker' + gid
			});
			}
		}
        var marker = new L.marker([parseFloat(markerElem.getAttribute('glatitude')), parseFloat(markerElem.getAttribute('glongitude'))],{
          icon: image
        });
        marker.id = gid;
        marker.bindPopup(html);
        marker.on('click', function() {
          marker.openPopup();
        });
        map.addLayer(marker);
      });
    });
}
function getQuests() {
	downloadUrl('/core/functions/frontend/sxml.php', function(data) {
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
        var html = `
            <div class="maplabel">
                <center>
                    <img src="<?= W_ASSETS ?>stops/queststop.png" height="45" width="45"></img>
                    <p><b>` + sname + `</b>
                <?php if ($sess->get('uname',null) === null) { ?>
                    <br>
                    (<b><span class="text-success">Quested</span></b>)
                    <br><hr>
                    <b><span class="text-danger">Login to add/view quests.</span></b>
                <?php } else if ($sess->get('uname',null) != null) { ?>
                    <br><hr>
                    <b>Quest:</b>
                    <br> ` + quest + `
                    <br><hr>
                    <b>Reward:</b>
                    <br>` + reward + `
                <?php } ?>
                    <br><hr>
                    <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('slatitude') + `,` + markerElem.getAttribute ('slongitude') + `">Google Maps</a>
                <?php if ($sess->get('uname',null)) { ?>
                    <br><hr>
                    <b>Spotted by: </b>` + questby + `
                <?php } ?>
                </center>
            </div>`;
        var icon = customLabel[type] || {};
        var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>stops/queststop.png',
            iconSize: [30, 30],
            className: 'marker' + gid
			});
		} else if (quested === ""){
        var html = `
            <div class="maplabel">
                <center>
                    <img src="<?= W_ASSETS ?>stops/stops.png" height="45" width="45"></img>
                    <p><b>` + sname + `</b>
                <?php if ($sess->get('uname',null) === null) { ?>
                    <br><hr>
                    <b><span class="text-danger">Login to add/view quests.</span></b>
                <?php } ?>
                    <br><hr>
                    <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('slatitude') + `,` + markerElem.getAttribute('slongitude') + `">Google Maps</a>
                </center>
            </div>`;
        var icon = customLabel[type] || {};
        var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>stops/stops.png',
            iconSize: [30, 30],
            className: 'marker' + gid
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

getPokemon();
getGyms();
getQuests();
}
</script>
