<?php
require 'config/config.php';
?>
<?php
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
                    
                    $import="INSERT IGNORE INTO stops (sid,slatitude,slongitude,quested,quest,reward,lured,type,date) VALUES (DEFAULT,'$item1','$item2',NULL,NULL,NULL,NULL,NULL,'$date')";
                    mysqli_query($conn,$import);
					
                } // <<-- END LOOP THROUGH CSV \\
                fclose($handle);
                print "Import STOPS CSV done!";
            }
        }
    }
?>
<html>
    <head>
        <title> Upload STOP CSV</title>
    <head>
    <body>
        <form method='POST' enctype='multipart/form-data'>
            Upload STOP CSV: <input type='file' name='csv_data' /> <input type='submit' name='submit' value='Upload CSV' />
        </form>
    </body>
</html>