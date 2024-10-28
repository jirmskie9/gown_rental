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
    $upass = md5($_POST['password']);
    $address = $_POST['address'];

    // Handle profile image upload
    $profile = $_FILES['profile'];
    $uploadDirectory = '../pages/uploads/';
    $uploadFile = $uploadDirectory . basename($profile['name']);
    
    // Check if the uploaded file is valid
    if (move_uploaded_file($profile['tmp_name'], $uploadFile)) {
        // File uploaded successfully, you can now store the path in the database
        $profilePath = $uploadFile; // Store the full path or just the filename

        $checkSql = "SELECT * FROM users WHERE email = '$email'";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            $_SESSION['[status]'] = "Account already exists!";
            $_SESSION['[status_code]'] = "error";
            $_SESSION['[status_button]'] = "Okay";
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

                // Disable SSL verification
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                $mail->isHTML(false);
                $mail->Subject = 'Boutique Gowns';
                $mail->Body    = "Hello $full_name, your verification code is: $otp";

                $mail->send();
                echo 'Email sent successfully.';
            } catch (Exception $e) {
                echo "Failed to send email. Error: {$mail->ErrorInfo}";
                exit();
            }

            $sql = "INSERT INTO users (`full_name`, `email`, `password`, `phone_number`, `address`, `created_at`, `user_type`, `profile`, `otp`, `status`)
                    VALUES ('$full_name', '$email', '$upass', '$phone', '$address', NOW(), 'customer', '$profilePath', '$otp', 'Pending')";

            $result = $conn->query($sql);

            if ($result) {
                $_SESSION['email'] = $email;
                $_SESSION['[status]'] = "Confirm your email";
                $_SESSION['[status_code]'] = "info";
                $_SESSION['[status_button]'] = "Okay";
                header("Location: ../confirmation.php");
                exit();
            } else {
                $_SESSION['[status]'] = "Error in SQL query: " . $conn->error;
                $_SESSION['[status_code]'] = "error";
                $_SESSION['[status_button]'] = "Okay";
                header("Location: ../signup.php");
                exit();
            }
        }
    } else {
        $_SESSION['[status]'] = "Failed to upload profile image.";
        $_SESSION['[status_code]'] = "error";
        $_SESSION['[status_button]'] = "Okay";
        header("Location:  ../signup.php");
        exit();
    }
}
?>
