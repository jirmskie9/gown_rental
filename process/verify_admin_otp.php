<?php
session_start();
include('../pages/process/config.php');

// Security checks
if (!isset($_SESSION['email']) || !isset($_SESSION['is_admin_login']) || !isset($_SESSION['admin_otp'])) {
    header('location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $submitted_otp = trim($_POST['otp']);
    $stored_otp = $_SESSION['admin_otp'];
    $otp_expiry = $_SESSION['admin_otp_expiry'];

    // Check if OTP has expired (5 minutes)
    if (time() > $otp_expiry) {
        $_SESSION['status'] = "OTP has expired. Please login again.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        unset($_SESSION['admin_otp']);
        unset($_SESSION['admin_otp_expiry']);
        unset($_SESSION['is_admin_login']);
        header('location: ../index.php');
        exit();
    }

    // Verify OTP
    if ($submitted_otp === $stored_otp) {
        // Clear OTP session variables
        unset($_SESSION['admin_otp']);
        unset($_SESSION['admin_otp_expiry']);
        unset($_SESSION['is_admin_login']);
        
        // Set admin session
        $_SESSION['status'] = "Welcome Admin";
        $_SESSION['status_code'] = "success";
        $_SESSION['status_button'] = "Okay";
        session_regenerate_id(true);
        header('location: ../pages/dashboard.php');
        exit();
    } else {
        $_SESSION['status'] = "Invalid OTP. Please try again.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header('location: ../admin_otp.php');
        exit();
    }
} else {
    header('location: ../index.php');
    exit();
}
?>
