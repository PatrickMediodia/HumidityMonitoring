<?php

include_once('../WebService/connects.php');

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include '../WebService/getDehumidifierStatus.php';

//taken from getDehumidifierStatus.php
if ($status != "NORMAL") {
    if ($status == "HIGH") {
        $message = "Humidity is not within range and is too HIGH, Dehumidifier is ON.";
    }
    else if ($status == "LOW") {
        $message = "Humidity is not within range and is too LOW, Dehumidifier is OFF.";
    }

    //get email and last_sent timestamp
    $query = "SELECT * FROM emailrecords";
	$result = mysqli_query($con,$query);
    $row = mysqli_fetch_assoc($result); 

    //set timezone to manila
    date_default_timezone_set('Asia/Manila');

    $current_timestamp = time();
    $last_sent = strtotime($row['last_sent']);

    //compare current and last sent time
    $time_difference = $current_timestamp - $last_sent;
    
    //if greater than 10 minutes, send another email
    if($time_difference > 600) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = "true";
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '465';
    
        $mail->Username = 'japatalava@gmail.com';
        $mail->Password = 'japatalava1234';
    
        $query = "SELECT * FROM emailrecords";
        $result = mysqli_query($con,$query);
    
        while($row=mysqli_fetch_array($result)) { 
            $mail->From =('japatalava@gmail.com');
            $mail->Subject = 'Humidity Update';
            $mail->Body = $message;
            $mail->AddAddress($row['email']);
        }
    
        //check if email has been sent successfully
        if ($mail->Send()) {
            $update_timestamp = 'UPDATE emailrecords SET last_sent = CURRENT_TIMESTAMP()';
            $update = mysqli_query($con, $update_timestamp);

            if($update) {
                //echo "Update Successful";
            }
        }
        else {
            //echo "Error";
        }
        $mail->smtpClose();
    }
}

?>