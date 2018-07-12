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
$query = "SELECT * FROM stops,quests,rewards WHERE stops.actquest = quests.qid AND stops.actreward = rewards.reid";
$result = mysqli_query($conn,$query)or die(mysqli_error($conn));

//////////////////// MAP XML \\\\\\\\\\\\\\\\\\\\\

header('Content-Type: text/xml');

// Start XML file, echo parent node
echo "<?xml version='1.0' ?>";
echo '<markers>';


// Iterate through the rows, printing XML nodes for each
while ($row = @mysqli_fetch_assoc($result)){
  // Add to XML document node
  
  // Get pic for quests-spotter
  $questurl = "SELECT url FROM users WHERE uname='".$row['questby']."'";
  $getquestuserurl = $conn->query($questurl);
  $rowurl = $getquestuserurl->fetch_array(MYSQLI_NUM);
  $questurlresult = $rowurl[0];
  $row_cnt = $getquestuserurl->num_rows;
  // End get pic for quests-spotter
  
// DEFINE quest SPOTTER PIC-URL
if ($row_cnt==0){ $questuserurl = ''; } else if (!$questurlresult) {$questuserurl = 'nopic.png';} else { $questuserurl = $questurlresult; }
  
  echo '<marker ';
  echo 'sid="' . $row['sid'] . '" ';
  echo 'sname="' . parseToXML($row['sname']) . '" ';
  echo 'slatitude="' . $row['slatitude'] . '" ';
  echo 'slongitude="' . $row['slongitude'] . '" ';
  echo 'quest="' . $row['qname'] . '" ';
  echo 'reward="' . $row['rname'] . '" ';
  echo 'type="' . $row['type'] . '" ';
  echo 'quested="' . $row['quested'] . '" ';
  echo 'questby="' . $row['questby'] . '" ';  
  echo 'questbypic="' . $questuserurl . '" ';    
  echo '/>';
}

$query2 = "SELECT * FROM stops WHERE quested=0";
$result2 = mysqli_query($conn,$query2)or die(mysqli_error($conn));


// Iterate through the row2s, printing XML nodes for each
while ($row2 = @mysqli_fetch_assoc($result2)){
  // Add to XML document node
  
  // Get pic for quests-spotter
  $questurl = "SELECT url FROM users WHERE uname='".$row['questby']."'";
  $getquestuserurl = $conn->query($questurl);
  $rowurl = $getquestuserurl->fetch_array(MYSQLI_NUM);
  $questurlresult = $rowurl[0];
  $row_cnt = $getquestuserurl->num_rows;
  // End get pic for quests-spotter
  
// DEFINE quest SPOTTER PIC-URL
if ($row_cnt==0){ $questuserurl = ''; } else if (!$questurlresult) {$questuserurl = 'nopic.png';} else { $questuserurl = $questurlresult; }
  
  echo '<marker ';
  echo 'sid="' . $row2['sid'] . '" ';
  echo 'sname="' . parseToXML($row2['sname']) . '" ';
  echo 'slatitude="' . $row2['slatitude'] . '" ';
  echo 'slongitude="' . $row2['slongitude'] . '" ';
  echo 'type="' . $row2['type'] . '" ';
  echo 'quested="' . $row['quested'] . '" ';  
  echo 'questby="' . $row['questby'] . '" ';    
  echo 'questbypic="' . $questuserurl . '" '; 
  echo '/>';
}

// End XML file
echo '</markers>';
?>
