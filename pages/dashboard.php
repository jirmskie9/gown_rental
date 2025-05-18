<?php
session_start();
include('process/config.php');
include('process/totals.php');

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
    $full_name = $row_session['full_name'];
    $email = $row_session['email'];
    $profile = $row_session['profile'];
    $type = $row_session['user_type'];

    if ($type != 'admin') {
      header('location: ../index.php');
      exit();
    }

  } else {
    header('location: ../index.php');
    exit();
  }

}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('header.php'); ?>
<title>
  Ging's Boutique | Dashboard
</title>
<style>
  .timeline {
    position: relative;
    padding: 0;
    margin: 0;
  }

  .timeline:before {
    content: '';
    position: absolute;
    top: 0;
    left: 30px;
    height: 100%;
    width: 2px;
    background: #e9ecef;
  }

  .timeline-block {
    position: relative;
    margin-bottom: 30px;
  }

  .timeline-step {
    position: absolute;
    left: 20px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    text-align: center;
    line-height: 20px;
    z-index: 1;
  }

  .timeline-content {
    margin-left: 50px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 4px;
  }

  .badge-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 0.25rem;
  }

  .bg-gradient-success {
    background: linear-gradient(310deg, #17a37f 0%, #17a37f 100%);
  }

  .bg-gradient-warning {
    background: linear-gradient(310deg, #fbcf33 0%, #fbcf33 100%);
  }

  .bg-gradient-danger {
    background: linear-gradient(310deg, #ea0606 0%, #ea0606 100%);
  }

  .countdown-text {
    animation: pulse 1s infinite;
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
    }

    50% {
      transform: scale(1.1);
    }

    100% {
      transform: scale(1);
    }
  }
</style>
<!-- <script>
    let idleTime = 0;
    const idleInterval = 10000; // Check every second
    const idleTimeout = 10; // Logout after 10 seconds of inactivity
    const warningTime = 5; // Show warning 5 seconds before logout
    let warningShown = false;
    let countdownInterval;

    // Reset timer on user activity
    function resetIdleTime() {
      idleTime = 0;
      warningShown = false;
      // If there's an existing warning, close it
      const existingAlert = document.querySelector('.swal-overlay');
      if (existingAlert) {
        clearInterval(countdownInterval);
        swal.close();
      }
    }

    // Show warning with live countdown
    function showWarningWithCountdown() {
      warningShown = true;
      let secondsLeft = warningTime;

      // Create warning dialog
      const warningDialog = document.createElement('div');
      warningDialog.innerHTML = `
        <div class="idle-warning text-center">
          <div class="warning-icon mb-3">
            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
          </div>
          <h4 class="mb-3">Idle Detection Warning!</h4>
          <div class="countdown-container">
            <div class="countdown-number" id="countdown">${secondsLeft}</div>
            <div class="countdown-text mt-2">seconds until logout</div>
          </div>
          <div class="mt-3 text-muted">Move mouse or press any key to cancel</div>
        </div>
      `;

      // Add styles for the countdown
      const style = document.createElement('style');
      style.textContent = `
        .idle-warning {
          padding: 20px;
        }
        .countdown-container {
          margin: 20px 0;
        }
        .countdown-number {
          font-size: 72px;
          font-weight: bold;
          color: #dc3545;
          animation: pulse 1s infinite;
          line-height: 1;
          text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .countdown-text {
          font-size: 1.2rem;
          color: #666;
        }
        @keyframes pulse {
          0% { transform: scale(1); opacity: 1; }
          50% { transform: scale(1.2); opacity: 0.8; }
          100% { transform: scale(1); opacity: 1; }
        }
        .warning-icon {
          animation: shake 1s infinite;
        }
        @keyframes shake {
          0%, 100% { transform: translateX(0); }
          25% { transform: translateX(-5px); }
          75% { transform: translateX(5px); }
        }
      `;
      document.head.appendChild(style);

      swal({
        content: warningDialog,
        buttons: false,
        closeOnEsc: false,
        closeOnClickOutside: false,
      });

      // Update countdown
      countdownInterval = setInterval(() => {
        secondsLeft--;
        const countdownElement = document.getElementById('countdown');
        if (countdownElement && secondsLeft >= 0) {
          countdownElement.textContent = secondsLeft;
          // Add extra visual feedback as time runs out
          if (secondsLeft <= 2) {
            countdownElement.style.color = '#dc3545';
            countdownElement.style.animation = 'pulse 0.5s infinite';
          }
        }
        if (secondsLeft < 0) {
          clearInterval(countdownInterval);
        }
      }, 1000);
    }

    // Increment idle time counter
    function timerIncrement() {
      idleTime += 1;
      
      // Show warning 5 seconds before logout
      if (idleTime === (idleTimeout - warningTime) && !warningShown) {
        showWarningWithCountdown();
      }
      
      if (idleTime >= idleTimeout) {
        clearInterval(countdownInterval);
        window.location.href = 'logout.php';
      }
    }

    // Initialize idle detection when document is ready
    document.addEventListener('DOMContentLoaded', function() {
      // Set up the timer that checks for inactivity
      setInterval(timerIncrement, idleInterval);

      // Reset timer on any user activity
      const events = ['mousemove', 'mousedown', 'keypress', 'scroll', 'touchstart'];
      events.forEach(function(event) {
        document.addEventListener(event, resetIdleTime, true);
      });
    });
  </script> -->
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#" target="_blank">
        <img src="images/dress.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Ging's Boutique</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="dashboard.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="manage_gowns.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-female text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Gowns</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="manage_sales.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-shopping-cart text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Sales</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link " href="manage_reservations.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-calendar-alt text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Reservations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="manage_customers.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-user text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Customers</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="manage_contract.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-file-contract text-secondary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Contract</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link " href="payments.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-money-bill-wave text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Payments</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="logout.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-sign-out-alt text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Log out</span>
          </a>
        </li>

      </ul>
    </div>

  </aside>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
      data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Dashboard</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Dashboard</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">
              <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
              <input type="text" class="form-control" placeholder="Type here...">
            </div>
          </div>
          <ul class="navbar-nav  justify-content-end">

            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0">
                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
              </a>
            </li>

            <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0 dropdown-toggle" id="dropdownMenuButton"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <?php
                $sql = "SELECT * FROM notifications WHERE type = 'admin' ORDER BY date_time DESC";
                $res = $conn->query($sql);

                if ($res->num_rows > 0) {
                  while ($row = $res->fetch_assoc()) {
                    $formatted_date_time = date("F j, Y, g:i a", strtotime($row['date_time']));
                    ?>
                    <li class="mb-2">
                      <a class="dropdown-item border-radius-md" href="javascript:;">
                        <div class="d-flex py-1">
                          <div class="my-auto">
                            <img src="images/email.png" class="avatar avatar-sm me-3">
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="text-sm font-weight-normal mb-0">
                              <span class=""><?php echo $row['content']; ?></span>
                            </h6>
                            <p class="text-xs text-secondary mb-0">
                              <i class="fa fa-clock me-1"></i>
                              <?php echo $formatted_date_time; ?>
                            </p>
                          </div>
                        </div>
                      </a>
                    </li>
                    <?php
                  }
                } else {
                  echo '<li class="mb-2">No notifications available.</li>';
                }
                ?>
              </ul>
            </li>

          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Customers</p>
                    <h5 class="font-weight-bolder">
                      <?php echo $total_users ?>
                    </h5>
                    <p class="mb-0">
                      <span class="text-success text-sm font-weight-bolder">
                        <i class="fa fa-arrow-up"></i> 3.48%
                      </span>
                      <span class="text-secondary text-sm font-weight-bolder">since last month</span>
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="fa fa-users text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Reservations</p>
                    <h5 class="font-weight-bolder">
                      <?php echo $total_reservations ?>
                    </h5>
                    <p class="mb-0">
                      <span class="text-success text-sm font-weight-bolder">
                        <i class="fa fa-arrow-up"></i> 2.6%
                      </span>
                      <span class="text-secondary text-sm font-weight-bolder">since last month</span>
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                    <i class="fa fa-calendar-check text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Gcash Payments</p>
                    <h5 class="font-weight-bolder">
                      <?php echo $gcash ?>
                    </h5>
                    <p class="mb-0">
                      <span class="text-success text-sm font-weight-bolder">
                        <i class="fa fa-arrow-up"></i> 4.2%
                      </span>
                      <span class="text-secondary text-sm font-weight-bolder">since last month</span>
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                    <i class="fa fa-mobile-alt text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">COPU Payments</p>
                    <h5 class="font-weight-bolder">
                      <?php echo $cash ?>
                    </h5>
                    <p class="mb-0">
                      <span class="text-success text-sm font-weight-bolder">
                        <i class="fa fa-arrow-up"></i> 1.8%
                      </span>
                      <span class="text-secondary text-sm font-weight-bolder">since last month</span>
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                    <i class="fa fa-money-bill-wave text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Rental Trends and Popular Gowns Section -->
      <div class="row mt-4">
        <div class="col-lg-6 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Rental Trends</h6>
              <p class="text-sm mb-0">
                <i class="fa fa-arrow-up text-success"></i>
                <span class="font-weight-bold">Monthly rental activity</span>
              </p>
            </div>
            <div class="card-body p-3">
              <div class="chart">
                <canvas id="rental-trends-chart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Most Popular Gowns</h6>
              <p class="text-sm mb-0">
                <i class="fa fa-trophy text-warning"></i>
                <span class="font-weight-bold">Top rented gowns</span>
              </p>
            </div>
            <div class="card-body p-3">
              <div class="chart">
                <canvas id="popular-gowns-chart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sales Section -->
      <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent d-flex justify-content-between align-items-center">
              <div>
                <h6 class="text-capitalize mb-0">Sales Overview</h6>
                <p class="text-sm mb-0">
                  Total revenue and sales trends
                </p>
              </div>
              <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary active" data-period="weekly">Weekly</button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-period="monthly">Monthly</button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-period="yearly">Yearly</button>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="row mb-4">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                  <div class="card">
                    <div class="card-body p-3">
                      <div class="row">
                        <div class="col-8">
                          <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Sales</p>
                            <?php
                            $sql_total_sales = "SELECT SUM(p.amount) as total 
                                                FROM payments p 
                                                JOIN reservations r ON p.reservation_id = r.reservation_id 
                                                WHERE r.payment_status = 'paid'";
                            $result_total_sales = $conn->query($sql_total_sales);
                            $row_total_sales = $result_total_sales->fetch_assoc();
                            $total_sales = $row_total_sales['total'] ?? 0;
                            ?>
                            <h5 class="font-weight-bolder mb-0">
                              ₱<?php echo number_format($total_sales, 2); ?>
                            </h5>
                          </div>
                        </div>
                        <div class="col-4 text-end">
                          <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                            <i class="fa fa-chart-line text-lg opacity-10" aria-hidden="true"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                  <div class="card">
                    <div class="card-body p-3">
                      <div class="row">
                        <div class="col-8">
                          <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Orders</p>
                            <?php
                            $sql_total_orders = "SELECT COUNT(*) as total 
                                                 FROM payments p 
                                                 JOIN reservations r ON p.reservation_id = r.reservation_id 
                                                 WHERE r.payment_status = 'paid'";
                            $result_total_orders = $conn->query($sql_total_orders);
                            $row_total_orders = $result_total_orders->fetch_assoc();
                            ?>
                            <h5 class="font-weight-bolder mb-0">
                              <?php echo $row_total_orders['total']; ?>
                            </h5>
                          </div>
                        </div>
                        <div class="col-4 text-end">
                          <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="fa fa-shopping-cart text-lg opacity-10" aria-hidden="true"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                  <div class="card">
                    <div class="card-body p-3">
                      <div class="row">
                        <div class="col-8">
                          <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Average Order Value</p>
                            <?php
                            $avg_order = $row_total_orders['total'] > 0 ? $total_sales / $row_total_orders['total'] : 0;
                            ?>
                            <h5 class="font-weight-bolder mb-0">
                              ₱<?php echo number_format($avg_order, 2); ?>
                            </h5>
                          </div>
                        </div>
                        <div class="col-4 text-end">
                          <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                            <i class="fa fa-calculator text-lg opacity-10" aria-hidden="true"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                  <div class="card">
                    <div class="card-body p-3">
                      <div class="row">
                        <div class="col-8">
                          <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Conversion Rate</p>
                            <?php
                            $sql_total_reservations = "SELECT COUNT(*) as total FROM reservations";
                            $result_total_reservations = $conn->query($sql_total_reservations);
                            $row_total_reservations = $result_total_reservations->fetch_assoc();
                            $conversion_rate = $row_total_reservations['total'] > 0 ?
                              ($row_total_orders['total'] / $row_total_reservations['total']) * 100 : 0;
                            ?>
                            <h5 class="font-weight-bolder mb-0">
                              <?php echo number_format($conversion_rate, 1); ?>%
                            </h5>
                          </div>
                        </div>
                        <div class="col-4 text-end">
                          <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                            <i class="fa fa-percentage text-lg opacity-10" aria-hidden="true"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-7">
                  <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="sales-chart"></canvas>
                  </div>
                </div>
                <div class="col-lg-5">
                  <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="sales-bar-chart"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activities Section -->
      <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <h6>Recent Activities</h6>
            </div>
            <div class="card-body p-3">
              <div class="timeline timeline-one-side">
                <?php
                // Get recent reservations
                $sql_recent = "SELECT r.*, u.full_name, g.name 
                               FROM reservations r 
                               JOIN users u ON r.customer_id = u.user_id 
                               JOIN gowns g ON r.gown_id = g.gown_id 
                               ORDER BY r.created_at DESC LIMIT 5";
                $result_recent = $conn->query($sql_recent);

                if ($result_recent && $result_recent->num_rows > 0) {
                  while ($row_recent = $result_recent->fetch_assoc()) {
                    $date = date("M d, Y", strtotime($row_recent['created_at']));
                    $time = date("h:i A", strtotime($row_recent['created_at']));
                    $status_class = ($row_recent['status'] == 'completed') ? 'success' :
                      (($row_recent['status'] == 'pending') ? 'warning' : 'danger');
                    ?>
                    <div class="timeline-block mb-3">
                      <span class="timeline-step bg-<?php echo $status_class; ?>">
                        <i class="fa fa-calendar-check text-white"></i>
                      </span>
                      <div class="timeline-content">
                        <h6 class="text-dark text-sm font-weight-bold mb-0">
                          <?php echo $row_recent['full_name']; ?> reserved <?php echo $row_recent['name']; ?>
                        </h6>
                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                          <?php echo $date; ?> at <?php echo $time; ?>
                        </p>
                      </div>
                    </div>
                    <?php
                  }
                } else {
                  echo '<p class="text-center">No recent activities found.</p>';
                }
                ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5 mb-lg-0 mb-4">
          <!-- Empty column for spacing -->
        </div>
      </div>

      <!-- Upcoming Reservations Section -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Upcoming Reservations</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Gown</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Start
                        Date</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">End
                        Date</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status
                      </th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Get upcoming reservations
                    $sql_upcoming = "SELECT r.*, u.full_name, g.name 
                                    FROM reservations r 
                                    JOIN users u ON r.customer_id = u.user_id 
                                    JOIN gowns g ON r.gown_id = g.gown_id 
                                    WHERE r.status = 'pending' 
                                    ORDER BY r.start_date ASC LIMIT 5";
                    $result_upcoming = $conn->query($sql_upcoming);

                    if ($result_upcoming && $result_upcoming->num_rows > 0) {
                      while ($row_upcoming = $result_upcoming->fetch_assoc()) {
                        $start_date = date("M d, Y", strtotime($row_upcoming['start_date']));
                        $end_date = date("M d, Y", strtotime($row_upcoming['end_date']));
                        $status_class = ($row_upcoming['status'] == 'completed') ? 'success' :
                          (($row_upcoming['status'] == 'pending') ? 'warning' : 'danger');
                        ?>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm"><?php echo $row_upcoming['full_name']; ?></h6>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-sm font-weight-bold mb-0"><?php echo $row_upcoming['name']; ?></p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <span class="text-secondary text-sm font-weight-bold"><?php echo $start_date; ?></span>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <span class="text-secondary text-sm font-weight-bold"><?php echo $end_date; ?></span>
                          </td>
                          <td class="align-middle text-center">
                            <span
                              class="badge badge-sm bg-gradient-<?php echo $status_class; ?>"><?php echo ucfirst($row_upcoming['status']); ?></span>
                          </td>
                          <td class="align-middle">
                            <a href="view_reservation.php?reservation_id=<?php echo $row_upcoming['reservation_id']; ?>"
                              class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                              data-original-title="View details">
                              View
                            </a>
                          </td>
                        </tr>
                        <?php
                      }
                    } else {
                      echo '<tr><td colspan="6" class="text-center">No upcoming reservations found.</td></tr>';
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php include('footer.php'); ?>
    </div>
  </main>
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="fa fa-cog py-2"> </i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3 ">
        <div class="float-start">
          <h5 class="mt-3 mb-0">System Configurator</h5>
          <p>See our dashboard options.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="fa fa-close"></i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0 overflow-auto">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary active" data-color="primary"
              onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between 2 different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-primary w-100 px-3 mb-2 active me-2" data-class="bg-white"
            onclick="sidebarType(this)">White</button>
          <button class="btn bg-gradient-primary w-100 px-3 mb-2" data-class="bg-default"
            onclick="sidebarType(this)">Dark</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
        <!-- Navbar Fixed -->
        <div class="d-flex my-3">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
          </div>
        </div>
        <hr class="horizontal dark my-sm-4">
        <div class="mt-2 mb-5 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>

      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <?php include('script.php'); ?>
  <?php
  // Get rental trends data
  $rental_trends_sql = "SELECT DATE_FORMAT(start_date, '%Y-%m') as month, COUNT(*) as count 
                       FROM reservations 
                       WHERE status = 'completed' 
                       GROUP BY DATE_FORMAT(start_date, '%Y-%m') 
                       ORDER BY month ASC 
                       LIMIT 12";
  $rental_trends_result = $conn->query($rental_trends_sql);
  $rental_months = [];
  $rental_counts = [];
  while ($row = $rental_trends_result->fetch_assoc()) {
    $rental_months[] = date('M Y', strtotime($row['month'] . '-01'));
    $rental_counts[] = $row['count'];
  }

  // Get popular gowns data
  $popular_gowns_sql = "SELECT g.name, COUNT(r.gown_id) as rent_count 
                        FROM gowns g 
                        LEFT JOIN reservations r ON g.gown_id = r.gown_id 
                        WHERE r.status = 'completed' 
                        GROUP BY g.gown_id, g.name 
                        ORDER BY rent_count DESC 
                        LIMIT 5";
  $popular_gowns_result = $conn->query($popular_gowns_sql);
  $gown_names = [];
  $rent_counts = [];
  while ($row = $popular_gowns_result->fetch_assoc()) {
    $gown_names[] = $row['name'];
    $rent_counts[] = $row['rent_count'];
  }
  ?>
  <script>
    // Rental Trends Chart
    var ctx1 = document.getElementById("rental-trends-chart").getContext("2d");
    new Chart(ctx1, {
      type: "line",
      data: {
        labels: <?php echo json_encode($rental_months); ?>,
        datasets: [{
          label: "Rentals",
          tension: 0.4,
          borderWidth: 2,
          borderColor: "#5e72e4",
          backgroundColor: "rgba(94, 114, 228, 0.2)",
          fill: true,
          data: <?php echo json_encode($rental_counts); ?>,
          maxBarThickness: 6
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: "#fbfbfb",
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: "#ccc",
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });

    // Popular Gowns Chart
    var ctx2 = document.getElementById("popular-gowns-chart").getContext("2d");
    new Chart(ctx2, {
      type: "bar",
      data: {
        labels: <?php echo json_encode($gown_names); ?>,
        datasets: [{
          label: "Rentals",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#fb6340",
          data: <?php echo json_encode($rent_counts); ?>,
          maxBarThickness: 40
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: "#fbfbfb",
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: "#ccc",
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <?php include('payment_chart.php'); ?>
  <script src="sweetalert.min.js"></script>
  <?php
  if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
    ?>
    <script>
      swal({
        title: "<?php echo $_SESSION['status']; ?>",
        icon: "<?php echo $_SESSION['status_code']; ?>",
        button: "<?php echo $_SESSION['status_button']; ?>",
      });
    </script>
    <?php
    unset($_SESSION['status']);
    unset($_SESSION['status_code']);
    unset($_SESSION['status_button']);
  }
  ?>

  <script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function () {
      // Initialize chart variables
      let lineChart = null;
      let barChart = null;

      // Get canvas elements
      const salesChartCanvas = document.getElementById('sales-chart');
      const salesBarChartCanvas = document.getElementById('sales-bar-chart');

      // Check if canvas elements exist
      if (!salesChartCanvas || !salesBarChartCanvas) {
        console.error('Chart canvas elements not found');
        return;
      }

      // Get contexts
      const salesChartCtx = salesChartCanvas.getContext('2d');
      const salesBarChartCtx = salesBarChartCanvas.getContext('2d');

      // Check if contexts are available
      if (!salesChartCtx || !salesBarChartCtx) {
        console.error('Could not get 2D context for charts');
        return;
      }

      // Function to fetch sales data
      async function fetchSalesData(period) {
        try {
          const response = await fetch(`process/get_sales_data.php?period=${period}`);
          const contentType = response.headers.get('content-type');
          if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server did not return JSON');
          }

          const data = await response.json();
          if (data.error) {
            throw new Error(data.message || 'Error fetching sales data');
          }
          return data;
        } catch (error) {
          console.error('Error fetching sales data:', error);
          return null;
        }
      }

      // Function to update charts
      async function updateCharts(period) {
        try {
          const data = await fetchSalesData(period);
          if (!data || !data.labels || !data.sales) {
            console.error('Invalid data received:', data);
            return;
          }

          // Destroy existing charts if they exist
          if (lineChart) {
            lineChart.destroy();
            lineChart = null;
          }
          if (barChart) {
            barChart.destroy();
            barChart = null;
          }

          // Common chart options
          const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                callbacks: {
                  label: function (context) {
                    return '₱' + context.raw.toLocaleString('en-US', {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2
                    });
                  }
                }
              }
            }
          };

          // Create line chart
          lineChart = new Chart(salesChartCtx, {
            type: 'line',
            data: {
              labels: data.labels,
              datasets: [{
                label: 'Sales',
                data: data.sales,
                tension: 0.4,
                borderWidth: 2,
                borderColor: '#17a37f',
                backgroundColor: 'rgba(23, 163, 127, 0.1)',
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#17a37f'
              }]
            },
            options: {
              ...commonOptions,
              scales: {
                y: {
                  beginAtZero: true,
                  grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: false,
                    borderDash: [5, 5]
                  },
                  ticks: {
                    callback: function (value) {
                      return '₱' + value.toLocaleString('en-US');
                    }
                  }
                },
                x: {
                  grid: {
                    drawBorder: false,
                    display: false,
                    drawOnChartArea: false,
                    drawTicks: false
                  }
                }
              }
            }
          });

          // Create bar chart
          barChart = new Chart(salesBarChartCtx, {
            type: 'bar',
            data: {
              labels: data.labels,
              datasets: [{
                label: 'Sales',
                data: data.sales,
                backgroundColor: 'rgba(23, 163, 127, 0.8)',
                borderColor: '#17a37f',
                borderWidth: 1,
                borderRadius: 4,
                maxBarThickness: 40
              }]
            },
            options: {
              ...commonOptions,
              scales: {
                y: {
                  beginAtZero: true,
                  grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: false,
                    borderDash: [5, 5]
                  },
                  ticks: {
                    callback: function (value) {
                      return '₱' + value.toLocaleString('en-US');
                    }
                  }
                },
                x: {
                  grid: {
                    drawBorder: false,
                    display: false,
                    drawOnChartArea: false,
                    drawTicks: false
                  },
                  ticks: {
                    maxRotation: 45,
                    minRotation: 45,
                    font: {
                      size: 10
                    }
                  }
                }
              }
            }
          });

          console.log('Charts updated successfully');
        } catch (error) {
          console.error('Error updating charts:', error);
        }
      }

      // Add click handlers for period buttons
      document.querySelectorAll('[data-period]').forEach(button => {
        button.addEventListener('click', function () {
          // Update active state
          document.querySelectorAll('[data-period]').forEach(btn => {
            btn.classList.remove('active');
          });
          this.classList.add('active');

          // Update charts
          updateCharts(this.dataset.period);
        });
      });

      // Initialize with weekly data
      updateCharts('weekly');
    });
  </script>

</body>

</html>