<?php

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($to, $title, $message) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.office365.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'webmaster@wpia.uni.lodz.pl';                     // SMTP username
        $mail->Password   = 'Haslo123';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;
        $mail->CharSet = "UTF-8";

        //Recipients
        $mail->setFrom('webmaster@wpia.uni.lodz.pl', 'Biblioteka WPiA UŁ');
        $mail->addAddress($to);     // Add a recipient
        $mail->addReplyTo('webmaster@wpia.uni.lodz.pl');

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = '[Biblioteka WPiA] '.$title;

        $message .= '<br><br><hr><ul><li>W sprawie problemów technicznych proszę pisać na adres <a href="mailto:tomasz.mizak@wpia.uni.lodz.pl">tomasz.mizak@wpia.uni.lodz.pl</a></li><li>W sprawach związanych z rezerwacją, proszę pisać na adres <a href="mailto:biblioteka@wpia.uni.lodz.pl">biblioteka@wpia.uni.lodz.pl</a></li></ul>';

        $mail->Body    = $message;
        $mail->AltBody = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        sendMail('tomasz.mizak@wpia.uni.lodz.pl', 'Błąd', $e->getMessage());
        return false;
    }
}

?>