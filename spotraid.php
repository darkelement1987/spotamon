<?php
$curl = curl_init();
ob_start();
require './config/config.php';
include'./functions.php';
$rboss = $conn->real_escape_string($_POST['rboss']);
$rhour = $conn->real_escape_string($_POST['rhour']);
$rmin = $conn->real_escape_string($_POST['rmin']);
$rampm = $conn->real_escape_string($_POST['rampm']);
$gname = $conn->real_escape_string($_POST['gname']);

$sql = "INSERT INTO spotraid (rboss, rhour, rmin, rampm) VALUES ('$rboss','$rhour','$rmin','$rampm')";
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
$bosscp = "(".$row[0]."CP)";

$siteurl = "[".$viewtitle."](".$viewurl."/?loc=$gymlat,$gymlon%26zoom=19)";
			
curl_setopt_array($curl, array(
  CURLOPT_URL => "$webhook_url",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "content=**Raid level $bosslevel spotted:**\nPokemon: *$bossname $bosscp*\nGym:*$gymname*\nRaid will start at: *$rhour:$rmin $rampm*\nView on $siteurl",
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
			
    header('Location:index.php?loc='.$gymlat.','.$gymlon.'&zoom=19');
    
?>
