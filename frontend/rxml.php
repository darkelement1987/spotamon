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
$query = "SELECT * FROM spotraid,raidbosses WHERE 1 AND spotraid.rboss = raidbosses.rid";
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
  echo 'rid="' . $row['rid'] . '" ';
  echo 'rboss="' . parseToXML($row['rboss']) . '" ';
  echo 'rlvl="' . parseToXML($row['rlvl']) . '" ';
  echo 'rcp="' . parseToXML($row['rcp']) . '" ';
  echo 'rhour="' . parseToXML($row['rhour']) . '" ';
  echo 'rmin="' . parseToXML($row['rmin']) . '" ';
  echo 'rampm="' . parseToXML($row['rampm']) . '" ';
  echo 'rlatitude="' . $row['rlatitude'] . '" ';
  echo 'rlongitude="' . $row['rlongitude'] . '" ';
  echo 'type="' . $row['rlvl'] . '" ';
  echo '/>';
  $ind = $ind + 1;
}

// End XML file
echo '</markers>';
?>