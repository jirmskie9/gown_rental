<?php
include('config.php');
session_start();

if (isset($_POST['reserve']) && isset($_POST['reservation_id']) && isset($_POST['user_id']) && isset($_POST['name']) && isset($_POST['gown_id'])) {
    $reservation_id = $_POST['reservation_id'];
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $gown_id = $_POST['gown_id'];

    // Check if reservation_id is numeric
    if (!is_numeric($reservation_id)) {
        $_SESSION['status'] = "Invalid Reservation ID!";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Try Again";
        header("Location: ../manage_reservations.php");
        exit();
    }

    // Update reservation status to 'confirmed'
    $sql = "UPDATE reservations SET status = ? WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $status = 'confirmed';
    $stmt->bind_param('si', $status, $reservation_id);

    if ($stmt->execute()) {
        // Insert a notification for the customer
        $sql = "INSERT INTO notifications(user_id, content, date_time, type) VALUES (?, ?, NOW(), ?)";
        $stmt_notify = $conn->prepare($sql);
        $content = "Your <b>$name</b> confirmed by Admin";
        $type = 'customer';
        $stmt_notify->bind_param('iss', $user_id, $content, $type);
        $stmt_notify->execute();  // No need to check this one if you are not handling notification errors

        // Update gown availability status to 'rented'
        $sql = "UPDATE gowns SET availability_status = ? WHERE gown_id = ?";
        $stmt_update_gown = $conn->prepare($sql);
        $availability_status = 'rented';
        $stmt_update_gown->bind_param('si', $availability_status, $gown_id);

        if ($stmt_update_gown->execute()) {
            // Set success message if gown availability was updated
            $_SESSION['status'] = "Reservation Confirmed!";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_button'] = "Okay";
        } else {
            // Set error message if gown update failed
            $_SESSION['status'] = "Failed to update gown status!";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Try Again";
        }
    } else {
        // Set error message if reservation confirmation failed
        $_SESSION['status'] = "Reservation Confirmation Failed!";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Try Again";
    }

    header("Location: ../manage_reservations.php");
    exit();
} else {
    $_SESSION['status'] = "Invalid Request!";
    $_SESSION['status_code'] = "error";
    $_SESSION['status_button'] = "Okay";
    header("Location: ../manage_reservations.php");
    exit();
}
?>
