<?php
$curl = curl_init();
ob_start();
require './config/config.php';
include'frontend/functions.php';
include("login/auth.php");

$quest = $conn->real_escape_string($_POST['quest']);
$reward = $conn->real_escape_string($_POST['reward']);
$sname = $conn->real_escape_string($_POST['sname']);             
$sid = $conn->real_escape_string($_POST['sid']); 
$reid = $conn->real_escape_string($_POST['reid']); 
$qid = $conn->real_escape_string($_POST['qid']); 
$spotter = $conn->real_escape_string($_SESSION['uname']);

if ($clock=="false"){
	$qhour = date('g');
	$qampm = date('A');
	} else {
		$qhour = date('H');
		$qampm = '';
		}
		
$qmin = date('i');

$sql1 = "UPDATE stops SET quested='1',actquest='$quest',actreward='$reward',hour='$qhour', min='$qmin',ampm='$qampm',questby='$spotter' WHERE sid='$sname'";
    if(!mysqli_query($conn,$sql1))
        {
            echo 'Not Inserted';
        }
            else
            {
                echo 'Inserted';
            }


$rewardquery = "SELECT rname FROM rewards WHERE reid='$reward'";
	if(!mysqli_query($conn,$rewardquery))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}

$resultreward = $conn->query($rewardquery);
$rowreward = $resultreward->fetch_array(MYSQLI_NUM);
$rname = $rowreward[0];

$questquery = "SELECT qname FROM quests WHERE qid='$quest'";
	if(!mysqli_query($conn,$questquery))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}

$resultquest = $conn->query($questquery);
$rowquest = $resultquest->fetch_array(MYSQLI_NUM);
$qname = $rowquest[0];


$pokestopquery = "SELECT sname,slatitude,slongitude FROM stops WHERE sid = '$sname'";
	if(!mysqli_query($conn,$pokestopquery))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}

$resultpokestop = $conn->query($pokestopquery);
$row = $resultpokestop->fetch_array(MYSQLI_NUM);
$pokestopname = $row[0];
$slat = $row[1];
$slon = $row[2];

$siteurl = "[".$viewtitle."](".$viewurl."/?loc=$slat,$slon&zoom=19)";

if ($clock=="false"){
	$date = date('g:i:s A');
	} else {
		$date = date('H:i:s');
		}

$hookObject = json_encode([
    "username" => "Quest Spotted!",
    "tts" => false,
	"avatar_url" => "$viewurl/static/stops/queststop.png",
    "embeds" => [
        [
            "type" => "rich",
            "description" => "$siteurl\n**Quest:** $qname\n**Reward:** $rname",
            "color" => hexdec( "FFFFFF" ),
            "footer" => [
                "text" => "Spotted by $spotter at $date",
				"icon_url" => "$viewurl/static/stops/queststop.png"
            ],
            
            "image" => [
				"url" => "http://staticmap.openstreetmap.de/staticmap.php?center=".$slat.",".$slon."&zoom=17&size=400x400&maptype=mapnik&markers=".$slat.",".$slon.",red-pushpin",
            ],
            
            "thumbnail" => [
				"url" => "$viewurl/static/stops/queststop.png",
            ],
            
            "author" => [
                "name" => "Quest spotted by $spotter",
            ],
            
            "fields" => [
                [
                    "name" => "Pokestop",
                    "value" => "$pokestopname",
                    "inline" => true
                ]
            ]
        ]
    ]
    
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

$ch = curl_init();

curl_setopt_array( $ch, [
    CURLOPT_URL => $quest_webhook_url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $hookObject,
    CURLOPT_HTTPHEADER => [
        "Length" => strlen( $hookObject ),
        "Content-Type" => "application/json"
    ]
]);

$response = curl_exec( $ch );
curl_close( $ch );
			
    header('Location:index.php?loc='.$slat.','.$slon.'&zoom=19');    
?>
