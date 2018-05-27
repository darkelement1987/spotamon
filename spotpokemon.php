<?php
$curl = curl_init();
ob_start();
require './config/config.php';
include'frontend/functions.php';
include("login/auth.php");
$pokemon = $conn->real_escape_string($_POST['pokemon']);
$cp = $conn->real_escape_string($_POST['cp']);
$hour = intval(date('h'));
$min = intval(date('i'));
$ampm = $conn->real_escape_string($_POST['ampm']);
$latitude = $conn->real_escape_string($_POST['latitude']);
$longitude = $conn->real_escape_string($_POST['longitude']);
$url  = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=false&key=".$gmaps;
$json = @file_get_contents($url);
$data = json_decode($json);
$status = $data->status;
$address = '';
$good = 0;
$bad = 0;
$spotter = $conn->real_escape_string($_SESSION['uname']);
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
$sql = "INSERT INTO spots (pokemon, cp, hour, min, ampm, latitude, longitude, fulladdress, good, bad, spotter) VALUES ('$pokemon','$cp','$hour','$min','$ampm','$latitude','$longitude','$address','$good','$bad', '$spotter')";
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
$siteurl = "[".$viewtitle."](".$viewurl."/?loc=$latitude,$longitude&zoom=19)";
$date = date('h:i:s');

$hookObject = json_encode([
    "username" => "$monname spotted!",
    "tts" => false,
	"avatar_url" => "$viewurl/static/icons/$pokemon.png",
    "embeds" => [
        [
            "type" => "rich",
            "description" => "$siteurl",
            "color" => hexdec( "FFFFFF" ),
            "footer" => [
                "text" => "Spotted by $spotter at $date",
				"icon_url" => "$viewurl/static/icons/$pokemon.png"
            ],
            
            "image" => [
				"url" => "https://maps.googleapis.com/maps/api/staticmap?center=$latitude,$longitude&markers=$latitude,$longitude&zoom=17&size=400x400",
            ],
            
            "thumbnail" => [
				"url" => "$viewurl/static/icons/$pokemon.png",
            ],
            
            "author" => [
                "name" => "Pokemon Spotted by $spotter",
            ],
            
            "fields" => [
                [
                    "name" => "Pokemon:",
                    "value" => "$monname",
                    "inline" => true
                ],
				[
					"name" => "Found:",
					"value" => "$hour:$min",
					"inline" => true
				],
                [
                    "name" => "CP:",
                    "value" => "$cp",
                    "inline" => true
                ],
                [
                    "name" => "Location",
                    "value" => "$address",
                    "inline" => true
                ]
            ]
        ]
    ]
    
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

$ch = curl_init();

curl_setopt_array( $ch, [
    CURLOPT_URL => $webhook_url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $hookObject,
    CURLOPT_HTTPHEADER => [
        "Length" => strlen( $hookObject ),
        "Content-Type" => "application/json"
    ]
]);

$response = curl_exec( $ch );
curl_close( $ch );	

header('Location:index.php?loc='.$latitude.','.$longitude.'&zoom=19');
    
?>
