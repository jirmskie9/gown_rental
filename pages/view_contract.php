<?php
session_start();
include('process/config.php');

if (!isset($_SESSION['email'])) {
  header('location: ../index.php');
  exit();
}
$reservation_id = intval($_GET['reservation_id']);

if (empty($reservation_id)) {
 
    header("Location: manage_contract.php");
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
  Ging's Boutique | View Contract
  </title>
  <style>
    @media print {
        /* Hide unnecessary elements */
        .navbar,
        .sidenav,
        .fixed-plugin,
        .card-header,
        .btn,
        .footer {
            display: none !important;
        }
        
        /* Reset background colors and shadows for better printing */
        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Poppins', Arial, sans-serif !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .card-body {
            padding: 0 !important;
        }
        
        /* Ensure text is black for better printing */
        .text-primary,
        .text-secondary,
        .text-info,
        .text-success,
        .text-warning,
        .text-danger {
            color: black !important;
        }
        
        /* Fix image sizing for print */
        img {
            max-width: 80px !important;
            height: auto !important;
            object-fit: contain !important;
        }
        
        .avatar {
            width: 40px !important;
            height: 40px !important;
        }
        
        .avatar-sm {
            width: 30px !important;
            height: 30px !important;
        }
        
        /* Ensure tables print properly */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 10px 0 !important;
        }
        
        th, td {
            border: 1px solid #ddd !important;
            padding: 6px !important;
            font-size: 12px !important;
        }
        
        /* Ensure signature image prints clearly */
        .signature-image {
            max-height: 60px !important;
            border: 1px solid #ddd !important;
        }
        
        /* Improve table layout */
        .table > :not(caption) > * > * {
            padding: 0.3rem !important;
        }
        
        .d-flex {
            display: flex !important;
            align-items: center !important;
        }
        
        .me-3 {
            margin-right: 0.5rem !important;
        }
        
        /* Adjust text sizes for print */
        .text-sm {
            font-size: 11px !important;
        }
        
        .text-xs {
            font-size: 10px !important;
        }
        
        /* Ensure proper spacing */
        .mb-0 {
            margin-bottom: 0 !important;
        }
        
        .mb-3 {
            margin-bottom: 0.5rem !important;
        }
        
        /* Enhanced contract styling */
        .contract-container {
            border: 1px solid #ddd !important;
            padding: 15px !important;
            margin: 0 auto !important;
            max-width: 100% !important;
        }
        
        .contract-header {
            text-align: center !important;
            margin-bottom: 15px !important;
            border-bottom: 2px solid #344767 !important;
            padding-bottom: 10px !important;
        }
        
        .contract-header h4 {
            font-size: 20px !important;
            font-weight: bold !important;
            margin-bottom: 5px !important;
        }
        
        .contract-section {
            margin-bottom: 15px !important;
        }
        
        .contract-section h5 {
            font-size: 16px !important;
            font-weight: bold !important;
            margin-bottom: 10px !important;
            border-bottom: 1px solid #eee !important;
            padding-bottom: 5px !important;
        }
        
        .contract-table {
            margin: 10px 0 !important;
            border: 1px solid #ddd !important;
        }
        
        .contract-table th {
            background-color: #f8f9fa !important;
            font-weight: bold !important;
        }
        
        .contract-signature {
            margin-top: 20px !important;
            border-top: 1px solid #eee !important;
            padding-top: 10px !important;
        }
        
        .contract-footer {
            margin-top: 20px !important;
            text-align: center !important;
            font-size: 10px !important;
            color: #666 !important;
        }
        
        /* Adjust spacing for one page */
        .row {
            margin-bottom: 10px !important;
        }
        
        .col-lg-4, .col-lg-8, .col-md-6, .col-md-12, .col-sm-12 {
            padding: 0 5px !important;
        }
        
        .card {
            margin-bottom: 10px !important;
        }
        
        .card-header {
            padding: 10px !important;
        }
        
        .card-body {
            padding: 10px !important;
        }
        
        /* Ensure content fits on one page */
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        
        /* Hide scrollbars */
        * {
            overflow: visible !important;
        }
    }
