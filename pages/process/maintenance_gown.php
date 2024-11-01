<?php
session_start();
include('config.php'); 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['maintenance_gown'])) {
    // Get the gown ID from the form
    $gown_id = $_POST['gown_id'];

    // Update gown status to "under maintenance"
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
     
        header("Location: gowns.php?maintenance=error"); // Redirect to gowns page with error message
    }

    $stmt->close();
} else {
    
    header("Location: gowns.php?maintenance=error"); // Redirect to gowns page with invalid access message
}
?>
