<?php
session_start();
include('pages/process/config.php');

if (!isset($_SESSION['email'])) {
    header('location: signup.php');
    exit();
  }
  
  if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql_session = "SELECT * FROM users WHERE email = '$email'";
    $result_session = $conn->query($sql_session);
  
    if ($result_session->num_rows > 0) {
        $row_session = $result_session->fetch_assoc();
        $email = $row_session['email'];
    }
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="pages/images/dress.png">
  <title>
  Boutique Gowns | Confirm
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
</head>

<body class="">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
          <div class="container-fluid">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="#">
              (BGRRS) - Boutique Gown Rental Reservation System
            </a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
            </button>
            <div class="collapse navbar-collapse" id="navigation">
              
              <ul class="navbar-nav d-lg-block d-none">
               
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
              <div class="card card-plain">
                <div class="card-header pb-0 text-start">
                  <h4 class="font-weight-bolder">Confirm your Email</h4>
                  <p class="mb-0">OTP has been sent to <?php echo $email ?></p>
                </div>
                <div class="card-body">
                  <form method = "POST" action = "process/confirm_email.php">
                    <div class="mb-3">
                        <input type="hidden" name = "email" value = "<?php echo $email?>">
                      <input type="number" name = "otp" class="form-control form-control-lg" placeholder="Enter OTP here..." aria-label="OTP">
                    </div>
                 
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="rememberMe">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <div class="text-center">
                      <button type="submit" name = "conf" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Confirm</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Don't have an account?
                    <a href="signup.php" class="text-primary text-gradient font-weight-bold">Sign up</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
    <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('gowns.jpg'); background-size: cover;">
        <span class="mask bg-gradient-primary opacity-6"></span>
        <h4 class="mt-5 text-white font-weight-bolder position-relative">"Elegance is the only beauty that never fades."</h4>
        <p class="text-white position-relative">Discover our exquisite collection of gowns that transform your style for any occasion.</p>
    </div>
</div>

          </div>
        </div>
      </div>
    </section>
  </main>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.0.4"></script>
  <script src="sweetalert.min.js"></script>
        <?php
            if (isset($_SESSION['[status]']) && $_SESSION['[status]'] != '') {
            ?>
            <script>
            swal({
                title: "<?php echo $_SESSION['[status]']; ?>",
                icon: "<?php echo $_SESSION['[status_code]']; ?>",
                button: "<?php echo $_SESSION['[status_button]']; ?>",
            });
            </script>
            <?php
            unset($_SESSION['[status]']);
            unset($_SESSION['[status_code]']);
            unset($_SESSION['[status_button]']);
            
            ?>
             <?php
    unset($_SESSION['status']);
    unset($_SESSION['status_code']);
    unset($_SESSION['status_button']);
  }
?>
</body>

</html>