<?php 

include_once('connects.php');

//get temp and hum from URL
$hum = $_GET['hum'];
$temp = $_GET['temp'];

//get high humidity
$query = "SELECT * FROM humidity";
$data =  mysqli_query($con, $query);
$row = mysqli_fetch_assoc($data); 
$minHumidity = $row['minHumidity'];
$maxHumidity = $row['maxHumidity'];

//insert new record
$insert = "INSERT INTO temphumrecords(humidity, minHumidity, maxHumidity, temperature) VALUES($hum, $minHumidity, $maxHumidity, $temp)";
$result = mysqli_query($con, $insert);

//check if successful
if($result) {
    echo "Insert Successful\n";
    //echo "{Temp: ", $temp, ", Hum: ", $hum, ", DateTime: ", $date, "}";
}
else {
    echo "Error, Insert not successful";
}

?>