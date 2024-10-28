<?php
session_start();
include('config.php'); 

$reservation_id = $_POST['reservation_id'];
$signature_data = $_POST['signature_data'];
$date_signed = date("Y-m-d H:i:s"); 

if (!empty($signature_data)) {

    $uniqueFileName = 'signature_' . uniqid() . '.png';

    $encodedData = explode(',', $signature_data)[1];

    $decodedImage = base64_decode($encodedData);

    $uploadDir = '../uploads/';
    $filePath = $uploadDir . $uniqueFileName;

    if (file_put_contents($filePath, $decodedImage)) {
      
        $query = "INSERT INTO agreements (reservation_id, signature, date_signed) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $reservation_id, $uniqueFileName, $date_signed);

        if ($stmt->execute()) {
            $_SESSION['status'] = "Signed Successful! Pay Now.";
            $_SESSION['status_code'] = "info";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../submit_payment.php?reservation_id=$reservation_id");
            exit();
        } else {
            $_SESSION['status'] = "Signing Error.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../my_payment.php");
            exit();
        }

        $stmt->close();
    } else {

        echo "<script>alert('Failed to save signature. Please try again.'); window.history.back();</script>";
    }
} else {
            $_SESSION['status'] = "Signature is Required.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../my_payment.php");
            exit();
}

$conn->close();
?>
