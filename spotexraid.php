<?php
$curl = curl_init();
ob_start();
require './config/config.php';
include'frontend/functions.php';
include("login/auth.php");
$exraiddate = $conn->real_escape_string($_POST['exraiddate']);
$gname = $conn->real_escape_string($_POST['gname']);
$spotter = $conn->real_escape_string($_SESSION['uname']);

$sql = "INSERT INTO exraids (gname, exraiddate, spotter) VALUES ('$gname', '$exraiddate', '$spotter')";
    if(!mysqli_query($conn,$sql))
        {
            echo 'Not Inserted';
        }
            else
            {
                echo 'Inserted';
            }

$sql1 = "UPDATE gyms SET exraid='1',exraiddate='$exraiddate' WHERE gid='$gname'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Updated';
        }
            else
            {
                echo 'Updated';
            }

	$gymquery = "SELECT gname,glatitude,glongitude FROM gyms WHERE gid = '$gname'";
	if(!mysqli_query($conn,$gymquery))
		{
			echo 'Not Selected';
		}
			else
			{
				echo 'Selected';
			}

$resultgym = $conn->query($gymquery);

$row = $resultgym->fetch_array(MYSQLI_NUM);
$gymname = $row[0];
$gymlat = $row[1];
$gymlon = $row[2];
		
	$siteurl = "[".$viewtitle."](".$viewurl."/?loc=$gymlat,$gymlon&zoom=19)";
		
		$hookObject = json_encode([
    "username" => "EX Raid Spotted!",
    "tts" => false,
    "embeds" => [
        [
            "type" => "rich",
            "description" => "$siteurl",
            "color" => hexdec( "FFFFFF" ),
            "image" => [
				"url" => "https://maps.googleapis.com/maps/api/staticmap?center=$gymlat,$gymlon&markers=$gymlat,$gymlon&zoom=17&size=400x400",
            ],            
            "fields" => [
                [
                    "name" => "Gym",
                    "value" => "$gymname",
                    "inline" => true
                ]
            ]
        ]
    ]
    
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

$ch = curl_init();

curl_setopt_array( $ch, [
    CURLOPT_URL => $exraid_webhook_url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $hookObject,
    CURLOPT_HTTPHEADER => [
        "Length" => strlen( $hookObject ),
        "Content-Type" => "application/json"
    ]
]);

$response = curl_exec( $ch );
curl_close( $ch );
	
	header('Location:index.php?loc='.$gymlat.','.$gymlon.'&zoom=19');
?>
