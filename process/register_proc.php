<?php
include('../pages/process/config.php');
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);
$otp = rand(100000, 999999);

if (isset($_POST['reg'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $upass = md5($_POST['password']); // Using MD5 for password hashing
    $address = $_POST['address'];

    // Handle profile image upload
    $profile = $_FILES['profile'];
    $uploadDirectory = '../pages/uploads/';
    $uploadFile = $uploadDirectory . basename($profile['name']);
    
    // Check if the uploaded file is valid
    if (move_uploaded_file($profile['tmp_name'], $uploadFile)) {
        $profilePath = $uploadFile;

        // Prepare and execute the email check query
        $checkSql = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $checkSql->bind_param("s", $email);
        $checkSql->execute();
        $checkResult = $checkSql->get_result();

        if ($checkResult->num_rows > 0) {
            $_SESSION['status'] = "Account already exists!";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../signup.php");
            exit();
        } else {
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'Kristinacassandrabalaba@gmail.com';
                $mail->Password   = 'fdwa jmgt hdjh bhtu';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('Kristinacassandrabalaba@gmail.com', 'Verification Code');
                $mail->addAddress($email, $full_name);

                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];

                $mail->isHTML(true);
                $mail->Subject = 'Boutique Gowns';
                $mail->Body = "
                <html>
                <head>
                    <style>
                        .email-container { font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
                        .header { background-color: #5E72E4; color: white; padding: 20px; text-align: center; }
                        .content { padding: 20px; font-size: 16px; line-height: 1.6; }
                        .otp-code { font-size: 24px; font-weight: bold; color: #5E72E4; text-align: center; margin: 20px 0; }
                        .cta-button { display: block; width: 200px; margin: 20px auto; padding: 10px 20px; background-color: #5E72E4; color: #fff; text-align: center; border-radius: 5px; text-decoration: none; font-size: 16px; font-weight: bold; }
                        .cta-button:hover { background-color: #6a4ea3; }
                        .footer { background-color: #f9f9f9; padding: 15px; text-align: center; font-size: 12px; color: #777; }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='header'><h1>Welcome to Peppings Boutique!</h1></div>
                        <div class='content'>
                            <p>Hello <strong>$full_name</strong>,</p>
                            <p>Thank you for registering with us! To complete your registration, please use the following verification code:</p>
                            <div class='otp-code'>$otp</div>
                            <p>Or, you can confirm your registration by clicking the button below:</p>
                            <a href='#' class='cta-button'>Confirm Registration</a>
                            <p>If you did not register, please ignore this email.</p>
                        </div>
                        <div class='footer'>
                            <p>Peppings Boutique, Tupi, South Cotabato</p>
                            <p>&copy; " . date('Y') . " PBGRRS. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";

                $mail->send();
            } catch (Exception $e) {
                echo "Failed to send email. Error: {$mail->ErrorInfo}";
                exit();
            }

            // Insert user information using prepared statement
            $sql = $conn->prepare("INSERT INTO users (`full_name`, `email`, `password`, `phone_number`, `address`, `created_at`, `user_type`, `profile`, `otp`, `status`) VALUES (?, ?, ?, ?, ?, NOW(), 'customer', ?, ?, 'Pending')");
            $sql->bind_param("sssssss", $full_name, $email, $upass, $phone, $address, $profilePath, $otp);

            if ($sql->execute()) {
                $_SESSION['email'] = $email;
                $_SESSION['status'] = "Confirm your email";
                $_SESSION['status_code'] = "info";
                $_SESSION['status_button'] = "Okay";
                header("Location: ../confirmation.php");
                exit();
            } else {
                $_SESSION['status'] = "Error in SQL query: " . $conn->error;
                $_SESSION['status_code'] = "error";
                $_SESSION['status_button'] = "Okay";
                header("Location: ../signup.php");
                exit();
            }
        }
    } else {
        $_SESSION['status'] = "Failed to upload profile image.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header("Location: ../signup.php");
        exit();
    }
}
?>
