<?php

include_once('connects.php');

if (isset($_POST['submit'])) {
	$minHumidity = $_POST['minHumidity'];
	$maxHumidity = $_POST['maxHumidity'];

	$query = "UPDATE humidity SET maxHumidity = '$maxHumidity', minHumidity = '$minHumidity';";
	$result = mysqli_query($con,$query);

	if($result) {
		header("Location: ../Home.php");
		exit;
	}
	else {
		echo "Error, Insert not successful";
	}
}
?>