<?php
require 'config/config.php';
include 'frontend/functions.php';
include 'frontend/menu.php';
?>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<?php
menu();

if(isset($_SESSION["uname"])){
$result = $conn->query("SELECT * FROM users,usergroup WHERE uname='".$_SESSION['uname']."' AND users.usergroup = usergroup.id LIMIT 1  ");
$id = $usergroup = "";
while ($row = $result->fetch_assoc()) {
$usergroup = $row['groupname'];
if ("$usergroup" == 'admin'){
// PRINTS DATE IN SQL FORMAT \\
$date = date("Y-m-d H:i:s");  	

    // START CHECK FOR CSV \\
	if(isset($_POST['submit'])){
        if($_FILES['csv_data']['name']){
            
            $arrFileName = explode('.',$_FILES['csv_data']['name']);
            if($arrFileName[1] == 'csv'){
                $handle = fopen($_FILES['csv_data']['tmp_name'], "r");
				// END CHECK FOR CSV \\
				
				
                while (($data = fgetcsv($handle, 1500, ",")) !== FALSE) { // <-- START LOOP THROUGH CSV FILE \\

                    $item1 = mysqli_real_escape_string($conn,$data[0]); // PUT CSV ROW 1 CONTENT IN VAR
                    $item2 = mysqli_real_escape_string($conn,$data[1]); // PUT CSV ROW 2 CONTENT IN VAR
                    $item3 = mysqli_real_escape_string($conn,$data[2]); // PUT CSV ROW 3 CONTENT IN VAR
                    
                    $import="INSERT IGNORE INTO gyms (gid,gname,glatitude,glongitude,gteam,actraid,actboss,hour,min,ampm,egg,type,date) VALUES (DEFAULT,'$item1','$item2','$item3','1','0','','0','0','0','0','GYM','$date')";
                    mysqli_query($conn,$import);
					
                } // <<-- END LOOP THROUGH CSV \\
                fclose($handle);
                print "<center><h1><b>CSV Loaded!</b></h1></center>";
			    echo "<meta http-equiv='refresh' content='3;url=profile.php'>";
            }
        }
    }
?>
       <center><h2><strong>Upload Gym CSV:</strong></h2><form method='POST' enctype='multipart/form-data'>
            Upload GYM CSV: <input type='file' name='csv_data' /> <input type='submit' name='submit' value='Upload CSV' />
        </form><br></center>
		</body>

<footer></footer>

<?php } else{ echo "Sorry you must be an ADMIN to upload this";}} } else {
	echo "<center><div style='margin-top:5%;'>";
	echo "Login to submit gyms csv";
		?><br /><br /><a href="/login/login.php">Login Here</a><?php
	echo "</div></center></table></center>";
} ?>
