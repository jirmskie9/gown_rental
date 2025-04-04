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
$payment_id = intval($_GET['payment_id']);
?>
<!DOCTYPE html>
<html lang="en">

  <?php include('header.php');?>
  <title>
  Ging's Boutique | Reservations
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
    <div class="row">
        <div class="col-12">
        <div class="card mt-3">
        <?php

                    if (isset($_GET['payment_id'])) {
                        $payment_id = (int)$_GET['payment_id'];

                        $sql = "SELECT p.reservation_id, p.amount, p.payment_method, p.name AS gcash_name,
                        p.number, p.transaction_id, p.proof, p.payment_date, r.gown_id, r.payment_status, r.customer_id, g.name, g.price, g.color FROM payments p JOIN reservations r
                        ON p.reservation_id = r.reservation_id JOIN gowns g ON r.gown_id = g.gown_id WHERE payment_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $payment_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $payment_status = $row['payment_status'];
                            $reservation_id = $row['reservation_id'];
                            
                            // Status badge class
                            $status_class = '';
                            if ($payment_status == 'completed') {
                                $status_class = 'bg-gradient-success';
                            } elseif ($payment_status == 'pending') {
                                $status_class = 'bg-gradient-warning';
                            } elseif ($payment_status == 'failed') {
                                $status_class = 'bg-gradient-danger';
                            } else {
                                $status_class = 'bg-gradient-secondary';
                            }
                        }
                    }
                            ?>
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">Payment Details</h5>
                                    <p class="text-sm text-secondary mb-0">Reservation ID: <strong>00000<?php echo $payment_id?></strong></p>
                                </div>
                                <div>
                                    <span class="badge badge-sm <?php echo $status_class; ?>"><?php echo ucfirst(htmlspecialchars($row['payment_status']))?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-5">
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header pb-0">
                                                <h6 class="mb-0">Payment Information</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md me-3">
                                                        <i class="fa fa-user text-lg opacity-10" aria-hidden="true"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-weight-bold mb-0"><?php echo htmlspecialchars($row['gcash_name'])?></p>
                                                        <p class="text-xs text-secondary mb-0"><?php echo htmlspecialchars($row['number'])?></p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md me-3">
                                                        <i class="fa fa-receipt text-lg opacity-10" aria-hidden="true"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-weight-bold mb-0">Transaction ID</p>
                                                        <p class="text-xs text-secondary mb-0"><?php echo htmlspecialchars($row['transaction_id'])?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header pb-0">
                                                <h6 class="mb-0">Payment Method</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md me-3">
                                                        <i class="fa fa-credit-card text-lg opacity-10" aria-hidden="true"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-weight-bold mb-0"><?php echo htmlspecialchars($row['payment_method'])?></p>
                                                        <p class="text-xs text-secondary mb-0">Payment Date: <?php echo date('M d, Y', strtotime($row['payment_date']))?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header pb-0">
                                                <h6 class="mb-0">Payment Proof</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <img src="<?php echo htmlspecialchars(str_replace('../', '', $row['proof'])); ?>" alt="Payment Proof" class="img-fluid rounded mb-3" style="max-height: 150px;">
                                                <p class="text-sm font-weight-bold mb-0"><?php echo htmlspecialchars($row['name'])?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card mb-4">
                                            <div class="card-header pb-0">
                                                <h6 class="mb-0">Order Details</h6>
                                            </div>
                                            <div class="card-body px-0 pt-0 pb-2">
                                                <div class="table-responsive p-0">
                                                    <table class="table align-items-center mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Item</th>
                                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Color</th>
                                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                                                <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex px-2 py-1">
                                                                        <div>
                                                                            <img src="images/dress.png" class="avatar avatar-sm me-3" alt="gown">
                                                                        </div>
                                                                        <div class="d-flex flex-column justify-content-center">
                                                                            <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($row['name'])?></h6>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['color'])?></p>
                                                                </td>
                                                                <td class="align-middle text-center">
                                                                    <span class="text-secondary text-xs font-weight-bold">1</span>
                                                                </td>
                                                                <td class="align-middle text-end">
                                                                    <span class="text-secondary text-xs font-weight-bold">₱<?php echo number_format($row['price'], 2); ?></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="card">
                                            <div class="card-header pb-0">
                                                <h6 class="mb-0">Payment Summary</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span class="text-sm font-weight-bold">Subtotal:</span>
                                                    <span class="text-sm">₱<?php echo number_format($row['price'], 2); ?></span>
                                                </div>
                                                <?php
                                                $price = htmlspecialchars($row['price']);
                                                $transactionFee = $price * 0.03; 
                                                $totalPrice = $price + $transactionFee;
                                                $added = $totalPrice - $price;
                                                ?>
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span class="text-sm font-weight-bold">Transaction Fee (3%):</span>
                                                    <span class="text-sm">₱<?php echo number_format($added, 2); ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span class="text-sm font-weight-bold">Security Deposit:</span>
                                                    <span class="text-sm">₱<?php echo number_format($added + 400, 2); ?></span>
                                                </div>
                                                <hr class="horizontal dark my-2">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-sm font-weight-bold">Total:</span>
                                                    <span class="text-sm font-weight-bold">₱<?php echo number_format($totalPrice, 2); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="card">
                                            <div class="card-header pb-0">
                                                <h6 class="mb-0">Payment Actions</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex flex-column gap-3">
                                                    <form action="process/paid_payment.php" method="POST">
                                                        <input type="hidden" value="<?php echo htmlspecialchars($row['gown_id'])?>" name="gown_id">
                                                        <input type="hidden" value="<?php echo $reservation_id ?>" name="reservation_id">
                                                        <input type="hidden" value="<?php echo htmlspecialchars($row['customer_id'])?>" name="user_id">
                                                        <input type="hidden" value="<?php echo htmlspecialchars($row['name'])?>" name="name">
                                                        <button class="btn btn-success w-100" type="submit" name="conf">
                                                            <i class="fas fa-check me-2"></i> Mark as Paid
                                                        </button>
                                                    </form>
                                                    
                                                    <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#validModal">
                                                        <i class="fas fa-times me-2"></i> Reject Payment
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="validModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="imageModalLabel">Rejection Letter</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="process/reject_payment.php" method="POST">
                                            <input type="hidden" value="<?php echo htmlspecialchars($row['gown_id'])?>" name="gown_id">
                                            <input type="hidden" value="<?php echo $reservation_id ?>" name="reservation_id">
                                            <input type="hidden" value="<?php echo htmlspecialchars($row['customer_id'])?>" name="user_id">
                                            <input type="hidden" value="<?php echo htmlspecialchars($row['name'])?>" name="name">
                                            <div class="form-group">
                                                <label for="reason" class="form-label">Reason for Rejection</label>
                                                <textarea name="reason" id="reason" class="form-control" rows="4" placeholder="Please state your reason for rejecting this payment..."></textarea>
                                            </div>
                                            <div class="mt-4">
                                                <button class="btn btn-primary w-100" type="submit" name="save">Submit Rejection</button>
                                            </div>
                                        </form>
                                    </div>
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