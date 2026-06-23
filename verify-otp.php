<?php
$page_title = 'Verify OTP';
require_once 'config/database.php';
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

// Check if email exists in session
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
    } else {
        // Verify OTP
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND otp = ? AND otp_expires > NOW()");
        mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Update user as verified
            $stmt2 = mysqli_prepare($conn, "UPDATE users SET is_verified = 1, otp = NULL, otp_expires = NULL WHERE email = ?");
            mysqli_stmt_bind_param($stmt2, "s", $email);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);
            
            $success = 'Email verified successfully! You can now login.';
            
            // Clear temp email from session
            unset($_SESSION['temp_email']);
            
            // Redirect to login after 2 seconds
            echo '<meta http-equiv="refresh" content="2;url=login.php">';
        } else {
            $error = 'Invalid or expired OTP! Please try again.';
        }
        mysqli_stmt_close($stmt);
    }
}

// Resend OTP
if (isset($_GET['resend'])) {
    $otp = generateOTP();
    $otp_expires = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    
    $stmt = mysqli_prepare($conn, "UPDATE users SET otp = ?, otp_expires = ? WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "sss", $otp, $otp_expires, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    // Send OTP email (simplified)
    $success = 'New OTP sent to your email!';
}
?>

<div class="row justify-content-center" style="min-height: 80vh; align-items: center;">
    <div class="col-lg-5 col-md-8 col-sm-10" data-aos="fade-up">
        <div class="card form-card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="brand-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h2 class="fw-bold welcome-title">Verify Your Email</h2>
                    <p class="text-muted subtitle-text">Enter the OTP sent to your email</p>
                    <p class="text-muted small">We sent a 6-digit code to <strong><?php echo htmlspecialchars($email); ?></strong></p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Enter OTP</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="text" name="otp" class="form-control" placeholder="Enter 6-digit OTP" maxlength="6" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-check-circle me-2"></i>VERIFY OTP
                    </button>
                </form>
                
                <div class="divider">
                    <span>Didn't receive code?</span>
                </div>
                
                <p class="text-center mt-3 mb-0">
                    <a href="verify-otp.php?resend=1" class="text-decoration-none fw-semibold register-link">
                        <i class="fas fa-sync me-2"></i>Resend OTP
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>