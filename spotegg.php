<?php
$curl = curl_init();
ob_start();
require './config/config.php';
include'frontend/functions.php';
include("login/auth.php");
$gname = $conn->real_escape_string($_POST['gname']);
$egg = $conn->real_escape_string($_POST['egg']);
$minutes = $conn->real_escape_string($_POST['etime']);
$pulltime = date('H:i:s');
$timeuntilegg = strtotime("+$minutes minutes", strtotime($pulltime));
$newtime = date('Y-m-d H:i:s', $timeuntilegg);
$rhour = intval(date('h', $timeuntilegg));
$rmin = intval(date('i', $timeuntilegg));
$rampm = date('A');
$eggby = $conn->real_escape_string($_SESSION['uname']);

// Start queries
$sql = "UPDATE gyms SET egg='$egg',hour='$rhour',min='$rmin',ampm='$rampm',eggby='$eggby' WHERE gid='$gname'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}	

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

$result = $conn->query($gymquery);

$row = $result->fetch_array(MYSQLI_NUM);
$gymname = $row[0];
$gymlat = $row[1];
$gymlon = $row[2];
$siteurl = "[".$viewtitle."](".$viewurl."/?loc=$gymlat,$gymlon&zoom=19)";		
$date = date('h:i:s');

$hookObject = json_encode([
    "username" => "Egg spotted!",
    "tts" => false,
	"avatar_url" => "$viewurl/static/eggs/$egg.png",
    "embeds" => [
        [
            "type" => "rich",
            "description" => "$siteurl",
            "color" => hexdec( "FFFFFF" ),
            "footer" => [
                "text" => "Spotted at by $eggby at $date",
				"icon_url" => "$viewurl/static/eggs/$egg.png"
            ],
            
            "image" => [
				"url" => "https://maps.googleapis.com/maps/api/staticmap?center=$gymlat,$gymlon&markers=$gymlat,$gymlon&zoom=17&size=400x400",
            ],
            
            "thumbnail" => [
				"url" => "$viewurl/static/eggs/$egg.png",
            ],
            
            "author" => [
                "name" => "Egg Spotted by $eggby",
            ],
            
            "fields" => [
                [
                    "name" => "Egg Level:",
                    "value" => "$egg",
                    "inline" => true
                ],
				[
					"name" => "Hatches at:",
					"value" => "$rhour:$rmin",
					"inline" => true
				],
                [
                    "name" => "Gym:",
                    "value" => "$gymname",
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
			
	header('Location:index.php?loc='.$gymlat.','.$gymlon.'&zoom=19');
	
?>
