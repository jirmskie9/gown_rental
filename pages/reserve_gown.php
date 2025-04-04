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

<!DOCTYPE html>
<html lang="en">

  <?php include('header.php');?>
  <title>
  Boutique Gown | Setup
  </title>
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#" target="_blank">
        <img src="images/dress.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold"><?php echo $full_name?></span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" href="find_gown.php">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-search text-primary text-sm opacity-10"></i> <!-- Replace with appropriate icon class -->
          </div>
          <span class="nav-link-text ms-1">Find Gown</span>
        </a>
      </li>
      <li class="nav-item">
      <a class="nav-link" href="my_payment.php">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
          <i class="fa fa-dollar-sign text-success text-sm opacity-10"></i> <!-- Changed to history icon -->
        </div>
        <span class="nav-link-text ms-1">Payments</span>
      </a>
    </li>
      <li class="nav-item">
      <a class="nav-link" href="history.php">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
          <i class="fa fa-history text-warning text-sm opacity-10"></i> <!-- Changed to history icon -->
        </div>
        <span class="nav-link-text ms-1">Reservation History</span>
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
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Find</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Gowns</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">
            <form method="POST" action="">
              <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Type here..." required>
                  <span class="input-group-text text-body" style="cursor: pointer;" onclick="this.parentNode.parentNode.querySelector('form').submit()">
                      
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
            <a href="javascript:;" class="nav-link text-white p-0 dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <?php
                $sql = "SELECT * FROM notifications WHERE user_id = '$user_id' AND type = 'customer' ORDER BY date_time DESC";
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
            <a href="javascript:;" class="nav-link text-white p-0 dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
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
    <?php
if (isset($_GET['gown_id']) && is_numeric($_GET['gown_id'])) {
$gown_id = (int)$_GET['gown_id'];

$sql = "SELECT * FROM gowns WHERE gown_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $gown_id);
$stmt->execute();
$result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
            $gown_name = $row['name'];
            $gown_image = $row['main_image'];
            $gown_description = $row['description'];
            $gown_category = $row['category'];
            }
        }
