<?php
include('config.php');
session_start();

if (isset($_POST['conf']) && isset($_POST['reservation_id']) && isset($_POST['gown_id']) && isset($_POST['user_id']) && isset($_POST['name'])) {
    $reservation_id = $_POST['reservation_id'];
    $gown_id = $_POST['gown_id'];
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];

    // Check if reservation_id is numeric
    if (!is_numeric($reservation_id)) {
        $_SESSION['status'] = "Invalid Reservation ID!";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Try Again";
        header("Location: ../payments.php");
        exit();
    }

    // Update the payment_status to 'paid' in the reservations table
    $sql = "UPDATE reservations SET payment_status = ? WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $payment_status = 'paid';
    $stmt->bind_param('si', $payment_status, $reservation_id);

    if ($stmt->execute()) {
   
        $sql = "INSERT INTO notifications(user_id, content, date_time, type) VALUES (?, ?, NOW(), ?)";
        $stmt_notify = $conn->prepare($sql);
        $content = "Your payment for the gown <b>$name</b> has been confirmed.";
        $type = 'customer';
        $stmt_notify->bind_param('iss', $user_id, $content, $type);
        $stmt_notify->execute();  

        $_SESSION['status'] = "Payment Confirmed!";
        $_SESSION['status_code'] = "success";
        $_SESSION['status_button'] = "Okay";
    } else {
        $_SESSION['status'] = "Payment Confirmation Failed!";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Try Again";
    }

    header("Location: ../payments.php");
    exit();
} else {

    $_SESSION['status'] = "Invalid Request!";
    $_SESSION['status_code'] = "error";
    $_SESSION['status_button'] = "Okay";
    header("Location: ../payments.php");
    exit();
}
?>
