<?php
session_start();
include('../pages/process/config.php');

    if (isset($_POST['conf'])) {
        $email = $_POST['email'];
        $otp = $_POST['otp'];

        // Check if OTP matches the one stored in the database
        $query = "SELECT * FROM users WHERE email = '$email' AND otp = '$otp'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Check the number of rows returned by the query
            $numRows = mysqli_num_rows($result);

            if ($numRows > 0) {
                // OTP matched, update confirmation status
                $updateQuery = "UPDATE users SET status = 'Complete' WHERE email = '$email'";
                $updateResult = mysqli_query($conn, $updateQuery);

                if ($updateResult) {
                    $_SESSION['[status]'] = "Verification complete!";
                    $_SESSION['[status_code]'] = "success";
                    $_SESSION['[status_button]'] = "Okay";
                    header("Location: ../index.php");
                    exit();
                } else {
                    echo "Error updating confirmation status. Please try again.";
                }
            } else {
                $_SESSION['[status]'] = "Invalid code!";
                $_SESSION['[status_code]'] = "error";
                $_SESSION['[status_button]'] = "Retry";
                header("Location: ../confirmation.php");
                exit();
            }
        } else {
            echo "Error in the query. Please try again.";
        }
    }
?>
