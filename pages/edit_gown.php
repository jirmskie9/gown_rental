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
$gown_id = intval($_GET['gown_id']);
?>

<!DOCTYPE html>
<html lang="en">

  <?php include('header.php');?>
  <title>
  Ging's Boutique | Edit Gown
  </title>
  <style>
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(0, 0, 0, 0.2);
    }
    .card-header {
      border-radius: 15px 15px 0 0 !important;
      background: linear-gradient(310deg, #5e72e4 0%, #825ee4 100%);
      color: white;
    }
    .form-control {
      border-radius: 8px;
      padding: 0.75rem 1rem;
      border: 1px solid #e9ecef;
      transition: all 0.2s ease;
    }
    .form-control:focus {
      border-color: #5e72e4;
      box-shadow: 0 3px 9px rgba(50, 50, 9, 0), 3px 4px 8px rgba(94, 114, 228, 0.1);
    }
    .form-control-label {
      font-weight: 600;
      color: #344767;
    }
    .btn {
      border-radius: 8px;
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
      transition: all 0.2s ease;
    }
    .btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
    }
    .btn-primary {
      background: linear-gradient(310deg, #5e72e4 0%, #825ee4 100%);
      border: none;
    }
    .btn-danger {
      background: linear-gradient(310deg, #f5365c 0%, #f56036 100%);
      border: none;
    }
    .btn-success {
      background: linear-gradient(310deg, #2dce89 0%, #2dcecc 100%);
      border: none;
    }
    .section-title {
      font-size: 1rem;
      font-weight: 600;
      color: #344767;
      margin-bottom: 1.5rem;
      position: relative;
      padding-bottom: 0.5rem;
    }
    .section-title:after {
      content: '';
      position: absolute;
      left: 0;
      bottom: 0;
      height: 3px;
      width: 50px;
      background: linear-gradient(310deg, #5e72e4 0%, #825ee4 100%);
      border-radius: 3px;
    }
    .file-input-wrapper {
      position: relative;
      margin-bottom: 1.5rem;
    }
    .file-input-wrapper .form-control {
      padding-right: 30px;
    }
    .file-input-wrapper:after {
      content: '\f093';
      font-family: 'Font Awesome 5 Free';
      font-weight: 900;
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #5e72e4;
      pointer-events: none;
    }
    .gown-status {
      display: inline-block;
      padding: 0.5rem 1rem;
      border-radius: 50px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .status-available {
      background-color: rgba(45, 206, 137, 0.1);
      color: #2dce89;
    }
    .status-maintenance {
      background-color: rgba(251, 99, 67, 0.1);
      color: #fb6343;
    }
    .status-rented {
      background-color: rgba(251, 207, 51, 0.1);
      color: #fbcf33;
    }
    .action-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: center;
    }
    .action-buttons .btn {
      flex: 1;
      min-width: 150px;
    }
    .gown-info-card {
      background: linear-gradient(310deg, #5e72e4 0%, #825ee4 100%);
      color: white;
      border-radius: 15px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .gown-info-card h5 {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    .gown-info-card p {
      margin-bottom: 0.25rem;
      opacity: 0.8;
    }
    .gown-info-card .info-label {
      font-weight: 600;
      opacity: 0.9;
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
    <div class="col-md-8">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Edit Gown</h5>
                <a href="manage_gowns.php" class="btn btn-sm btn-outline-light">
                  <i class="fa fa-arrow-left me-1"></i> Back to Gowns
                </a>
              </div>
            </div>
            <?php 
            $sql2 = "SELECT * FROM gowns WHERE gown_id = '$gown_id'";
            $res = $conn->query($sql2);

            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                
                    $gown_name = htmlspecialchars($row['name']);
                    $size = htmlspecialchars($row['size']);
                    $color = htmlspecialchars($row['color']);
                    $price = htmlspecialchars($row['price']);
                    $description = htmlspecialchars($row['description']);
                    $main_image = htmlspecialchars($row['main_image']);
                    $status = htmlspecialchars($row['availability_status']);
                }
            }
            ?>
            <div class="card-body">
              <div class="gown-info-card">
                <h5><?php echo $gown_name; ?></h5>
                <p><span class="info-label">ID:</span> #<?php echo $gown_id; ?></p>
                <p><span class="info-label">Status:</span> 
                  <?php if($status == 'available'): ?>
                    <span class="gown-status status-available">Available</span>
                  <?php elseif($status == 'maintenance'): ?>
                    <span class="gown-status status-maintenance">Under Maintenance</span>
                  <?php else: ?>
                    <span class="gown-status status-rented">Rented</span>
                  <?php endif; ?>
                </p>
              </div>
              
              <p class="section-title">Gown Information</p>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <form method = "POST" action="process/update_gown.php" enctype="multipart/form-data">
                    <input type="hidden" name="gown_id" value="<?php echo htmlspecialchars($gown_id); ?>">

                    <label for="example-text-input" class="form-control-label">Gown Name</label>
                    <input class="form-control" type="text" name = "gown_name" value = "<?php echo $gown_name?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Gown Size</label>
                    <select name="gown_size" id="" class = "form-control">
                      <option value="">Small</option>
                      <option value="">Medium</option>
                      <option value="">Large</option>
                      <option value="">Extra Large</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Color</label>
                    <input class="form-control" type="text" name = "gown_color" value = "<?php echo $color?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Price</label>
                    <div class="input-group">
                      <span class="input-group-text">₱</span>
                      <input class="form-control" type="number" name = "gown_price" value = "<?php echo $price?>">
                    </div>
                  </div>
                </div>
              </div>
              <hr class="horizontal dark">
              <p class="section-title">Images</p>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Main Image</label>
                    <div class="file-input-wrapper">
                      <input type="file" class = "form-control" name = "gown_main">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label" >Gown Image 1</label>
                    <div class="file-input-wrapper">
                      <input type="file" class = "form-control" name = "img1">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Gown Image 2</label>
                    <div class="file-input-wrapper">
                      <input type="file" class = "form-control" name = "img2">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Gown Image 3</label>
                    <div class="file-input-wrapper">
                      <input type="file" class = "form-control" name = "img3">
                    </div>
                  </div>
                </div>
              </div>
              <hr class="horizontal dark">
              <p class="section-title">Description</p>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">About me</label>
                    <textarea name="description" id="" class = "form-control" style = "height: 100px;"><?php echo $description?></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-center">
              <div class="action-buttons">
                <form action="return_gown.php" method="POST" style="display: inline;">
                  <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>">
                  <input type="hidden" name="gown_id" value="<?php echo $gown_id; ?>">
                  <button type="submit" name="save_gown" class="btn btn-primary me-2">
                    <i class="fa fa-save me-2"></i> Save Gown
                  </button>
                </form>
                
                <form action="process/maintenance_gown.php" method="POST" style="display: inline;">
                  <input type="hidden" name="gown_id" value="<?php echo $gown_id; ?>"> <!-- Include gown_id for maintenance action -->
                  <button type="submit" name="maintenance_gown" class="btn btn-danger">
                    <i class="fa fa-wrench me-2"></i> Set Under Maintenance
                  </button>
                </form>
                <form action="process/fix_gown.php" method="POST" style="display: inline;">
                  <input type="hidden" name="gown_id" value="<?php echo $gown_id; ?>"> <!-- Include gown_id for maintenance action -->
                  <button type="submit" name="fix_gown" class="btn btn-success">
                    <i class="fa fa-check me-2"></i> Fix Gown
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-header pb-0">
              <h5 class="mb-0">Gown Preview</h5>
            </div>
            <div class="card-body">
              <div class="text-center">
                <?php if(!empty($main_image) && file_exists("../uploads/gowns/".$main_image)): ?>
                  <img src="../uploads/gowns/<?php echo $main_image; ?>" alt="Gown Preview" class="img-fluid rounded" style="max-height: 300px; object-fit: contain;">
                <?php else: ?>
                  <div class="text-center p-5">
                    <i class="fa fa-female fa-5x mb-3 text-secondary"></i>
                    <p class="text-secondary">No image available</p>
                  </div>
                <?php endif; ?>
              </div>
              
              <div class="mt-4">
                <h6 class="text-uppercase text-sm font-weight-bold">Quick Stats</h6>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Status</span>
                    <?php if($status == 'available'): ?>
                      <span class="gown-status status-available">Available</span>
                    <?php elseif($status == 'maintenance'): ?>
                      <span class="gown-status status-maintenance">Under Maintenance</span>
                    <?php else: ?>
                      <span class="gown-status status-rented">Rented</span>
                    <?php endif; ?>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Size</span>
                    <span class="font-weight-bold"><?php echo $size; ?></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Color</span>
                    <span class="font-weight-bold"><?php echo $color; ?></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Price</span>
                    <span class="font-weight-bold">₱<?php echo number_format($price, 2); ?></span>
                  </li>
                </ul>
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