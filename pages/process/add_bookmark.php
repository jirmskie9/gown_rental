<?php
include('config.php');
session_start();

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$gown_id = isset($_GET['gown_id']) ? intval($_GET['gown_id']) : 0;

if ($user_id <= 0 || $gown_id <= 0) {
    echo 'Invalid user or gown ID';
    exit;
}

// Fetch gown name from the 'gown' table
$gown_query = "SELECT name FROM gowns WHERE gown_id = ?";
$stmt = $conn->prepare($gown_query);
$stmt->bind_param("i", $gown_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row2 = $result->fetch_assoc();
    $gown_name = $row2['name'];
} else {
    echo 'Error: Gown not found';
    exit;
}

// Insert into bookmarks
$insert_query = "INSERT INTO bookmarks (user_id, content, date_time) VALUES (?, ?, NOW())";
$insert_stmt = $conn->prepare($insert_query);
$insert_stmt->bind_param("is", $user_id, $gown_name);
$res = $insert_stmt->execute();

if ($res) {
    $_SESSION['status'] = "Added to Bookmark!";
    $_SESSION['status_code'] = "success";
    $_SESSION['status_button'] = "Okay";
    header("Location: ../find_gown.php");
    exit;
} else {
    echo 'Error Adding to Bookmark';
}
?>
