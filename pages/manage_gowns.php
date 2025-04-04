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
  Ging's Boutique | Gowns
  </title>
  <style>
    /* Enhanced Gown Management Table Styles */
    .gown-container {
      background: linear-gradient(to bottom, #f8f9fa, #ffffff);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      transition: all 0.3s ease;
    }
    
    .gown-header {
      background: linear-gradient(45deg, #ffc107, #ffdb6d);
      color: #856404;
      padding: 20px;
      border-radius: 15px 15px 0 0;
      position: relative;
      overflow: hidden;
    }
    
    .gown-header:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: rgba(133, 100, 4, 0.2);
    }
    
    .gown-header h6 {
      font-size: 1.2rem;
      font-weight: 600;
      margin: 0;
      display: flex;
      align-items: center;
    }
    
    .gown-header h6 i {
      margin-right: 10px;
      font-size: 1.4rem;
    }
    
    .gown-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }
    
    .gown-table thead th {
      background-color: #f8f9fa;
      color: #344767;
      font-weight: 600;
      padding: 15px;
      border-bottom: 2px solid #e9ecef;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
    }
    
    .gown-table tbody tr {
      transition: all 0.3s ease;
    }
    
    .gown-table tbody tr:hover {
      background-color: rgba(255, 193, 7, 0.05);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .gown-table tbody td {
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
    
    .gown-color {
      font-size: 0.75rem;
      color: #67748e;
    }
    
    .price-tag {
      display: inline-flex;
      align-items: center;
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      background: linear-gradient(45deg, #ffc107, #ffdb6d);
      color: #856404;
    }
    
    .price-tag i {
      margin-right: 5px;
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
    
    .status-badge.available {
      background: linear-gradient(45deg, #28a745, #75b798);
      color: #ffffff;
    }
    
    .status-badge.unavailable {
      background: linear-gradient(45deg, #dc3545, #e4606d);
      color: #ffffff;
    }
    
    .date-added {
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
    
    .action-buttons {
      display: flex;
      gap: 8px;
      justify-content: center;
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
      color: white;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    
    .action-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 15px rgba(0, 0, 0, 0.3);
    }
    
    .action-btn.edit {
      background: linear-gradient(45deg, #ffc107, #ffdb6d);
    }
    
    .action-btn.delete {
      background: linear-gradient(45deg, #dc3545, #e4606d);
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
    
    .add-btn {
      display: inline-flex;
      align-items: center;
      padding: 8px 16px;
      border-radius: 8px;
      background: linear-gradient(45deg, #ffc107, #ffdb6d);
      color: #856404;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3);
      margin-top: 20px;
    }
    
    .add-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 15px rgba(255, 193, 7, 0.4);
      color: #856404;
    }
    
    .add-btn i {
      margin-right: 8px;
    }
    
    .modal-content {
      border-radius: 15px;
      overflow: hidden;
    }
    
    .modal-header {
      background: linear-gradient(45deg, #ffc107, #ffdb6d);
      color: #856404;
      border-bottom: none;
      padding: 15px 20px;
    }
    
    .modal-header p {
      font-weight: 600;
      margin: 0;
    }
    
    .modal-body {
      padding: 20px;
    }
    
    .modal-footer {
      border-top: none;
      padding: 15px 20px;
    }
    
    .form-control {
      border-radius: 8px;
      padding: 10px 15px;
      border: 1px solid #e9ecef;
      transition: all 0.3s ease;
    }
    
    .form-control:focus {
      border-color: #ffc107;
      box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    
    .btn-danger {
      background: linear-gradient(45deg, #dc3545, #e4606d);
      border: none;
      box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
    }
    
    .btn-danger:hover {
      background: linear-gradient(45deg, #c82333, #d9534f);
      box-shadow: 0 7px 15px rgba(220, 53, 69, 0.4);
    }
    
    @media (max-width: 768px) {
      .gown-table thead th {
        font-size: 0.7rem;
        padding: 10px;
      }
      
      .gown-table tbody td {
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
          <div class="card gown-container mb-4">
            <div class="card-header gown-header">
              <h6><i class="fa fa-female me-2"></i> Manage Gowns</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table gown-table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Gown Details</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Price</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date Added</th>
                      <th class="text-secondary opacity-7 text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php

            $sql = "SELECT * FROM gowns";
            $result = $conn->query($sql);

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
                    $formatted_date = date("M d, Y", strtotime($created_at));
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
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p>Enter Password to Delete</p>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              
                                <div class="form-group">
                                <form action="process/delete_gown.php" method = "POST">
                                <input type="hidden" id="gown_id" name = "gown_id" class="form-control" readonly>
                                <input type="hidden" name = "user_id" value = "<?php echo $user_id ?>">
                                <input type="password" name = "password" class = "form-control" placeholder="Enter your password">
                                </div>
                            </div>
                            <div class="modal-footer">
                               
                               
                                <button type="submit" name = "delete" class="btn btn-danger" onclick="deleteGown()">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
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