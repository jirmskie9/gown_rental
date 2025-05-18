<?php
session_start();
include ('process/config.php');

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

  <?php include ('header.php'); ?>
  <title>
  Ging's Boutique | Customers
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
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
  </style>
  <script>
    let idleTime = 0;
    const idleInterval = 6000; // Check every second
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
  </script>
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#" target="_blank">
        <img src="images/dress.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Ging's Boutique</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="manage_gowns.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
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
          <a class="nav-link" href="manage_reservations.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-calendar-alt text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Reservations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="manage_customers.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-user text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Customers</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="manage_contract.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-file-contract text-secondary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Contract</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="payments.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-money-bill-wave text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Payments</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="logout.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
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
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Manage</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Customers</h6>
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
            <a href="javascript:;" class="nav-link text-white p-0 dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <?php
                $sql = "SELECT * FROM notifications WHERE type = 'admin' ORDER BY date_time DESC";
                $res = $conn->query($sql);

                if ($res->num_rows > 0) {
                  while ($row = $res->fetch_assoc()) {
                    $formatted_date_time = date('F j, Y, g:i a', strtotime($row['date_time']));
                    ?>
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="images/email.png" class="avatar avatar-sm me-3">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
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
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Manage Customers</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Reserved Gown</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Start Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">End Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php

                  $sql = 'SELECT u.user_id, u.full_name, u.profile, g.gown_id, g.main_image, g.name, 
                         r.start_date, r.end_date, r.status
                         FROM users u 
                         JOIN reservations r ON u.user_id = r.customer_id 
                         JOIN gowns g ON r.gown_id = g.gown_id
                         ORDER BY r.start_date DESC';

                  $result = $conn->query($sql);

                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $main_image = $row['profile'];
                      $gown = $row['name'];
                      $fname = $row['full_name'];
                      $start_date = date('M d, Y', strtotime($row['start_date']));
                      $end_date = date('M d, Y', strtotime($row['end_date']));
                      $status = $row['status'];
                      ?>
                    <tr>
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div>
                                <img src="<?php echo $main_image; ?>" class="avatar avatar-sm me-3" alt="<?php echo $main_image ?>">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm"><?php echo $fname ?></h6>
                                <p class="text-xs text-secondary mb-0"></p>
                            </div>
                        </div>
                    </td>
                    <td class="text-sm">
                        <span class="badge badge-sm bg-gradient-warning"><?php echo $gown ?></span>
                    </td>
                    <td class="text-sm">
                        <span class="text-secondary text-xs font-weight-bold"><?php echo $start_date ?></span>
                    </td>
                    <td class="text-sm">
                        <span class="text-secondary text-xs font-weight-bold"><?php echo $end_date ?></span>
                    </td>
                    <td class="text-sm">
                        <span class="badge badge-sm <?php 
                            echo $status == 'completed' ? 'bg-gradient-success' : 
                                ($status == 'pending' ? 'bg-gradient-warning' : 
                                ($status == 'canceled' ? 'bg-gradient-danger' : 'bg-gradient-info')); 
                        ?>">
                            <?php echo ucfirst($status) ?>
                        </span>
                    </td>
                    </tr>
                    <?php
                    }
                  } else {
                    echo "<tr><td colspan='5' class='text-center align-middle'><img src='images/folder.png' class='img-fluid' alt='Empty Image'><p class='text-danger'>No Customers Found</p></td></tr>";
                  }

                  $conn->close();
                  ?>
                    
                  </tbody>
                  
                </table>
                
               
              </div>
              
            </div>
            
          </div>
         
        </div>

      <?php include ('footer.php'); ?>
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
            <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
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
          <button class="btn bg-gradient-primary w-100 px-3 mb-2 active me-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
          <button class="btn bg-gradient-primary w-100 px-3 mb-2" data-class="bg-default" onclick="sidebarType(this)">Dark</button>
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
  <?php include ('script.php'); ?>
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

</body>

</html>