</style>
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
        <img src="images/dress.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Boutique Gowns</span>
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
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">View</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Contract</h6>
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
              <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New message</span> from Laur
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          13 minutes ago
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New album</span> by Travis Scott
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          1 day
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <title>credit-card</title>
                          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                              <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(453.000000, 454.000000)">
                                  <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                  <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                </g>
                              </g>
                            </g>
                          </g>
                        </svg>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          Payment successfully completed
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          2 days
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
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
            <?php
             $sql = "SELECT a.reservation_id, a.signature, a.date_signed, r.customer_id, r.gown_id, r.start_date, r.end_date, u.full_name, g.name, g.size, g.color, g.price FROM agreements a
             JOIN reservations r ON a.reservation_id = r.reservation_id JOIN users u ON r.customer_id = u.user_id JOIN gowns g ON r.gown_id = g.gown_id WHERE r.reservation_id = '$reservation_id'";
             $result = $conn->query($sql);
 
             if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    $full_name = htmlspecialchars($row['full_name']);
                    $date_to_pick = htmlspecialchars($row['start_date']);
                    $date_to_return = htmlspecialchars($row['end_date']);
                    $size = htmlspecialchars($row['size']);
                    $color = htmlspecialchars($row['color']);
                    $price = htmlspecialchars($row['price']);
                    $signature = htmlspecialchars($row['signature']);
                    $gown_name = htmlspecialchars($row['name']);

                    $interest = $price * 0.03;
                    $total = $interest + $price;
                }
            }
            ?>
           
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Contract Details</h5>
                        <p class="text-sm text-secondary mb-0">Reservation ID: <strong>#<?php echo $reservation_id ?></strong></p>
                    </div>
                    <div>
                        <span class="badge badge-sm bg-gradient-info">Rental Agreement</span>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card h-100">
                            <div class="card-header pb-0">
                                <h6 class="mb-0">Customer Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md me-3">
                                        <i class="fa fa-user text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-weight-bold mb-0"><?php echo $full_name ?></p>
                                        <p class="text-xs text-secondary mb-0">Customer</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card h-100">
                            <div class="card-header pb-0">
                                <h6 class="mb-0">Rental Period</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md me-3">
                                        <i class="fa fa-calendar-alt text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-weight-bold mb-0"><?php echo date('M d, Y', strtotime($date_to_pick)) ?> - <?php echo date('M d, Y', strtotime($date_to_return)) ?></p>
                                        <p class="text-xs text-secondary mb-0">Pickup to Return</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card h-100">
                            <div class="card-header pb-0">
                                <h6 class="mb-0">Gown Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md me-3">
                                        <i class="fa fa-female text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-weight-bold mb-0"><?php echo $gown_name ?></p>
                                        <p class="text-xs text-secondary mb-0"><?php echo $color ?>, Size: <?php echo $size ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6 class="mb-0">Contract Agreement</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="contract-container p-4" id="contractContent" style="border-radius: 10px; font-family: 'Poppins', sans-serif; background-color: #f8f9fa; box-shadow: 0px 0px 15px rgba(0,0,0,0.05);">
                                    <div class="text-center mb-4">
                                        <h4 class="text-primary font-weight-bold">Gown Rental Contract</h4>
                                        <div class="divider bg-gradient-primary my-3"></div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <p class="text-sm">
                                            This agreement is made between 
                                            <span class="font-weight-bold"><?php echo $full_name ?></span>, 
                                            hereinafter referred to as "The Renter," and 
                                            <span class="font-weight-bold">Ging's Boutique</span>, 
                                            referred to as "The Rental Service Provider," on 
                                            <span class="font-weight-bold"><?php echo date('F d, Y') ?></span>.
                                        </p>
                                    </div>

                                    <div class="mb-4">
                                        <h5 class="text-primary font-weight-bold">Rental Details</h5>
                                        <p class="text-sm">
                                            The Renter agrees to rent the gown described below for the period starting from 
                                            <span class="font-weight-bold"><?php echo date('F d, Y', strtotime($date_to_pick)) ?></span> 
                                            to 
                                            <span class="font-weight-bold"><?php echo date('F d, Y', strtotime($date_to_return)) ?></span>.
                                        </p>
                                        <div class="table-responsive">
                                            <table class="table align-items-center mb-0">
                                                <thead>
                                                    <tr>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Item</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Color</th>
                                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Size</th>
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
                                                                    <h6 class="mb-0 text-sm"><?php echo $gown_name ?></h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <p class="text-xs font-weight-bold mb-0"><?php echo $color ?></p>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-xs font-weight-bold"><?php echo $size ?></span>
                                                        </td>
                                                        <td class="align-middle text-end">
                                                            <span class="text-secondary text-xs font-weight-bold">₱<?php echo number_format($price, 2); ?></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h5 class="text-primary font-weight-bold">Terms & Conditions</h5>
                                        <ol class="text-sm">
                                            <li class="mb-2">The gown must be returned in the same condition it was received.</li>
                                            <li class="mb-2">A late return fee of <span class="font-weight-bold">₱100</span> per day will be applied for late returns.</li>
                                            <li class="mb-2">The Renter is responsible for any damage or loss to the gown.</li>
                                            <li class="mb-2">No alterations can be made to the gown without prior permission from the Rental Service Provider.</li>
                                        </ol>
                                    </div>

                                    <div class="mb-4">
                                        <h5 class="text-primary font-weight-bold">Payment & Deposit</h5>
                                        <p class="text-sm">
                                            The total rental fee is 
                                            <span class="font-weight-bold">₱<?php echo number_format($total, 2); ?></span>, 
                                            including a 3% transaction fee. A refundable security deposit of 
                                            <span class="font-weight-bold">₱400</span> 
                                            will be collected and returned upon inspection of the gown.
                                        </p>
                                    </div>

                                    <div class="mb-4">
                                        <h5 class="text-primary font-weight-bold">Signature</h5>
                                        <p class="text-sm">
                                            The Renter agrees to the terms and conditions by signing below.
                                        </p>
                                        <div class="mt-3">
                                            <img src="uploads/<?php echo $signature ?>" alt="Customer Signature" class="img-fluid" style="max-height: 80px;">
                                            <p class="text-sm font-weight-bold mt-2"><?php echo strtoupper($full_name); ?></p>
                                            <p class="text-xs text-secondary">Signature over Printed Name</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6 class="mb-0">Payment Summary</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-sm font-weight-bold">Rental Fee:</span>
                                    <span class="text-sm">₱<?php echo number_format($price, 2); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-sm font-weight-bold">Transaction Fee (3%):</span>
                                    <span class="text-sm">₱<?php echo number_format($interest, 2); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-sm font-weight-bold">Security Deposit:</span>
                                    <span class="text-sm">₱400.00</span>
                                </div>
                                <hr class="horizontal dark my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-sm font-weight-bold">Total Amount:</span>
                                    <span class="text-sm font-weight-bold">₱<?php echo number_format($total + 400, 2); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6 class="mb-0">Contract Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column gap-3">
                                    <button class="btn btn-primary w-100" type="button" onclick="printContract()">
                                        <i class="fa fa-print me-2"></i> Print Contract
                                    </button>
                                    <a href="manage_contract.php" class="btn btn-secondary w-100">
                                        <i class="fa fa-arrow-left me-2"></i> Back to Contracts
                                    </a>
                                </div>
                            </div>
                        </div>
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

