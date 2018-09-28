<?php


//=============================
//    AUTO VERSION CSS
//=============================

function versionFile($url) {
    $dir = S_ROOT . $url;
    if (file_exists($dir)) {
	    $path = pathinfo($url);
	    $ver = '.'.filemtime($dir).'.';
	    return $path['dirname'].'/'.preg_replace('/\.(css|js|png|jpg|jpeg|svg|ico)$/', $ver."$1", $path['basename']);
    } else {
        return $url;
    }
}
//=========AND Images==========
function versionImage($url) {
    $dir = S_ROOT . $url;
    if (file_exists($dir)) {
	    $path = pathinfo($url);
	    $ver = $url. '?lastmod=' .filemtime($dir);
        return $ver;
    }
}


//=============================
//    MAPS
//=============================
function maps()
{
    require './config/config.php';
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
    })
map.addLayer(L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'}))
    // Change this depending on the name of your PHP or XML file
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
        <?php if (isset($_SESSION["uname"])) { ?>
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
                <?php if (isset($_SESSION["uname"])) { ?>
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
                        <p><b>` + gname + `</b><br>Team: ` + tid + `
                        <?php if (!isset($_SESSION["uname"])) { ?>
                        <hr>
                        <b><span class="text-danger">Login to change/add teams or raids.</span></b>
                        <?php } else { ?>
                        <br>
                        <hr><strong>EX Raid On:</strong><br> ` + exraiddate + `
                        <br><hr><b>Choose team:</b>
                        <br>
                        <form action="./gymteam.php" name="postInstinct" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="2">
                        </form>
                        <form action="./gymteam.php" name="postValor" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="3">
                        </form>
                        <form action="./gymteam.php" name="postMystic" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="4">
                        </form>
                        <a href="javascript:void(0);" class="Instinct">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="javascript:void(0);" class="Valor">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="javascript:void(0);" class="Mystic">
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
            iconSize: [55, 55]
			});
			} else if (exraid === "0"){
            var html = `
                <div class="maplabel">
                    <center>
                        <img src="<?=W_ASSETS?>gyms/` + gteam + `.png" height="45px" width="45px"></img>
                        <p><b>` + gname + `</b><br>Team: ` + tid + `
                    <?php if (!isset($_SESSION["uname"])) { ?>
                        <hr><b><span class="text-danger">Login to change/add teams or raids.</span></b>
                    <?php } else { ?>
                        <br>
                        <hr><b>Choose team:</b><br>
                        <form action="./gymteam.php" name="postInstinct" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="2">
                        </form>
                        <form action="./gymteam.php" name="postValor" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="3">
                        </form>
                        <form action="./gymteam.php" name="postMystic" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="4">
                        </form>
                        <a href="javascript:void(0);" class="Instinct">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="javascript:void(0);" class="Valor">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="javascript:void(0);" class="Mystic">
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
            iconSize: [55, 55]
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
                    <?php if (!isset($_SESSION["uname"])) { ?>
                        <hr><b><span class="text-danger">Login to change/add teams or raids.</span></b>
                    <?php } else if (isset($_SESSION["uname"])) { ?>
                        <br><hr>
                        <b>Choose team:</b>
                        <br>
                        <form action="./gymteam.php" name="postInstinct" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="2">
                        </form>
                        <form action="./gymteam.php" name="postValor" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="3">
                        </form>
                        <form action="./gymteam.php" name="postMystic" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="4">
                        </form>
                        <a href="javascript:void(0);" class="Instinct">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="javascript:void(0);" class="Valor">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="javascript:void(0);" class="Mystic">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                    <?php } ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                    <?php if (isset($_SESSION["uname"])) { ?>
                        <br><hr><b>Spotted by: </b>` + raidby + `
                    <?php } ?>
                    </center>
                </div>`;
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>raids/' + actboss + '.png',
            iconSize: [55, 55]
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
                    <?php if (!isset($_SESSION["uname"])) { ?>
                        <hr><b><span class="text-danger">Login to change/add teams or raids.</span></b>
                    <?php } else if (isset($_SESSION["uname"])) { ?>
                        <br><hr><strong>EX Raid On:</strong>
                        <br> ` + exraiddate + `
                        <br><hr><b>Choose team:</b>
                        <br>
                        <form action="./gymteam.php" name="postInstinct" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="2">
                        </form>
                        <form action="./gymteam.php" name="postValor" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="3">
                        </form>
                        <form action="./gymteam.php" name="postMystic" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="4">
                        </form>
                        <a href="javascript:void(0);" class="Instinct">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="javascript:void(0);" class="Valor">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="javascript:void(0);" class="Mystic">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                    <?php } ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                    <?php if (isset($_SESSION["uname"])) { ?>
                        <br><hr><b>Spotted by: </b>` + raidby + `
                    <?php } ?>
                    </center>
                </div>`;
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>raids/' + actboss + '.png',
            iconSize: [75, 75]
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
                    <?php if (!isset($_SESSION["uname"])) { ?>
                        <hr><b><span class="text-danger">Login to change/add teams or raids.</span></b>
                    <?php } else if (isset($_SESSION["uname"])) { ?>
                    <br><hr>
                    <b>Choose team:</b>
                    <br>
                    <form action="./gymteam.php" name="postInstinct" method="post">
                        <input type="hidden" name="gname" value="` + gid + `">
                        input type="hidden" name="tname" value="2">
                    </form>
                    <form action="./gymteam.php" name="postValor" method="post">
                        <input type="hidden" name="gname" value="` + gid + `">
                        <input type="hidden" name="tname" value="3">
                    </form>
                    <form action="./gymteam.php" name="postMystic" method="post">
                        <input type="hidden" name="gname" value="` + gid + `">
                        <input type="hidden" name="tname" value="4">
                    </form>
                    <a href="javascript:void(0);" class="Instinct">
                        <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                    </a> / <a href="javascript:void(0);" class="Valor">
                        <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                    </a> / <a href="javascript:void(0);" class="Mystic">
                        <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                    </a>
                <?php } ?>
                    <br><hr>
                    <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                <?php if (isset($_SESSION["uname"])) { ?>
                    <br><hr>
                    <b>Spotted by: </b>` + eggby + `
                <?php } ?>
                </center>
            </div>`;
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>eggs/' + egg + '.png',
            iconSize: [55, 55]
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
                    <?php if (!isset($_SESSION["uname"])) { ?>
                        <hr><b><span class="text-danger">Login to change/add teams or raids.</span></b>
                    <?php } else if (isset($_SESSION["uname"])) { ?>
                        <br><hr>
                        <strong>EX Raid On:</strong>
                        <br> ` + exraiddate + `
                        <br><hr>
                        <b>Choose team:</b>
                        <br>
                        <form action="./gymteam.php" name="postInstinct" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="2">
                        </form>
                        <form action="./gymteam.php" name="postValor" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="3">
                        </form>
                        <form action="./gymteam.php" name="postMystic" method="post">
                            <input type="hidden" name="gname" value="` + gid + `">
                            <input type="hidden" name="tname" value="4">
                        </form>
                        <a href="javascript:void(0);" class="Instinct">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/2.png" width="25" height="25">
                        </a> / <a href="javascript:void(0);" class="Valor">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/3.png" width="25" height="25">
                        </a> / <a href="javascript:void(0);" class="Mystic">
                            <img border="0" alt="W3Schools" src="<?= W_ASSETS ?>teams/4.png" width="25" height="25">
                        </a>
                    <?php }; ?>
                        <br><hr>
                        <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('glatitude') + `,` + markerElem.getAttribute('glongitude') + `">Google Maps</a>
                    <?php if (isset($_SESSION["uname"])) { ?>
                        <br>
                        <hr><b>Spotted by: </b>` + eggby + `
                    <?php } ?>
                    </center>
                </div>`;
			var icon = customLabel[type] || {};
			var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>eggs/' + egg + '.png',
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
                <?php if (!isset($_SESSION["uname"])) { ?>
                    <br>
                    (<b><span class="text-success">Quested</span></b>)
                    <br><hr>
                    <b><span class="text-danger">Login to add/view quests.</span></b>
                <?php } else if (isset($_SESSION["uname"])) { ?>
                    <br><hr>
                    <b>Quest:</b>
                    <br> ` + quest + `
                    <br><hr>
                    <b>Reward:</b>
                    <br>` + reward + `
                <?php } ?>
                    <br><hr>
                    <a href="http://maps.google.com/maps?q=` + markerElem.getAttribute('slatitude') + `,` + markerElem.getAttribute ('slongitude') + `">Google Maps</a>
                <?php if (isset($_SESSION["uname"])) { ?>
                    <br><hr>
                    <b>Spotted by: </b>` + questby + `
                <?php } ?>
                </center>
            </div>`;
        var icon = customLabel[type] || {};
        var image = new L.Icon({
            iconUrl: '<?= W_ASSETS ?>stops/queststop.png',
            iconSize: [30, 30]
			});
		} else if (quested === ""){
        var html = `
            <div class="maplabel">
                <center>
                    <img src="<?= W_ASSETS ?>stops/stops.png" height="45" width="45"></img>
                    <p><b>` + sname + `</b>
                <?php if (!isset($_SESSION["uname"])) { ?>
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

</script>
<?php

//=============================

//=============================
//     MY OPEN OFFERS
//=============================
function mynatrades()
{
    require './config/config.php';
    $results_per_page = 10;
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    } else {
        $page = 1;
    }
    $start_from = ($page - 1) * $results_per_page;
    $sql = "SELECT * FROM offers WHERE offers.tname = '" . $_SESSION['uname'] . "' AND accepted = 0 ORDER BY oid DESC LIMIT $start_from," . $results_per_page;
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $sqlcnt = "SELECT COUNT(OID) AS total FROM offers";
    $resultcnt = $conn->query($sqlcnt);
    $row = $resultcnt->fetch_assoc();
    $total_pages = ceil($row["total"] / $results_per_page);
    ?>
                                <h3 style="text-align:center;"><strong>My Available Trades:</strong></h3>
                                <center>
                                    <!--///////////////////// START OF TABLE \\\\\\\\\\\\\\\\\\\\\-->
                                    <table id="spotted" class="table table-bordered">
                                        <?php
if (isset($_SESSION["uname"])) {?>
                                        <tr>
                                            <th>#</th>
                                            <th>OFFERED POKEMON</th>
                                            <th>REQUESTED POKEMON</th>
                                            <th>CITY TO TRADE</th>
                                            <th>STATUS</th>
                                            <th>DATE</th>
                                        </tr>
                                        <?php } else {?>
                                        <tr>
                                            <th>#</th>
                                            <th>OFFERED POKEMON</th>
                                            <th>REQUESTED POKEMON</th>
                                            <th>CITY TO TRADE</th>
                                            <th>STATUS</th>
                                            <th>DATE</th>
                                        </tr>
                                        <?php }
    while ($row = mysqli_fetch_array($result)) {
        $oid = $row['oid'];
        $offmon = $row['offmon'];
        $reqmon = $row['reqmon'];
        $tradeloc = $row['tradeloc'];
        $date = $row['date'];
        $tname = $row['tname'];
        if (isset($_SESSION["uname"])) {?>
                                        <tr>
                                            <td style='text-align:center;'>
                                                <?=$oid?>
                                            </td>
                                            <td>
                                                <img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$offmon?>.png"
                                                    title="<?=$offmon?> (#<?=$offmon?>)" height="24" width="28">
                                                <p style="padding-top:6%;">
                                                    <?=$offmon?>
                                                </p>
                                            </td>
                                            <td><img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$reqmon?>.png"
                                                    title="<?=$reqmon?> (#<?=$reqmon?>)" height="24" width="28">
                                                <p style="padding-top:6%;">
                                                    <?=$reqmon?>
                                                </p>
                                            </td>
                                            <td>
                                                <?=$tradeloc?>
                                            </td>
                                            <td style='text-align:center; color:green;'>AVAILABLE</td>
                                            <td>
                                                <?=$date?>
                                            </td>
                                        </tr>
                                        <?php } else {?>
                                        <tr>
                                            <td style='text-align:center;'>
                                                <?=$tid?>
                                            </td>
                                            <td><img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$offmon?>.png"
                                                    title="<?=$offmon?> (#<?=$offmon?>)" height="24" width="28">
                                                <p style="padding-top:6%;">
                                                    <?=$offmon?>
                                                </p>
                                            </td>
                                            <td><img style="float:left; padding-right:5px;" src="<?=W_ASSETS?>icons/<?=$reqmon?>.png"
                                                    title="<?=$reqmon?> (#<?=$reqmon?>)" height="24" width="28">
                                                <p style="padding-top:6%;">
                                                    <?=$reqmon?>
                                                </p>
                                            </td>
                                            <td>
                                                <?=$tradeloc?>
                                            </td>
                                            <td style='text-align:center; color:orange;'> ACCEPTED / IN PROGRESS</td>
                                            <td>
                                                <?=$rname?>
                                            </td>
                                            <td>
                                                <?=$date?>
                                            </td>
                                        </tr>
                                        <?php }
    }?>
                                    </table>
                                </center>
                                <p id='pages'>
                                    <center>
                                        <?php
//-----------------------------
    //     pagenation
    //-----------------------------
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='" . basename($_SERVER['PHP_SELF']) . "?page=" . $i . "'>" . $i . "</a> ";
    }
    ?>
                                    </center>
                                    <?php }
}
