<?php
// Set secure session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');

// Set security headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'");

session_start();
include('../pages/process/config.php');

// CSRF Protection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) || $_SESSION['csrf_token'] !== $_POST['csrf_token']) {
        $_SESSION['status'] = "Invalid request";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header('location: ../index.php');
        exit();
    }
}

// Rate limiting by IP
function checkRateLimit() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $timeframe = 300; // 5 minutes
    $max_attempts = 10; // Maximum attempts per timeframe
    
    if (!isset($_SESSION['ip_attempts'][$ip])) {
        $_SESSION['ip_attempts'][$ip] = array(
            'count' => 0,
            'first_attempt' => time()
        );
    }
    
    // Reset if timeframe has passed
    if (time() - $_SESSION['ip_attempts'][$ip]['first_attempt'] > $timeframe) {
        $_SESSION['ip_attempts'][$ip] = array(
            'count' => 0,
            'first_attempt' => time()
        );
    }
    
    $_SESSION['ip_attempts'][$ip]['count']++;
    
    if ($_SESSION['ip_attempts'][$ip]['count'] > $max_attempts) {
        $_SESSION['status'] = "Too many requests from your IP. Please try again later.";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_button'] = "Okay";
        header('location: ../index.php');
        exit();
    }
}

// Anti-brute force protection
function checkBruteForce($email) {
  if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt'] = time();
    $_SESSION['lockout_until'] = 0;
  }

  // Check if user is in lockout period
  if ($_SESSION['lockout_until'] > time()) {
    $remaining = $_SESSION['lockout_until'] - time();
    $_SESSION['status'] = "Account is locked";
    $_SESSION['status_code'] = "error";
    $_SESSION['status_button'] = "Okay";
    $_SESSION['lockout_remaining'] = $remaining;
    header('location: ../index.php');
    exit();
  }

  // If 30 seconds have passed since last attempt, reset counter
  if ((time() - $_SESSION['last_attempt']) > 30) {
    $_SESSION['login_attempts'] = 0;
  }

  $_SESSION['last_attempt'] = time();
  $_SESSION['login_attempts']++;

  // If more than 3 attempts, lock for 30 seconds
  if ($_SESSION['login_attempts'] >= 3) {
    $_SESSION['lockout_until'] = time() + 30;
    $_SESSION['status'] = "Account is locked";
    $_SESSION['status_code'] = "error";
    $_SESSION['status_button'] = "Okay";
    $_SESSION['lockout_remaining'] = 30;
    header('location: ../index.php');
    exit();
  }
}

if (isset($_POST['email']) && isset($_POST['password'])) {
  // Check rate limit first
  checkRateLimit();
  
  // Anti XSS - Sanitize inputs
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = trim($_POST['password']); // Don't sanitize password before verification

  // Check for brute force attempts
  checkBruteForce($email);

  if (empty($email) || empty($password)) {
    $_SESSION['status'] = "Please fill in all fields";
    $_SESSION['status_code'] = "error";
    $_SESSION['status_button'] = "Okay";
    header('location: ../index.php');
    exit();
  }

  // Prevent SQL injection using prepared statements
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND status = 'Complete'");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify password using MD5
    if (md5($password) === $row['password']) {
      // Check password strength on login to prompt users with weak passwords
      $password_strength = 0;
      if (strlen($password) >= 8) $password_strength++;
      if (preg_match("/[A-Z]/", $password)) $password_strength++;
      if (preg_match("/[a-z]/", $password)) $password_strength++;
      if (preg_match("/[0-9]/", $password)) $password_strength++;
      if (preg_match("/[^A-Za-z0-9]/", $password)) $password_strength++;
      
      // Reset login attempts on successful login
      $_SESSION['login_attempts'] = 0;
      $_SESSION['lockout_until'] = 0;
      
      $_SESSION['email'] = $email;

      if ($row['user_type'] == 'admin') {
        // Include OTP sender
        require_once 'send_admin_otp.php';
        
        // Generate and send OTP
        $otp = sendAdminOTP($email);
        if ($otp === false) {
          $_SESSION['status'] = "Failed to send OTP. Please try again.";
          $_SESSION['status_code'] = "error";
          $_SESSION['status_button'] = "Okay";
          header('location: ../index.php');
          exit();
        }
        
        // Store OTP in session
        $_SESSION['admin_otp'] = $otp;
        $_SESSION['admin_otp_expiry'] = time() + (5 * 60); // 5 minutes expiry
        $_SESSION['is_admin_login'] = true;
        
        $_SESSION['status'] = "OTP has been sent to your email";
        $_SESSION['status_code'] = "info";
        $_SESSION['status_button'] = "Okay";
        session_regenerate_id(true);
        header('location: ../admin_otp.php');
        exit();
      } elseif ($row['user_type'] == 'customer') {
        $_SESSION['status'] = "Welcome Customer";
        $_SESSION['status_code'] = "info";
        $_SESSION['status_button'] = "Okay";
        // Regenerate session ID for security
        session_regenerate_id(true);
        header('location: ../pages/find_gown.php');
        exit();
      } else {
        header('location: default.php');
        exit();
      }
    } else {
      $_SESSION['status'] = "Invalid email or password";
      $_SESSION['status_code'] = "error";
      $_SESSION['status_button'] = "Okay";
      header('location: ../index.php');
      exit();
    }
  } else {
    $_SESSION['status'] = "Invalid email or password";
    $_SESSION['status_code'] = "error";
    $_SESSION['status_button'] = "Okay";
    header('location: ../index.php');
    exit();
  }

  $stmt->close();
} else {
  $_SESSION['status'] = "Access Denied";
  $_SESSION['status_code'] = "error";
  $_SESSION['status_button'] = "Okay";
  header('location: ../index.php');
  exit();
}

$conn->close();
?>
