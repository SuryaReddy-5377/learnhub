<?php
$page_title = 'Reset Password';
require_once 'config/database.php';
require_once 'includes/header.php';

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

// Verify token
if (empty($token)) {
    redirect('forgot-password.php');
}

$stmt = mysqli_prepare($conn, "SELECT email FROM users WHERE reset_token = ? AND reset_expires > NOW()");
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    $error = 'Invalid or expired reset token!';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($password) || strlen($password) < 6) {
        $error = 'Password must be at least 6 characters!';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match!';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $email = $user['email'];
        
        $stmt = mysqli_prepare($conn, "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        $success = 'Password reset successfully! <a href="login.php">Login here</a>';
    }
}
?>

<div class="row justify-content-center" style="min-height: 80vh; align-items: center;">
    <div class="col-lg-5 col-md-8 col-sm-10" data-aos="fade-up">
        <div class="card form-card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="brand-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h2 class="fw-bold welcome-title">Reset Password</h2>
                    <p class="text-muted subtitle-text">Enter your new password</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <?php if ($user && !$success): ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Enter new password" required minlength="6">
                        </div>
                        <small class="text-muted">Password must be at least 6 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-check"></i></span>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-save me-2"></i>Reset Password
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>