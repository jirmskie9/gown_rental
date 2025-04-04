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
    $full_name = htmlspecialchars($row_session['full_name']);
    $email = $row_session['email'];
	  $profile = $row_session['profile'];
	  $type = $row_session['user_type'];

	  if ($type != 'customer') {
    header('location: ../index.php');
    exit();
	}
      
  }else{
    header('location: ../index.php');
    exit();
  }

}
?>
<?php
if (isset($_GET['reservation_id']) && is_numeric($_GET['reservation_id'])) {
$reservation_id = (int)$_GET['reservation_id'];

$sql = "SELECT * FROM reservations WHERE reservation_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

            }
        }

?>
 <?php

$sql = "SELECT r.reservation_id, r.customer_id, r.gown_id, r.start_date, r.end_date, r.total_price, r.status, g.name, g.main_image, g.description 
FROM reservations r JOIN gowns g ON r.gown_id = g.gown_id WHERE r.customer_id = '$user_id' AND r.status = 'confirmed' AND reservation_id = '$reservation_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $gown_id = $row['gown_id'];
        $gown_name = $row['name'];
        $main_image = 'uploads/' . basename($row['main_image']);
        $name = $row['name'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
        $status = $row['status'];
        $reservation_id = $row['reservation_id'];
        $description = $row['description'];
    }
  } else {
    $_SESSION['status'] = "Reservation Completed";
    $_SESSION['status_code'] = "info";
    $_SESSION['status_button'] = "Okay";
    header('location: history.php');
    exit();
  }
        ?>
<!DOCTYPE html>
<html lang="en">

  <?php include('header.php');?>
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<style>
 #calendar {
    width: 100%; /* Full width of the container */
    max-width: 100%;
    margin: 0 auto;
    padding: 10px;
    box-sizing: border-box;
}

.card-body, .profile-tab, .custom-tab-1 {
    display: flex;
    flex-direction: column;
    align-items: stretch;
}

/* Enhanced styles for view history page */
.history-container {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    overflow: hidden;
}

