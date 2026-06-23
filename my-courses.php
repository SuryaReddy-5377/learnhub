<?php
$page_title = 'My Courses';
require_once 'config/database.php';
require_once 'includes/header.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Get all enrolled courses
$stmt = mysqli_prepare($conn, "
    SELECT e.*, c.title, c.image, c.level, c.instructor_id, 
           u.first_name, u.last_name,
           (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as total_lessons
    FROM enrollments e
    JOIN courses c ON e.course_id = c.id
    JOIN users u ON c.instructor_id = u.id
    WHERE e.user_id = ?
    ORDER BY e.enrolled_at DESC
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$enrolled_courses = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);
?>

<div class="row" data-aos="fade-up">
    <div class="col-12">
        <div class="card form-card">
            <div class="card-body p-4">
                <h2 class="fw-bold welcome-title">
                    <i class="fas fa-graduation-cap me-2"></i>My Courses
                </h2>
                <p class="text-muted">All your enrolled courses in one place</p>
                
                <?php if (mysqli_num_rows($enrolled_courses) > 0): ?>
                    <div class="row g-4 mt-3">
                        <?php while ($course = mysqli_fetch_assoc($enrolled_courses)): ?>
                        <div class="col-md-4">
                            <div class="course-card">
                                <div class="course-image" style="height: 180px;">
                                    <img src="assets/uploads/courses/<?php echo $course['image'] ?? 'default-course.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($course['title']); ?>">
                                    <span class="course-level"><?php echo ucfirst($course['level']); ?></span>
                                    <span class="course-badge <?php echo $course['status'] === 'completed' ? 'completed' : 'active'; ?>">
                                        <?php echo $course['status'] === 'completed' ? 'Completed' : 'In Progress'; ?>
                                    </span>
                                </div>
                                <div class="course-body">
                                    <h6 class="course-title">
                                        <a href="course-details.php?id=<?php echo $course['course_id']; ?>">
                                            <?php echo htmlspecialchars($course['title']); ?>
                                        </a>
                                    </h6>
                                    <p class="course-instructor">
                                        <i class="fas fa-user me-1"></i>
                                        <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?>
                                    </p>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-primary" style="width: <?php echo $course['progress']; ?>%;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small"><?php echo $course['progress']; ?>% complete</span>
                                        <a href="lesson.php?course=<?php echo $course['course_id']; ?>" class="btn btn-primary btn-sm">
                                            <?php echo $course['status'] === 'completed' ? 'Review' : 'Continue'; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-book-open" style="font-size: 4rem; color: var(--text-muted);"></i>
                        <h3 class="mt-3">No Courses Enrolled</h3>
                        <p class="text-muted">Start your learning journey today!</p>
                        <a href="courses.php" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Browse Courses
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>