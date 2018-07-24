<?php
$curl = curl_init();
ob_start();
require_once 'initiate.php';
include S_FUNCTIONS . 'functions.php';
$pokemon = $conn->real_escape_string($_POST['pokemon']);
$cp = $conn->real_escape_string($_POST['cp']);
$iv = $conn->real_escape_string($_POST['moniv']);
if ($clock=="false"){
	$hour = date('g');
	$ampm = date('A');
	} else {
		$hour = date('H');
		$ampm = '';
		}
		
$min = date('i');

$latitude = $conn->real_escape_string($_POST['latitude']);
$longitude = $conn->real_escape_string($_POST['longitude']);
$address = $conn->real_escape_string($_POST['addressinput']);
$good = 0;
$bad = 0;
$spotter = $conn->real_escape_string($_SESSION['uname']);

// Start queries

	if ($clock=="false"){
$sql = "INSERT INTO spots (pokemon, cp, iv, hour, min, ampm, latitude, longitude, fulladdress, good, bad, spotter) VALUES ('$pokemon','$cp','$iv','$hour','$min','$ampm','$latitude','$longitude','$address','$good','$bad', '$spotter')";
if(!mysqli_query($conn,$sql))
{
    echo 'Not Inserted';
}
else
{
    echo 'Inserted';
}    

	} else {
		
$sql = "INSERT INTO spots (pokemon, cp, iv, hour, min, ampm, latitude, longitude, fulladdress, good, bad, spotter) VALUES ('$pokemon','$cp','$iv','$hour','$min','','$latitude','$longitude','$address','$good','$bad', '$spotter')";
if(!mysqli_query($conn,$sql))
{
    echo 'Not Inserted';
}
else
{
    echo 'Inserted';
}   

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

if ($clock=="false"){
	$date = date('g:i:s A');
	} else {
		$date = date('H:i:s');
		}

$hookObject = json_encode([
    "username" => "$monname spotted!",
    "tts" => false,
	"avatar_url" => W_ASSETS . "icons/$pokemon.png",
    "embeds" => [
        [
            "type" => "rich",
            "description" => "$siteurl",
            "color" => hexdec( "FFFFFF" ),
            "footer" => [
                "text" => "Spotted by $spotter at $date",
				"icon_url" => W_ASSETS . "icons/$pokemon.png"
            ],
            
            "image" => [
				"url" => "http://staticmap.openstreetmap.de/staticmap.php?center=".$latitude.",".$longitude."&zoom=17&size=400x400&maptype=mapnik&markers=".$latitude.",".$longitude.",red-pushpin",
            ],
            
            "thumbnail" => [
				"url" => W_ASSETS . "icons/$pokemon.png",
            ],
            
            "author" => [
                "name" => "$monname spotted by $spotter",
            ],
            
            "fields" => [
				[
					"name" => "Found:",
					"value" => "$hour:$min $ampm",
					"inline" => true
				],
                [
                    "name" => "CP:",
                    "value" => "$cp",
                    "inline" => true
                ],
                [
                    "name" => "IV:",
                    "value" => "$iv%",
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
    CURLOPT_URL => $pokemon_webhook_url,
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
