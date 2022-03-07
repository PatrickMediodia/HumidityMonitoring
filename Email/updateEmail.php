<?php

include_once('../WebService/connects.php');

if (isset($_POST['submit'])){
    $usernameEmail = $_POST['updateEmail'];

    $query = "UPDATE emailrecords SET email = '$usernameEmail'";
    $result = mysqli_query($con,$query);

    if($result){
        header("Location: ../Home.php");
        exit;
    }
    else{
        echo "Error, Insert not successful";
    }
}
?>