<?php
    include_once('connects.php');
    
    $query = "SELECT AVG(temperature) AS temp_average, AVG(humidity) AS hum_average FROM temphumrecords";
    $result= mysqli_query($con,$query);
    $row = mysqli_fetch_assoc($result); 

    $temp_average = $row['temp_average'];
    $hum_average = $row['hum_average'];

    echo round($temp_average,2) . ',' . round($hum_average,2);
?>