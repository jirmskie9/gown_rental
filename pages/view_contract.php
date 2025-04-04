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
  Ging's Boutique | View
  </title>
  <style>
    @media print {

        button {
            display: none; 
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
           
         
            <div class="card-body px-0 pt-0 pb-2" style="position: relative; background-image: url('background.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; padding: 20px;">
              <div class="contract-container" id="contractContent" style="padding: 40px; border-radius: 10px; font-family: Arial, sans-serif; background-color: rgba(255, 255, 255, 0.9); box-shadow: 0px 0px 15px rgba(0,0,0,0.1);">
                  <h4 style="text-align: center; color: #886CC0; font-weight: bold;">Gown Rental Contract</h4>
                  <p style="text-align: justify; color: #333;">
                      This agreement is made between 
                      <input type="text" name="renter_name" value="<?php echo $full_name ?>" style="border: none; border-bottom: 1px solid #333; width: 150px;" readOnly>, 
                      hereinafter referred to as "The Renter," and 
                      <input type="text" name="business_name" value="Ging's Boutique" style="border: none; border-bottom: 1px solid #333; width: 150px;" readOnly>, 
                      referred to as "The Rental Service Provider," on 
                      <input type="text" name="contract_date" value="07/09/2004" style="border: none; border-bottom: 1px solid #333;" readOnly>.
                  </p>

                  <h5 style="color: #886CC0; margin-top: 20px;">Rental Details</h5>
                  <p style="text-align: justify; color: #333;">
                      The Renter agrees to rent the gown described below for the period starting from 
                      <input type="date" name="pickup_date" value="<?php echo $date_to_pick ?>" style="border: none; border-bottom: 1px solid #333;" readOnly> 
                      to 
                      <input type="date" name="return_date" value="<?php echo $date_to_return ?>" style="border: none; border-bottom: 1px solid #333;" readOnly>.
                  </p>
                  <ul style="color: #333;">
                      <li><strong>Gown Name:</strong> <input type="text" name="gown_name" value="<?php echo $gown_name ?>" placeholder="Gown Name" style="border: none; border-bottom: 1px solid #333;" readOnly></li>
                      <li><strong>Size:</strong> <input type="text" name="gown_size" value="<?php echo $size ?>" placeholder="Size" style="border: none; border-bottom: 1px solid #333;" readOnly></li>
                      <li><strong>Color:</strong> <input type="text" name="gown_color" value="<?php echo $color ?>" placeholder="Color" style="border: none; border-bottom: 1px solid #333;" readOnly></li>
                      <li><strong>Rental Price:</strong> <input type="text" name="gown_price" value="<?php echo $price ?> PHP" placeholder="Price" style="border: none; border-bottom: 1px solid #333;" readOnly></li>
                  </ul>

                  <h5 style="color: #886CC0; margin-top: 20px;">Terms & Conditions</h5>
                  <ol style="color: #333;">
                      <li>The gown must be returned in the same condition it was received.</li>
                      <li>A late return fee of <input type="text" name="late_fee" value="100" placeholder="Fee Amount" style="border: none; border-bottom: 1px solid #333;" readOnly> per day will be applied for late returns.</li>
                      <li>The Renter is responsible for any damage or loss to the gown.</li>
                      <li>No alterations can be made to the gown without prior permission from the Rental Service Provider.</li>
                  </ol>

                  <h5 style="color: #886CC0; margin-top: 20px;">Payment & Deposit</h5>
                  <p style="text-align: justify; color: #333;">
                      The total rental fee is 
                      <input type="text" name="total_fee" placeholder="Total Rental Fee" value="<?php echo $total ?> PHP" style="border: none; border-bottom: 1px solid #333;" required>, 
                      including a 3% transaction fee. A refundable security deposit of 
                      <input type="text" name="deposit_amount" value="400 PHP" placeholder="Deposit Amount" style="border: none; border-bottom: 1px solid #333;" required> 
                      will be collected and returned upon inspection of the gown.
                  </p>

                  <h5 style="color: #886CC0; margin-top: 20px;">Signature</h5>
                  <p style="text-align: justify; color: #333;">
                      The Renter agrees to the terms and conditions by signing below.
                  </p>
                  <br>

                  <input type="hidden" name="reservation_id" value="<?php echo $reservation_id ?>">
                  <img src="uploads/<?php echo $signature ?>" alt="<?php echo $signature ?>" style="width: 100px; height: 80px; display;">
                  <p style="text-decoration: underline;"><b><?php echo strtoupper($full_name); ?></b></p>
                  <p>Signature over Printed Name</p>

                  
              </div>
              <div style="text-align: center; margin-top: 20px;">
                      <button class="btn btn-primary" type="button" onclick="printContract()"><i class="fa fa-print"></i> Print</button>
                      <a href="manage_contract.php" class="btn btn-block"><i class="fa fa-arrow-left"></i> Return</a>
                  </div>
          </div>

            <script>
                function printContract() {
                    var printContents = document.getElementById('contractContent').innerHTML;
                    var originalContents = document.body.innerHTML;

                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                    location.reload(); 
                }
            </script>

            </div>
        </div>
        <br>
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