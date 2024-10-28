<?php
session_start();
include('process/config.php');

if (!isset($_SESSION['email'])) {
    header('location: ../index.php');
    exit();
}

$uemail = $_SESSION['email'];
$sql_session = "SELECT user_id FROM users WHERE email = '$uemail'";
$result_session = $conn->query($sql_session);

$gown_id = (int)$_GET['gown_id']; 

if ($result_session->num_rows > 0) {
    $row_session = $result_session->fetch_assoc();
    $user_id = $row_session['user_id'];

    // Fetch reservations for the specific gown_id
    $sql = "SELECT start_date, end_date FROM reservations WHERE gown_id = '$gown_id' AND status = 'confirmed'";
    $result = $conn->query($sql);

    $events = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = array(
                'title' => 'Reserved (Not Available)',
                'start' => $row['start_date'],
                'end' => $row['end_date'] 
            );
        }
    }

    // Return events as JSON
    echo json_encode($events);
} else {
    // If the session query failed or no user found
    echo json_encode(array());
}

$conn->close();
?>
