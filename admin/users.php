<?php
$page_title = 'Manage Users';
require_once '../config/database.php';
require_once 'admin-header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>

<div class="row">
    <div class="col-12">
        <h2 class="fw-bold"><i class="fas fa-users me-2"></i>Manage Users</h2>
        <p class="text-muted">View all registered users</p>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card form-card">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Verified</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = mysqli_fetch_assoc($users)): ?>
                            <tr>
                                <td>#<?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><span class="badge bg-primary"><?php echo ucfirst($user['role']); ?></span></td>
                                <td>
                                    <?php if ($user['is_verified']): ?>
                                        <span class="badge bg-success">Verified</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Not Verified</span>
                                    <?php endif; ?>
                                </td>
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

<?php require_once 'admin-footer.php'; ?>