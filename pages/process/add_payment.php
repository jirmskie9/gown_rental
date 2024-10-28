<?php
include('config.php');
session_start();

if (isset($_POST['pay'])) {
    // Sanitize inputs
    $paymentMethod = htmlspecialchars($_POST['paymentMethod']);
    $transaction_id = htmlspecialchars($_POST['transaction_id']);
    $reservation_id = htmlspecialchars($_POST['reservation_id']);
    $amount = htmlspecialchars($_POST['amount']);

    // Check if paymentMethod is empty
    if (empty($paymentMethod)) {
        $_SESSION['status'] = "Please select a payment method!";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header("Location: ../my_payment.php");
        exit;
    }

    $interest = $amount * 0.03;
    $total = $interest + $amount + 400;

    $gcash_name = '';
    $gcash_number = '';
    $proof_of_payment = '';

    if ($paymentMethod == 'Gcash') {
        $gcash_name = htmlspecialchars($_POST['gcash_name']);
        $gcash_number = htmlspecialchars($_POST['gcash_number']);

        if (isset($_FILES['proof']) && $_FILES['proof']['error'] == 0) {
            $target_dir = "../uploads/";
            $proof_of_payment = $target_dir . basename($_FILES["proof"]["name"]);

            // Check if the file is an image
            $imageFileType = strtolower(pathinfo($proof_of_payment, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["proof"]["tmp_name"]);
            if ($check !== false && move_uploaded_file($_FILES["proof"]["tmp_name"], $proof_of_payment)) {
                // File uploaded successfully
            } else {
                echo "Error uploading proof of payment.";
                exit();
            }
        } else {
            $_SESSION['status'] = "Complete the input fields for GCASH!";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../my_payment.php");
            exit;
        }

        if (empty($gcash_name) || empty($gcash_number) || empty($proof_of_payment)) {
            $_SESSION['status'] = "Fill Details for GCASH!";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../my_payment.php");
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO payments (reservation_id, amount, payment_method, name, number, transaction_id, proof, payment_date) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

    // Bind the parameters to the SQL query
    $stmt->bind_param("sssssss", $reservation_id, $total, $paymentMethod, $gcash_name, $gcash_number, $transaction_id, $proof_of_payment);

    // Execute the query
    if ($stmt->execute()) {
        
        $_SESSION['status'] = "Payment Sent. Wait for Approval";
        $_SESSION['status_code'] = "info";
        $_SESSION['status_button'] = "Okay";
        header("Location: ../my_payment.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
