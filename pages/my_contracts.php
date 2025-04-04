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
      
  } else {
    header('location: ../index.php');
    exit();
  }

}
?>
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

            }
        }
?>
<!DOCTYPE html>
<html lang="en">

  <?php include('header.php');?>
  <title>
  Boutique Gown | Contracts
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
      <a class="nav-link active" href="contracts.php">
        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
          <i class="fa fa-file text-info text-sm opacity-10"></i> <!-- Changed to history icon -->
        </div>
        <span class="nav-link-text ms-1">Contracts</span>
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
          $reservation_id = intval($_GET['reservation_id']);

          $sql = "SELECT a.reservation_id, a.signature, a.date_signed, r.customer_id, r.gown_id, r.start_date, r.end_date, u.full_name, g.name, g.size, g.color, g.price FROM agreements a
          JOIN reservations r ON a.reservation_id = r.reservation_id JOIN users u ON r.customer_id = u.user_id JOIN gowns g ON r.gown_id = g.gown_id WHERE r.reservation_id = '$reservation_id' AND r.customer_id = '$user_id'";
          $res = $conn->query($sql);

          if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $gown_name = htmlspecialchars($row['name']);
                $price = htmlspecialchars($row['price']);
                $date_to_pick = htmlspecialchars($row['start_date']);
                $date_to_return = htmlspecialchars($row['end_date']);
                $size = htmlspecialchars($row['size']);
                $color = htmlspecialchars($row['color']);
                $price = htmlspecialchars($row['price']);
                $signature = $row['signature'];
                $gown_name = htmlspecialchars($row['name']);
                $date_signed = $row['date_signed'];

                $interest = $price * 0.03;
                $total = $interest + $price;
     
              }
          }

          $interest = $price * 0.03;
          $total = $interest + $price

          ?>
    <div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body px-0 pt-0 pb-2" style="position: relative; background-image: url('background.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; padding: 20px;">
                <div class="contract-container" id="contractContent" style="padding: 40px; border-radius: 10px; font-family: 'Times New Roman', Times, serif; background-color: rgba(255, 255, 255, 0.95); box-shadow: 0px 0px 20px rgba(0,0,0,0.15); max-width: 800px; margin: 0 auto; border: 1px solid #e0e0e0;">
                    <!-- Contract Header with Logo -->
                    <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #886CC0; padding-bottom: 20px; position: relative;">
                        <div style="position: absolute; top: -40px; left: 50%; transform: translateX(-50%); background: white; padding: 10px; border-radius: 50%; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            <i class="fas fa-file-contract" style="font-size: 30px; color: #886CC0;"></i>
                        </div>
                        <h3 style="color: #886CC0; font-weight: bold; margin: 20px 0 10px 0; font-size: 24px; letter-spacing: 1px;">GOWN RENTAL CONTRACT</h3>
                        <p style="color: #666; font-style: italic; margin: 0; font-size: 14px;">This is a legally binding agreement between the parties</p>
                    </div>
                    
                    <!-- Contract Number and Date -->
                    <div style="display: flex; justify-content: space-between; margin-bottom: 25px; font-size: 14px; color: #666;">
                        <div><strong>Contract #:</strong> <span style="color: #886CC0;"><?php echo "CNT-" . date("Ymd") . "-" . substr(md5($full_name), 0, 4); ?></span></div>
                        <div><strong>Date:</strong> <span style="color: #886CC0;"><?php echo date("F j, Y"); ?></span></div>
                    </div>
                    
                    <div style="margin-bottom: 25px;">
                        <p style="text-align: justify; color: #333; line-height: 1.6; margin-bottom: 15px;">
                            This agreement is made between 
                            <span style="font-weight: bold; color: #886CC0; border-bottom: 1px solid #886CC0; padding: 0 5px;"><?php echo $full_name?></span>, 
                            hereinafter referred to as "The Renter," and 
                            <span style="font-weight: bold; color: #886CC0; border-bottom: 1px solid #886CC0; padding: 0 5px;">Ging's Boutique</span>, 
                            referred to as "The Rental Service Provider," on 
                            <span style="font-weight: bold; color: #886CC0; border-bottom: 1px solid #886CC0; padding: 0 5px;"><?php echo date("F j, Y"); ?></span>.
                        </p>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <h5 style="color: #886CC0; margin-bottom: 15px; font-weight: bold; border-left: 3px solid #886CC0; padding-left: 10px; display: flex; align-items: center;">
                            <i class="fas fa-calendar-alt" style="margin-right: 10px;"></i> Rental Details
                        </h5>
                        <p style="text-align: justify; color: #333; line-height: 1.6; margin-bottom: 15px;">
                            The Renter agrees to rent the gown described below for the period starting from 
                            <span style="font-weight: bold; color: #886CC0; border-bottom: 1px solid #886CC0; padding: 0 5px;"><?php echo $date_to_pick?></span> 
                            to 
                            <span style="font-weight: bold; color: #886CC0; border-bottom: 1px solid #886CC0; padding: 0 5px;"><?php echo $date_to_return?></span>.
                        </p>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0; border: 1px solid #e0e0e0;">
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <li style="margin-bottom: 10px; display: flex; justify-content: space-between; border-bottom: 1px dashed #e0e0e0; padding-bottom: 8px;">
                                    <strong style="color: #886CC0;"><i class="fas fa-tshirt" style="margin-right: 8px;"></i>Gown Name:</strong> 
                                    <span><?php echo $gown_name?></span>
                                </li>
                                <li style="margin-bottom: 10px; display: flex; justify-content: space-between; border-bottom: 1px dashed #e0e0e0; padding-bottom: 8px;">
                                    <strong style="color: #886CC0;"><i class="fas fa-ruler" style="margin-right: 8px;"></i>Size:</strong> 
                                    <span><?php echo $size?></span>
                                </li>
                                <li style="margin-bottom: 10px; display: flex; justify-content: space-between; border-bottom: 1px dashed #e0e0e0; padding-bottom: 8px;">
                                    <strong style="color: #886CC0;"><i class="fas fa-palette" style="margin-right: 8px;"></i>Color:</strong> 
                                    <span><?php echo $color?></span>
                                </li>
                                <li style="margin-bottom: 10px; display: flex; justify-content: space-between; padding-bottom: 8px;">
                                    <strong style="color: #886CC0;"><i class="fas fa-tag" style="margin-right: 8px;"></i>Rental Price:</strong> 
                                    <span style="font-weight: bold;">₱<?php echo number_format($price, 2)?> PHP</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <h5 style="color: #886CC0; margin-bottom: 15px; font-weight: bold; border-left: 3px solid #886CC0; padding-left: 10px; display: flex; align-items: center;">
                            <i class="fas fa-file-contract" style="margin-right: 10px;"></i> Terms & Conditions
                        </h5>
                        <ol style="color: #333; line-height: 1.6; padding-left: 20px;">
                            <li style="margin-bottom: 10px; position: relative; padding-left: 5px;">The gown must be returned in the same condition it was received.</li>
                            <li style="margin-bottom: 10px; position: relative; padding-left: 5px;">A late return fee of <span style="font-weight: bold; color: #886CC0;">₱100</span> per day will be applied for late returns.</li>
                            <li style="margin-bottom: 10px; position: relative; padding-left: 5px;">The Renter is responsible for any damage or loss to the gown.</li>
                            <li style="margin-bottom: 10px; position: relative; padding-left: 5px;">No alterations can be made to the gown without prior permission from the Rental Service Provider.</li>
                        </ol>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <h5 style="color: #886CC0; margin-bottom: 15px; font-weight: bold; border-left: 3px solid #886CC0; padding-left: 10px; display: flex; align-items: center;">
                            <i class="fas fa-money-bill-wave" style="margin-right: 10px;"></i> Payment & Deposit
                        </h5>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0; border: 1px solid #e0e0e0;">
                            <p style="text-align: justify; color: #333; line-height: 1.6; margin-bottom: 10px;">
                                The total rental fee is <span style="font-weight: bold; color: #886CC0;">₱<?php echo number_format($total, 2)?> PHP</span>, 
                                including a 3% transaction fee. A refundable security deposit of 
                                <span style="font-weight: bold; color: #886CC0;">₱400 PHP</span> will be collected and returned upon inspection of the gown.
                            </p>
                            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #e0e0e0;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span>Rental Fee:</span>
                                    <span>₱<?php echo number_format($price, 2)?> PHP</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span>Transaction Fee (3%):</span>
                                    <span>₱<?php echo number_format($interest, 2)?> PHP</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-weight: bold; border-top: 1px solid #e0e0e0; padding-top: 8px; margin-top: 8px;">
                                    <span>Total Amount:</span>
                                    <span style="color: #886CC0;">₱<?php echo number_format($total, 2)?> PHP</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <h5 style="color: #886CC0; margin-bottom: 15px; font-weight: bold; border-left: 3px solid #886CC0; padding-left: 10px; display: flex; align-items: center;">
                            <i class="fas fa-signature" style="margin-right: 10px;"></i> Signature
                        </h5>
                        <p style="text-align: justify; color: #333; line-height: 1.6; margin-bottom: 15px;">
                            The Renter agrees to the terms and conditions by signing below.
                        </p>
                        <div style="margin-top: 30px;">
                            <p style="color: #666; margin-bottom: 5px;"><i class="far fa-calendar-alt" style="margin-right: 5px;"></i>Date Signed: <?php echo date("F j, Y, g:i a", strtotime($date_signed)); ?></p>
                            <div style="margin: 20px 0; text-align: center;">
                                <div style="border: 1px solid #e0e0e0; padding: 15px; border-radius: 8px; display: inline-block; background: #f8f9fa;">
                                    <img id="signatureImg" src="uploads/<?php echo $signature ?>" alt="<?php echo $signature ?>" style="width: 150px; height: 100px; margin-bottom: 10px;">
                                    <p style="border-top: 1px solid #886CC0; padding-top: 5px; margin: 0; font-weight: bold; color: #886CC0;">
                                        <?php echo strtoupper($full_name); ?>
                                    </p>
                                    <p style="color: #666; margin: 5px 0 0 0; font-size: 0.9em;">Signature over Printed Name</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #886CC0;">
                        <button class="btn btn-primary" type="button" onclick="printContract()" style="background: #886CC0; border: none; padding: 10px 25px; margin-right: 10px; border-radius: 5px; transition: all 0.3s ease;">
                            <i class="fa fa-print"></i> Print Contract
                        </button>
                        <a href="contracts.php" class="btn btn-secondary" style="background: #6c757d; border: none; padding: 10px 25px; border-radius: 5px; transition: all 0.3s ease;">
                            <i class="fa fa-arrow-left"></i> Return to Contracts
                        </a>
                    </div>
                </div>
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

<script>
    function printContract() {
        // Create a new window for printing
        var printWindow = window.open('', '_blank');
        
        // Get the contract content
        var contractContent = document.getElementById('contractContent').innerHTML;
        
        // Create the HTML content for the print window
        var printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Gown Rental Contract</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                <style>
                    body {
                        font-family: 'Times New Roman', Times, serif;
                        line-height: 1.6;
                        color: #333;
                        margin: 0;
                        padding: 20px;
                    }
                    .contract-container {
                        max-width: 800px;
                        margin: 0 auto;
                        padding: 40px;
                        border: 1px solid #e0e0e0;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    }
                    h3, h5 {
                        color: #886CC0;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 30px;
                        border-bottom: 2px solid #886CC0;
                        padding-bottom: 20px;
                        position: relative;
                    }
                    .header-icon {
                        position: absolute;
                        top: -40px;
                        left: 50%;
                        transform: translateX(-50%);
                        background: white;
                        padding: 10px;
                        border-radius: 50%;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    }
                    .section {
                        margin-bottom: 25px;
                    }
                    .section-title {
                        color: #886CC0;
                        margin-bottom: 15px;
                        font-weight: bold;
                        border-left: 3px solid #886CC0;
                        padding-left: 10px;
                        display: flex;
                        align-items: center;
                    }
                    .info-box {
                        background: #f8f9fa;
                        padding: 15px;
                        border-radius: 8px;
                        margin: 15px 0;
                        border: 1px solid #e0e0e0;
                    }
                    .signature-box {
                        border: 1px solid #e0e0e0;
                        padding: 15px;
                        border-radius: 8px;
                        display: inline-block;
                        background: #f8f9fa;
                        text-align: center;
                    }
                    @media print {
                        body {
                            padding: 0;
                        }
                        .contract-container {
                            box-shadow: none;
                            border: none;
                        }
                        .no-print {
                            display: none;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="contract-container">
                    ${contractContent}
                </div>
                <div class="no-print" style="text-align: center; margin-top: 20px;">
                    <button onclick="window.print()" style="background: #886CC0; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                        <i class="fa fa-print"></i> Print
                    </button>
                </div>
            </body>
            </html>
        `;
        
        // Write the content to the new window
        printWindow.document.write(printContent);
        printWindow.document.close();
        
        // Wait for resources to load
        printWindow.onload = function() {
            // Print the window
            printWindow.print();
            
            // Close the window after printing (optional)
            // printWindow.close();
        };
    }
</script>

</body>

</html>