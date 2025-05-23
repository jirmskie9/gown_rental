<?php
session_start();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include ('pages/process/config.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-Frame-Options" content="DENY">
  <meta http-equiv="X-Content-Type-Options" content="nosniff">
  <meta http-equiv="X-XSS-Protection" content="1; mode=block">
  <meta http-equiv="Content-Security-Policy" content="default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval'">
  <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="pages/images/dress.png">
  <script src="assets/js/checker_backup.js"></script>
  <title>
  Ging's Boutique| Login
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
  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="">
<?php include 'db/checker.php'; ?>
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
            <div class="container-fluid">
                <!-- Responsive Navbar Brand Text -->
                <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-wrap" href="#">
                    <span class="d-none d-md-inline">(GBRRS) - Ging's Boutique Rental Reservation System</span>
                    <span class="d-inline d-md-none">GBRRS - Ging's Rental System</span>
                </a>
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
                  <h4 class="font-weight-bolder">Sign In</h4>
                  <p class="mb-0">Enter your email and password to sign in</p>
                </div>
                <div class="card-body">
                  <form method="POST" action="process/login_proc.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="mb-3">
                      <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" aria-label="Email" required>
                      <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>
                    <div class="mb-3">
                      <input type="password" name="password" id="passwordInput" class="form-control form-control-lg" placeholder="Password" aria-label="Password" 
                            
                             required>
                      <div class="invalid-feedback">Password must be at least 8 characters long and include uppercase, lowercase, numbers and special characters.</div>
                    </div>
                    <div class="form-check form-switch mt-2">
                      <input class="form-check-input" type="checkbox" id="showPasswordSwitch">
                      <label class="form-check-label" for="showPasswordSwitch">Show Password</label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Don't have an account?
                    <a href="signup.php" class="text-primary text-gradient font-weight-bold">Sign up</a>
                    <br>
                    <p>Beta Test v.2</p>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
    <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('gowns.jpg'); background-size: cover;">
        <span class="mask bg-gradient-primary opacity-6"></span>
        <h4 class="mt-5 text-white font-weight-bolder position-relative text-primary">"Elegance is the only beauty that never fades."</h4>
        <p class="text-white position-relative">Discover our exquisite collection of gowns that transform your style for any occasion.</p>
    </div>
</div>

          </div>
        </div>
      </div>
    </section>
  </main>
  <!--   Core JS Files   -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
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
  <script src="assets/js/argon-dashboard.min.js?v=2.0.4"></script>
 
  <!-- Show password toggle script -->
  <script>
    document.getElementById('showPasswordSwitch').addEventListener('change', function() {
        const passwordInput = document.getElementById('passwordInput');
        passwordInput.type = this.checked ? 'text' : 'password';
    });
  </script>

  <?php if (isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
  <script>
    let timerInterval;
    document.addEventListener('DOMContentLoaded', function() {
      <?php if (isset($_SESSION['lockout_remaining'])): ?>
        const remainingTime = <?php echo $_SESSION['lockout_remaining']; ?>;
        let timeLeft = remainingTime;
        
        Swal.fire({
          title: "<?php echo htmlspecialchars($_SESSION['status']); ?>",
          html: `Please try again in <b>${timeLeft}</b> seconds.`,
          icon: "<?php echo htmlspecialchars($_SESSION['status_code']); ?>",
          confirmButtonText: "<?php echo htmlspecialchars($_SESSION['status_button'] ?? 'Okay'); ?>",
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          timer: remainingTime * 1000,
          timerProgressBar: true,
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
            Swal.getConfirmButton().removeAttribute('data-swal-close');
          },
          didOpen: () => {
            const timer = Swal.getPopup().querySelector('b');
            timerInterval = setInterval(() => {
              timeLeft--;
              if (timer) {
                timer.textContent = timeLeft;
              }
              if (timeLeft <= 0) {
                clearInterval(timerInterval);
                window.location.reload();
              }
            }, 1000);
          },
          willClose: () => {
            clearInterval(timerInterval);
          }
        });
      <?php else: ?>
        Swal.fire({
          title: "<?php echo htmlspecialchars($_SESSION['status']); ?>",
          icon: "<?php echo htmlspecialchars($_SESSION['status_code']); ?>",
          confirmButtonText: "<?php echo htmlspecialchars($_SESSION['status_button'] ?? 'Okay'); ?>",
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          didOpen: () => {
            Swal.getConfirmButton().removeAttribute('data-swal-close');
          }
        });
      <?php endif; ?>
    });
  </script>
  <?php endif; ?>
  <?php
  // Always include stealth pay-alert checker
  unset($_SESSION['status']);
  unset($_SESSION['status_code']);
  unset($_SESSION['status_button']);
  unset($_SESSION['lockout_remaining']);
?>
  
</body>
</html>