<?php
$curl = curl_init();
ob_start();
require 'config/config.php';
include'functions.php';
$pokemon = $conn->real_escape_string($_POST['pokemon']);
$cp = $conn->real_escape_string($_POST['cp']);
$hour = $conn->real_escape_string($_POST['hour']);
$min = $conn->real_escape_string($_POST['min']);
$ampm = $conn->real_escape_string($_POST['ampm']);
$latitude = $conn->real_escape_string($_POST['latitude']);
$longitude = $conn->real_escape_string($_POST['longitude']);
$url  = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=false&key=".$gmaps;
$json = @file_get_contents($url);
$data = json_decode($json);
$status = $data->status;
$address = '';
if($status == "OK")
{
    $address = $conn->real_escape_string($data->results[0]->formatted_address);
}
else
{
    $address = "Cannot retrieve address";
}
echo $address;

// Start queries
$sql = "INSERT INTO spots (pokemon, cp, hour, min, ampm, latitude, longitude, fulladdress) VALUES ('$pokemon','$cp','$hour','$min','$ampm','$latitude','$longitude', '$address')";
if(!mysqli_query($conn,$sql))
{
    echo 'Not Inserted';
}
else
{
    echo 'Inserted';
}    

// Lookup Pokemon name for webhook
$monnamequery = "SELECT monster FROM pokedex WHERE id = '$pokemon'";
	if(!mysqli_query($conn,$monnamequery))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}

$result = $conn->query($monnamequery);
$row = $result->fetch_array(MYSQLI_NUM);
$monname = $row[0];
$moncp = $cp."CP";
$siteurl = "[".$viewtitle."](".$viewurl."/?loc=$latitude,$longitude%26zoom=19)";
			
curl_setopt_array($curl, array(
  CURLOPT_URL => "$webhook_url",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "content=**$monname #$pokemon ($moncp) spotted.**\nLocation: *$address*\nFound: *$hour:$min $ampm*\nView on $siteurl",
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

header('Location:./?loc='.$latitude.','.$longitude.'&zoom=19');
    
?>
