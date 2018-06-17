<?php
require('../config/config.php');
function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}
$query = "SELECT gid, gname, glatitude, glongitude, gteam, type, tname, actraid, actboss, hour, min, ampm, egg, monster, raidby, exraid, exraiddate, eggby, teamby, rcp FROM gyms as g LEFT JOIN teams AS t ON g.gteam = t.tid LEFT JOIN pokedex AS p ON p.id = g.actboss LEFT JOIN raidbosses AS r ON r.rid = g.actboss WHERE (g.gteam = t.tid AND g.egg=0 AND g.actraid=0) OR (g.gteam = t.tid AND p.id=g.actboss AND r.rid = g.actboss) OR (g.gteam = t.tid AND g.egg!=0)";
$result = mysqli_query($conn,$query)or die(mysqli_error($conn));

//////////////////// MAP XML \\\\\\\\\\\\\\\\\\\\\

header('Content-Type: text/xml');

// Start XML file, echo parent node
echo "<?xml version='1.0' ?>";
echo '<markers>';
$ind=0;
// Iterate through the rows, printing XML nodes for each
while ($row = @mysqli_fetch_assoc($result)){
  // Add to XML document node

  // Get pic for raids-spotter
  $raidurl = "SELECT url FROM users WHERE uname='".$row['raidby']."'";
  $getraiduserurl = $conn->query($raidurl);
  $rowurl = $getraiduserurl->fetch_array(MYSQLI_NUM);
  $raidurlresult = $rowurl[0];
  $row_cnt = $getraiduserurl->num_rows;
  // End get pic for raids-spotter
  
  // Get pic for eggs-spottter
  $eggurl = "SELECT url FROM users WHERE uname='".$row['eggby']."'";
  $getegguserurl = $conn->query($eggurl);
  $rowurl2 = $getegguserurl->fetch_array(MYSQLI_NUM);
  $eggurlresult = $rowurl2[0];
  $row_cnt2 = $getegguserurl->num_rows;
  // End get pic for eggs-spotter  

  // GET EXRAID SPOTTER
  $erquery = "SELECT spotter FROM exraids WHERE gname='".$row['gid']."'";
  $getspotter = $conn->query($erquery);
  $rower = $getspotter->fetch_array(MYSQLI_NUM);
  $exspotter = $rower[0];
  
  // Get pic for exraids-spotter
  $exraidurl = "SELECT url FROM exraids,users WHERE uname=exraids.spotter AND gname='".$row['gid']."'";
  $getexraiduserurl = $conn->query($exraidurl);
  $rowurl3 = $getexraiduserurl->fetch_array(MYSQLI_NUM);
  $exraidurlresult = $rowurl3[0];
  $row_cnt3 = $getexraiduserurl->num_rows;
  // End get pic for exraids-spotter
  
  // Get pic for teamby-spotter
  $teambyurl = "SELECT url FROM users WHERE uname='".$row['teamby']."'";
  $getteambyuserurl = $conn->query($teambyurl);
  $rowurl4 = $getteambyuserurl->fetch_array(MYSQLI_NUM);
  $teambyurlresult = $rowurl4[0];
  $row_cnt4 = $getteambyuserurl->num_rows;
  // End get pic for teambys-spotter

// DEFINE SPOTTER URLS
  
// DEFINE RAID SPOTTER PIC-URL
if ($row_cnt==0){ $raiduserurl = ''; } else if (!$raidurlresult) {$raiduserurl = 'nopic.png';} else { $raiduserurl = $raidurlresult; }

// DEFINE EGG SPOTTER PIC-URL
if ($row_cnt2==0){ $egguserurl = ''; } else if (!$eggurlresult) {$egguserurl = 'nopic.png';} else { $egguserurl = $eggurlresult; }

// DEFINE EXRAID SPOTTER PIC-URL
if ($row_cnt3==0){ $exraiduserurl = ''; } else if (!$exraidurlresult) {$exraiduserurl = 'nopic.png';} else { $exraiduserurl = $exraidurlresult; }

// DEFINE teamby SPOTTER PIC-URL
if ($row_cnt4==0){ $teambyuserurl = 'question.png'; } else if (!$teambyurlresult) {$teambyuserurl = 'nopic.png';} else { $teambyuserurl = $teambyurlresult; }

// END OF DEFINES
  
  echo '<marker ';
  echo 'gid="' . $row['gid'] . '" ';
  echo 'gname="' . parseToXML($row['gname']) . '" ';
  echo 'glatitude="' . $row['glatitude'] . '" ';
  echo 'glongitude="' . $row['glongitude'] . '" ';
  echo 'gteam="' . $row['gteam'] . '" ';
  echo 'type="' . $row['type'] . '" ';
  echo 'tid="' . $row['tname'] . '" ';
  echo 'actraid="' . $row['actraid'] . '" ';
  echo 'actboss="' . $row['actboss'] . '" ';
  echo 'hour="' . $row['hour'] . '" ';
  echo 'min="' . $row['min'] . '" ';
  echo 'ampm="' . $row['ampm'] . '" ';
  echo 'egg="' . $row['egg'] . '" ';
  echo 'bossname="' . $row['monster'] . '" ';
  echo 'raidby="' . $row['raidby'] . '" ';
  echo 'raidbypic="' . $raiduserurl . '" ';
  echo 'eggby="' . $row['eggby'] . '" ';  
  echo 'eggbypic="' . $egguserurl . '" ';  
  echo 'teamby="' . $row['teamby'] . '" '; 
  echo 'teambypic="' . $teambyuserurl . '" ';   
  echo 'bosscp="' . $row['rcp'] . '" ';
  echo 'exraid="' . $row['exraid'] . '" ';
  echo 'exraiddate="' . $row['exraiddate'] . '" ';
  echo 'exraidby="' . $exspotter . '" ';
  echo 'exraidbypic="' . $exraiduserurl . '" ';
  echo '/>';
  $ind = $ind + 1;
}

// End XML file
echo '</markers>';
?>
