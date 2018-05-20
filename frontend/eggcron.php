	<?php
	require '../config/config.php';
	$sql = "UPDATE gyms SET egg='0',hour='0',min='0',ampm='0' WHERE date < (NOW() - INTERVAL 45 MINUTE)";
	if(!mysqli_query($conn,$sql))
	{
		echo 'Not Deleted';
	}
	else
	{
		echo 'Deleted';
	}        
	 // ends *_query() call
	?>