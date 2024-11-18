<?php
session_start();

require_once 'config.php'; // Ensure this includes your DB connection setup

if (isset($_POST['returned'])) {
    // Sanitize input data
    $gown_id = htmlspecialchars($_POST['gown_id']);
    $reservation_id = htmlspecialchars($_POST['reservation_id']);
    $user_id = htmlspecialchars($_POST['user_id']);
    $name = htmlspecialchars($_POST['name']);

    // SQL queries to update gown and reservation
    $updateGownSql = "UPDATE gowns SET availability_status = 'available' WHERE gown_id = ?";
    $updateReservationSql = "UPDATE reservations SET status = 'completed' WHERE reservation_id = ?";
    
    // SQL query for notification
    $sql_notify = "INSERT INTO notifications(user_id, content, date_time, type) VALUES (?, ?, NOW(), ?)";

    // Prepare and execute the first statement for gown update
    if ($stmt = $conn->prepare($updateGownSql)) {
        $stmt->bind_param('i', $gown_id);
        $stmt->execute();
        $stmt->close();
    }

    // Prepare and execute the second statement for reservation update
    if ($stmt = $conn->prepare($updateReservationSql)) {
        $stmt->bind_param('i', $reservation_id);
        $stmt->execute();
        $stmt->close();
    }

    // Prepare and execute the notification insert
    if ($stmt = $conn->prepare($sql_notify)) {
        $content = "The gown has been successfully returned";
        $type = "customer"; 
        $stmt->bind_param('ssi', $user_id, $content, $type);
        $stmt->execute();
        $stmt->close();
    }

    // Set session status messages
    $_SESSION['status'] = "Gown Returned";
    $_SESSION['status_code'] = "success";
    $_SESSION['status_button'] = "Okay";

    // Redirect to view reservation page
    header("Location: ../view_reservation.php?reservation_id=$reservation_id");
    exit();
}
?>
