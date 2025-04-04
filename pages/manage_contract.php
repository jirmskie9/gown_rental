<?php
session_start();
include('process/config.php');

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

  <?php include('header.php');?>
  <title>
  Ging's Boutique | Contract
  </title>
  <style>
    /* Enhanced Contract Management Table Styles */
    .contract-container {
      background: linear-gradient(to bottom, #f8f9fa, #ffffff);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      transition: all 0.3s ease;
    }
    
    .contract-header {
      background: linear-gradient(45deg, #17a2b8, #5bc0de);
      color: white;
      padding: 20px;
      border-radius: 15px 15px 0 0;
      position: relative;
      overflow: hidden;
    }
    
    .contract-header:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: rgba(255, 255, 255, 0.2);
    }
    
    .contract-header h6 {
      font-size: 1.2rem;
      font-weight: 600;
      margin: 0;
      display: flex;
      align-items: center;
    }
    
    .contract-header h6 i {
      margin-right: 10px;
      font-size: 1.4rem;
    }
    
    .contract-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }
    
    .contract-table thead th {
      background-color: #f8f9fa;
      color: #344767;
      font-weight: 600;
      padding: 15px;
      border-bottom: 2px solid #e9ecef;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
    }
    
    .contract-table tbody tr {
      transition: all 0.3s ease;
    }
    
    .contract-table tbody tr:hover {
      background-color: rgba(23, 162, 184, 0.05);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .contract-table tbody td {
      padding: 15px;
      vertical-align: middle;
      border-bottom: 1px solid #e9ecef;
    }
    
    .gown-item {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .gown-image {
      width: 60px;
      height: 60px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }
    
    .gown-image:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
    
    .gown-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .gown-details {
      display: flex;
      flex-direction: column;
    }
    
    .gown-name {
      font-weight: 600;
      color: #344767;
      margin-bottom: 5px;
    }
    
    .customer-name {
      display: inline-block;
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      background: linear-gradient(45deg, #ffc107, #ffdb6d);
      color: #856404;
    }
    
    .date-cell {
      display: flex;
      flex-direction: column;
    }
    
    .date-label {
      font-size: 0.7rem;
      color: #67748e;
      margin-bottom: 3px;
    }
    
    .date-value {
      font-weight: 600;
      color: #344767;
    }
    
    .status-badge {
      display: inline-block;
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .status-badge.confirmed {
      background: linear-gradient(45deg, #28a745, #75b798);
      color: #ffffff;
    }
    
    .action-btn {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      border: none;
      background: linear-gradient(45deg, #17a2b8, #5bc0de);
      color: white;
      box-shadow: 0 4px 10px rgba(23, 162, 184, 0.3);
    }
    
    .action-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 15px rgba(23, 162, 184, 0.4);
    }
    
    .empty-state {
      padding: 40px 20px;
      text-align: center;
    }
    
    .empty-state img {
      max-width: 150px;
      margin-bottom: 20px;
      opacity: 0.7;
    }
    
    .empty-state p {
      font-size: 1rem;
      color: #67748e;
      margin: 0;
    }
    
    .edit-btn {
      display: inline-flex;
      align-items: center;
      padding: 8px 16px;
      border-radius: 8px;
      background: linear-gradient(45deg, #17a2b8, #5bc0de);
      color: white;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(23, 162, 184, 0.3);
      margin-top: 20px;
    }
    
    .edit-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 15px rgba(23, 162, 184, 0.4);
      color: white;
    }
    
    .edit-btn i {
      margin-right: 8px;
    }
    
    @media (max-width: 768px) {
      .contract-table thead th {
        font-size: 0.7rem;
        padding: 10px;
      }
      
      .contract-table tbody td {
        padding: 10px;
      }
      
      .gown-image {
        width: 50px;
        height: 50px;
      }
      
      .gown-name {
        font-size: 0.9rem;
      }
      
      .date-value {
        font-size: 0.8rem;
      }
    }
  </style>
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
          <a class="nav-link" href="manage_reservations.php">
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
          <a class="nav-link active" href="manage_contract.php">
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
          <h6 class="font-weight-bolder text-white mb-0">Contracts</h6>
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
                        $formatted_date_time = date("F j, Y, g:i a", strtotime($row['date_time']));
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
          <div class="card contract-container mb-4">
            <div class="card-header contract-header">
              <h6><i class="fas fa-file-contract me-2"></i> Manage Contract</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table contract-table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Gown Details</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Reserved By</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pick-up Date</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Return Date</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                      <th class="text-secondary opacity-7 text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php

            $sql = "SELECT r.reservation_id, r.customer_id, r.gown_id, r.start_date, r.end_date, r.total_price, r.status, g.name, g.main_image, u.full_name 
            FROM reservations r JOIN gowns g ON r.gown_id = g.gown_id JOIN users u ON r.customer_id = u.user_id WHERE r.status = 'confirmed'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    $gown_id = $row['gown_id'];
                    $main_image = 'uploads/' . basename($row['main_image']);
                    $name = $row['name'];
                    $start_date = $row['start_date'];
                    $end_date = $row['end_date'];
                    $status = $row['status'];
                    $reservation_id = $row['reservation_id'];
                    $full_name = $row['full_name'];
                    
                    // Format dates for better display
                    $formatted_start_date = date("M d, Y", strtotime($start_date));
                    $formatted_end_date = date("M d, Y", strtotime($end_date));
         
                    ?>
                    <tr>
                    <td>
                        <div class="gown-item">
                            <div class="gown-image">
                                <img src="<?php echo $main_image; ?>" alt="<?php echo htmlspecialchars($name); ?>">
                            </div>
                            <div class="gown-details">
                                <div class="gown-name"><?php echo htmlspecialchars($name); ?></div>
                                <div class="gown-id">ID: <?php echo $gown_id; ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="customer-name"><?php echo htmlspecialchars($full_name); ?></span>
                    </td>
                    <td>
                        <div class="date-cell">
                            <div class="date-label">Pick-up On</div>
                            <div class="date-value"><?php echo $formatted_start_date; ?></div>
                        </div>
                    </td>
                    <td>
                        <div class="date-cell">
                            <div class="date-label">Return On</div>
                            <div class="date-value"><?php echo $formatted_end_date; ?></div>
                        </div>
                    </td>
                    <td class="align-middle text-center">
                        <span class="status-badge confirmed"><?php echo htmlspecialchars($status); ?></span>
                    </td>
                    <td class="align-middle text-center">
                        <a class="action-btn" href="view_contract.php?reservation_id=<?php echo $row['reservation_id']; ?>" title="View Contract">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                    </tr>
                    <?php
                        }
                    } else {
                      ?>
                      <tr>
                        <td colspan="6" class="empty-state">
                            <img src="images/folder.png" class="img-fluid" alt="No Contracts">
                            <p>No Contracts Found</p>
                        </td>
                      </tr>
                      <?php
                    }


                    $conn->close();
                    ?>
                    
                  </tbody>
                  
                </table>
                
               
              </div>
              
            </div>
            
          </div>
          <br>
            <a class="edit-btn" href="edit_contract.php"><i class="fa fa-edit"></i> Edit Contract</a>
         
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
  <?php include('script.php'); ?>
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