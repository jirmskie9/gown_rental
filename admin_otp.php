<?php
session_start();
include('pages/process/config.php');

// Redirect if not admin or no email in session
if (!isset($_SESSION['email']) || !isset($_SESSION['is_admin_login'])) {
    header('location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="pages/images/dress.png">
  <title>Admin OTP Verification</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <link id="pagestyle" href="assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-200">
  <main class="main-content mt-0">
    <div class="page-header align-items-start min-vh-100">
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Admin Verification</h4>
                </div>
              </div>
              <div class="card-body">
                <form role="form" class="text-start" method="POST" action="process/verify_admin_otp.php">
                  <div class="mb-3">
                    <input type="text" name="otp" class="form-control form-control-lg" placeholder="Enter OTP" required maxlength="6" pattern="[0-9]{6}" inputmode="numeric">
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Verify OTP</button>
                  </div>
                  <p class="mt-4 text-sm text-center">
                    OTP has been sent to admin Gmail<br>
                    OTP expires in 5 minutes.
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php if (isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
  <script>
    let timerInterval;
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        title: "<?php echo htmlspecialchars($_SESSION['status']); ?>",
        icon: "<?php echo htmlspecialchars($_SESSION['status_code']); ?>",
        confirmButtonText: "<?php echo htmlspecialchars($_SESSION['status_button'] ?? 'Okay'); ?>",
        customClass: {
          confirmButton: 'btn btn-primary'
        }
      });
    });
  </script>
  <?php 
    unset($_SESSION['status']);
    unset($_SESSION['status_code']);
    unset($_SESSION['status_button']);
  endif; 
  ?>
</body>
</html>
