<?php
$curl = curl_init();
ob_start();
require './config/config.php';
include'frontend/functions.php';
include("login/auth.php");
$rboss = $conn->real_escape_string($_POST['rboss']);
$minutes = $conn->real_escape_string($_POST['rtime']);
$pulltime = date('H:i:s');
$timeuntilraid = strtotime("+$minutes minutes", strtotime($pulltime));
$newtime = date('Y-m-d H:i:s', $timeuntilraid);
if ($clock=="false"){
	$rhour = date('g');
	$rmin = date('i');
	$rampm = date('A');
	} else {
		$rhour = date('H');
		$rmin = date('i');
		$rampm = '';
		}

$gname = $conn->real_escape_string($_POST['gname']);
$spotter = $conn->real_escape_string($_SESSION['uname']);

	if ($clock=="false"){
$sql = "INSERT INTO spotraid (rboss, rhour, rmin, rampm, spotter) VALUES ('$rboss','$rhour','$rmin','$rampm','$spotter')";
    if(!mysqli_query($conn,$sql))
        {
            echo 'Not Inserted';
        }
            else
            {
                echo 'Inserted';
            }
$sql1 = "UPDATE gyms SET actraid='1',actboss='$rboss',hour='$rhour',min='$rmin',ampm='$rampm' WHERE gid='$gname'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }
            else
            {
                echo 'Inserted';
            }                
			
	} else {
		$sql = "INSERT INTO spotraid (rboss, rhour, rmin, rampm, spotter) VALUES ('$rboss','$rhour','$rmin','','$spotter')";
    if(!mysqli_query($conn,$sql))
        {
            echo 'Not Inserted';
        }
            else
            {
                echo 'Inserted';
            }
$sql1 = "UPDATE gyms SET actraid='1',actboss='$rboss',hour='$rhour',min='$rmin',ampm='' WHERE gid='$gname'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }
            else
            {
                echo 'Inserted';
            }
	}

$bosslevelquery = "SELECT rlvl FROM raidbosses WHERE rid='$rboss'";
	if(!mysqli_query($conn,$bosslevelquery))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}

$resultboss = $conn->query($bosslevelquery);

$rowboss = $resultboss->fetch_array(MYSQLI_NUM);
$bosslevel = $rowboss[0];

$bossnamequery = "SELECT monster FROM pokedex WHERE id='$rboss'";
	if(!mysqli_query($conn,$bossnamequery))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}

$resultbossname = $conn->query($bossnamequery);

$rowbossname = $resultbossname->fetch_array(MYSQLI_NUM);
$bossname = $rowbossname[0]; 

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

$bosscpquery = "SELECT rcp FROM raidbosses where rid='$rboss'";
	if(!mysqli_query($conn,$bosscpquery))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}

$resultbosscp = $conn->query($bosscpquery);

$row = $resultbosscp->fetch_array(MYSQLI_NUM);
$bosscp = $row[0]."CP";
$siteurl = "[".$viewtitle."](".$viewurl."/?loc=$gymlat,$gymlon&zoom=19)";

if ($clock=="false"){
	$date = date('g:i:s A');
	} else {
		$date = date('H:i:s');
		}

$hookObject = json_encode([
    "username" => "Raid Spotted!",
    "tts" => false,
	"avatar_url" => "$viewurl/static/raids/$rboss.png",
    "embeds" => [
        [
            "type" => "rich",
            "description" => "$siteurl",
            "color" => hexdec( "FFFFFF" ),
            "footer" => [
                "text" => "Spotted by $spotter at $date",
				"icon_url" => "$viewurl/static/raids/$rboss.png"
            ],
            
            "image" => [
				"url" => "https://maps.googleapis.com/maps/api/staticmap?center=$gymlat,$gymlon&markers=$gymlat,$gymlon&zoom=17&size=400x400",
            ],
            
            "thumbnail" => [
				"url" => "$viewurl/static/raids/$rboss.png",
            ],
            
            "author" => [
                "name" => "Raid Spotted by $spotter",
            ],
            
            "fields" => [
                [
                    "name" => "Boss:",
                    "value" => "$bossname",
                    "inline" => true
                ],
				[
					"name" => "Expires:",
					"value" => "$rhour:$rmin $rampm",
					"inline" => true
				],
                [
                    "name" => "Strength:",
                    "value" => "Level $bosslevel / $bosscp",
                    "inline" => true
                ],
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
