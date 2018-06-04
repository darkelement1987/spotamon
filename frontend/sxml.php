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
$query = "SELECT stops.type AS stype, quests.type AS qtype, stops.quest AS quest, quests.quest AS questname, rewards.reward, quests.qid, stops.sid, stops.sname, stops.slatitude, stops.slongitude, stops.quested FROM quests,stops,rewards WHERE quests.qid = stops.quest AND rewards.reid = stops.reward";
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
  echo 'sid="' . $row['sid'] . '" ';
  echo 'sname="' . parseToXML($row['sname']) . '" ';
  echo 'slatitude="' . $row['slatitude'] . '" ';
  echo 'slongitude="' . $row['slongitude'] . '" ';
  echo 'quest="' . $row['questname'] . '" ';
  echo 'reward="' . $row['reward'] . '" ';
  echo 'type="' . $row['type'] . '" ';
  echo '/>';
  $ind = $ind + 1;
}

// End XML file
echo '</markers>';
?>