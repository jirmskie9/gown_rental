<?php
session_start();
include('config.php'); 

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log function
function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, "../error.log");
}

if (isset($_POST['save_gown'])) {
    logError("Starting gown addition process");
    
    // Log POST data
    logError("POST data: " . print_r($_POST, true));
    
    // Log FILES data
    logError("FILES data: " . print_r($_FILES, true));

    $gown_name = htmlspecialchars($_POST['gown_name']);
    $gown_size = htmlspecialchars($_POST['gown_size']);
    $gown_color = htmlspecialchars($_POST['gown_color']);
    $gown_price = $_POST['gown_price'];
    $description = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['category']);
    
    // Validate required fields
    if (empty($gown_name) || empty($gown_size) || empty($gown_color) || empty($gown_price) || empty($category)) {
        logError("Missing required fields");
        $_SESSION['status'] = "Please fill in all required fields";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header("Location: ../add_gown.php");
        exit;
    }

    // Validate file uploads
    if (!isset($_FILES['gown_main']) || !isset($_FILES['img1']) || !isset($_FILES['img2']) || !isset($_FILES['img3'])) {
        logError("Missing required image files");
        $_SESSION['status'] = "Please upload all required images";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header("Location: ../add_gown.php");
        exit;
    }
    
    $uploadDir = '../uploads/';
    
    // Create unique filenames to prevent overwriting
    $main_image = $uploadDir . uniqid() . '_' . basename($_FILES['gown_main']['name']);
    $img1 = $uploadDir . uniqid() . '_' . basename($_FILES['img1']['name']);
    $img2 = $uploadDir . uniqid() . '_' . basename($_FILES['img2']['name']);
    $img3 = $uploadDir . uniqid() . '_' . basename($_FILES['img3']['name']);

    logError("Attempting to upload files to: " . $uploadDir);

    // Try to upload each file and log any errors
    $uploadSuccess = true;
    $uploadErrors = [];

    if (!move_uploaded_file($_FILES['gown_main']['tmp_name'], $main_image)) {
        $uploadSuccess = false;
        $uploadErrors[] = "Error uploading main image: " . error_get_last()['message'];
    }
    if (!move_uploaded_file($_FILES['img1']['tmp_name'], $img1)) {
        $uploadSuccess = false;
        $uploadErrors[] = "Error uploading image 1: " . error_get_last()['message'];
    }
    if (!move_uploaded_file($_FILES['img2']['tmp_name'], $img2)) {
        $uploadSuccess = false;
        $uploadErrors[] = "Error uploading image 2: " . error_get_last()['message'];
    }
    if (!move_uploaded_file($_FILES['img3']['tmp_name'], $img3)) {
        $uploadSuccess = false;
        $uploadErrors[] = "Error uploading image 3: " . error_get_last()['message'];
    }

    if (!$uploadSuccess) {
        logError("File upload errors: " . implode(", ", $uploadErrors));
        $_SESSION['status'] = "Error uploading images. Please try again.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header("Location: ../add_gown.php");
        exit;
    }

    logError("Files uploaded successfully, attempting database insertion");
    
    // Prepare the SQL statement
    $sql = "INSERT INTO gowns (name, category, size, color, price, description, main_image, img1, img2, img3, availability_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'available')";
    
    if ($stmt = $conn->prepare($sql)) {
        logError("SQL prepared successfully");
        
        $stmt->bind_param("ssssssssss", $gown_name, $category, $gown_size, $gown_color, $gown_price, $description, $main_image, $img1, $img2, $img3);
        
        if ($stmt->execute()) {
            logError("Gown added successfully");
            $_SESSION['status'] = "Gown Added Successfully!";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../manage_gowns.php");
            exit;
        } else {
            logError("Database error: " . $stmt->error);
            $_SESSION['status'] = "Error adding gown: " . $stmt->error;
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../add_gown.php");
            exit;
        }
        $stmt->close();
    } else {
        logError("Error preparing statement: " . $conn->error);
        $_SESSION['status'] = "Error preparing database statement: " . $conn->error;
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header("Location: ../add_gown.php");
        exit;
    }
} else {
    logError("Form not submitted properly");
    $_SESSION['status'] = "Invalid form submission";
    $_SESSION['status_code'] = "error";
    $_SESSION['status_button'] = "Okay";
    header("Location: ../add_gown.php");
    exit;
}

$conn->close();
?> 