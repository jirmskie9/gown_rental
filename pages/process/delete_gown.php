<?php
include('config.php');
session_start();

// Check if the form is submitted
if (isset($_POST['delete'])) {
    // Retrieve and sanitize inputs
    $gown_id = htmlspecialchars($_POST['gown_id']);
    $user_id = htmlspecialchars($_POST['user_id']);
    $password = htmlspecialchars($_POST['password']);

    // Check if user exists and get the hashed password from the database
    $sql = "SELECT password FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password using MD5
        if (md5($password) === $hashed_password) {
            // Password is correct, proceed to delete the gown
            $delete_sql = "DELETE FROM gowns WHERE gown_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("s", $gown_id);

            // Log the ID being deleted
            error_log("Attempting to delete gown ID: " . $gown_id);

            if ($delete_stmt->execute()) {
                $_SESSION['status'] = "Gown deleted successfully!";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_button'] = "Okay";
            } else {
                // Log error if deletion fails
                error_log("Error executing deletion: " . $delete_stmt->error);
                $_SESSION['status'] = "Error deleting gown: " . $delete_stmt->error;
                $_SESSION['status_code'] = "error";
                $_SESSION['status_button'] = "Okay";
            }
            $delete_stmt->close();
        } else {
            $_SESSION['status'] = "Incorrect password. Please try again.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Okay";
        }
    } else {
        $_SESSION['status'] = "User not found.";
        $_SESSION['status_code'] = "error";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the manage gowns page
    header('location: ../manage_gowns.php');
    exit();
}
?>
