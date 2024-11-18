<?php
session_start();
include('config.php');

$reservation_id = $_POST['reservation_id'];
$signature_data = $_POST['signature_data'];
$gown_id = $_POST['gown_id'];
$date_signed = date("Y-m-d H:i:s");

// Check if signature data is not empty
if (!empty($signature_data)) {
    // Generate a unique filename for the signature
    $uniqueFileName = 'signature_' . uniqid() . '.png';

    // Extract base64 data and decode the image
    $encodedData = explode(',', $signature_data)[1];
    $decodedImage = base64_decode($encodedData);

    // Define upload directory and save path
    $uploadDir = '../uploads/';
    $filePath = $uploadDir . $uniqueFileName;

    // Attempt to save the signature image
    if (file_put_contents($filePath, $decodedImage)) {
        // Insert signature data into the agreements table
        $query = "INSERT INTO agreements (reservation_id, signature, date_signed) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param('sss', $reservation_id, $uniqueFileName, $date_signed);

            if ($stmt->execute()) {
                // Update the gown's availability status to 'rented'
                $sql_gown = "UPDATE gowns SET availability_status = ? WHERE gown_id = ?";
                $stmt_update_gown = $conn->prepare($sql_gown);

                // Update the reservation's status to 'confirmed'
                $sql_reservation = "UPDATE reservations SET status = ? WHERE reservation_id = ?";
                $stmt_update_reservation = $conn->prepare($sql_reservation);

                if ($stmt_update_gown && $stmt_update_reservation) {
                    $availability_status = 'rented';
                    $reservation_status = 'confirmed';

                    // Bind parameters and execute the gown update
                    $stmt_update_gown->bind_param('si', $availability_status, $gown_id);
                    $stmt_update_reservation->bind_param('si', $reservation_status, $reservation_id);

                    if ($stmt_update_gown->execute() && $stmt_update_reservation->execute()) {
                        // Successfully signed, updated gown status, and confirmed reservation
                        $_SESSION['status'] = "Signed Successful! Pay Now.";
                        $_SESSION['status_code'] = "info";
                        $_SESSION['status_button'] = "Okay";
                        header("Location: ../submit_payment.php?reservation_id=$reservation_id");
                        exit();
                    } else {
                        // Error updating gown or reservation status
                        $_SESSION['status'] = "Error updating gown or reservation status.";
                        $_SESSION['status_code'] = "error";
                        $_SESSION['status_button'] = "Okay";
                        header("Location: ../my_payment.php");
                        exit();
                    }
                } else {
                    // Error preparing gown or reservation update statement
                    $_SESSION['status'] = "Error preparing update statements.";
                    $_SESSION['status_code'] = "error";
                    $_SESSION['status_button'] = "Okay";
                    header("Location: ../my_payment.php");
                    exit();
                }
            } else {
                // Error executing the agreements insertion
                $_SESSION['status'] = "Error saving signature data.";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_button'] = "Okay";
                header("Location: ../my_payment.php");
                exit();
            }
            $stmt->close();
        } else {
            // Error preparing the agreements statement
            $_SESSION['status'] = "Error preparing agreements statement.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../my_payment.php");
            exit();
        }
    } else {
        // Failed to save the signature image
        $_SESSION['status'] = "Failed to save signature. Please try again.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header("Location: ../my_payment.php");
        exit();
    }
} else {
    // Signature data is empty
    $_SESSION['status'] = "Signature is Required.";
    $_SESSION['status_code'] = "error";
    $_SESSION['status_button'] = "Okay";
    header("Location: ../my_payment.php");
    exit();
}

$conn->close();
?>
