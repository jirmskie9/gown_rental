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

$gown_id = intval($_GET['gown_id']);
if($gown_id == ""){
    header("Location: find_gown.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

  <?php include('header.php');?>
  <title>
  Ging's Boutique | View Gown
  </title>
  <style>
    /* Enhanced Gown View Styles */
    .gown-view-container {
      background: linear-gradient(to bottom, #f8f9fa, #ffffff);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      transition: all 0.3s ease;
    }
    
    .gown-image-container {
      position: relative;
      overflow: hidden;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .gown-image-container img {
      transition: transform 0.5s ease;
    }
    
    .gown-image-container:hover img {
      transform: scale(1.05);
    }
    
    .gown-thumbnails {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 15px;
    }
    
    .gown-thumbnail {
      width: 60px;
      height: 60px;
      border-radius: 8px;
      overflow: hidden;
      cursor: pointer;
      border: 2px solid transparent;
      transition: all 0.3s ease;
    }
    
    .gown-thumbnail:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }
    
    .gown-thumbnail.active {
      border-color: #886CC0;
    }
    
    .gown-details {
      padding: 25px;
    }
    
    .gown-title {
      font-size: 1.8rem;
      font-weight: 700;
      color: #344767;
      margin-bottom: 15px;
      position: relative;
      padding-bottom: 10px;
    }
    
    .gown-title:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 60px;
      height: 3px;
      background: linear-gradient(to right, #886CC0, #c4b5e0);
      border-radius: 3px;
    }
    
    .gown-price {
      font-size: 1.5rem;
      font-weight: 700;
      color: #886CC0;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
    }
    
    .gown-price i {
      margin-right: 8px;
      font-size: 1.2rem;
    }
    
    .gown-info {
      background-color: #f8f9fa;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 20px;
    }
    
    .gown-info-item {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }
    
    .gown-info-item i {
      width: 30px;
      color: #886CC0;
      margin-right: 10px;
    }
    
    .gown-description {
      line-height: 1.7;
      color: #67748e;
      margin-bottom: 25px;
      padding: 15px;
      background-color: #f8f9fa;
      border-radius: 10px;
      border-left: 4px solid #886CC0;
    }
    
    .size-selector {
      margin-bottom: 25px;
    }
    
    .size-selector h5 {
      font-weight: 600;
      margin-bottom: 15px;
      color: #344767;
    }
    
    .size-btn {
      margin-right: 10px;
      margin-bottom: 10px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .size-btn:hover {
      transform: translateY(-2px);
    }
    
    .action-buttons {
      display: flex;
      gap: 15px;
      margin-top: 30px;
    }
    
    .reserve-btn {
      background: linear-gradient(45deg, #886CC0, #a898d0);
      border: none;
      border-radius: 10px;
      padding: 12px 25px;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(136, 108, 192, 0.3);
    }
    
    .reserve-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 7px 15px rgba(136, 108, 192, 0.4);
    }
    
    .return-btn {
      background: #f8f9fa;
      border: 1px solid #e9ecef;
      border-radius: 10px;
      padding: 12px 25px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .return-btn:hover {
      background: #e9ecef;
      transform: translateY(-3px);
    }
    
    @media (max-width: 768px) {
      .gown-title {
        font-size: 1.5rem;
      }
      
      .gown-price {
        font-size: 1.3rem;
      }
      
      .action-buttons {
        flex-direction: column;
      }
      
      .reserve-btn, .return-btn {
        width: 100%;
        margin-bottom: 10px;
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
    
    <div class="row">
        <div class="col-lg-12">
                        <div class="card gown-view-container">
                        <?php
                    // Ensure that the gown_id is set in the URL and is an integer
                    if (isset($_GET['gown_id']) && is_numeric($_GET['gown_id'])) {
                        $gown_id = (int)$_GET['gown_id'];

                        // Prepare the SQL statement to fetch gown details
                        $sql = "SELECT * FROM gowns WHERE gown_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $gown_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Check if a gown was found
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            ?>
                            <div class="card-body">
                                <div class="row">
                                <div class="col-xl-4 col-lg-5 col-md-6 col-xxl-5 align-items-center">
    <!-- Tab panes -->
    <div class="tab-content gown-image-container">
    <div role="tabpanel" class="tab-pane fade show active" id="first">
        <img class="img-fluid" src="<?php echo htmlspecialchars(str_replace('../', '', $row['main_image'])); ?>" alt="" style="width: 100%; height: 400px; object-fit: cover;">
    </div>
    <div role="tabpanel" class="tab-pane fade" id="second">
        <img class="img-fluid" src="<?php echo htmlspecialchars(str_replace('../', '', $row['img1'])); ?>" alt="" style="width: 100%; height: 400px; object-fit: cover;">
    </div>
    <div role="tabpanel" class="tab-pane fade" id="third">
        <img class="img-fluid" src="<?php echo htmlspecialchars(str_replace('../', '', $row['img2'])); ?>" alt="" style="width: 100%; height: 400px; object-fit: cover;">
    </div>
    <div role="tabpanel" class="tab-pane fade" id="for">
        <img class="img-fluid" src="<?php echo htmlspecialchars(str_replace('../', '', $row['img3'])); ?>" alt="" style="width: 100%; height: 400px; object-fit: cover;">
    </div>
</div>

<div class="gown-thumbnails">
    <!-- Nav tabs -->
    <div class="gown-thumbnail active" id="tab-first" onclick="activateTab(event, 'first')">
        <img class="img-fluid" src="<?php echo htmlspecialchars(str_replace('../', '', $row['main_image'])); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
    <div class="gown-thumbnail" id="tab-second" onclick="activateTab(event, 'second')">
        <img class="img-fluid" src="<?php echo htmlspecialchars(str_replace('../', '', $row['img1'])); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
    <div class="gown-thumbnail" id="tab-third" onclick="activateTab(event, 'third')">
        <img class="img-fluid" src="<?php echo htmlspecialchars(str_replace('../', '', $row['img2'])); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
    <div class="gown-thumbnail" id="tab-for" onclick="activateTab(event, 'for')">
        <img class="img-fluid" src="<?php echo htmlspecialchars(str_replace('../', '', $row['img3'])); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
</div>
        </div>
        <div class="col-xl-8 col-lg-7 col-md-6 col-xxl-7 col-sm-12">
            <div class="gown-details">
                <!-- Product details -->
                <h2 class="gown-title"><?php echo htmlspecialchars($row['name']); ?></h2>
                <div class="gown-price">
                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars($row['price']); ?> PHP
                </div>
                
                <div class="gown-info">
                    <div class="gown-info-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Availability: <strong><?php echo htmlspecialchars($row['availability_status']); ?></strong></span>
                    </div>
                    <div class="gown-info-item">
                        <i class="fas fa-ruler"></i>
                        <span>Size: <strong><?php echo htmlspecialchars($row['size']); ?></strong></span>
                    </div>
                </div>
                
                <div class="gown-description">
                    <h5><i class="fas fa-info-circle me-2"></i>Description</h5>
                    <p><?php echo $row['description']; ?></p>
                </div>

                <div class="size-selector">
                    <h5><i class="fas fa-ruler-combined me-2"></i>Available Sizes</h5>
                   
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <?php
                     
                        $sizes = ['Small', 'Medium', 'Large', 'Extra Large']; 
                        foreach ($sizes as $size) {
                           
                            $checked = ($size === $row['size']) ? 'checked' : '';
                            $disabled = ($size === $row['size']) ? '' : 'disabled';
                            echo '<input type="radio" class="btn-check" name="btnradio" id="btnradio' . $size . '" value="' . $size . '" ' . $checked . ' ' . $disabled . '>';
                            echo '<label class="btn btn-outline-primary size-btn" for="btnradio' . $size . '">' . $size . '</label>';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="reserve_gown.php?gown_id=<?php echo $row['gown_id']; ?>" class="btn btn-success reserve-btn">
                        <i class="fas fa-check-circle me-2"></i> Reserve Now
                    </a>
                    <a href="find_gown.php" class="btn btn-block return-btn">
                        <i class="fas fa-arrow-left me-2"></i> Return to Gowns
                    </a>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "No gown found with the specified ID.";
    }

    $stmt->close();
} else {
    header("Location: find_gown.php");
    exit();
}
?>

                                </div>
                            </div>
                        </div>
                    </div>
					<!-- review -->
					<div class="modal fade" id="reviewModal">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">Review</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal">
									</button>
								</div>
								<div class="modal-body">
									<form>
										<div class="text-center mb-4">
											<img class="img-fluid rounded" width="78" src="images/avatar/1.jpg" alt="DexignZone">
										</div>
										<div class="mb-3">
											<div class="rating-widget mb-4 text-center">
												<!-- Rating Stars Box -->
												<div class="rating-stars">
													<ul id="stars">
														<li class="star" title="Poor" data-value="1">
															<i class="fa fa-star fa-fw"></i>
														</li>
														<li class="star" title="Fair" data-value="2">
															<i class="fa fa-star fa-fw"></i>
														</li>
														<li class="star" title="Good" data-value="3">
															<i class="fa fa-star fa-fw"></i>
														</li>
														<li class="star" title="Excellent" data-value="4">
															<i class="fa fa-star fa-fw"></i>
														</li>
														<li class="star" title="WOW!!!" data-value="5">
															<i class="fa fa-star fa-fw"></i>
														</li>
													</ul>
												</div>
											</div>
										</div>
										<div class="mb-3">
											<textarea class="form-control" placeholder="Comment" rows="5"></textarea>
										</div>
										<button class="btn btn-success btn-block">RATE</button>
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
<script>
    function activateTab(event, tabId) {
        // Prevent default action
        event.preventDefault();
        
        // Update active thumbnail
        const thumbnails = document.querySelectorAll('.gown-thumbnail');
        thumbnails.forEach(thumb => {
            thumb.classList.remove('active');
        });
        document.getElementById('tab-' + tabId).classList.add('active');
        
        // Show the active tab content
        const tabPanes = document.querySelectorAll('.tab-pane');
        tabPanes.forEach((pane) => {
            pane.classList.remove('show', 'active');
        });
        
        const activePane = document.getElementById(tabId);
        activePane.classList.add('show', 'active');
    }
    
    // Initialize the first tab as active
    document.addEventListener('DOMContentLoaded', function() {
        const firstThumbnail = document.getElementById('tab-first');
        if (firstThumbnail) {
            firstThumbnail.classList.add('active');
        }
    });
</script>
</body>

</html>