<?php
    include_once('connects.php');

    $query = "SELECT * FROM `temphumrecords` ORDER BY id ASC";
    $check = mysqli_query($con,$query);
    $row = mysqli_num_rows($check);
    
    $records = array();

    while($row=mysqli_fetch_array($check)) {    
        $timeStamp = $row['timestamp'];
        $timeStamp = date( "M d Y h:i a", strtotime($timeStamp));

        $record = array(
            'humidity' => $row['humidity'], 
            'timestamp' => $timeStamp
        );

        array_push($records, $record);
    }
    header('Content-Type: application/json');
    echo json_encode($records);
?>