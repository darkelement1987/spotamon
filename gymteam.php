<?php
$curl = curl_init();
ob_start();
require './config/config.php';
include'./functions.php';
include("login/auth.php");
$gname = $conn->real_escape_string($_POST['gname']);
$tname = $conn->real_escape_string($_POST['tname']);
$teamby = $conn->real_escape_string($_SESSION['uname']);


$sql = "UPDATE gyms SET gteam='$tname', teamby='$teamby' WHERE gid='$gname'";
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

$siteurl = "[".$viewtitle."](".$viewurl."/?loc=$gymlat,$gymlon&zoom=19)";
$date = date('h:i:s');

$hookObject = json_encode([
    "username" => "Gym taken!",
    "tts" => false,
	"avatar_url" => "https://www.spotamon.com/static/teams/$tname.png",
    "embeds" => [
        [
            "type" => "rich",
            "description" => "$siteurl",
            "color" => hexdec( "FFFFFF" ),
            "footer" => [
                "text" => "Spotted by $teamby at $date",
				"icon_url" => "https://www.spotamon.com/static/teams/$tname.png"
            ],
            
            "image" => [
				"url" => "https://maps.googleapis.com/maps/api/staticmap?center=$gymlat,$gymlon&markers=$gymlat,$gymlon&zoom=17&size=400x400",
            ],
            
            "thumbnail" => [
				"url" => "https://www.spotamon.com/static/teams/$tname.png",
            ],
            
            "author" => [
                "name" => "Gym Taken (spotted by $spotter)",
            ],
            
            "fields" => [
                [
                    "name" => "Gym:",
                    "value" => "$gymname",
                    "inline" => false
                ],
				[
					"name" => "Taken at:",
					"value" => "$date",
					"inline" => false
				],
                [
                    "name" => "Now controlled by:",
                    "value" => "$teamname",
                    "inline" => false
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

	header('Location:index.php?loc='.$gymlat.','.$gymlon.'&zoom=19');
	
?>
