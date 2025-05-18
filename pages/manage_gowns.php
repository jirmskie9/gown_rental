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

// Add pagination logic at the top of the file after session checks
$items_per_page = 5; // Number of items to show per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1

// Get total number of gowns
$total_query = "SELECT COUNT(*) as count FROM gowns";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_items = $total_row['count'];
$total_pages = ceil($total_items / $items_per_page);

// Calculate offset
$offset = ($page - 1) * $items_per_page;

// Modify your gowns query to include LIMIT and OFFSET
$sql = "SELECT * FROM gowns ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

  <?php include ('header.php'); ?>
  <title>
  Ging's Boutique | Gowns
  </title>
  <style>
    /* Enhanced Gown Management Table Styles */
    .gown-container {
      background: linear-gradient(to bottom, #f8f9fa, #ffffff);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      margin-bottom: 1rem;
      height: calc(100vh - 200px); /* Adjust height to fit viewport */
      display: flex;
      flex-direction: column;
    }
    
    .gown-header {
      background: linear-gradient(45deg, #ffc107, #ffdb6d);
      color: #856404;
      padding: 15px 20px;
      border-radius: 15px 15px 0 0;
      position: relative;
      flex-shrink: 0;
    }
    
    .card-body {
      flex: 1;
      overflow: hidden;
      padding: 0 !important;
    }
    
    .table-responsive {
      height: 100%;
      overflow: hidden;
    }
    
    .gown-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin-bottom: 0;
    }
    
    .gown-table thead th {
      background-color: #f8f9fa;
      color: #344767;
      font-weight: 600;
      padding: 12px 15px;
      border-bottom: 2px solid #e9ecef;
      text-transform: uppercase;
      font-size: 0.7rem;
      letter-spacing: 0.5px;
      white-space: nowrap;
      position: sticky;
      top: 0;
      z-index: 1;
    }
    
    .gown-table tbody {
      display: block;
      height: calc(100% - 40px);
      overflow-y: auto;
    }
    
    .gown-table thead,
    .gown-table tbody tr {
      display: table;
      width: 100%;
      table-layout: fixed;
    }
    
    .gown-table tbody tr {
      transition: all 0.2s ease;
    }
    
    .gown-table tbody tr:hover {
      background-color: rgba(255, 193, 7, 0.05);
    }
    
    .gown-table tbody td {
      padding: 10px 15px;
      vertical-align: middle;
      border-bottom: 1px solid #e9ecef;
      font-size: 0.85rem;
    }
    
    .gown-item {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .gown-image {
      width: 40px;
      height: 40px;
      border-radius: 8px;
      overflow: hidden;
      flex-shrink: 0;
    }
    
    .gown-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .gown-details {
      min-width: 0;
    }
    
    .gown-name {
      font-weight: 600;
      margin-bottom: 2px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    .gown-color {
      color: #6c757d;
      font-size: 0.8rem;
    }
    
    .price-tag {
      font-weight: 600;
      color: #2d3436;
      white-space: nowrap;
    }
    
    .status-badge {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: capitalize;
    }
    
    .status-badge.available {
      background-color: #d4edda;
      color: #155724;
    }
    
    .status-badge.unavailable {
      background-color: #f8d7da;
      color: #721c24;
    }
    
    .date-added {
      font-size: 0.8rem;
    }
    
    .date-label {
      color: #6c757d;
      font-size: 0.7rem;
      margin-bottom: 2px;
    }
    
    .date-value {
      font-weight: 500;
    }
    
    .action-buttons {
      display: flex;
      gap: 8px;
      justify-content: center;
    }
    
    .action-btn {
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      color: #fff;
      transition: all 0.2s ease;
    }
    
    .action-btn.edit {
      background-color: #17a2b8;
    }
    
    .action-btn.delete {
      background-color: #dc3545;
    }
    
    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    /* Column widths */
    .gown-table th:nth-child(1),
    .gown-table td:nth-child(1) { width: 35%; }
    .gown-table th:nth-child(2),
    .gown-table td:nth-child(2) { width: 15%; }
    .gown-table th:nth-child(3),
    .gown-table td:nth-child(3) { width: 15%; }
    .gown-table th:nth-child(4),
    .gown-table td:nth-child(4) { width: 20%; }
    .gown-table th:nth-child(5),
    .gown-table td:nth-child(5) { width: 15%; }
    
    /* Add Gown Button */
    .add-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      background: linear-gradient(45deg, #ffc107, #ffdb6d);
      color: #856404;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
    }
    
    .add-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(255, 193, 7, 0.3);
      color: #856404;
    }
    
    .add-btn i {
      font-size: 1.1rem;
    }
    
    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 2rem !important;
    }
    
    .empty-state img {
      max-width: 150px;
      margin-bottom: 1rem;
      opacity: 0.7;
    }
    
    .empty-state p {
      color: #6c757d;
      margin: 0;
    }

    /* Pagination Styles */
    .pagination-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 0;
      margin-top: 1rem;
    }

    .pagination-info {
      color: #6c757d;
      font-size: 0.9rem;
    }

    .pagination {
      margin: 0;
      display: flex;
      gap: 0.5rem;
    }

    .pagination .page-item .page-link {
      padding: 0.5rem 0.75rem;
      border-radius: 0.375rem;
      color: #344767;
      border: 1px solid #e9ecef;
      background-color: #fff;
      transition: all 0.2s ease;
    }

    .pagination .page-item.active .page-link {
      background: linear-gradient(45deg, #ffc107, #ffdb6d);
      border-color: #ffc107;
      color: #856404;
    }

    .pagination .page-item:not(.active) .page-link:hover {
      background-color: #f8f9fa;
      border-color: #dee2e6;
    }

    .pagination .page-item.disabled .page-link {
      color: #6c757d;
      pointer-events: none;
      background-color: #fff;
      border-color: #dee2e6;
    }
  </style>
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
    const idleInterval = 1000; // Check every second
    const idleTimeout = 60; // Logout after 10 seconds of inactivity
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
          <a class="nav-link active" href="manage_gowns.php">
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
          <a class="nav-link " href="manage_reservations.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-calendar-alt text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Reservations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="manage_customers.php">
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
            <li class="breadcrumb-item text-sm text-white active" aria-current="page"?>Manage</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Gown</h6>
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
          <div class="card gown-container">
            <div class="card-header gown-header">
              <h6><i class="fa fa-female me-2"></i> Manage Gowns</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table gown-table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th>Gown Details</th>
                      <th>Price</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Date Added</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php

                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $gown_id = $row['gown_id'];
                      $main_image = 'uploads/' . basename($row['main_image']);
                      $name = $row['name'];
                      $color = $row['color'];
                      $price = $row['price'];
                      $availability_status = $row['availability_status'];
                      $created_at = $row['created_at'];

                      // Format date for better display
                      $formatted_date = date('M d, Y', strtotime($created_at));
                      ?>
                    <tr>
                    <td>
                        <div class="gown-item">
                            <div class="gown-image">
                                <img src="<?php echo $main_image; ?>" alt="<?php echo htmlspecialchars($name); ?>">
                            </div>
                            <div class="gown-details">
                                <div class="gown-name"><?php echo htmlspecialchars($name); ?></div>
                                <div class="gown-color"><?php echo htmlspecialchars($color); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="price-tag">
                            <i class="fas fa-tag"></i> <?php echo htmlspecialchars($price); ?> PHP
                        </div>
                    </td>
                    <td class="align-middle text-center">
                        <span class="status-badge <?php echo strtolower($availability_status) == 'available' ? 'available' : 'unavailable'; ?>">
                            <?php echo htmlspecialchars($availability_status); ?>
                        </span>
                    </td>
                    <td class="align-middle text-center">
                        <div class="date-added">
                            <div class="date-label">Added On</div>
                            <div class="date-value"><?php echo $formatted_date; ?></div>
                        </div>
                    </td>
                    <td class="align-middle text-center">
                        <div class="action-buttons">
                            <a class="action-btn edit" href="edit_gown.php?gown_id=<?php echo $row['gown_id']; ?>" title="Edit Gown">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a class="action-btn delete" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" onclick="setPropertyId('<?php echo $gown_id; ?>')" title="Delete Gown">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                    </tr>
                    <?php
                    }
                  } else {
                    ?>
                      <tr>
                        <td colspan="5" class="empty-state">
                            <img src="images/empty.png" class="img-fluid" alt="No Gowns">
                            <p>No gowns found</p>
                        </td>
                      </tr>
                      <?php
                  }

                  $conn->close();
                  ?>
                    
                  </tbody>
                  
                </table>
                
                <!-- Add Pagination -->
                <div class="pagination-container">
                    <div class="pagination-info">
                        Showing <?php echo min($offset + 1, $total_items); ?> to <?php echo min($offset + $items_per_page, $total_items); ?> of <?php echo $total_items; ?> entries
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <!-- Previous Page -->
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            
                            <!-- Page Numbers -->
                            <?php
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);
                            
                            if ($start_page > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                                if ($start_page > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }
                            
                            for ($i = $start_page; $i <= $end_page; $i++) {
                                echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '">';
                                echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
                                echo '</li>';
                            }
                            
                            if ($end_page < $total_pages) {
                                if ($end_page < $total_pages - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                                echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '">' . $total_pages . '</a></li>';
                            }
                            ?>
                            
                            <!-- Next Page -->
                            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
               
              </div>
              
            </div>
            
          </div>
          <br>
          <a href = "add_gown.php" class = "add-btn"><i class = "fa fa-plus"></i> Add Gown</a>
        </div>
        <script>
    function setPropertyId(propertyId) {
        document.getElementById('gown_id').value = propertyId;
    }

    function deleteGown() {
        const gownId = document.getElementById('gown_id').value;
      
        console.log("Deleting gown with ID: " + gownId);
   
    }
    </script>
      
      
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
  <!-- Delete Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <p>Enter Password to Delete</p>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <form action="process/delete_gown.php" method="POST">
              <input type="hidden" id="gown_id" name="gown_id" class="form-control" readonly>
              <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
              <input type="password" name="password" class="form-control" placeholder="Enter your password">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="delete" class="btn btn-danger" onclick="deleteGown()">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>

</html>