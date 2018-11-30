<?php
    require_once 'initiate.php';
?>
<style>
    .maplabel {
        text-align: center;
    }
    .leaflet-popup-content {
        max-height:0px;
        width:px;
        overflow:hidden;
        transition: width .3s linear, max-height .7s linear .3s;
    }
    </style>
<div id="map"></div>
<!-- Interactive Map Library -->
<script src="<?=versionFile(W_JS . 'leaflet.js')?>">
</script>
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
var monMarkers = new Object;
var stopMarkers = new Object;
var gymMarkers = new Object;
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
    var monLayer = L.layerGroup();
    var stopLayer = L.layerGroup();
    var gymLayer = L.layerGroup();

    var overlays = {
        'Pokémon': monLayer,
        'Gyms': gymLayer,
        'Stops': stopLayer
    };
    L.control.layers(null, overlays, {collapsed:false, position:'topleft'}).addTo(map);


map.addLayer(
        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'From the<a href="https://spotamon.com" style=" margin:0 -3px;"><img src="' + w_root + 'core/assets/img/spotamon2.svg" style="max-height: 20px;" alt="spotamon"></img></a>Team',
                detectRetina: true
            })
        );
        var attribution = map.attributionControl;


attribution.setPrefix('');

    // Change this depending on the name of your PHP or XML file
    downloadUrl(w_root + 'core/functions/frontend/xml.php', function(data) {
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
                <?php } ?></div>`;
        var popup = new L.popup({
            className: 'monpop',
            autoPan:true
        }).setContent(html);
        monMarkers[spotid] = new L.marker([parseFloat(markerElem.getAttribute('latitude')), parseFloat(markerElem.getAttribute('longitude'))],{ icon: image, title: pokemon }).bindPopup(popup).addTo(monLayer);
        monMarkers[spotid].bindTooltip("Spotted by " + spotter + " at " + hour + ":" + min);
        monMarkers[spotid].on('click', function() {
          monMarkers[spotid].openPopup();
        });
      });
      monLayer.addTo(map);

    });


	downloadUrl(w_root + 'core/functions/frontend/gxml.php', function(data) {
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
        var teamclass = {2:'instincttrace', 3:'valortrace',4:'mystictrace'};
		if (actraid === "0" && egg === "0"){
			if (exraid === "1"){
            var html = `
                <div class="maplabel">

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
                        <a href="#"  data-gym="` + gid + `" id="pickInstinct" title="Click to choose Instinct">
                            <img border="0" alt="Instinct" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="#"  data-gym="` + gid + `" id="pickValor" title="Click to choose Valor">
                            <img border="0" alt="Valor" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="#"  data-gym="` + gid + `" id="pickMystic" title="Click to choose Mystic">
                            <img border="0" alt="Mystic" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                        <?php } ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>

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

                        <img src="<?=W_ASSETS?>gyms/` + gteam + `.png" height="45px" width="45px"></img>
                        <h6>` + gname + `</h6><br><p>Team: ` + tid + `</p>
                    <?php if ($sess->get('uname',null) === null) { ?>
                        <hr><b><span class="text-danger">Login to change/add teams or raids.</span></b>
                    <?php } else { ?>
                        <br>
                        <hr><b>Choose team:</b><br>
                        <a href="#"  data-gym="` + gid + `" id="pickInstinct" title="Click to choose Instinct">
                            <img border="0" alt="Instinct" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="#"  data-gym="` + gid + `" id="pickValor" title="Click to choose Valor">
                            <img border="0" alt="Valor" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="#"  data-gym="` + gid + `" id="pickMystic" title="Click to choose Mystic">
                            <img border="0" alt="Mystic" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                    <?php } ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>

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
                        <a href="#"  data-gym="` + gid + `" id="pickInstinct" title="Click to choose Instinct">
                            <img border="0" alt="Instinct" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="#"  data-gym="` + gid + `" id="pickValor" title="Click to choose Valor">
                            <img border="0" alt="Valor" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="#"  data-gym="` + gid + `" id="pickMystic" title="Click to choose Mystic">
                            <img border="0" alt="Mystic" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                    <?php } ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                    <?php if ($sess->get('uname',null) != null) { ?>
                        <br><hr><b>Spotted by: </b>` + raidby + `
                    <?php } ?>

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
                        <a href="#"  data-gym="` + gid + `" id="pickInstinct" title="Click to choose Instinct">
                            <img border="0" alt="Instinct" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="#"  data-gym="` + gid + `" id="pickValor" title="Click to choose Valor">
                            <img border="0" alt="Valor" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="#"  data-gym="` + gid + `" id="pickMystic" title="Click to choose Mystic">
                            <img border="0" alt="Mystic" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                    <?php } ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                    <?php if ($sess->get('uname',null) != null) { ?>
                        <br><hr><b>Spotted by: </b>` + raidby + `
                    <?php } ?>

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
                    <a href="#"  data-gym="` + gid + `" id="pickInstinct" title="Click to choose Instinct">
                        <img border="0" alt="Instinct" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                    </a> / <a href="#"  data-gym="` + gid + `" id="pickValor" title="Click to choose Valor">
                        <img border="0" alt="Valor" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                    </a> / <a href="#"  data-gym="` + gid + `" id="pickMystic" title="Click to choose Mystic">
                        <img border="0" alt="mystic" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                    </a>
                <?php } ?>
                    <br><hr>
                    <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                <?php if ($sess->get('uname',null) != null) { ?>
                    <br><hr>
                    <b>Spotted by: </b>` + eggby + `
                <?php } ?>

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
                        <a href="#"  data-gym="` + gid + `" id="pickInstinct" title="Click to choose Instinct">
                            <img border="0" alt="Instinct" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="#"  data-gym="` + gid + `" id="pickValor" title="Click to choose Valor">
                            <img border="0" alt="Valor" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="#"  data-gym="` + gid + `" id="pickMystic" title="Click to choose Mystic">
                            <img border="0" alt="Mystic" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                    <?php }; ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                    <?php if ($sess->get('uname',null) != null) { ?>
                        <br>
                        <hr><b>Spotted by: </b>` + eggby + `
                    <?php } ?>

                </div>`;
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>eggs/' + egg + '.png',
            iconSize: [55, 55],
            className: 'marker' + gid
			});
			}
        }
        gymMarkers[gid] = new L.marker([parseFloat(markerElem.getAttribute('glatitude')), parseFloat(markerElem.getAttribute('glongitude'))],{
          icon: image,
          title:gname
        }).addTo(gymLayer);
        var popup = new L.popup({
                className: 'gympop ' + teamclass[gteam],
                maxWidth: 300,
                autoPan:true
            }).setContent(html);
        gymMarkers[gid].bindPopup(popup);
        gymMarkers[gid].on('click', function(e) {

            gymMarkers[gid].openPopup();

        });

      });
      gymLayer.addTo(map);

    });

	downloadUrl(w_root + 'core/functions/frontend/sxml.php', function(data) {
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

                    <img src="<?= W_ASSETS ?>stops/stops.png" height="45" width="45"></img>
                    <p><b>` + sname + `</b>
                <?php if ($sess->get('uname',null) === null) { ?>
                    <br><hr>
                    <b><span class="text-danger">Login to add/view quests.</span></b>
                <?php } ?>
                    <br><hr>
                    <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('slatitude') + `,` + markerElem.getAttribute('slongitude') + `">Google Maps</a>

            </div>`;
        var icon = customLabel[type] || {};
        var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>stops/stops.png',
            iconSize: [30, 30],
            className: 'marker'
			});
        }
        var popup = new L.popup({
            className: 'stoppop',
            autoPan:true
        }).setContent(html);
        stopMarkers[sid] = new L.marker([parseFloat(markerElem.getAttribute('slatitude')), parseFloat(markerElem.getAttribute('slongitude'))],{
          icon: image,
          title:sname
        }).bindPopup(popup).addTo(stopLayer);
        stopMarkers[sid].on('click', function() {
            stopMarkers[sid].openPopup();
        });
      });
      stopLayer.addTo(map);
    });

}
</script>
<script>

    $('#content').on('click', '.leaflet-marker-icon', function() {
        $('.leaflet-popup-content').css({
            'max-height':"1000px",
            'width':'280px',
        });
        $('.leaflet-popup').css({
         'left':'-164px',
        'transition': 'left .3s linear'});
    });

    </script>