<?php
$page_title = 'Dashboard';
require_once 'config/database.php';
require_once 'includes/header.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$user = getUser($user_id);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h2>
                <p class="text-muted">Your LearnHub Dashboard</p>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5>Active Courses</h5>
                                <h2>0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5>Completed</h5>
                                <h2>0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5>Total Enrollments</h5>
                                <h2>0</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="courses.php" class="btn btn-primary">Browse Courses</a>
                    <a href="profile.php" class="btn btn-secondary">My Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>