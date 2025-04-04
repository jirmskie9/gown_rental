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
  Ging's Boutique | Payments
  </title>
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
          <a class="nav-link" href="manage_contract.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-file-contract text-secondary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Contract</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="payments.php">
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
          <h6 class="font-weight-bolder text-white mb-0">Payments</h6>
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
      <!-- Payment Summary Cards -->
      <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Payments</p>
                    <?php
                    $sql_total = "SELECT COUNT(*) as total FROM payments";
                    $result_total = $conn->query($sql_total);
                    $row_total = $result_total->fetch_assoc();
                    ?>
                    <h5 class="font-weight-bolder mb-0">
                      <?php echo $row_total['total']; ?>
                    </h5>
                    <p class="mb-0 text-sm">
                      <span class="text-success text-sm font-weight-bolder">+3%</span>
                      since last month
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="fa fa-money-bill-wave text-lg opacity-10" aria-hidden="true"></i>
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
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Revenue</p>
                    <?php
                    $sql_revenue = "SELECT SUM(amount) as total FROM payments";
                    $result_revenue = $conn->query($sql_revenue);
                    $row_revenue = $result_revenue->fetch_assoc();
                    $total_revenue = $row_revenue['total'] ?? 0;
                    ?>
                    <h5 class="font-weight-bolder mb-0">
                      ₱<?php echo number_format($total_revenue, 2); ?>
                    </h5>
                    <p class="mb-0 text-sm">
                      <span class="text-success text-sm font-weight-bolder">+5%</span>
                      since last month
                    </p>
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
        
        
      </div>
      
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
              <h6 class="mb-0">Manage Payments</h6>
              <div class="d-flex">
                <div class="dropdown me-2">
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Filter
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#">All Payments</a></li>
                    <li><a class="dropdown-item" href="#">Completed</a></li>
                    <li><a class="dropdown-item" href="#">Pending</a></li>
                    <li><a class="dropdown-item" href="#">Failed</a></li>
                  </ul>
                </div>
                <div class="input-group input-group-sm" style="width: 250px;">
                  <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                  <input type="text" class="form-control" placeholder="Search payments...">
                </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Gown Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Renter Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Transaction ID</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php

            $sql = "SELECT p.payment_id, p.reservation_id, p.payment_method, p.transaction_id, p.proof, p.amount, p.payment_date, r.gown_id, r.customer_id, g.name, g.main_image, u.full_name FROM payments p
            JOIN reservations r ON p.reservation_id = r.reservation_id JOIN gowns g ON r.gown_id = g.gown_id JOIN users u ON r.customer_id = u.user_id ORDER BY p.payment_date DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    $full_name = $row['full_name'];
                    $main_image = 'uploads/' . basename($row['main_image']);
                    $name = $row['name'];
                    $transaction_id = $row['transaction_id'];
                    $amount = $row['amount'];
                    $status = $row['status'] ?? 'pending';
                    $date_paid = $row['payment_date'] ? date('M d, Y', strtotime($row['payment_date'])) : 'N/A';
                    
                    // Status badge class
                    $status_class = '';
                    if ($status == 'completed') {
                        $status_class = 'bg-gradient-success';
                    } elseif ($status == 'pending') {
                        $status_class = 'bg-gradient-warning';
                    } elseif ($status == 'failed') {
                        $status_class = 'bg-gradient-danger';
                    } else {
                        $status_class = 'bg-gradient-secondary';
                    }
                    ?>
                    <tr>
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div>
                                <img src="<?php echo $main_image; ?>" class="avatar avatar-sm me-3" alt="<?php echo htmlspecialchars($name); ?>">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($name); ?></h6>
                                <p class="text-xs text-secondary mb-0">ID: <?php echo $row['gown_id']; ?></p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($full_name); ?></h6>
                            <p class="text-xs text-secondary mb-0">ID: <?php echo $row['customer_id']; ?></p>
                        </div>
                    </td>
                    <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($transaction_id); ?></p>
                        <p class="text-xs text-secondary mb-0"><?php echo $row['payment_method']; ?></p>
                    </td>
                    <td>
                        <p class="text-xs font-weight-bold mb-0">₱<?php echo number_format($amount, 2); ?></p>
                    </td>
                    <td>
                        <span class="badge badge-sm <?php echo $status_class; ?>"><?php echo ucfirst($status); ?></span>
                    </td>
                    <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo $date_paid; ?></p>
                    </td>
                    <td class="align-middle">
                        <a class="btn btn-link text-dark px-3 mb-0" href="view_payments.php?payment_id=<?php echo $row['payment_id']; ?>">
                            <i class="fas fa-eye text-dark me-2"></i>View
                        </a>
                    </td>
                    </tr>
                    <?php
                        }
                    } else {
                      echo "<tr><td colspan='7' class='text-center align-middle py-4'><img src='images/folder.png' class='img-fluid mb-2' style='max-width: 100px;' alt='Empty Image'><p class='text-danger mb-0'>No Payment Found</p></td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer py-3">
              <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mb-0">
                  <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                  </li>
                  <li class="page-item active"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                  </li>
                </ul>
              </nav>
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