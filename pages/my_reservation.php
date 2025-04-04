<?php
session_start();
include('process/config.php');

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

if (isset($_SESSION['email'])) {
    $uemail = $_SESSION['email'];
    $sql_session = "SELECT * FROM users WHERE email = ?";
    $stmt_session = $conn->prepare($sql_session);
    $stmt_session->bind_param("s", $uemail);
    $stmt_session->execute();
    $result_session = $stmt_session->get_result();

    if ($result_session->num_rows > 0) {
        $row_session = $result_session->fetch_assoc();
        $user_id = $row_session['user_id'];
        $full_name = htmlspecialchars($row_session['full_name']);
        $email = $row_session['email'];
        $profile = $row_session['profile'];
        $type = $row_session['user_type'];

        if ($type != 'customer') {
            header('Location: ../index.php');
            exit();
        }
    }else {
      header('location: ../index.php');
    exit();
    }

    $stmt_session->close();
}

// Check if gown_id is present in the URL
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

    $stmt->close();
}
$reservation_id = $_GET['reservation_id'] ?? null;

if ($reservation_id) {
    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT * FROM agreements WHERE reservation_id = ?");
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect to submit_payment.php if an agreement is found
        header("Location: submit_payment.php?reservation_id=" . $reservation_id);
        exit();
    } else {
        // Set a session variable with the status message
        $_SESSION['status'] = "No agreement found for this reservation.";
        $_SESSION['status_code'] = "error";  // You can use this for CSS classes like 'alert-danger'
    }

    $stmt->close();
} else {
    header("Location: payment.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

  <?php include('header.php');?>
  <title>
  Boutique  
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
      <a class="nav-link active" href="my_payment.php">
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

          $sql = "SELECT r.reservation_id, r.gown_id, r.start_date, r.end_date, g.name, g.price, g.size, g.color FROM reservations r
          JOIN gowns g ON r.gown_id = g.gown_id WHERE reservation_id = '$reservation_id'";
          $res = $conn->query($sql);

          if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $gown_name = htmlspecialchars($row['name']);
                $price = htmlspecialchars($row['price']);
                $date_to_pick = htmlspecialchars($row['start_date']);
                $date_to_return = htmlspecialchars($row['end_date']);
                $size = htmlspecialchars($row['size']);
                $color = htmlspecialchars($row['color']);
                $gown_id = htmlspecialchars($row['gown_id']);
     
              }
          }

          $interest = $price * 0.03;
          $total = $interest + $price

          ?>
    <div class="row">
    <div class="col-12">
        <div class="card mb-4">
           
            <div class="card-body px-0 pt-0 pb-2" style="position: relative; background-image: url('background.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; padding: 20px;">
                <div class="contract-container" style="padding: 40px; border-radius: 10px; font-family: 'Times New Roman', serif; background-color: rgba(255, 255, 255, 0.95); box-shadow: 0px 0px 20px rgba(0,0,0,0.15); max-width: 900px; margin: 0 auto;">
                    <h4 style="text-align: center; color: #886CC0; font-weight: bold; font-size: 24px; margin-bottom: 5px; position: relative;">
                        <span style="position: relative; z-index: 1;">Gown Rental Contract</span>
                        <div style="position: absolute; bottom: -5px; left: 50%; transform: translateX(-50%); width: 80%; height: 3px; background: linear-gradient(90deg, transparent, #886CC0, transparent);"></div>
                    </h4>
                    <p style="text-align: center; color: #777; font-style: italic; margin-bottom: 25px; font-size: 14px;">A legally binding agreement between the Renter and the Rental Service Provider</p>
                       
                        <p style="text-align: justify; color: #333; line-height: 1.8;">
                            This agreement is made between 
                            <input type="text" name="renter_name" value = "<?php echo $full_name?>" style="border: none; border-bottom: 1px solid #333; width: 150px; text-align: center; font-weight: bold; color: #886CC0;" readOnly>, 
                            hereinafter referred to as "The Renter," and 
                            <input type="text" name="business_name" value = "Ging's Boutique" style="border: none; border-bottom: 1px solid #333; width: 150px; text-align: center; font-weight: bold; color: #886CC0;" readOnly>, 
                            referred to as "The Rental Service Provider," on 
                            <input type="text" name="contract_date" value = "<?php echo date("F d, Y"); ?>" style="border: none; border-bottom: 1px solid #333; text-align: center; font-weight: bold; color: #886CC0;" readOnly>.
                        </p>

                        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #886CC0; margin: 20px 0;">
                            <h5 style="color: #886CC0; margin-top: 0; font-weight: bold; font-size: 18px;"><i class="fas fa-calendar-alt me-2"></i> Rental Details</h5>
                            <p style="text-align: justify; color: #333; line-height: 1.8;">
                                The Renter agrees to rent the gown described below for the period starting from 
                                <input type="date" name="pickup_date" value = "<?php echo $date_to_pick?>" style="border: none; border-bottom: 1px solid #333; text-align: center; font-weight: bold; color: #886CC0;" readOnly> 
                                to 
                                <input type="date" name="return_date" value = "<?php echo $date_to_return?>" style="border: none; border-bottom: 1px solid #333; text-align: center; font-weight: bold; color: #886CC0;" readOnly>.
                            </p>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                                <div>
                                    <p><strong>Gown Name:</strong> <input type="text" name="gown_name" value = "<?php echo $gown_name?>" placeholder="Gown Name" style="border: none; border-bottom: 1px solid #333; width: 100%; text-align: center; font-weight: bold; color: #886CC0;" readOnly></p>
                                    <p><strong>Size:</strong> <input type="text" name="gown_size" value = "<?php echo $size?>" placeholder="Size" style="border: none; border-bottom: 1px solid #333; width: 100%; text-align: center; font-weight: bold; color: #886CC0;" readOnly></p>
                                </div>
                                <div>
                                    <p><strong>Color:</strong> <input type="text" name="gown_color" value = "<?php echo $color?>" placeholder="Color" style="border: none; border-bottom: 1px solid #333; width: 100%; text-align: center; font-weight: bold; color: #886CC0;" readOnly></p>
                                    <p><strong>Rental Price:</strong> <input type="text" name="gown_price" value = "<?php echo $price?> PHP" placeholder="Price" style="border: none; border-bottom: 1px solid #333; width: 100%; text-align: center; font-weight: bold; color: #886CC0;" readOnly></p>
                                </div>
                            </div>
                        </div>

                        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #886CC0; margin: 20px 0;">
                            <h5 style="color: #886CC0; margin-top: 0; font-weight: bold; font-size: 18px;"><i class="fas fa-file-contract me-2"></i> Terms & Conditions</h5>
                            <ol style="color: #333; line-height: 1.8;">
                                <li>The gown must be returned in the same condition it was received.</li>
                                <li>A late return fee of <input type="text" name="late_fee" value = "100" placeholder="Fee Amount" style="border: none; border-bottom: 1px solid #333; width: 50px; text-align: center; font-weight: bold; color: #886CC0;" readOnly> per day will be applied for late returns.</li>
                                <li>The Renter is responsible for any damage or loss to the gown.</li>
                                <li>No alterations can be made to the gown without prior permission from the Rental Service Provider.</li>
                            </ol>
                        </div>

                        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #886CC0; margin: 20px 0;">
                            <h5 style="color: #886CC0; margin-top: 0; font-weight: bold; font-size: 18px;"><i class="fas fa-money-bill-wave me-2"></i> Payment & Deposit</h5>
                            <p style="text-align: justify; color: #333; line-height: 1.8;">
                                The total rental fee is 
                                <input type="text" name="total_fee" placeholder="Total Rental Fee" value = "<?php echo $total ?> PHP" style="border: none; border-bottom: 1px solid #333; width: 150px; text-align: center; font-weight: bold; color: #886CC0;" required>, 
                                including a 3% transaction fee. A refundable security deposit of 
                                <input type="text" name="deposit_amount"  value = "400 PHP" placeholder="Deposit Amount" style="border: none; border-bottom: 1px solid #333; width: 100px; text-align: center; font-weight: bold; color: #886CC0;" required> 
                                will be collected and returned upon inspection of the gown.
                            </p>
                        </div>

                        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #886CC0; margin: 20px 0;">
                            <h5 style="color: #886CC0; margin-top: 0; font-weight: bold; font-size: 18px;"><i class="fas fa-signature me-2"></i> Signature</h5>
                            <p style="text-align: justify; color: #333; line-height: 1.8;">
                                The Renter agrees to the terms and conditions by signing below.
                            </p>
                            <br>
                            <form action="process/sign_agreement.php" method = "POST" enctype = "multiform/part">
                              <input type="hidden" name = "reservation_id" value = "<?php echo $reservation_id?>">
                              <input type="hidden" name = "gown_id" value = "<?php echo $gown_id?>">
                            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 20px;">
                                <div style="text-align: center; width: 45%;">
                                    <img id="signatureImg" src="<?php echo $signature_img_url ?? ''; ?>" alt="Signature" style="width: 100%; height: 80px; object-fit: contain; display: <?php echo !empty($signature_img_url) ? 'block' : 'none'; ?>;">
                                    <p style = "text-decoration: underline; margin-top: 10px;"><b><?php echo strtoupper($full_name); ?></b></p>
                                    <p style="color: #777; font-size: 14px;">Signature over Printed Name</p>
                                </div>
                                <div style="text-align: center; width: 45%;">
                                    <div style="border: 1px solid #ddd; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                                        <p style="color: #777; font-style: italic;">Ging's Boutique Stamp</p>
                                    </div>
                                    <p style="margin-top: 10px; font-weight: bold;">Ging's Boutique</p>
                                    <p style="color: #777; font-size: 14px;">Authorized Representative</p>
                                </div>
                            </div>

                            <canvas id="signatureCanvas" style="border: 1px solid #ddd; width: 100%; height: 150px; margin: 20px 0; border-radius: 5px;"></canvas>
                            <input type="hidden" name="signature_data" id="signature_data">
                            <br>
                            <div style="display: flex; justify-content: center; gap: 15px; margin: 20px 0;">
                                <button type="button" id="signButton" class="btn btn-success" style="background: linear-gradient(45deg, #28a745, #20c997); border: none; padding: 10px 25px; border-radius: 8px; font-weight: 500; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);">
                                    <i class="fas fa-signature me-2"></i> Sign
                                </button>
                                <button class="btn btn-danger" type="button" id="clearCanvas" style="background: linear-gradient(45deg, #dc3545, #f86b7d); border: none; padding: 10px 25px; border-radius: 8px; font-weight: 500; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2);">
                                    <i class="fas fa-eraser me-2"></i> Clear Signature
                                </button>
                            </div>

                            <div style="text-align: center; margin-top: 20px;">
                                <button class="btn btn-primary" type="submit" name="sign" style="background: linear-gradient(45deg, #886CC0, #9b7ed3); border: none; padding: 12px 30px; border-radius: 8px; font-weight: 500; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(136, 108, 192, 0.3);">
                                    <i class="fa fa-save me-2"></i> Continue to Payment
                                </button>
                            </div>
                            </form>
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
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
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
// Enhanced signature pad functionality
document.addEventListener('DOMContentLoaded', function() {
    var canvas = document.getElementById('signatureCanvas');
    var signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: '#886CC0',
        minWidth: 1,
        maxWidth: 2.5
    });
    var signatureImg = document.getElementById('signatureImg');
    
    // Resize canvas to fit container
    function resizeCanvas() {
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear(); // Clear the canvas after resize
    }
    
    window.addEventListener("resize", resizeCanvas);
    resizeCanvas();
    
    // Sign button functionality
    document.getElementById('signButton').addEventListener('click', function () {
        if (!signaturePad.isEmpty()) {
            var dataURL = signaturePad.toDataURL(); 
            document.getElementById('signature_data').value = dataURL;
            signatureImg.src = dataURL; 
            signatureImg.style.display = 'block';
            canvas.style.display = 'none';
            
            // Show success message
            swal({
                title: "Signature Added",
                text: "Your signature has been successfully added to the contract.",
                icon: "success",
                button: "Continue",
            });
        } else {
            swal({
                title: "Signature Required",
                text: "Please provide a signature before proceeding.",
                icon: "warning",
                button: "Try Again",
            });
        }
    });
    
    // Clear canvas functionality
    document.getElementById('clearCanvas').addEventListener('click', function () {
        signaturePad.clear();
        signatureImg.style.display = 'none';
        canvas.style.display = 'block';
    });
    
    // Add hover effects to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 6px 15px rgba(0,0,0,0.2)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 10px rgba(0,0,0,0.1)';
        });
    });
    
    // Debug information
    console.log("Signature pad initialized");
    console.log("Canvas dimensions:", canvas.width, "x", canvas.height);
    console.log("Canvas style dimensions:", canvas.style.width, "x", canvas.style.height);
    console.log("Canvas offset dimensions:", canvas.offsetWidth, "x", canvas.offsetHeight);
});
</script>
</body>

</html>