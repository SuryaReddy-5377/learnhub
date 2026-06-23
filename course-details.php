<?php
$page_title = 'Course Details';
require_once 'config/database.php';
require_once 'includes/header.php';

$course_id = intval($_GET['id'] ?? 0);

if ($course_id <= 0) {
    redirect('courses.php');
}

// Get course details
$stmt = mysqli_prepare($conn, "
    SELECT c.*, u.first_name, u.last_name, u.id as instructor_id, 
           cat.name as category_name,
           (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') as enrolled_count,
           (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as total_lessons
    FROM courses c
    JOIN users u ON c.instructor_id = u.id
    JOIN categories cat ON c.category_id = cat.id
    WHERE c.id = ? AND c.status = 'published'
");
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$course = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$course) {
    redirect('courses.php');
}

// Check if user is enrolled
$is_enrolled = false;
$enrollment_progress = 0;
if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    $stmt = mysqli_prepare($conn, "SELECT status, progress FROM enrollments WHERE user_id = ? AND course_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $course_id);
    mysqli_stmt_execute($stmt);
    $enrollment = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    
    if ($enrollment) {
        $is_enrolled = true;
        $enrollment_progress = $enrollment['progress'];
    }
}

// Get lessons
$lessons = mysqli_query($conn, "SELECT * FROM lessons WHERE course_id = $course_id ORDER BY order_number ASC");

// Get instructor's other courses
$instructor_courses = mysqli_query($conn, "
    SELECT id, title, rating, price 
    FROM courses 
    WHERE instructor_id = {$course['instructor_id']} AND id != $course_id AND status = 'published' 
    LIMIT 3
");
?>

<div class="row" data-aos="fade-up">
    <!-- Course Main Content -->
    <div class="col-lg-8">
        <!-- Course Image -->
        <div class="card form-card mb-4">
            <div class="card-body p-0">
                <img src="assets/uploads/courses/<?php echo $course['image'] ?? 'default-course.jpg'; ?>" 
                     alt="<?php echo htmlspecialchars($course['title']); ?>"
                     style="width: 100%; height: 350px; object-fit: cover; border-radius: 20px 20px 0 0;">
            </div>
        </div>

        <!-- Course Info -->
        <div class="card form-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="fw-bold welcome-title"><?php echo htmlspecialchars($course['title']); ?></h1>
                        <p class="text-muted">
                            <i class="fas fa-user me-1"></i>
                            <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?>
                            <span class="mx-2">|</span>
                            <i class="fas fa-tag me-1"></i>
                            <?php echo htmlspecialchars($course['category_name']); ?>
                            <span class="mx-2">|</span>
                            <i class="fas fa-signal me-1"></i>
                            <?php echo ucfirst($course['level']); ?>
                        </p>
                    </div>
                    <div class="text-end">
                        <h2 class="text-primary">
                            <?php if ($course['price'] > 0): ?>
                                ₹<?php echo number_format($course['price'], 2); ?>
                            <?php else: ?>
                                Free
                            <?php endif; ?>
                        </h2>
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star"></i> <?php echo number_format($course['rating'] ?? 0, 1); ?>
                        </span>
                        <span class="badge bg-secondary">
                            <i class="fas fa-users"></i> <?php echo $course['enrolled_count']; ?> students
                        </span>
                    </div>
                </div>

                <hr>

                <!-- Description -->
                <h5 class="fw-bold"><i class="fas fa-align-left me-2"></i>Description</h5>
                <p><?php echo nl2br(htmlspecialchars($course['description'] ?? 'No description available.')); ?></p>

                <!-- Course Stats -->
                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <div class="stat-card p-3">
                            <div class="stat-icon"><i class="fas fa-video"></i></div>
                            <h4><?php echo $course['total_lessons']; ?></h4>
                            <small>Lessons</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card p-3">
                            <div class="stat-icon"><i class="fas fa-clock"></i></div>
                            <h4><?php echo $course['duration'] ?? 'N/A'; ?></h4>
                            <small>Total Duration</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card p-3">
                            <div class="stat-icon"><i class="fas fa-users"></i></div>
                            <h4><?php echo $course['enrolled_count']; ?></h4>
                            <small>Enrolled Students</small>
                        </div>
                    </div>
                </div>

                <!-- Lessons -->
                <h5 class="fw-bold mt-4"><i class="fas fa-list me-2"></i>Course Content</h5>
                <div class="list-group">
                    <?php if (mysqli_num_rows($lessons) > 0): ?>
                        <?php $lesson_num = 1; ?>
                        <?php while ($lesson = mysqli_fetch_assoc($lessons)): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary me-2"><?php echo $lesson_num; ?></span>
                                <?php echo htmlspecialchars($lesson['title']); ?>
                                <?php if ($lesson['is_preview']): ?>
                                    <span class="badge bg-info text-white ms-2">Preview</span>
                                <?php endif; ?>
                            </div>
                            <span>
                                <?php if ($lesson['is_preview'] || $is_enrolled): ?>
                                    <a href="lesson.php?course=<?php echo $course_id; ?>&lesson=<?php echo $lesson['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-play"></i>
                                    </a>
                                <?php else: ?>
                                    <i class="fas fa-lock text-muted"></i>
                                <?php endif; ?>
                                <small class="text-muted ms-2"><?php echo $lesson['duration']; ?> min</small>
                            </span>
                        </div>
                        <?php $lesson_num++; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="list-group-item text-muted">No lessons available yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Enroll Button -->
        <div class="card form-card mb-4">
            <div class="card-body p-4">
                <?php if (isLoggedIn()): ?>
                    <?php if ($is_enrolled): ?>
                        <div class="text-center">
                            <h5 class="text-success">
                                <i class="fas fa-check-circle me-2"></i>Enrolled
                            </h5>
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar bg-primary" style="width: <?php echo $enrollment_progress; ?>%;"></div>
                            </div>
                            <p class="text-muted small"><?php echo $enrollment_progress; ?>% complete</p>
                            <a href="lesson.php?course=<?php echo $course_id; ?>" class="btn btn-primary w-100">
                                <i class="fas fa-play me-2"></i>Continue Learning
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <h5 class="fw-bold">Enroll Now</h5>
                            <p class="text-muted">Start learning this course today!</p>
                            <a href="enroll.php?course=<?php echo $course_id; ?>" class="btn btn-primary w-100">
                                <i class="fas fa-shopping-cart me-2"></i>Enroll Now
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center">
                        <p class="text-muted">Please login to enroll in this course.</p>
                        <a href="login.php" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Login to Enroll
                        </a>
                        <a href="register.php" class="btn btn-outline-primary w-100 mt-2">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Instructor Info -->
        <div class="card form-card mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold"><i class="fas fa-user-tie me-2"></i>Instructor</h5>
                <div class="d-flex align-items-center mt-2">
                    <div class="profile-picture-wrapper me-3">
                        <img src="assets/uploads/profiles/default.png" 
                             style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary);">
                    </div>
                    <div>
                        <h6 class="mb-0"><?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?></h6>
                        <small class="text-muted">Instructor</small>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Total Students</span>
                    <span class="fw-bold"><?php echo $course['enrolled_count']; ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Course Rating</span>
                    <span class="fw-bold">
                        <i class="fas fa-star text-warning"></i> <?php echo number_format($course['rating'] ?? 0, 1); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Instructor's Other Courses -->
        <?php if (mysqli_num_rows($instructor_courses) > 0): ?>
        <div class="card form-card">
            <div class="card-body p-4">
                <h5 class="fw-bold"><i class="fas fa-book me-2"></i>More from Instructor</h5>
                <?php while ($ic = mysqli_fetch_assoc($instructor_courses)): ?>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <h6 class="mb-0"><?php echo htmlspecialchars($ic['title']); ?></h6>
                        <small class="text-muted">
                            <i class="fas fa-star text-warning"></i> <?php echo number_format($ic['rating'] ?? 0, 1); ?>
                        </small>
                    </div>
                    <a href="course-details.php?id=<?php echo $ic['id']; ?>" class="btn btn-sm btn-primary">View</a>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>