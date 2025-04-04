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
<!DOCTYPE html>
<html lang="en">

  <?php include('header.php');?>
  <title>
  Ging's Boutique | Find Gown
  </title>
  <style>
    .image-zoom {
      transition: transform 0.3s ease; 
    }

    .image-zoom:hover {
      transform: scale(1.05); 
    }
    
    .gown-card {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      height: 100%;
      background: white;
      border: 1px solid rgba(0,0,0,0.05);
    }
    
    .gown-card:hover {
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      transform: translateY(-5px);
    }
    
    .gown-image-container {
      position: relative;
      overflow: hidden;
      border-radius: 15px 15px 0 0;
      height: 350px;
    }
    
    .gown-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: all 0.5s ease;
    }
    
    .gown-details {
      padding: 1.5rem;
    }
    
    .gown-title {
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: #344767;
      font-size: 1.2rem;
    }
    
    .gown-title a {
      color: #344767;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    
    .gown-title a:hover {
      color: #5e72e4;
    }
    
    .gown-info {
      margin-bottom: 1rem;
    }
    
    .gown-info p {
      margin-bottom: 0.5rem;
    }
    
    .gown-description {
      color: #67748e;
      margin-bottom: 1.5rem;
      line-height: 1.6;
      font-size: 0.9rem;
    }
    
    .gown-actions {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }
    
    .gown-actions .btn {
      border-radius: 8px;
      padding: 0.5rem 1rem;
      font-weight: 500;
      transition: all 0.3s ease;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
    }
    
    .gown-actions .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .pagination {
      margin-top: 2rem;
    }
    
    .pagination .page-link {
      border-radius: 8px;
      margin: 0 3px;
      color: #5e72e4;
      border: none;
      padding: 0.5rem 1rem;
      transition: all 0.3s ease;
    }
    
    .pagination .page-item.active .page-link {
      background-color: #5e72e4;
      color: white;
    }
    
    .search-container {
      position: relative;
      margin-bottom: 2rem;
    }
    
    .search-container .form-control {
      border-radius: 10px;
      padding-left: 1rem;
      padding-right: 3rem;
      height: 3rem;
      border: 1px solid #e9ecef;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }
    
    .search-container .form-control:focus {
      box-shadow: 0 4px 15px rgba(94, 114, 228, 0.15);
      border-color: #5e72e4;
    }
    
    .search-container .input-group-text {
      position: absolute;
      right: 0;
      top: 0;
      height: 100%;
      border: none;
      background: transparent;
      z-index: 10;
    }
    
    .search-container .input-group-text i {
      color: #5e72e4;
      font-size: 1.2rem;
    }
    
    .section-title {
      margin-bottom: 1.5rem;
      color: #344767;
      font-weight: 600;
      position: relative;
      display: inline-block;
    }
    
    .section-title:after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 0;
      width: 50px;
      height: 3px;
      background: linear-gradient(90deg, #5e72e4, #825ee4);
      border-radius: 3px;
    }
    
    .no-results {
      text-align: center;
      padding: 3rem;
      background: #f8f9fa;
      border-radius: 15px;
      color: #67748e;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    
    .no-results i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: #5e72e4;
    }
    
    /* Enhanced hero section */
    .hero-section {
      background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
      border-radius: 15px;
      padding: 3rem;
      margin-bottom: 2.5rem;
      color: white;
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(94, 114, 228, 0.2);
    }
    
    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 40%;
      height: 100%;
      background: url('images/dress-pattern.png') no-repeat center center;
      background-size: contain;
      opacity: 0.1;
    }
    
    .hero-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .hero-subtitle {
      font-size: 1.2rem;
      opacity: 0.9;
      margin-bottom: 1.5rem;
      max-width: 70%;
      line-height: 1.6;
    }
    
    .hero-search {
      max-width: 500px;
      position: relative;
    }
    
    .hero-search .form-control {
      height: 3.5rem;
      border-radius: 10px;
      padding-left: 1.5rem;
      padding-right: 3.5rem;
      border: none;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      font-size: 1rem;
    }
    
    .hero-search .btn {
      position: absolute;
      right: 0;
      top: 0;
      height: 100%;
      border-radius: 0 10px 10px 0;
      padding: 0 1.5rem;
      background: #5e72e4;
      border: none;
      color: white;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .hero-search .btn:hover {
      background: #4a5bd0;
    }
    
    /* Enhanced filter section */
    .filter-section {
      background: white;
      border-radius: 15px;
      padding: 1.8rem;
      margin-bottom: 2.5rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      border: 1px solid rgba(0,0,0,0.05);
    }
    
    .filter-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 1.2rem;
      color: #344767;
      display: flex;
      align-items: center;
    }
    
    .filter-title i {
      margin-right: 0.5rem;
      color: #5e72e4;
    }
    
    .filter-options {
      display: flex;
      flex-wrap: wrap;
      gap: 0.8rem;
    }
    
    .filter-option {
      padding: 0.6rem 1.2rem;
      border-radius: 8px;
      background: #f8f9fa;
      color: #67748e;
      font-size: 0.9rem;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 1px solid rgba(0,0,0,0.05);
      text-decoration: none;
      display: inline-block;
    }
    
    .filter-option:hover, .filter-option.active {
      background: #5e72e4;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(94, 114, 228, 0.2);
      text-decoration: none;
    }
    
    .results-count {
      font-size: 0.95rem;
      color: #67748e;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
    }
    
    .results-count i {
      margin-right: 0.5rem;
      color: #5e72e4;
    }
    
    .gown-price {
      font-weight: 600;
      color: #5e72e4;
      font-size: 1.3rem;
      margin-bottom: 1rem;
      display: flex;
      align-items: baseline;
    }
    
    .gown-price small {
      font-size: 0.8rem;
      color: #67748e;
      margin-left: 0.5rem;
    }
    
    .gown-badge {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: rgba(255, 255, 255, 0.95);
      color: #5e72e4;
      padding: 0.4rem 1rem;
      border-radius: 8px;
      font-size: 0.8rem;
      font-weight: 600;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
    }
    
    .gown-badge i {
      margin-right: 0.3rem;
      color: #5e72e4;
    }
    
    .gown-features {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }
    
    .gown-feature {
      background: #f8f9fa;
      padding: 0.4rem 0.9rem;
      border-radius: 8px;
      font-size: 0.8rem;
      color: #67748e;
      display: flex;
      align-items: center;
      border: 1px solid rgba(0,0,0,0.05);
    }
    
    .gown-feature i {
      margin-right: 0.4rem;
      color: #5e72e4;
    }
    
    .view-more {
      text-align: center;
      margin-top: 3rem;
      padding: 2rem;
      background: #f8f9fa;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    
    .view-more p {
      font-size: 1.1rem;
      color: #67748e;
      margin-bottom: 1.5rem;
    }
    
    .view-more-btn {
      background: transparent;
      border: 1px solid #5e72e4;
      color: #5e72e4;
      padding: 0.6rem 2.5rem;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
      text-transform: uppercase;
      font-size: 0.8rem;
      letter-spacing: 0.5px;
    }
    
    .view-more-btn:hover {
      background: #5e72e4;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(94, 114, 228, 0.2);
    }
    
    /* Available gowns section enhancement */
    .available-gowns-section {
      margin-bottom: 2rem;
      padding: 1.5rem;
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    
    .available-gowns-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    
    .available-gowns-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #344767;
      margin: 0;
    }
    
    .available-gowns-count {
      background: #f8f9fa;
      padding: 0.4rem 1rem;
      border-radius: 8px;
      font-size: 0.9rem;
      color: #67748e;
      display: flex;
      align-items: center;
    }
    
    .available-gowns-count i {
      margin-right: 0.5rem;
      color: #5e72e4;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .hero-section {
        padding: 2rem;
      }
      
      .hero-title {
        font-size: 2rem;
      }
      
      .hero-subtitle {
        max-width: 100%;
      }
      
      .gown-image-container {
        height: 300px;
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
    
    <!-- Hero Section -->
    
   
    <div class="row">
      <div class="col-12">
        <div class="filter-section">
          <h5 class="filter-title"><i class="fas fa-filter"></i> Filter by Category</h5>
          <div class="filter-options">
            <a href="find_gown.php" class="filter-option <?php echo (!isset($_GET['category']) || $_GET['category'] == 'all') ? 'active' : ''; ?>">All Gowns</a>
            <a href="find_gown.php?category=wedding" class="filter-option <?php echo (isset($_GET['category']) && $_GET['category'] == 'wedding') ? 'active' : ''; ?>">Wedding Gowns</a>
            <a href="find_gown.php?category=evening" class="filter-option <?php echo (isset($_GET['category']) && $_GET['category'] == 'evening') ? 'active' : ''; ?>">Evening Gowns</a>
            <a href="find_gown.php?category=prom" class="filter-option <?php echo (isset($_GET['category']) && $_GET['category'] == 'prom') ? 'active' : ''; ?>">Prom Dresses</a>
            <a href="find_gown.php?category=cocktail" class="filter-option <?php echo (isset($_GET['category']) && $_GET['category'] == 'cocktail') ? 'active' : ''; ?>">Cocktail Dresses</a>
          </div>
        </div>
      </div>
    </div>
    
    <div class="available-gowns-section">
      <div class="available-gowns-header">
        <h4 class="available-gowns-title">Available Gowns</h4>
        <div class="available-gowns-count">
          <i class="fas fa-dress"></i> Showing gowns that match your search criteria
        </div>
      </div>
      
      <div class="row">
    <?php
    // Get the current page from the URL, default to 1 if not set
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $itemsPerPage = 4; // Number of gowns per page
    $offset = ($currentPage - 1) * $itemsPerPage; // Calculate the offset

    $searchQuery = isset($_POST['search']) ? $_POST['search'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';

    // Prepare the SQL statement with LIMIT and OFFSET
    if ($category == 'all') {
        $sql = "SELECT * FROM gowns WHERE availability_status = 'available' AND name LIKE ? LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $searchParam = "%" . $searchQuery . "%";
        $stmt->bind_param("sii", $searchParam, $itemsPerPage, $offset);
    } else {
        $sql = "SELECT * FROM gowns WHERE availability_status = 'available' AND category = ? AND name LIKE ? LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $searchParam = "%" . $searchQuery . "%";
        $stmt->bind_param("ssii", $category, $searchParam, $itemsPerPage, $offset);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="col-lg-6 col-xl-3 mb-4">
                <div class="gown-card">
                    <div class="gown-image-container">
                        <img class="gown-image image-zoom" src="<?php echo str_replace('../', '', $row['main_image']); ?>" alt="<?php echo $row['name']; ?>">
                        <div class="gown-badge"><i class="fas fa-check-circle"></i> Available</div>
                    </div>
                    <div class="gown-details">
                        <h4 class="gown-title"><a href="view_gown.php?id=<?php echo $row['gown_id']; ?>"><?php echo $row['name']; ?></a></h4>
                        <div class="gown-price">
                          ₱<?php echo number_format($row['price'], 2); ?> <small>per rental</small>
                        </div>
                        <div class="gown-features">
                          <div class="gown-feature"><i class="fas fa-ruler"></i> <?php echo $row['size']; ?></div>
                          <div class="gown-feature"><i class="fas fa-palette"></i> <?php echo $row['color']; ?></div>
                        </div>
                        <p class="gown-description"><?php echo substr($row['description'], 0, 100) . (strlen($row['description']) > 100 ? '...' : ''); ?></p>
                        <div class="gown-actions">
                            <a href="reserve_gown.php?gown_id=<?php echo $row['gown_id']; ?>" class="btn btn-success">
                                <i class="fas fa-check-circle me-1"></i> Reserve
                            </a>
                            <a class="btn btn-warning" href="process/add_bookmark.php?gown_id=<?php echo $row['gown_id'];?>&user_id=<?php echo $user_id?>">
                                <i class="fas fa-bookmark me-1"></i> Bookmark
                            </a>
                            <a class="btn btn-primary" href="view_gown.php?gown_id=<?php echo $row['gown_id']; ?>">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="col-12">
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h4>No gowns found</h4>
                <p>Try adjusting your search criteria or browse all available gowns.</p>
                <a href="find_gown.php" class="btn btn-primary mt-3">View All Gowns</a>
            </div>
        </div>
        <?php
    }

    // Get the total number of gowns for pagination
    if ($category == 'all') {
        $countSql = "SELECT COUNT(*) as total FROM gowns WHERE name LIKE ?";
        $countStmt = $conn->prepare($countSql);
        $countStmt->bind_param("s", $searchParam);
    } else {
        $countSql = "SELECT COUNT(*) as total FROM gowns WHERE category = ? AND name LIKE ?";
        $countStmt = $conn->prepare($countSql);
        $countStmt->bind_param("ss", $category, $searchParam);
    }
    
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalRows = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $itemsPerPage); // Calculate total pages

    // Display pagination
    if ($totalPages > 1) {
        echo '<div class="col-12"><nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
        
        // Previous button
        if ($currentPage > 1) {
            echo '<li class="page-item">
                    <a class="page-link" href="?page=' . ($currentPage - 1) . '&search=' . urlencode($searchQuery) . '&category=' . $category . '">
                      <i class="fas fa-chevron-left"></i>
                    </a>
                  </li>';
        }
        
        // Page numbers
        for ($i = 1; $i <= $totalPages; $i++) {
            echo '<li class="page-item' . ($i == $currentPage ? ' active' : '') . '">
                    <a class="page-link" href="?page=' . $i . '&search=' . urlencode($searchQuery) . '&category=' . $category . '">' . $i . '</a>
                  </li>';
        }
        
        // Next button
        if ($currentPage < $totalPages) {
            echo '<li class="page-item">
                    <a class="page-link" href="?page=' . ($currentPage + 1) . '&search=' . urlencode($searchQuery) . '&category=' . $category . '">
                      <i class="fas fa-chevron-right"></i>
                    </a>
                  </li>';
        }
        
        echo '</ul></nav></div>';
    }

    $stmt->close();
    $countStmt->close();
    ?>
</div>

<div class="row">
  <div class="col-12">
    <div class="view-more">
      <p>Can't find what you're looking for? Contact us for custom gown options.</p>
   
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
  
  <script>
    // Make filter options interactive
    document.addEventListener('DOMContentLoaded', function() {
      const filterOptions = document.querySelectorAll('.filter-option');
      
      // Add hover effect to filter options
      filterOptions.forEach(option => {
        option.addEventListener('mouseenter', function() {
          if (!this.classList.contains('active')) {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 10px rgba(94, 114, 228, 0.2)';
          }
        });
        
        option.addEventListener('mouseleave', function() {
          if (!this.classList.contains('active')) {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
          }
        });
      });
      
      // Enhance search functionality
      const searchForm = document.querySelector('.hero-search form');
      const searchInput = searchForm.querySelector('input[name="search"]');
      
      searchInput.addEventListener('focus', function() {
        this.parentElement.style.boxShadow = '0 4px 15px rgba(94, 114, 228, 0.2)';
      });
      
      searchInput.addEventListener('blur', function() {
        this.parentElement.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
      });
      
      // Add animation to gown cards
      const gownCards = document.querySelectorAll('.gown-card');
      
      gownCards.forEach((card, index) => {
        // Add a staggered animation effect
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, 100 * index);
      });
    });
  </script>
  
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