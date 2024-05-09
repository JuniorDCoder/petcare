<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$errors = array(); // Array to hold validation errors
$data = array(); // Array to hold data to be sent back

// Validate form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty($_POST['name'])) {
        $errors['name'] = 'Please enter your name.';
    }

    // Validate email
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    // Validate subject
    if (empty($_POST['subject'])) {
        $errors['subject'] = 'Please enter the subject.';
    }

    // Validate message
    if (empty($_POST['message'])) {
        $errors['message'] = 'Please enter your message.';
    }

    if (empty($errors)) {
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email'; 
		$mail->Port = 465;                   
		$mail->SMTPAuth = true;                               
		$mail->Username = 'info@sleepinggiantanimalclinic.online';                
		$mail->Password = '@sleepinggiantanimalclinic';                           
		$mail->SMTPSecure = 'ssl'; 

        $mail->From = 'info@sleepinggiantanimalclinic.online';
        $mail->FromName = $_POST['name'];
        $mail->addAddress('info@sleepinggiantanimalclinic.online');

        $mail->isHTML(true);
        $mail->Subject = 'You have a message from your Vet Site.';
        $cmessage = $_POST['message'].'<br><br>You can contact me ('.$_POST['name'].') via my email, '.$_POST['email'];
        $mail->Body = $cmessage;

        if (!$mail->send()) {
            $errors['mail'] = 'Message could not be sent.';
        } else {
            $data['success'] = true;
            $data['message'] = 'Message has been sent.';
        }
    }
}

// Pass errors and data to the page
$data['errors'] = $errors;

// Convert data to JSON format
$jsonData = json_encode($data);

// Redirect back to the form page with the data as query parameters
header('Location: contact.php?data=' . urlencode($jsonData));
exit();