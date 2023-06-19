<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

//mailtrap

$mail = new PHPMailer;

$mail->isSMTP();
$mail->Host = 'sandbox.smtp.mailtrap.io';
$mail->SMTPAuth = true;
$mail->Username = 'e1174e5c1b6e61';
$mail->Password = 'c006300a8565a4';
$mail->Port = 2525;

if(!array_key_exists('email', $_GET))
    die('invalid input');

$address_mail = $_GET['email'];

$mail->setFrom('tehnologiiweb@web.com', 'SenderName');

$mail->addAddress($address_mail);

$mail->isHTML(true);

$mail->Subject = 'Subscribe to newsletter';

$bodyContent = '<h1>Hello there!</h1>';
$bodyContent .= "<p>Thanks {$address_mail} for subscribing to our newsletter</p>";
$mail->Body    = $bodyContent;

if(!$mail->send()) {
    echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
} else {
    echo 'Message has been sent.';
}