<?php
require_once 'config.php'; 

$sql_count = "SELECT COUNT(*) as total FROM users WHERE user_type = 'customer'";

if ($result = $conn->query($sql_count)) {
   
    $row = $result->fetch_assoc();
    $total_users = $row['total']; 
    $result->close(); 
} else {

    echo "Error: " . $conn->error;
}
$sql_count = "SELECT COUNT(*) as total FROM reservations";

if ($result = $conn->query($sql_count)) {
   
    $row = $result->fetch_assoc();
    $total_reservations = $row['total']; 
    $result->close(); 
} else {

    echo "Error: " . $conn->error;
}
$sql_count = "SELECT COUNT(*) as total FROM reservations";

if ($result = $conn->query($sql_count)) {
   
    $row = $result->fetch_assoc();
    $total_reservations = $row['total']; 
    $result->close(); 
} else {

    echo "Error: " . $conn->error;
}
$sql_count = "SELECT COUNT(*) as total FROM payments WHERE payment_method='Gcash'";

if ($result = $conn->query($sql_count)) {
   
    $row = $result->fetch_assoc();
    $gcash = $row['total']; 
    $result->close(); 
} else {

    echo "Error: " . $conn->error;
}
$sql_count = "SELECT COUNT(*) as total FROM payments WHERE payment_method='Cash on Pick Up'";

if ($result = $conn->query($sql_count)) {
   
    $row = $result->fetch_assoc();
    $cash = $row['total']; 
    $result->close(); 
} else {

    echo "Error: " . $conn->error;
}


?>
