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
      
  }else{
    header('location: ../index.php');
    exit();
  }

}
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
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Edit</li>
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
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h5 class="mb-0">Edit Contract</h5>
                  <p class="text-sm text-secondary mb-0">Create or modify a gown rental contract</p>
                </div>
                <div>
                  <span class="badge badge-sm bg-gradient-info">Contract Form</span>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12">
                  <div class="card mb-4">
                    <div class="card-header pb-0">
                      <h6 class="mb-0">Contract Details</h6>
                    </div>
                    <div class="card-body">
                      <div class="contract-container p-4" style="border-radius: 10px; font-family: 'Poppins', sans-serif; background-color: #f8f9fa; box-shadow: 0px 0px 15px rgba(0,0,0,0.05);">
                        <div class="text-center mb-4">
                          <h4 class="text-primary font-weight-bold">Gown Rental Contract</h4>
                          <div class="divider bg-gradient-primary my-3"></div>
                        </div>
                        
                        <form action="process/submit_contract.php" method="POST">
                          <div class="mb-4">
                            <p class="text-sm">
                              This agreement is made between 
                              <div class="input-group input-group-outline my-2">
                                <input type="text" name="renter_name" class="form-control" placeholder="Renter's Name" required>
                              </div>
                              hereinafter referred to as "The Renter," and 
                              <div class="input-group input-group-outline my-2">
                                <input type="text" name="business_name" class="form-control" placeholder="Your Business Name" required>
                              </div>
                              referred to as "The Rental Service Provider," on 
                              <div class="input-group input-group-outline my-2">
                                <input type="date" name="contract_date" class="form-control" required>
                              </div>
                            </p>
                          </div>

                          <div class="mb-4">
                            <h5 class="text-primary font-weight-bold">Rental Details</h5>
                            <p class="text-sm">
                              The Renter agrees to rent the gown described below for the period starting from 
                              <div class="input-group input-group-outline my-2">
                                <input type="date" name="pickup_date" class="form-control" required>
                              </div>
                              to 
                              <div class="input-group input-group-outline my-2">
                                <input type="date" name="return_date" class="form-control" required>
                              </div>
                            </p>
                            
                            <div class="row mt-3">
                              <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                  <label class="form-label">Gown Name</label>
                                  <input type="text" name="gown_name" class="form-control" placeholder="Gown Name" required>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                  <label class="form-label">Size</label>
                                  <input type="text" name="gown_size" class="form-control" placeholder="Size" required>
                                </div>
                              </div>
                            </div>
                            
                            <div class="row">
                              <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                  <label class="form-label">Color</label>
                                  <input type="text" name="gown_color" class="form-control" placeholder="Color" required>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                  <label class="form-label">Rental Price</label>
                                  <input type="text" name="gown_price" class="form-control" placeholder="Price" required>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="mb-4">
                            <h5 class="text-primary font-weight-bold">Terms & Conditions</h5>
                            <ol class="text-sm">
                              <li class="mb-2">The gown must be returned in the same condition it was received.</li>
                              <li class="mb-2">A late return fee of 
                                <div class="input-group input-group-outline my-2">
                                  <input type="text" name="late_fee" class="form-control" placeholder="Fee Amount" required>
                                </div>
                                per day will be applied for late returns.
                              </li>
                              <li class="mb-2">The Renter is responsible for any damage or loss to the gown.</li>
                              <li class="mb-2">No alterations can be made to the gown without prior permission from the Rental Service Provider.</li>
                            </ol>
                          </div>

                          <div class="mb-4">
                            <h5 class="text-primary font-weight-bold">Payment & Deposit</h5>
                            <p class="text-sm">
                              The total rental fee is 
                              <div class="input-group input-group-outline my-2">
                                <input type="text" name="total_fee" class="form-control" placeholder="Total Rental Fee" required>
                              </div>
                              including a 3% transaction fee. A refundable security deposit of 
                              <div class="input-group input-group-outline my-2">
                                <input type="text" name="deposit_amount" class="form-control" placeholder="Deposit Amount" required>
                              </div>
                              will be collected and returned upon inspection of the gown.
                            </p>
                          </div>
                          
                          <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-primary">
                              <i class="fa fa-save me-2"></i> Save Contract
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="col-lg-4 col-md-12 col-sm-12">
                  <div class="card mb-4">
                    <div class="card-header pb-0">
                      <h6 class="mb-0">Contract Preview</h6>
                    </div>
                    <div class="card-body">
                      <div class="text-center mb-3">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md me-3 mx-auto">
                          <i class="fa fa-file-contract text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                        <h5 class="mt-3">Contract Preview</h5>
                        <p class="text-sm text-secondary">This is how your contract will appear to customers</p>
                      </div>
                      
                      <div class="preview-container p-3" style="border-radius: 10px; background-color: #f8f9fa; border: 1px dashed #dee2e6;">
                        <div class="text-center mb-3">
                          <h6 class="text-primary font-weight-bold">Gown Rental Contract</h6>
                          <div class="divider bg-gradient-primary my-2"></div>
                        </div>
                        
                        <div class="mb-2">
                          <p class="text-xs">
                            This agreement is made between <span class="text-primary">[Renter's Name]</span>, 
                            hereinafter referred to as "The Renter," and 
                            <span class="text-primary">[Business Name]</span>, 
                            referred to as "The Rental Service Provider," on 
                            <span class="text-primary">[Contract Date]</span>.
                          </p>
                        </div>
                        
                        <div class="mb-2">
                          <h6 class="text-primary font-weight-bold">Rental Details</h6>
                          <p class="text-xs">
                            The Renter agrees to rent the gown described below for the period starting from 
                            <span class="text-primary">[Pickup Date]</span> 
                            to 
                            <span class="text-primary">[Return Date]</span>.
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
                                        <h6 class="mb-0 text-xs">[Gown Name]</h6>
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <p class="text-xs font-weight-bold mb-0">[Color]</p>
                                  </td>
                                  <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">[Size]</span>
                                  </td>
                                  <td class="align-middle text-end">
                                    <span class="text-secondary text-xs font-weight-bold">₱[Price]</span>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="card">
                    <div class="card-header pb-0">
                      <h6 class="mb-0">Contract Actions</h6>
                    </div>
                    <div class="card-body">
                      <div class="d-flex flex-column gap-3">
                        <a href="manage_contract.php" class="btn btn-secondary w-100">
                          <i class="fa fa-arrow-left me-2"></i> Back to Contracts
                        </a>
                        <button type="button" class="btn btn-info w-100" onclick="window.print()">
                          <i class="fa fa-print me-2"></i> Print Preview
                        </button>
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

</body>

</html>