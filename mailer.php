<?php
// Load Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server (Gmail example)
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'faiteplus08@gmail.com';                 // SMTP username (Your email)
    $mail->Password   = 'kfqd mhbg sxtz znth';                    // SMTP password (Use app-specific password if using Gmail)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
    $mail->Port       = 587;                                    // TCP port to connect to (TLS port)
    $mail->isHTML(true);                                        // Set email format to HTML
    return $mail;

} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
?>