<script>
function printContract() {
    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    
    // Get the contract content
    const contractContent = document.getElementById('contractContent').innerHTML;
    
    // Create the print-friendly HTML
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Gown Rental Contract - #${<?php echo $reservation_id; ?>}</title>
            <style>
                body {
                    font-family: 'Poppins', Arial, sans-serif;
                    line-height: 1.4;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                .contract-container {
                    border: 1px solid #ddd;
                    padding: 15px;
                    margin: 0 auto;
                    max-width: 100%;
                }
                .contract-header {
                    text-align: center;
                    margin-bottom: 15px;
                    border-bottom: 2px solid #344767;
                    padding-bottom: 10px;
                }
                .contract-header h4 {
                    font-size: 20px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .contract-section {
                    margin-bottom: 15px;
                }
                .contract-section h5 {
                    font-size: 16px;
                    font-weight: bold;
                    margin-bottom: 10px;
                    border-bottom: 1px solid #eee;
                    padding-bottom: 5px;
                }
                .contract-table {
                    margin: 10px 0;
                    border: 1px solid #ddd;
                }
                .contract-table th {
                    background-color: #f8f9fa;
                    font-weight: bold;
                }
                .contract-signature {
                    margin-top: 20px;
                    border-top: 1px solid #eee;
                    padding-top: 10px;
                }
                .contract-footer {
                    margin-top: 20px;
                    text-align: center;
                    font-size: 10px;
                    color: #666;
                }
                .divider {
                    border-top: 2px solid #344767;
                    margin: 15px 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 10px 0;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 6px;
                    text-align: left;
                    font-size: 12px;
                }
                th {
                    background-color: #f8f9fa;
                }
                .signature-section {
                    margin-top: 20px;
                }
                .signature-image {
                    max-height: 60px;
                    margin: 5px 0;
                }
                /* Fix image sizing */
                img {
                    max-width: 80px !important;
                    height: auto !important;
                    object-fit: contain !important;
                }
                .avatar {
                    width: 40px !important;
                    height: 40px !important;
                }
                .avatar-sm {
                    width: 30px !important;
                    height: 30px !important;
                }
                /* Adjust spacing for one page */
                .row {
                    margin-bottom: 10px;
                }
                .col-lg-4, .col-lg-8, .col-md-6, .col-md-12, .col-sm-12 {
                    padding: 0 5px;
                }
                .card {
                    margin-bottom: 10px;
                }
                .card-header {
                    padding: 10px;
                }
                .card-body {
                    padding: 10px;
                }
                @media print {
                    @page {
                        size: A4 portrait;
                        margin: 10mm;
                    }
                }
            </style>
        </head>
        <body>
            <div class="contract-container">
                <div class="contract-header">
                    <h4>Gown Rental Contract</h4>
                    <p>Contract #${<?php echo $reservation_id; ?>}</p>
                    <p>Date: ${new Date().toLocaleDateString()}</p>
                </div>
                ${contractContent}
                <div class="contract-footer">
                    <p>This document is a legally binding contract between the renter and Ging's Boutique.</p>
                    <p>Generated on ${new Date().toLocaleString()}</p>
                </div>
            </div>
        </body>
        </html>
    `;
    
    // Write the content to the new window
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Wait for images to load before printing
    printWindow.onload = function() {
        printWindow.print();
        printWindow.close();
    };
}
</script>

</body>

</html>