<?php
session_start();
include('process/config.php');

if (!isset($_SESSION['email'])) {
  header('location: ../index.php');
  exit();
}

$reservation_id = intval($_GET['reservation_id']);
if (empty($reservation_id)) {
  header("Location: my_payment.php");
  exit();
}

if (isset($_SESSION['email'])) {
  $uemail = $_SESSION['email'];
  $sql_session = "SELECT * FROM users WHERE email = '$uemail'";
  $result_session = $conn->query($sql_session);

  if ($result_session->num_rows > 0) {
    $row_session = $result_session->fetch_assoc();
    $user_id = $row_session['user_id'];
    $full_name = htmlspecialchars($row_session['full_name']);
    $email = $row_session['email'];
    $profile = $row_session['profile'];
    $type = $row_session['user_type'];

    if ($type != 'customer') {
      header('location: ../index.php');
      exit();
    }

  } else {
    header('location: ../index.php');
    exit();
  }

}
?>
<?php
if (isset($_GET['gown_id']) && is_numeric($_GET['gown_id'])) {
  $gown_id = (int) $_GET['gown_id'];

  $sql = "SELECT * FROM gowns WHERE gown_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $gown_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

  }
}
?>
<?php


$sql = "SELECT r.reservation_id, r.gown_id, r.start_date, r.end_date, g.name, g.price FROM reservations r
         JOIN gowns g ON r.gown_id = g.gown_id WHERE reservation_id = '$reservation_id'";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
  while ($row = $res->fetch_assoc()) {
    $gown_name2 = htmlspecialchars($row['name']);
    $price2 = htmlspecialchars($row['price']);
    $date_to_pick2 = htmlspecialchars($row['start_date']);
    $date_to_return2 = htmlspecialchars($row['end_date']);
  }
}

$interest = $price2 * 0.03;
$total = $interest + $price2 + 400;

?>
<?php

$reservation_id = isset($_GET['reservation_id']) ? $_GET['reservation_id'] : null;

