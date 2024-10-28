<?php
include('../pages/process/config.php');
session_start();

// Check if POST variables are set
if (isset($_POST['email']) && isset($_POST['password'])) {
    $uemail = $_POST['email'];
    $pass = md5($_POST['password']);

    // Prepare the SQL statement
    $sql = "SELECT * FROM users WHERE email = ? AND password = ? AND status = 'Complete'";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        // Prepare failed
        $_SESSION['status'] = "Database query error: " . $conn->error;
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header('location: ../index.php');
        exit();
    }

    // Bind parameters and execute
    $stmt->bind_param('ss', $uemail, $pass);
    if (!$stmt->execute()) {
        // Execution failed
        $_SESSION['status'] = "Execution error: " . $stmt->error;
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header('location: ../index.php');
        exit();
    }

    // Get the result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $result->num_rows;

    // Check if a user was found
    if ($count == 1) {
        $_SESSION['email'] = $uemail;

        if ($row['user_type'] == 'admin') {
            $_SESSION['status'] = "Welcome Admin";
            $_SESSION['status_code'] = "info";
            $_SESSION['status_button'] = "Okay";
            header('location: ../pages/dashboard.php');
            exit();
        } elseif ($row['user_type'] == 'customer') {
            $_SESSION['status'] = "Welcome Customer";
            $_SESSION['status_code'] = "info";
            $_SESSION['status_button'] = "Okay";
            header('location: ../pages/find_gown.php');
            exit();
        } else {
            header('location: default.php');
            exit();
        }
    } else {
        // Invalid credentials
        $_SESSION['status'] = "Invalid credentials!";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header('location: ../index.php');
        exit();
    }

    // Close the statement
    $stmt->close();
} else {
    // POST variables are not set
    $_SESSION['status'] = "Please enter your email and password!";
    $_SESSION['status_code'] = "error";
    $_SESSION['status_button'] = "Okay";
    header('location: ../index.php');
    exit();
}

// Close the database connection
$conn->close();
?>
