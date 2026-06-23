<?php
$page_title = 'Register';
require_once 'config/database.php';
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = 'All fields are required!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format!';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters!';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match!';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Email already registered!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'student';
            
            $stmt = mysqli_prepare($conn, "INSERT INTO users (first_name, last_name, email, password, role, is_verified) VALUES (?, ?, ?, ?, ?, 1)");
            mysqli_stmt_bind_param($stmt, "sssssi", $first_name, $last_name, $email, $hashed_password, $role, 1);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['registration_success'] = 'Registration successful! Please login.';
                header('Location: login.php');
                exit();
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="row justify-content-center" style="min-height: 80vh; align-items: center;">
    <div class="col-md-6">
        <div class="card form-card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="brand-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3 class="fw-bold mt-3">Create Account</h3>
                    <p class="text-muted">Join LearnHub today</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" placeholder="John" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" placeholder="Doe" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Create a password" required minlength="6">
                        <small class="text-muted">Must be at least 6 characters</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </button>
                </form>
                
                <div class="divider my-4">
                    <span>Already have an account?</span>
                </div>
                
                <p class="text-center mb-0">
                    <a href="login.php" class="text-decoration-none fw-bold" style="color: var(--primary);">
                        <i class="fas fa-sign-in-alt me-2"></i>Login Here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>