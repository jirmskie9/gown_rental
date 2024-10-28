<?php
session_start();
include('pages/process/config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="pages/images/dress.png">
  <title>
    Boutique Gowns | Signup
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
  <style>
       <style>
    .password-strength {
      margin-top: 5px;
      font-size: 12px;
    }

    #strength-bar {
      height: 5px;
      margin-top: 5px;
    }

    .very-weak {
      height: 5px;
      background-color: #ff4d4d;
    }

    .weak {
      background-color: #ffa07a;
    }

    .fair {
      background-color: #ffd700;
    }

    .moderate {
      background-color: #add8e6;
    }

    .strong {
      background-color: #90ee90;
    }

    .very-strong {
      background-color: #00cc00;
    }
  </style>
  </style>
</head>

<body class="">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent mt-4">
    <div class="container">
      <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-white" href="../pages/dashboard.html">
        Boutique Gowns
      </a>
      <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon mt-2">
          <span class="navbar-toggler-bar bar1"></span>
          <span class="navbar-toggler-bar bar2"></span>
          <span class="navbar-toggler-bar bar3"></span>
        </span>
      </button>
      <div class="collapse navbar-collapse" id="navigation">

      </div>
    </div>
  </nav>
  <!-- End Navbar -->
  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('gowns.jpg'); background-position: top;">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 text-center mx-auto">
          <h1 class="text-white mb-2 mt-5">Welcome!</h1>
        <p class="text-lead text-white">We’re delighted to have you here. Explore our stunning collection of gowns!</p>

          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card z-index-0">
            <div class="card-header text-center pt-4">
              <h5>Register</h5>
            </div>
            
            <div class="card-body">
              <form method = "POST" action="process/register_proc.php" enctype="multipart/form-data">
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Full Name" aria-label="Name" name = "full_name">
                </div>
                <div class="mb-3">
                  <input type="email" class="form-control" placeholder="Email" aria-label="Email" name = "email">
                </div>
                <div class="mb-3">
                  <input type="password" name = "password" class="form-control" placeholder="Password" aria-label="Password" id="passwordInput" pattern="^(?=.*[A-Z])(?=.*\d).{8,}$" title="Password must be at least 8 characters long and include at least one uppercase letter and one digit">
                  <div class="password-strength" id="passwordStrength"><label>Password Strength: </label></div>
                                    <div id="strength-bar"></div>
                            
                </div>
                <div class="mb-3">
                  <input type="number" class="form-control" placeholder="Phone Number" aria-label="Phone Number" name = "phone">
                </div>
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Address" aria-label="Address" name = "address">
                </div>
                <div class="mb-3">
                    <label for="">Profile</label>
                  <input type="file" class = "form-control" name = "profile">
                </div>
                <div class="form-check form-check-info text-start">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
                  <label class="form-check-label" for="flexCheckDefault">
                    I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
                  </label>
                </div>
                <div class="text-center">
                  <button type="submit" name = "reg" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign up</button>
                </div>
                </form>
                <p class="text-sm mt-3 mb-0">Already have an account? <a href="index.php" class="text-dark font-weight-bolder">Sign in</a></p>
                <script>
                                document.getElementById('passwordInput').addEventListener('input', function () {
                                    var password = document.getElementById('passwordInput').value;
                                    var strengthBadge = document.getElementById('passwordStrength');
                                    var strengthBar = document.getElementById('strength-bar');

                                    // Check password strength
                                    var strength = 0;
                                    if (password.match(/[a-z]+/)) {
                                    strength += 1;
                                    }
                                    if (password.match(/[A-Z]+/)) {
                                    strength += 1;
                                    }
                                    if (password.match(/[0-9]+/)) {
                                    strength += 1;
                                    }
                                    if (password.length >= 8) {
                                    strength += 1;
                                    }

                                    // Update the strength indicator and color bar
                                    switch (strength) {
                                    case 0:
                                        strengthBadge.innerHTML = 'Password Strength: Very Weak';
                                        strengthBar.className = 'very-weak';
                                        break;
                                    case 1:
                                        strengthBadge.innerHTML = 'Password Strength: Weak';
                                        strengthBar.className = 'weak';
                                        break;
                                    case 2:
                                        strengthBadge.innerHTML = 'Password Strength: Fair';
                                        strengthBar.className = 'fair';
                                        break;
                                    case 3:
                                        strengthBadge.innerHTML = 'Password Strength: Moderate';
                                        strengthBar.className = 'moderate';
                                        break;
                                    case 4:
                                        strengthBadge.innerHTML = 'Password Strength: Strong';
                                        strengthBar.className = 'strong';
                                        break;
                                    }
                                });
                                </script>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">
      
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
             © <script>
              document.write(new Date().getFullYear())
            </script> .
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
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