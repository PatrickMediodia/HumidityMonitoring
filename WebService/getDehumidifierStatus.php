<?php
	include_once('connects.php');

	$query = "SELECT * FROM `humidity`";
	$result = mysqli_query($con,$query);
    $row = mysqli_fetch_assoc($result); 
	
    if($row['currentHumidity'] > $row['maxHumidity']) {
        $status = "HIGH";  
    }
    else if($row['currentHumidity'] < $row['minHumidity']) {
        $status = "LOW";
    }
    else {
        $status = "NORMAL";
    }
    echo $status;
?>