<?php
include('process/config.php');
session_start();
$reservation_id = $_GET['reservation_id'] ?? null;
if ($reservation_id) {
 
    $stmt = $conn->prepare("SELECT * FROM agreements WHERE reservation_id = ?");
    $stmt->bind_param("i", $reservation_id);  
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: submit_payment.php?reservation_id=" . $reservation_id);
        exit(); 
    }else{
      header("Location: my_reservation.php?reservation_id=" . $reservation_id);
      exit();
    }

    $stmt->close();
} else {
  header("Location: payment.php");
  exit(); 
}

?>