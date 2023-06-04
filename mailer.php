<?php
require 'mailer/Exception.php';
require 'mailer/PHPMailer.php';
require 'mailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function send_code_email($email, $code){
    $mail = new PHPMailer(true);
    try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.mail.ru';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'noreply@example.org';
    $mail->Password   = 'password';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('noreply@example.org', 'MyCoin');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Код подтверждения - '.$code;
    $mail->Body    = str_replace('%code%', $code, file_get_contents('../assets/mail.html'));
    $mail->AltBody = 'Код подтверждения транзакции MyCoin - '.$code;
    $mail->CharSet = "UTF-8";
    $mail->send();
    } catch (Exception $e) {
        return false;
    }
    return true;
}