<?php
$page_title = 'Edit Profile';
require_once 'config/database.php';
require_once 'includes/header.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$user = getUser($user_id);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);
    $bio = trim($_POST['bio']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($first_name) || empty($last_name)) {
        $error = 'Name is required!';
    } else {
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters!';
            } elseif ($password !== $confirm_password) {
                $error = 'Passwords do not match!';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = mysqli_prepare($conn, "UPDATE users SET first_name=?, last_name=?, phone=?, bio=?, password=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, "sssssi", $first_name, $last_name, $phone, $bio, $hashed_password, $user_id);
            }
        } else {
            $stmt = mysqli_prepare($conn, "UPDATE users SET first_name=?, last_name=?, phone=?, bio=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "ssssi", $first_name, $last_name, $phone, $bio, $user_id);
        }
        
        if (isset($stmt) && mysqli_stmt_execute($stmt)) {
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $success = 'Profile updated successfully!';
            $user = getUser($user_id);
        } elseif (isset($stmt)) {
            $error = 'Failed to update profile!';
        }
        if (isset($stmt)) mysqli_stmt_close($stmt);
    }
}
?>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-lg-8 col-md-10">
        <div class="card form-card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="brand-icon" style="width: 70px; height: 70px; font-size: 2rem;">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <h2 class="fw-bold welcome-title">Edit Profile</h2>
                    <p class="text-muted subtitle-text">Update your personal information</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">First Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="first_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Last Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="last_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="tel" name="phone" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                                   placeholder="Enter your phone number">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bio</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-quote-left"></i></span>
                            <textarea name="bio" class="form-control" rows="3" 
                                      placeholder="Tell us about yourself"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="fw-bold"><i class="fas fa-lock me-2"></i>Change Password (Optional)</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" 
                                       placeholder="Enter new password" minlength="6">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-check"></i></span>
                                <input type="password" name="confirm_password" class="form-control" 
                                       placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary py-2">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <a href="profile.php" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>Back to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>