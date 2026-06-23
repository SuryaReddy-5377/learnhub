<?php
$page_title = 'Manage Courses';
require_once '../config/database.php';
require_once 'admin-header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY created_at DESC");
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold"><i class="fas fa-book me-2"></i>Manage Courses</h2>
            <a href="add-course.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Course
            </a>
        </div>
        <p class="text-muted">Add, edit, or remove courses</p>
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
                                <th>Title</th>
                                <th>Price</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($courses) > 0): ?>
                                <?php while ($course = mysqli_fetch_assoc($courses)): ?>
                                <tr>
                                    <td>#<?php echo $course['id']; ?></td>
                                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                                    <td>₹<?php echo number_format($course['price'], 2); ?></td>
                                    <td><span class="badge bg-info"><?php echo ucfirst($course['level']); ?></span></td>
                                    <td>
                                        <span class="badge <?php echo $course['status'] === 'published' ? 'bg-success' : 'bg-warning'; ?>">
                                            <?php echo ucfirst($course['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="edit-course.php?id=<?php echo $course['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete-course.php?id=<?php echo $course['id']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Delete this course?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No courses found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'admin-footer.php'; ?>