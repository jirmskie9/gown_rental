<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendAdminOTP($loginEmail)
{
    // Generate 6-digit OTP
    $otp = sprintf('%06d', mt_rand(0, 999999));

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'Kristinacassandrabalaba@gmail.com';
        $mail->Password = 'fdwa jmgt hdjh bhtu';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('Kristinacassandrabalaba@gmail.com', 'Gings Boutique');
        $mail->addAddress('jirmskie9@gmail.com');  // Always send to this email

//gwynezia23@gmail.com

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Admin Login OTP Verification';
        $mail->Body = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                <h2 style="color: #333;">Admin Login Verification</h2>
                <p>Your OTP for admin login is:</p>
                <h1 style="color: #4CAF50; font-size: 32px; letter-spacing: 5px;">' . $otp . '</h1>
                <p>This OTP will expire in 5 minutes.</p>
                <p style="color: #666; font-size: 14px;">Login attempt from email: ' . htmlspecialchars($loginEmail) . '</p>
                <p style="color: #666; font-size: 14px;">If you did not request this OTP, please ignore this email.</p>
            </div>';

        $mail->send();
        return $otp;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
