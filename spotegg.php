<?php
$curl = curl_init();
ob_start();
require_once 'initiate.php';
include S_FUNCTIONS . 'functions.php';
include("login/auth.php");
$gname = $conn->real_escape_string($_POST['gname']);
$egg = $conn->real_escape_string($_POST['egg']);
$minutes = $conn->real_escape_string($_POST['etime']);
$pulltime = date('H:i:s');
$timeuntilegg = strtotime("+$minutes minutes", strtotime($pulltime));
$newtime = date('Y-m-d H:i:s', $timeuntilegg);
if ($clock=="false"){
	$rhour = date('g', $timeuntilegg);
	$rampm = date('A');
	} else {
		$rhour = date('H', $timeuntilegg);
		$rampm = '';
		}
		
			$rmin = date('i', $timeuntilegg);

$eggby = $conn->real_escape_string($_SESSION['uname']);

// Start queries

// Check if active raid
$checkraid = "SELECT actraid,egg FROM gyms WHERE gid='$gname'";
	if(!mysqli_query($conn,$checkraid))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}

$checkresult = $conn->query($checkraid);
$checkrow = $checkresult->fetch_array(MYSQLI_NUM);
$checkactraid = $checkrow[0];
$checkactegg = $checkrow[1];

if($checkactraid == "1") {
echo '<script language="javascript">';
echo 'alert("Cannot submit an Egg while there is an active raid")';
echo '</script>';
header('Refresh: 0; URL=submit-egg.php');
} elseif($checkactegg!=0) {
	echo '<script language="javascript">';
	echo 'alert("Cannot submit multiple eggs on the same gym")';
	echo '</script>';
	header('Refresh: 0; URL=submit-egg.php');
} else {

	if ($clock=="false"){
$sql = "UPDATE gyms SET egg='$egg',hour='$rhour',min='$rmin',ampm='$rampm',eggby='$eggby',date='$newtime' WHERE gid='$gname'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}
	} else {
$sql = "UPDATE gyms SET egg='$egg',hour='$rhour',min='$rmin',ampm='',eggby='$eggby',date='$newtime' WHERE gid='$gname'";
	if(!mysqli_query($conn,$sql))
		{
			echo 'Not Inserted';
		}
			else
			{
				echo 'Inserted';
			}
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

if ($clock=="false"){
	$date = date('g:i:s A');
	} else {
		$date = date('H:i:s');
		}

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
				"url" => "http://staticmap.openstreetmap.de/staticmap.php?center=".$gymlat.",".$gymlon."&zoom=17&size=400x400&maptype=mapnik&markers=".$gymlat.",".$gymlon.",red-pushpin",
            ],
            
            "thumbnail" => [
				"url" => "$viewurl/static/eggs/$egg.png",
            ],
            
            "author" => [
                "name" => "Level $egg egg spotted by $eggby",
            ],
            
            "fields" => [
				[
					"name" => "Hatches at:",
					"value" => "$rhour:$rmin $rampm",
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
    CURLOPT_URL => $egg_webhook_url,
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
}
?>
