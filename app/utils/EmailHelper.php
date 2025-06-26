<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

function sendVerificationEmail($toEmail, $toName, $verificationLink) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Cambia si usas otro proveedor
        $mail->SMTPAuth = true;
        $mail->Username = 'av2020066922@virtual.upt.pe'; // Tu correo
        $mail->Password = 'Fitmatch';    // Tu contraseña o app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('av2020066922@virtual.upt.pe', 'Fitmatch');
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = 'Verifica tu correo en Fitmatch';
        $mail->Body = "<h1>¡Bienvenido a Fitmatch!</h1>\n"
            . "<p>Por favor, haz clic en el siguiente enlace para verificar tu correo:</p>\n"
            . "<a href='$verificationLink'>$verificationLink</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
} 