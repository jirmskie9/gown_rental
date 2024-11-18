<?php
// $servername = "localhost";
// $username = "u507130350_peppings";
// $password = "User2366";
// $dbname = "u507130350_gown_rental";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gown_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
