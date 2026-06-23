<?php
$page_title = 'Forgot Password';
require_once 'config/database.php';
require_once 'includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $error = 'Please enter your email address!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format!';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $stmt2 = mysqli_prepare($conn, "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
            mysqli_stmt_bind_param($stmt2, "sss", $token, $expires, $email);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);
            
            // Send reset link (simplified)
            $reset_link = SITE_URL . 'reset-password.php?token=' . $token;
            $success = 'Password reset link sent to your email. Please check your inbox.';
        } else {
            $error = 'Email not found!';
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="row justify-content-center" style="min-height: 80vh; align-items: center;">
    <div class="col-lg-5 col-md-8 col-sm-10" data-aos="fade-up">
        <div class="card form-card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="brand-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h2 class="fw-bold welcome-title">Forgot Password</h2>
                    <p class="text-muted subtitle-text">Enter your email to reset your password</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-envelope me-2"></i>Email Address
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                    </button>
                </form>
                
                <div class="divider">
                    <span>Remember your password?</span>
                </div>
                
                <p class="text-center mt-3 mb-0">
                    <a href="login.php" class="text-decoration-none fw-semibold register-link">
                        <i class="fas fa-sign-in-alt me-2"></i>Back to Login
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>