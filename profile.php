<?php
$page_title = 'My Profile';
require_once 'config/database.php';
require_once 'includes/header.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$user = getUser($user_id);

$message = '';
if (isset($_GET['success'])) {
    $message = '<div class="alert alert-success">Profile updated successfully!</div>';
}
if (isset($_GET['error'])) {
    $message = '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
}
?>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-lg-8 col-md-10">
        <?php echo $message; ?>
        
        <div class="card form-card">
            <div class="card-body p-5 text-center">
                <!-- Profile Picture -->
                <div class="profile-section">
                    <div class="profile-picture-wrapper">
                        <?php 
                        $profile_pic = $user['profile_pic'] ?? 'default.png';
                        $pic_path = 'assets/uploads/profiles/' . $profile_pic;
                        if (!file_exists($pic_path)) {
                            $pic_path = 'assets/uploads/profiles/default.png';
                        }
                        ?>
                        <img src="<?php echo $pic_path; ?>" 
                             alt="Profile Picture" 
                             class="profile-picture"
                             id="profilePreview">
                        <div class="profile-upload">
                            <form action="upload-profile.php" method="POST" enctype="multipart/form-data">
                                <label for="profilePicInput" class="upload-label">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" name="profile_pic" id="profilePicInput" accept="image/*" style="display: none;">
                                <button type="submit" class="btn btn-primary btn-sm upload-btn" style="display: none;">
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                            </form>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">Max: 5MB | JPG, PNG, GIF, WEBP</small>
                </div>

                <!-- User Info -->
                <div class="profile-info mt-4">
                    <h2 class="fw-bold"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                    <p class="text-muted"><i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($user['email']); ?></p>
                    <p class="text-muted"><i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></p>
                    <p>
                        <span class="badge bg-primary"><?php echo ucfirst($user['role']); ?></span>
                        <?php if ($user['is_verified']): ?>
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Verified</span>
                        <?php endif; ?>
                    </p>
                    <p><small class="text-muted">Member since: <?php echo date('d M Y', strtotime($user['created_at'])); ?></small></p>
                    
                    <?php if ($user['bio']): ?>
                        <p class="mt-3"><i class="fas fa-quote-left me-2 text-muted"></i><?php echo htmlspecialchars($user['bio']); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4">
                    <a href="edit-profile.php" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>