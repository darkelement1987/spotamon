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

	$gymquery = "SELECT gname,glatitude,glongitude,gteam FROM gyms WHERE gid = '$gname'";
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
$gymteam = $row[3];
		
	$siteurl = "[".$viewtitle."](".$viewurl."/?loc=$gymlat,$gymlon&zoom=19)";
		
		$hookObject = json_encode([
    "username" => "EX Raid Spotted!",
    "tts" => false,
	"avatar_url" => "$viewurl/static/gyms/".$gymteam."ex.png",
    "embeds" => [
        [
            "type" => "rich",
            "color" => hexdec( "FFFFFF" ),
            "footer" => [
                "text" => "Spotted by $spotter at $exraiddate",
				"icon_url" => "$viewurl/static/gyms/".$gymteam."ex.png"
            ],
            
            "image" => [
				"url" => "http://staticmap.openstreetmap.de/staticmap.php?center=".$gymlat.",".$gymlon."&zoom=17&size=400x400&maptype=mapnik&markers=".$gymlat.",".$gymlon.",red-pushpin",
            ],   
            "author" => [
                "name" => "Ex-Raid spotted by $spotter",
            ],			
            "thumbnail" => [
				"url" => "$viewurl/static/gyms/".$gymteam."ex.png",
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
