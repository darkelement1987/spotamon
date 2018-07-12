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
$query = "SELECT * FROM users,spots,pokedex WHERE 1 AND spots.pokemon = pokedex.id AND users.uname = spotter";
$result = mysqli_query($conn,$query)or die(mysqli_error($conn));

$getuserurl = $conn->query($query);

$row = $getuserurl->fetch_array(MYSQLI_NUM);
$picresult = $row[6];

if (!$picresult){ $userpic = 'nopic.png'; } else { $userpic = $picresult; }

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
  echo 'id="' . $row['pokemon'] . '" ';
  echo 'spotid="' . $row['spotid'] . '" ';
  echo 'pokemon="' . parseToXML($row['monster']) . '" ';
  echo 'cp="' . parseToXML($row['cp']) . '" ';
  echo 'iv="' . parseToXML($row['iv']) . '" ';
  echo 'hour="' . parseToXML($row['hour']) . '" ';
  echo 'min="' . parseToXML($row['min']) . '" ';
  echo 'ampm="' . parseToXML($row['ampm']) . '" ';
  echo 'latitude="' . $row['latitude'] . '" ';
  echo 'longitude="' . $row['longitude'] . '" ';
  echo 'type="' . $row['pokemon'] . '" ';
  echo 'good="' . $row['good'] . '" ';
  echo 'bad="' . $row['bad'] . '" ';
  echo 'spotter="' . $row['spotter'] . '" ';  
  echo 'spotterpic="' . $userpic . '" ';  
  echo '/>';
  $ind = $ind + 1;
}

// End XML file
echo '</markers>';
?>