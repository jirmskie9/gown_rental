<?php
session_start();
include('config.php'); 

if (isset($_POST['save_gown'])) {

    $gown_name = htmlspecialchars($_POST['gown_name']);
    $gown_size = htmlspecialchars($_POST['gown_size']);
    $gown_color = htmlspecialchars($_POST['gown_color']);
    $gown_price = $_POST['gown_price'];
    $description = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['category']);
    
    $uploadDir = '../uploads/';
    $main_image = $uploadDir . basename($_FILES['gown_main']['name']);
    $img1 = $uploadDir . basename($_FILES['img1']['name']);
    $img2 = $uploadDir . basename($_FILES['img2']['name']);
    $img3 = $uploadDir . basename($_FILES['img3']['name']);

    if (move_uploaded_file($_FILES['gown_main']['tmp_name'], $main_image) &&
        move_uploaded_file($_FILES['img1']['tmp_name'], $img1) &&
        move_uploaded_file($_FILES['img2']['tmp_name'], $img2) &&
        move_uploaded_file($_FILES['img3']['tmp_name'], $img3)) {
        
        $sql = "INSERT INTO gowns (name, category, size, color, price, description, main_image, img1, img2, img3)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssssssss", $gown_name, $category, $gown_size, $gown_color, $gown_price, $description, $main_image, $img1, $img2, $img3);
            
            if ($stmt->execute()) {
                $_SESSION['status'] = "Gown Added Successfully!";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_button'] = "Okay";
                header("Location: ../manage_gowns.php");
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error uploading images.";
    }
}

header("Location: ../manage_gowns.php");
exit;

$conn->close();
?>
