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

// Query for gyms without eggs/raids
$query = "SELECT * FROM gyms,teams WHERE gyms.gteam = teams.tid AND egg=0 AND actraid=0";
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
  echo 'eggby="' . $row['eggby'] . '" ';  
  echo 'teamby="' . $row['teamby'] . '" '; 
  echo '/>';
  $ind = $ind + 1;
}

// Query for active raids
$query2 = "SELECT * FROM gyms,teams,pokedex WHERE gyms.gteam = teams.tid AND pokedex.id=gyms.actboss";
$result2 = mysqli_query($conn,$query2)or die(mysqli_error($conn));
//////////////////// MAP XML \\\\\\\\\\\\\\\\\\\\\

header('Content-Type: text/xml');

// Start XML file, echo parent node
$ind=0;
// Iterate through the rows, printing XML nodes for each
while ($row = @mysqli_fetch_assoc($result2)){
  // Add to XML document node
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
  echo 'eggby="' . $row['eggby'] . '" ';  
  echo 'teamby="' . $row['teamby'] . '" '; 
  echo '/>';
  $ind = $ind + 1;
}

// Query for active eggs
$query3 = "SELECT * FROM gyms,teams WHERE gyms.gteam = teams.tid AND egg=1";
$result3 = mysqli_query($conn,$query3)or die(mysqli_error($conn));
//////////////////// MAP XML \\\\\\\\\\\\\\\\\\\\\

header('Content-Type: text/xml');

// Start XML file, echo parent node
$ind=0;
// Iterate through the rows, printing XML nodes for each
while ($row = @mysqli_fetch_assoc($result3)){
  // Add to XML document node
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
  echo 'eggby="' . $row['eggby'] . '" ';  
  echo 'teamby="' . $row['teamby'] . '" '; 
  echo '/>';
  $ind = $ind + 1;
}

// End XML file
echo '</markers>';
?>
