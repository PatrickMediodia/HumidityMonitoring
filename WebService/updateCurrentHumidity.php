<?php 

include_once('connects.php');
$hum = $_GET['hum'];

$query = "UPDATE humidity SET currentHumidity = '$hum' ";
$result = mysqli_query($con, $query);

$update_timestamp = "UPDATE humidity SET timestamp = CURRENT_TIMESTAMP()";
$update = mysqli_query($con, $update_timestamp);

if($result) {
    echo "Insert Successful\n";
    echo "{Current Humidity: ", $hum, " }";
}
else {
    echo "Error, Insert not successful";
}


?>