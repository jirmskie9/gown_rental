<?php
session_start();
include('config.php');

if (isset($_POST['reserve'])) {

    $date_to_pick = $_POST['date_to_pick'];
    $date_to_return = $_POST['date_to_return'];
    $gown_id = $_POST['gown_id'];
    $user_id = $_POST['user'];
    $price = $_POST['price']; 
    $full_name = $_POST['full_name'];
    $gown_name = $_POST['gown_name'];
    $priceWithTax = $price + ($price * 0.03); 

    // Check for existing reservations for the same customer and gown
    $checkSql = "SELECT * FROM reservations 
             WHERE gown_id = ? AND customer_id = ? AND status = 'confirmed' AND
             ( (start_date <= ? AND end_date >= ?) OR 
               (start_date <= ? AND end_date >= ?) OR 
               (start_date >= ? AND end_date <= ?) )";

    if ($checkStmt = $conn->prepare($checkSql)) {
        $checkStmt->bind_param("iissssss", $gown_id, $user_id, $date_to_pick, $date_to_pick, $date_to_return, $date_to_return, $date_to_pick, $date_to_return);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        // Check if there are overlapping reservations for the same customer
        if ($result->num_rows > 0) {
            $_SESSION['status'] = "Reservation failed! You already have a reservation for this gown during the selected dates.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../find_gown.php");
            exit();
        }

        $checkStmt->close();
    } else {
        echo "Error preparing check statement: " . $conn->error;
    }

    // Check if the gown has already been reserved by the same customer
    $duplicateCheckSql = "SELECT * FROM reservations WHERE gown_id = ? AND customer_id = ?";
    
    if ($duplicateCheckStmt = $conn->prepare($duplicateCheckSql)) {
        $duplicateCheckStmt->bind_param("ii", $gown_id, $user_id);
        $duplicateCheckStmt->execute();
        $duplicateResult = $duplicateCheckStmt->get_result();

        // Check if the same gown is already reserved by the same customer
        if ($duplicateResult->num_rows > 0) {
            $_SESSION['status'] = "Reservation failed! You have already reserved this gown.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../find_gown.php");
            exit();
        }

        $duplicateCheckStmt->close();
    } else {
        echo "Error preparing duplicate check statement: " . $conn->error;
    }

    // Proceed with insertion if no conflicts found
    $sql = "INSERT INTO reservations (customer_id, gown_id, start_date, end_date, total_price, payment_status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $status = 'Pending'; 
        $payment_status = 'unpaid';
        $created_at = date('Y-m-d H:i:s');

        $stmt->bind_param("iissdss", $user_id, $gown_id, $date_to_pick, $date_to_return, $priceWithTax, $payment_status, $created_at);

        if ($stmt->execute()) {
            $notif = "INSERT INTO notifications (user_id, content, date_time, type) VALUES ('$user_id', '$full_name wants to rent $gown_name', NOW(), 'admin')";
            $res = $conn->query($notif);
            $_SESSION['status'] = "Reservation successful!";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_button'] = "Okay";
            header("Location: ../find_gown.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>
