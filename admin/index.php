<?php
$page_title = 'Dashboard';
require_once '../config/database.php';
require_once 'admin-header.php';

// Check if logged in and admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Get statistics
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$total_courses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM courses"))['count'];
$total_enrollments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM enrollments"))['count'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(price) as total FROM courses"))['total'] ?? 0;

// Recent users
$recent_users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
?>

<div class="row">
    <div class="col-12">
        <h2 class="fw-bold">Admin Dashboard</h2>
        <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mt-2">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <h3><?php echo $total_users; ?></h3>
            <small>Total Users</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-book"></i></div>
            <h3><?php echo $total_courses; ?></h3>
            <small>Total Courses</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
            <h3><?php echo $total_enrollments; ?></h3>
            <small>Total Enrollments</small>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-rupee-sign"></i></div>
            <h3>₹<?php echo number_format($total_revenue, 2); ?></h3>
            <small>Total Revenue</small>
        </div>
    </div>
</div>

<!-- Recent Users -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card form-card">
            <div class="card-body p-4">
                <h5 class="fw-bold"><i class="fas fa-users me-2"></i>Recent Users</h5>
                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = mysqli_fetch_assoc($recent_users)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><span class="badge bg-primary"><?php echo ucfirst($user['role']); ?></span></td>
                                <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card form-card">
            <div class="card-body p-4">
                <h5 class="fw-bold"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                <div class="d-flex flex-wrap gap-3">
                    <a href="courses.php" class="btn btn-primary">
                        <i class="fas fa-book me-2"></i>Manage Courses
                    </a>
                    <a href="users.php" class="btn btn-success">
                        <i class="fas fa-users me-2"></i>Manage Users
                    </a>
                    <a href="analytics.php" class="btn btn-info text-white">
                        <i class="fas fa-chart-line me-2"></i>Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'admin-footer.php'; ?>