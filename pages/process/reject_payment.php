<?php
include('config.php');
session_start();

if (isset($_POST['save']) && isset($_POST['reservation_id']) && isset($_POST['user_id']) && isset($_POST['name']) && isset($_POST['gown_id']) && isset($_POST['reason'])) {
    $reservation_id = $_POST['reservation_id'];
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $gown_id = $_POST['gown_id'];
    $reason = $_POST['reason'];

    // Check if reservation_id is numeric to avoid invalid input
    if (!is_numeric($reservation_id)) {
        $_SESSION['status'] = "Invalid Reservation ID!";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Try Again";
        header("Location: ../my_reservation.php");
        exit();
    }

    // Insert the rejection notification into the notifications table
    $sql_notify = "INSERT INTO notifications(user_id, content, date_time, type) VALUES (?, ?, NOW(), ?)";
    $stmt_notify = $conn->prepare($sql_notify);  // Use $sql_notify here
    $content = "Your payment for <b>$name</b> has been rejected. Reason: $reason";
    $type = 'customer';
    $stmt_notify->bind_param('iss', $user_id, $content, $type);

    if ($stmt_notify->execute()) {
        $_SESSION['status'] = "Payment Rejected!";
        $_SESSION['status_code'] = "success";
        $_SESSION['status_button'] = "Okay";
    } else {
        $_SESSION['status'] = "Payment Rejection Failed!";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Try Again";
    }

    // Redirect to payments.php after processing
    header("Location: ../payments.php");
    exit();

} else {
    // Set error message for invalid request
    $_SESSION['status'] = "Invalid Request!";
    $_SESSION['status_code'] = "error";
    $_SESSION['status_button'] = "Okay";
    header("Location: ../payments.php");
    exit();
}
?>