if ($reservation_id) {
  // Prepare the query to check for unpaid reservations
  $query = "SELECT * FROM reservations WHERE reservation_id = ? AND payment_status = 'unpaid'";

  if ($stmt = $conn->prepare($query)) {
    // Bind the reservation_id to the prepared statement
    $stmt->bind_param("s", $reservation_id);

    // Execute the query
    $stmt->execute();

    // Get the result of the query
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // If reservation_id exists and payment status is unpaid
      echo "Payment record exists. You can proceed.";
      // Add additional logic if needed
    } else {
      // Set session status messages for already paid reservations
      $_SESSION['status'] = "You have already paid.";
      $_SESSION['status_code'] = "info";
      $_SESSION['status_button'] = "Okay";

      // Redirect to the find_gown.php page
      header("Location: find_gown.php");
      exit;
    }

    // Close the result and statement
    $result->free();
    $stmt->close();
  } else {
    // Handle SQL preparation errors
    die("Error preparing the SQL statement: " . $conn->error);
  }
} else {
  // Redirect to find_gown.php if no reservation_id is provided
  header("Location: find_gown.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include('header.php'); ?>
<title>
  Boutique Gown | Payments
</title>
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
        <span class="ms-1 font-weight-bold"><?php echo $full_name ?></span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="find_gown.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-search text-primary text-sm opacity-10"></i> <!-- Replace with appropriate icon class -->
            </div>
            <span class="nav-link-text ms-1">Find Gown</span>
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link active" href="my_payment.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-dollar-sign text-success text-sm opacity-10"></i> <!-- Changed to history icon -->
            </div>
            <span class="nav-link-text ms-1">Payments</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contracts.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-file text-info text-sm opacity-10"></i> <!-- Changed to history icon -->
            </div>
            <span class="nav-link-text ms-1">Contracts</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="history.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-history text-warning text-sm opacity-10"></i> <!-- Changed to history icon -->
            </div>
            <span class="nav-link-text ms-1">Reservation History</span>
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
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Payment</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Gowns</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">
              <form method="POST" action="">
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Type here..." required>
                  <span class="input-group-text text-body" style="cursor: pointer;"
                    onclick="this.parentNode.parentNode.querySelector('form').submit()">

                  </span>
                </div>
              </form>
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
                $sql = "SELECT * FROM notifications WHERE user_id = '$user_id' ORDER BY date_time DESC";
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
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0 dropdown-toggle" id="dropdownMenuButton"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bookmark cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <?php
                $sql = "SELECT * FROM bookmarks WHERE user_id = '$user_id' ORDER BY date_time DESC";
                $res = $conn->query($sql);

                if ($res->num_rows > 0) {
                  while ($row = $res->fetch_assoc()) {
                    $formatted_date_time = date("F j, Y, g:i a", strtotime($row['date_time']));
                    ?>
                    <li class="mb-2">
                      <a class="dropdown-item border-radius-md" href="javascript:;">
                        <div class="d-flex py-1">
                          <div class="my-auto">
                            <img src="images/bookmark.png" class="avatar avatar-sm me-3">
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
                  echo '<li class="mb-2">No Bookmarks added.</li>';
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

            <div class="card-body px-4 pt-4 pb-4">
              <div class="row">
                <div class="col-lg-4 order-lg-2 mb-4">
                  <h5 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Your cart</span>
                    <span class="badge badge-primary badge-pill">3</span>
                  </h5>
                  <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                      <div>
                        <h6 class="my-0">Gown name</h6>
                        <small class="text-muted"><?php echo $gown_name2 ?></small>
                      </div>

                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                      <div>
                        <h6 class="my-0">Rental Price</h6>
                        <small class="text-muted"><?php echo $price2 ?> PHP</small>
                      </div>

                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                      <div>
                        <h6 class="my-0">Security Deposit</h6>
                        <small class="text-muted">400 PHP</small>
                      </div>

                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                      <div>
                        <h6 class="my-0">Date to Pick</h6>
                        <small class="text-muted"><?php echo $date_to_pick2 ?></small>
                      </div>

                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                      <div>
                        <h6 class="my-0">Date to Return</h6>
                        <small class="text-muted"><?php echo $date_to_return2 ?></small>
                      </div>

                    </li>
                    <li class="list-group-item d-flex justify-content-between active">
                      <div class="text-white">
                        <h6 class="my-0 text-white">Promo code</h6>
                        <small>Enter Code</small>
                      </div>
                      <span class="text-white">100 PHP off</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">

                      <span>Total (PHP)</span>
                      <strong><?php echo $total ?></strong>
                    </li>
                  </ul>

                  <form>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Promo code">
                      <button type="submit" class="input-group-text">Redeem</button>
                    </div>
                  </form>
                </div>
                <div class="col-lg-8 order-lg-1">
                  <form class="needs-validation" novalidate="" method="POST" action="process/add_payment.php"
                    enctype="multipart/form-data">
                    <h5 class="mb-3">Payment</h5>
                    <small class="text-muted">Please leave empty the Gcash Fields if you choose Cash on Pick Up payment
                      method</small>
                    <input type="hidden" name="reservation_id" value="<?php echo $reservation_id ?>">
                    <input type="hidden" name="amount" value="<?php echo $price2 ?>">
                    <div class="d-block my-3">
                      <div class="form-check custom-radio mb-2">
                        <input id="credit" name="paymentMethod" type="radio" class="form-check-input"
                          value="Cash on Pick Up" required="">
                        <label class="form-check-label" for="credit">Cash on Pick Up</label>
                      </div>
                      <div class="form-check custom-radio mb-2">
                        <input id="debit" name="paymentMethod" type="radio" class="form-check-input" value="Gcash"
                          required="">
                        <label class="form-check-label" for="debit">Gcash</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="cc-name" class="form-label">Gcash Name</label>
                        <input type="text" class="form-control" id="gcash_name" name="gcash_name" placeholder=""
                          required="">
                        <small class="text-muted">Full name as your Gcash Name</small>
                        <div class="invalid-feedback">
                          Name on card is required
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="cc-number" class="form-label">Gcash Number</label>
                        <input type="text" class="form-control" id="gcash_number" name="gcash_number" placeholder=""
                          required="">
                        <div class="invalid-feedback">
                          Gcash number is required
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="cc-expiration" class="form-label">Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control" id="cc-expiration" placeholder=""
                          required="">
                        <div class="invalid-feedback">
                          Transaction ID is required
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="cc-expiration" class="form-label">Proof of Payment</label>
                        <input type="file" class="form-control" name="proof">
                        <div class="invalid-feedback">
                          Proof of Payment is required
                        </div>
                      </div>
                    </div>
                    <hr class="mb-4">
                    <button class="btn btn-primary btn-lg btn-block" type="submit" name="pay">
                      <i class="fa fa-credit-card"></i> Continue to checkout
                    </button>
                  </form>
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