?>
    
    <div class="row">
        <div class="col-md-12">
          <div class="card shadow-lg border-0">
            <div class="card-header bg-gradient-primary text-white py-3">
              <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i> Set up your Reservation</h5>
                <span class="badge bg-white text-primary"><?php echo $gown_name; ?></span>
              </div>
            </div>
            <div class="card-body p-4">
              <div class="row">
                <div class="col-md-4 mb-4">
                  <div class="gown-preview-card p-3 border rounded h-100">
                    <h6 class="text-primary mb-3"><i class="fas fa-dress me-2"></i>Gown Details</h6>
                    <div class="gown-image-container mb-3">
                      <img src="uploads/<?php echo $gown_image; ?>" class="img-fluid rounded" alt="<?php echo $gown_name; ?>">
                    </div>
                    <div class="gown-info">
                      <p class="mb-1"><strong>Name:</strong> <?php echo $gown_name; ?></p>
                      <p class="mb-1"><strong>Category:</strong> <?php echo $gown_category; ?></p>
                      <p class="mb-0"><strong>Description:</strong> <?php echo $gown_description; ?></p>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-8">
                  <div class="reservation-form-card p-3 border rounded h-100">
                    <h6 class="text-primary mb-3"><i class="fas fa-calendar-alt me-2"></i>Reservation Details</h6>
                    
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="calendar-section p-3 bg-light rounded">
                          <div class="d-flex justify-content-between align-items-center">
                            <div>
                              <h6 class="mb-1">Check Availability</h6>
                              <p class="text-muted small mb-0">View the calendar to see when this gown is available</p>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#calendarModal">
                              <i class="fas fa-calendar-week me-2"></i>View Calendar
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <form action="process/reserve_gown.php" method="POST" class="needs-validation" novalidate> 
                      <input type="hidden" name="full_name" value="<?php echo $full_name?>">
                      <input type="hidden" name="gown_name" value="<?php echo $gown_name?>">
                      <input type="hidden" value="<?php echo $user_id?>" name="user">
                      <input type="hidden" value="<?php echo $gown_id?>" name="gown_id">
                      
                      <div class="row mb-4">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="date_to_pick" class="form-label">Date to Pick Up</label>
                            <div class="input-group">
                              <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                              <input type="date" class="form-control" id="date_to_pick" name="date_to_pick" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="invalid-feedback">Please select a pick-up date</div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="date_to_return" class="form-label">Date to Return</label>
                            <div class="input-group">
                              <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                              <input type="date" class="form-control" id="date_to_return" name="date_to_return" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="invalid-feedback">Please select a return date</div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="cost-breakdown p-3 bg-light rounded mb-4">
                        <h6 class="text-primary mb-3"><i class="fas fa-receipt me-2"></i>Cost Breakdown</h6>
                        
                        <?php 
                        $price = $row['price'];
                        $priceWithTax = $price + ($price * 0.03);
                        $securityDeposit = 400;
                        $totalAmount = $priceWithTax + $securityDeposit;
                        ?>
                        
                        <div class="cost-item d-flex justify-content-between mb-2 pb-2 border-bottom">
                          <div>
                            <h6 class="mb-0">Gown Rental</h6>
                            <small class="text-muted">Base price</small>
                          </div>
                          <div class="text-end">
                            <h6 class="mb-0"><?php echo $price; ?> PHP</h6>
                            <input type="hidden" name="price" value="<?php echo $price; ?>">
                          </div>
                        </div>
                        
                        <div class="cost-item d-flex justify-content-between mb-2 pb-2 border-bottom">
                          <div>
                            <h6 class="mb-0">Transaction Fee</h6>
                            <small class="text-muted">3% of rental price</small>
                          </div>
                          <div class="text-end">
                            <h6 class="mb-0"><?php echo $price * 0.03; ?> PHP</h6>
                          </div>
                        </div>
                        
                        <div class="cost-item d-flex justify-content-between mb-2 pb-2 border-bottom">
                          <div>
                            <h6 class="mb-0">Security Deposit</h6>
                            <small class="text-muted">Refundable upon return</small>
                          </div>
                          <div class="text-end">
                            <h6 class="mb-0"><?php echo $securityDeposit; ?> PHP</h6>
                          </div>
                        </div>
                        
                        <div class="total-cost d-flex justify-content-between mt-3 pt-2 border-top">
                          <div>
                            <h5 class="mb-0 text-primary">Total Amount</h5>
                            <small class="text-muted">Inclusive of all fees</small>
                          </div>
                          <div class="text-end">
                            <h4 class="mb-0 text-primary"><?php echo $totalAmount; ?> PHP</h4>
                          </div>
                        </div>
                      </div>
                      
                      <div class="terms-section mb-4">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="terms" required>
                          <label class="form-check-label" for="terms">
                            I agree to the <a href="#" class="text-primary">Terms and Conditions</a> and confirm that all information provided is accurate
                          </label>
                          <div class="invalid-feedback">
                            You must agree to the terms before proceeding
                          </div>
                        </div>
                      </div>
                      
                      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="find_gown.php" class="btn btn-outline-secondary me-md-2">
                          <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                        <button class="btn btn-primary" type="submit" name="reserve">
                          <i class="fas fa-check-circle me-2"></i> Reserve Now
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
                         <div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="calendarModalLabel"><i class="fas fa-calendar-alt me-2"></i>Availability Calendar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i> This calendar shows when the gown is already reserved. Please select dates when the gown is available.
                </div>
                <div id="calendar" class="border rounded p-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
  .gown-preview-card, .reservation-form-card {
    transition: all 0.3s ease;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }
  
  .gown-preview-card:hover, .reservation-form-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  }
  
  .gown-image-container {
    overflow: hidden;
    border-radius: 0.5rem;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
  }
  
  .gown-image-container img {
    transition: transform 0.5s ease;
  }
  
  .gown-image-container:hover img {
    transform: scale(1.05);
  }
  
  .cost-breakdown {
    border-left: 4px solid #886CC0;
  }
  
  .btn-primary {
    background: linear-gradient(45deg, #886CC0, #5E3F8E);
    border: none;
  }
  
  .btn-primary:hover {
    background: linear-gradient(45deg, #5E3F8E, #886CC0);
  }
  
  .bg-gradient-primary {
    background: linear-gradient(45deg, #886CC0, #5E3F8E);
  }
  
  .text-primary {
    color: #886CC0 !important;
  }
  
  .form-control:focus {
    border-color: #886CC0;
    box-shadow: 0 0 0 0.25rem rgba(136, 108, 192, 0.25);
  }
  
  .input-group-text {
    background-color: #f8f9fa;
    border-right: none;
  }
  
  .form-control {
    border-left: none;
  }
  
  .form-control:focus + .input-group-text {
    border-color: #886CC0;
  }
  
  .form-control:focus ~ .input-group-text {
    border-color: #886CC0;
  }
  
  @media (max-width: 768px) {
    .gown-preview-card, .reservation-form-card {
      margin-bottom: 1.5rem;
    }
  }
</style>

      
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
  <?php include('calendar.php');?>
  <script>
    $(document).ready(function() {
        var calendar;

        $('#calendarModal').on('shown.bs.modal', function () {
            var calendarEl = document.getElementById('calendar');
            var gown_id = <?php echo (int)$_GET['gown_id']; ?>; 

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: 'fetch_reservations.php',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            gown_id: gown_id 
                        },
                        success: function(data) {
                            successCallback(data);
                        },
                        error: function() {
                            failureCallback();
                        }
                    });
                },
                eventContent: function(arg) {
                    let badge = `<span class="badge bg-danger">${arg.event.title}</span>`;
                    return { html: badge }; 
                },
                height: '100%',
                contentHeight: 'auto',
                eventColor: '#886CC0',
                eventTextColor: '#fff',
                eventBorderColor: '#5E3F8E'
            });

            calendar.render();
        });

        $('#calendarModal').on('hidden.bs.modal', function () {
            if (calendar) {
                calendar.destroy();
            }
        });
        
        // Form validation
        (function() {
            'use strict';
            
            var forms = document.querySelectorAll('.needs-validation');
            
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    
                    form.classList.add('was-validated');
                }, false);
            });
        })();
        
        // Date validation
        document.getElementById('date_to_pick').addEventListener('change', function() {
            var pickDate = new Date(this.value);
            var returnDate = new Date(document.getElementById('date_to_return').value);
            
            if (pickDate > returnDate) {
                document.getElementById('date_to_return').value = this.value;
            }
            
            document.getElementById('date_to_return').min = this.value;
        });
        
        document.getElementById('date_to_return').addEventListener('change', function() {
            var pickDate = new Date(document.getElementById('date_to_pick').value);
            var returnDate = new Date(this.value);
            
            if (returnDate < pickDate) {
                this.value = document.getElementById('date_to_pick').value;
            }
        });
    });
</script>
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