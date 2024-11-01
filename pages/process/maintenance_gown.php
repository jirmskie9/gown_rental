<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['maintenance_gown'])) {
    // Get the gown ID from the form
    $gown_id = $_POST['gown_id'];

    // First, check the current status of the gown
    $checkStatusQuery = "SELECT availability_status FROM gowns WHERE gown_id = ?";
    $checkStmt = $conn->prepare($checkStatusQuery);
    $checkStmt->bind_param("i", $gown_id);
    $checkStmt->execute();
    $checkStmt->store_result();
    
    // Check if the gown exists
    if ($checkStmt->num_rows > 0) {
        $checkStmt->bind_result($current_status);
        $checkStmt->fetch();

        // Prevent update if the gown is rented
        if ($current_status === 'rented') {
            $_SESSION['status'] = "Cannot set to maintenance: Gown is currently rented.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Okay";
            $checkStmt->close();
            header("Location: ../manage_gowns.php");
            exit();
        }

        // Update gown status to "maintenance"
        $updateGownQuery = "UPDATE gowns SET availability_status = 'maintenance' WHERE gown_id = ?";
        $stmt = $conn->prepare($updateGownQuery);
        $stmt->bind_param("i", $gown_id); // Assuming gown_id is an integer
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['status'] = "Set to Maintenance";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../manage_gowns.php");
            exit();
        } else {
            // Update failed
            $_SESSION['status'] = "Failed to update gown status.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../manage_gowns.php");
            exit();
        }
    } else {
        // Gown not found
        $_SESSION['status'] = "Gown not found.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header("Location: ../manage_gowns.php");
        exit();
    }

    $stmt->close();
    $checkStmt->close();
} else {
    header("Location: gowns.php?maintenance=error"); // Redirect to gowns page with invalid access message
}
?>
