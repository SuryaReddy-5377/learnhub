<?php
$page_title = 'Verify OTP';
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/send-email.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

if (!isset($_SESSION['temp_email'])) {
    redirect('register.php');
}

$email = $_SESSION['temp_email'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp'] ?? '');
    
    if (empty($otp)) {
        $error = 'Please enter the OTP!';
    } elseif (strlen($otp) !== 6 || !is_numeric($otp)) {
        $error = 'Please enter a valid 6-digit OTP!';
    } else {
        // SIMPLE CHECK - NO EXPIRY (Temporary)
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND otp = ?");
        mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Update user as verified
            $stmt2 = mysqli_prepare($conn, "UPDATE users SET is_verified = 1, otp = NULL, otp_expires = NULL WHERE email = ?");
            mysqli_stmt_bind_param($stmt2, "s", $email);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);
            
            $success = '✅ Email verified successfully! Redirecting to login...';
            unset($_SESSION['temp_email']);
            unset($_SESSION['last_otp']);
            
            echo '<meta http-equiv="refresh" content="2;url=login.php">';
        } else {
            $error = '❌ Invalid OTP! Please try again.';
        }
        mysqli_stmt_close($stmt);
    }
}

// Resend OTP
if (isset($_GET['resend'])) {
    $otp = rand(100000, 999999);
    
    // Get user name
    $stmt = mysqli_prepare($conn, "SELECT first_name FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    $name = $user['first_name'] ?? 'User';
    mysqli_stmt_close($stmt);
    
    // Update OTP (no expiry)
    $stmt = mysqli_prepare($conn, "UPDATE users SET otp = ?, otp_expires = DATE_ADD(NOW(), INTERVAL 60 MINUTE) WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $otp, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    // Send OTP via email
    sendOTPEmail($email, $otp, $name);
    $_SESSION['last_otp'] = $otp;
    
    $success = '✅ New OTP sent to your email!';
}
?>

<div class="row justify-content-center" style="min-height: 80vh; align-items: center;">
    <div class="col-md-5">
        <div class="card form-card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="brand-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="fw-bold mt-3">Verify Your Email</h3>
                    <p class="text-muted">Enter the 6-digit OTP sent to your email</p>
                    <p class="text-muted small">We sent a code to <strong><?php echo htmlspecialchars($email); ?></strong></p>
                    <p class="text-muted small">⏳ OTP is valid for <strong>60 minutes</strong></p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Enter OTP</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="text" name="otp" class="form-control" placeholder="Enter 6-digit OTP" maxlength="6" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check-circle me-2"></i>VERIFY OTP
                    </button>
                </form>
                
                <div class="divider my-4">
                    <span>Didn't receive code?</span>
                </div>
                
                <div class="text-center">
                    <a href="verify-otp.php?resend=1" class="btn btn-warning w-100">
                        <i class="fas fa-sync me-2"></i>Resend OTP
                    </a>
                </div>
                
                <p class="text-center mt-3">
                    <a href="register.php" class="text-decoration-none text-muted small">
                        <i class="fas fa-arrow-left me-1"></i>Back to Register
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>