.gown-image-container {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.gown-image-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.gown-image-container img {
    width: 100%;
    height: auto;
    transition: transform 0.5s ease;
}

.gown-image-container:hover img {
    transform: scale(1.05);
}

.gown-details {
    padding: 20px;
    background: linear-gradient(to bottom, #f8f9fa, #ffffff);
    border-radius: 10px;
    margin-top: 15px;
}

.gown-title {
    color: #886CC0;
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 1.5rem;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
}

.gown-description {
    color: #555;
    line-height: 1.6;
    font-size: 0.95rem;
}

.rental-dates {
    display: flex;
    justify-content: space-between;
    margin: 20px 0;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #886CC0;
}

.date-item {
    text-align: center;
}

.date-label {
    font-size: 0.8rem;
    color: #777;
    margin-bottom: 5px;
}

.date-value {
    font-weight: 600;
    color: #333;
}

.return-btn {
    background: linear-gradient(45deg, #886CC0, #9b7ed3);
    color: white;
    border: none;
    padding: 10px 25px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(136, 108, 192, 0.2);
}

.return-btn:hover {
    background: linear-gradient(45deg, #9b7ed3, #886CC0);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(136, 108, 192, 0.3);
}

.calendar-container {
    background-color: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin-top: 20px;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.calendar-title {
    font-weight: 600;
    color: #886CC0;
    font-size: 1.2rem;
}

/* Modal enhancements */
.modal-content {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.modal-header {
    background: linear-gradient(45deg, #886CC0, #9b7ed3);
    color: white;
    border-bottom: none;
    padding: 20px;
}

.modal-title {
    font-weight: 600;
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    border-top: 1px solid #eee;
    padding: 15px 20px;
}

.terms-list {
    list-style-type: none;
    padding-left: 0;
}

.terms-list li {
    margin-bottom: 10px;
    padding-left: 25px;
    position: relative;
}

.terms-list li:before {
    content: "•";
    color: #886CC0;
    font-weight: bold;
    position: absolute;
    left: 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .rental-dates {
        flex-direction: column;
    }
    
    .date-item {
        margin-bottom: 15px;
    }
}
</style>

  <title>
  Ging's Boutique | History
  </title>
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
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
      <a class="nav-link" href="contracts.php">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
          <i class="fa fa-file text-info text-sm opacity-10"></i> <!-- Changed to history icon -->
        </div>
        <span class="nav-link-text ms-1">Contracts</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="history.php">
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
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">My</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Reservation</li>
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
   
    <div class="row">
        <div class="col-12">
          <div class="card mb-4 history-container">
  
            <div class="card-body px-0 pt-0 pb-2">
        
                <div class="row">
                    <div class="col-xl-4">
						<div class="row">
							
							<div class="col-xl-12">
								<div class="card">
									<div class="card-body">
										<div class="profile-blog">
											<div class="d-flex justify-content-between align-items-center mb-4">
												<h5 class="text-primary m-0">Gown Rented</h5>
												<a href="#" class="btn return-btn" data-bs-toggle="modal" data-bs-target="#returnGownModal">
													<i class="fas fa-undo-alt me-2"></i> Return Gown
												</a>
											</div>
											
											<div class="rental-dates mb-4">
												<div class="date-item">
													<div class="date-label"><i class="fas fa-calendar-alt"></i> Start Date</div>
													<div class="date-value"><?php echo date("M d, Y", strtotime($start_date)); ?></div>
												</div>
												<div class="date-item">
													<div class="date-label"><i class="fas fa-calendar-check"></i> Return Date</div>
													<div class="date-value"><?php echo date("M d, Y", strtotime($end_date)); ?></div>
												</div>
											</div>
											
											<div class="gown-image-container">
												<img src="<?php echo $main_image?>" alt="<?php echo $main_image?>" class="img-fluid mt-4 mb-4 w-100">
											</div>
											<div class="gown-details">
												<h4 class="gown-title"><?php echo $gown_name?></h4>
												<p class="gown-description"><?php echo $description?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
              <div class="text-center">
												<!-- Removed the rental dates and return button from here -->
											</div>
							
						</div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card calendar-container">
                            <div class="card-body">
                                <div class="profile-tab">
                                    <div class="custom-tab-1">
										<div class="calendar-header">
											<div class="calendar-title">
												<i class="fas fa-calendar-alt me-2"></i> Rental Calendar
											</div>
											<div class="calendar-legend">
												<span class="badge bg-primary me-2">Start Date</span>
												<span class="badge bg-danger me-2">Return Date</span>
											</div>
										</div>
                                    <div id="calendar"></div>
                                    <script>
                                      document.addEventListener('DOMContentLoaded', function() {
                                          var calendarEl = document.getElementById('calendar');
                                          
                                          // Check if the event is overdue by comparing end_date to the current date
                                          var endDate = new Date('<?php echo $end_date; ?>');
                                          var isOverdue = new Date() > endDate;
                                          var returnTitle = isOverdue ? 'Return Date (Overdue)' : 'Return Date';
                                          var returnColor = isOverdue ? '#FF0000' : '#FF6347'; // Red for overdue, default color otherwise

                                          var calendar = new FullCalendar.Calendar(calendarEl, {
                                      initialView: 'dayGridMonth', // Default view (month view)
                                      headerToolbar: {
                                          left: 'prev,next today',
                                          center: 'title',
                                          right: 'dayGridMonth,timeGridWeek,timeGridDay' // View options
                                      },
                                      aspectRatio: 1.2, // Lower ratio for more height flexibility
                                      height: 'auto', // Adjusts calendar height responsively
                                      expandRows: true, // Makes rows fill available space
                                      contentHeight: 'auto', // Makes height responsive
                                      windowResize: function(view) { // Re-renders calendar on window resize
                                          calendar.updateSize();
                                      },
                                      themeSystem: 'bootstrap', // Use Bootstrap theme
                                      bootstrapFontAwesome: {
                                          close: 'fa-times',
                                          prev: 'fa-chevron-left',
                                          next: 'fa-chevron-right',
                                          prevYear: 'fa-angle-double-left',
                                          nextYear: 'fa-angle-double-right'
                                      },
                                      buttonText: {
                                          today: 'Today',
                                          month: 'Month',
                                          week: 'Week',
                                          day: 'Day'
                                      },
                                      eventClassNames: 'calendar-event',
                                      eventContent: function(arg) {
                                          return {
                                              html: '<div class="fc-content"><div class="fc-title">' + arg.event.title + '</div></div>'
                                          };
                                      },
                                              events: [
                                                  {
                                                      title: 'Start Date', // Title for the start date event
                                                      start: '<?php echo $start_date; ?>', // PHP variable for start date
                                                      color: '#886CC0' // Customize color for start date
                                                  },
                                                  {
                                                      title: returnTitle, // Shows 'Return Date (Overdue)' if overdue
                                                      start: '<?php echo $end_date; ?>', // PHP variable containing the date
                                                      color: returnColor // Red color if overdue, otherwise default
                                                  }
                                              ]
                                          });

                                          calendar.render();
                                      });
                                  </script>
                                     
                                    </div>
		
									
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              
            </div>
            
          </div>
         
        </div>

        <div class="modal fade" id="returnGownModal" tabindex="-1" aria-labelledby="returnGownModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="returnGownModalLabel"><i class="fas fa-undo-alt me-2"></i> Return Gown</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <!-- Content of the modal -->
                      <p class="text-primary fw-bold mb-3">Are you sure you want to return this gown?</p>
                      <div class="alert alert-info">
                          <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i> Return Terms</h6>
                          <ul class="terms-list">
                              <li>The gown must be returned in the same condition it was received.</li>
                              <li>A late return fee of ₱100 per day will be applied for late returns.</li>
                              <li>The Renter is responsible for any damage or loss to the gown.</li>
                              <li>No alterations can be made to the gown without prior permission from the Rental Service Provider.</li>
                          </ul>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <form action="process/return_gown.php" method="POST">
                      <input type="hidden" name="reservation_id" value="<?php echo $reservation_id?>">
                      <input type="hidden" name="gown_id" value="<?php echo $gown_id?>">
                      <input type="hidden" name="user_id" value="<?php echo $user_id?>">
                      <button type="submit" class="btn return-btn" name="return">
                          <i class="fas fa-check me-2"></i> Confirm Return
                      </button>
                      </form>
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
// Add animation to the page elements
document.addEventListener('DOMContentLoaded', function() {
    // Animate the gown image container
    const gownImage = document.querySelector('.gown-image-container');
    if (gownImage) {
        gownImage.style.opacity = '0';
        gownImage.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            gownImage.style.transition = 'all 0.8s ease';
            gownImage.style.opacity = '1';
            gownImage.style.transform = 'translateY(0)';
        }, 300);
    }
    
    // Animate the calendar container
    const calendarContainer = document.querySelector('.calendar-container');
    if (calendarContainer) {
        calendarContainer.style.opacity = '0';
        calendarContainer.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            calendarContainer.style.transition = 'all 0.8s ease';
            calendarContainer.style.opacity = '1';
            calendarContainer.style.transform = 'translateY(0)';
        }, 600);
    }
    
    // Add hover effect to the return button
    const returnBtn = document.querySelector('.return-btn');
    if (returnBtn) {
        returnBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        returnBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    }
});
</script>
</body>

</html>