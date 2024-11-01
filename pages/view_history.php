<?php
session_start();
include('process/config.php');

if (!isset($_SESSION['email'])) {
  header('location: ../index.php');
  exit();
}

if (isset($_SESSION['email'])) {
  $uemail = $_SESSION['email'];
  $sql_session = "SELECT * FROM users WHERE email = '$uemail'";
  $result_session = $conn->query($sql_session);

  if ($result_session->num_rows > 0) {
    $row_session = $result_session->fetch_assoc();
	  $user_id = $row_session['user_id'];
    $full_name = htmlspecialchars($row_session['full_name']);
    $email = $row_session['email'];
	  $profile = $row_session['profile'];
	  $type = $row_session['user_type'];

	  if ($type != 'customer') {
    header('location: ../index.php');
    exit();
	}
      
  }else{
    header('location: ../index.php');
    exit();
  }

}
$reservation_id = intval($_GET['reservation_id']);
?>