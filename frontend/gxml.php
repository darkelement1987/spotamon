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
$query = "SELECT * FROM gyms,teams WHERE gyms.gteam = teams.tid";
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
  echo '/>';
  $ind = $ind + 1;
}

// End XML file
echo '</markers>';
?>