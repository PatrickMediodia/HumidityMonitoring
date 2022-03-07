<?php

include_once('../WebService/connects.php');

$query = "SELECT * FROM emailrecords";
$result = mysqli_query($con,$query);
$row = mysqli_fetch_assoc($result); 

echo $row['email'];

?>