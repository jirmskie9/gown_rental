<?php
session_start();

require_once 'config.php'; // Ensure this includes your DB connection setup

if (isset($_POST['return'])) {
    // Sanitize input data
    $gown_id = htmlspecialchars($_POST['gown_id']);
    $reservation_id = htmlspecialchars($_POST['reservation_id']);
    $user_id = htmlspecialchars($_POST['user_id']);

    // Ensure all required fields are not empty
    if (empty($gown_id) || empty($reservation_id) || empty($user_id)) {
        $_SESSION['status'] = "Missing required information.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header("Location: ../view_reservation.php?reservation_id=$reservation_id");
        exit();
    }

    // Use a transaction to ensure atomicity of operations
    $conn->begin_transaction();

    try {
        // SQL queries to update gown and reservation
        $updateGownSql = "UPDATE gowns SET availability_status = 'available' WHERE gown_id = ?";
        $updateReservationSql = "UPDATE reservations SET status = 'completed' WHERE reservation_id = ?";
        $sql_notify = "INSERT INTO notifications (user_id, content, date_time, type) VALUES (?, ?, NOW(), ?)";

        // Prepare and execute the gown update
        $stmt = $conn->prepare($updateGownSql);
        $stmt->bind_param('i', $gown_id);
        $stmt->execute();
        $stmt->close();

        // Prepare and execute the reservation update
        $stmt = $conn->prepare($updateReservationSql);
        $stmt->bind_param('i', $reservation_id);
        $stmt->execute();
        $stmt->close();

        // Prepare and execute the notification insert
        $stmt = $conn->prepare($sql_notify);
        $content = "The gown has been successfully returned.";
        $type = "customer";
        $stmt->bind_param('ssi', $user_id, $content, $type);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $conn->commit();

        // Set success message
        $_SESSION['status'] = "Gown Returned Successfully.";
        $_SESSION['status_code'] = "success";
        $_SESSION['status_button'] = "Okay";

        // Redirect to the view reservation page
        header("Location: ../history.php");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();

        // Log the error (optional, for debugging purposes)
        error_log("Error in gown return process: " . $e->getMessage());

        // Set error message
        $_SESSION['status'] = "An error occurred. Please try again.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";

        // Redirect back to the view reservation page
        header("Location: ../history.php");
        exit();
    }
} else {
    // Redirect if accessed without the return form submission
    header("Location: ../find_gown.php");
    exit();
}
?>
