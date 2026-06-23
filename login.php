<?php
$page_title = 'Login';
require_once 'config/database.php';
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';
$success = '';

// Check for registration success message
if (isset($_SESSION['registration_success'])) {
    $success = $_SESSION['registration_success'];
    unset($_SESSION['registration_success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter email and password!';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
            if ($user['role'] === 'admin') {
                redirect('admin/index.php');
            } else {
                redirect('dashboard.php');
            }
            exit();
        } else {
            $error = 'Invalid email or password!';
        }
    }
}
?>

<div class="row justify-content-center" style="min-height: 80vh; align-items: center;">
    <div class="col-md-6">
        <div class="card form-card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="brand-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="fw-bold mt-3">Welcome Back</h3>
                    <p class="text-muted">Login to your LearnHub account</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>
                
                <div class="divider my-4">
                    <span>New to LearnHub?</span>
                </div>
                
                <p class="text-center mb-0">
                    <a href="register.php" class="text-decoration-none fw-bold" style="color: var(--primary);">
                        <i class="fas fa-user-plus me-2"></i>Create an Account
                    </a>
                </p>
                <p class="text-center mt-2">
                    <a href="forgot-password.php" class="text-decoration-none text-muted small">Forgot Password?</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>