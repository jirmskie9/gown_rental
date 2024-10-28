<?php
include('config.php');
session_start();

// Check if the form is submitted
if (isset($_POST['save_gown'])) {
  
    $gown_name = htmlspecialchars($_POST['gown_name']);
    $gown_size = htmlspecialchars($_POST['gown_size']);
    $gown_color = htmlspecialchars($_POST['gown_color']);
    $gown_price = htmlspecialchars($_POST['gown_price']);
    $description = htmlspecialchars($_POST['description']);
    $gown_id = $_POST['gown_id']; 

    // Retrieve current images to keep existing ones if no new file is uploaded
    $sql_get = "SELECT main_image, img1, img2, img3 FROM gowns WHERE gown_id = ?";
    $stmt_get = $conn->prepare($sql_get);
    $stmt_get->bind_param("s", $gown_id);
    $stmt_get->execute();
    $result = $stmt_get->get_result();
    $row = $result->fetch_assoc();

    // Handle file uploads
    $gown_main = !empty($_FILES['gown_main']['name']) ? $_FILES['gown_main']['name'] : $row['main_image'];
    $img1 = !empty($_FILES['img1']['name']) ? $_FILES['img1']['name'] : $row['img1'];
    $img2 = !empty($_FILES['img2']['name']) ? $_FILES['img2']['name'] : $row['img2'];
    $img3 = !empty($_FILES['img3']['name']) ? $_FILES['img3']['name'] : $row['img3'];

    // Specify the upload directory
    $upload_dir = '../uploads/';

    // Upload the images
    if (!empty($_FILES['gown_main']['name'])) {
        move_uploaded_file($_FILES['gown_main']['tmp_name'], $upload_dir . $gown_main);
    }
    if (!empty($_FILES['img1']['name'])) {
        move_uploaded_file($_FILES['img1']['tmp_name'], $upload_dir . $img1);
    }
    if (!empty($_FILES['img2']['name'])) {
        move_uploaded_file($_FILES['img2']['tmp_name'], $upload_dir . $img2);
    }
    if (!empty($_FILES['img3']['name'])) {
        move_uploaded_file($_FILES['img3']['tmp_name'], $upload_dir . $img3);
    }

    // Prepare the SQL update statement
    $sql = "UPDATE gowns SET 
                name = ?, 
                size = ?, 
                color = ?, 
                price = ?, 
                description = ?, 
                main_image = ?, 
                img1 = ?, 
                img2 = ?, 
                img3 = ? 
            WHERE gown_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $gown_name, $gown_size, $gown_color, $gown_price, $description, $gown_main, $img1, $img2, $img3, $gown_id);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Gown Details Updated Successfully!";
        $_SESSION['status_code'] = "success";
        $_SESSION['status_button'] = "Okay";
        header('location: ../manage_gowns.php');
        exit();
    } else {
        echo "Error updating gown details: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
