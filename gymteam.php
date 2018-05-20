<?php
$curl = curl_init();
ob_start();
require 'config/config.php';
include'functions.php';
$gname = $conn->real_escape_string($_POST['gname']);
$tname = $conn->real_escape_string($_POST['tname']);


$sql = "UPDATE gyms SET gteam='$tname' WHERE gid='$gname'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}	

// Lookup teamname for webhook
$teamquery = "SELECT tname FROM teams WHERE tid = '$tname'";
	if(!mysqli_query($conn,$teamquery))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}

$resultteam = $conn->query($teamquery);

$row = $resultteam->fetch_array(MYSQLI_NUM);
$teamname = $row[0];
			
// Lookup gymname for webhook
$gymquery = "SELECT gname,glatitude,glongitude FROM gyms WHERE gid = '$gname'";
	if(!mysqli_query($conn,$gymquery))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}

$resultgym = $conn->query($gymquery);

$row = $resultgym->fetch_array(MYSQLI_NUM);
$gymname = $row[0];
$gymlat = $row[1];
$gymlon = $row[2];

$siteurl = "[".$viewtitle."](".$viewurl."/?loc=$gymlat,$gymlon%26zoom=19)";
			
curl_setopt_array($curl, array(
  CURLOPT_URL => "$webhook_url",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "content=**Gym taken:**\n$gymname is now controlled by $teamname\nView on $siteurl",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/x-www-form-urlencoded",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}	

	header('Location:/');
	
?>
