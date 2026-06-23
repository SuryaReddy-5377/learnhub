<?php
$page_title = 'Lesson';
require_once 'config/database.php';
require_once 'includes/header.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$course_id = intval($_GET['course'] ?? 0);
$lesson_id = intval($_GET['lesson'] ?? 0);

if ($course_id <= 0) {
    redirect('courses.php');
}

// Check enrollment
$user_id = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT id, progress FROM enrollments WHERE user_id = ? AND course_id = ? AND status = 'active'");
mysqli_stmt_bind_param($stmt, "ii", $user_id, $course_id);
mysqli_stmt_execute($stmt);
$enrollment = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$enrollment) {
    redirect('course-details.php?id=' . $course_id);
}

// Get course info
$course = mysqli_fetch_assoc(mysqli_query($conn, "SELECT title FROM courses WHERE id = $course_id"));

// Get current lesson
if ($lesson_id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM lessons WHERE id = ? AND course_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $lesson_id, $course_id);
    mysqli_stmt_execute($stmt);
    $current_lesson = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
} else {
    // Get first lesson
    $current_lesson = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lessons WHERE course_id = $course_id ORDER BY order_number ASC LIMIT 1"));
}

if (!$current_lesson) {
    redirect('course-details.php?id=' . $course_id);
}

// Get all lessons for navigation
$lessons = mysqli_query($conn, "SELECT id, title, order_number FROM lessons WHERE course_id = $course_id ORDER BY order_number ASC");
$all_lessons = [];
while ($l = mysqli_fetch_assoc($lessons)) {
    $all_lessons[] = $l;
}

// Find current index and next/prev
$current_index = 0;
foreach ($all_lessons as $index => $l) {
    if ($l['id'] == $current_lesson['id']) {
        $current_index = $index;
        break;
    }
}

$prev_lesson = $current_index > 0 ? $all_lessons[$current_index - 1] : null;
$next_lesson = $current_index < count($all_lessons) - 1 ? $all_lessons[$current_index + 1] : null;

// Update progress
if ($enrollment['progress'] < 100) {
    $new_progress = min(100, $enrollment['progress'] + round(100 / count($all_lessons)));
    $stmt = mysqli_prepare($conn, "UPDATE enrollments SET progress = ? WHERE user_id = ? AND course_id = ?");
    mysqli_stmt_bind_param($stmt, "iii", $new_progress, $user_id, $course_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
?>

<div class="row" data-aos="fade-up">
    <div class="col-lg-8 mx-auto">
        <div class="card form-card">
            <div class="card-body p-4">
                <!-- Progress -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold">
                        <i class="fas fa-graduation-cap me-2"></i>
                        <?php echo htmlspecialchars($course['title']); ?>
                    </h5>
                    <span class="badge bg-primary"><?php echo $enrollment['progress']; ?>% complete</span>
                </div>
                <div class="progress mb-4" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: <?php echo $enrollment['progress']; ?>%;"></div>
                </div>

                <!-- Lesson Title -->
                <h2 class="fw-bold welcome-title">
                    <?php echo htmlspecialchars($current_lesson['title']); ?>
                </h2>
                <p class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    <?php echo $current_lesson['duration']; ?> minutes
                </p>

                <!-- Video Player -->
                <div class="ratio ratio-16x9 mb-4">
                    <?php if ($current_lesson['video_url']): ?>
                        <video controls class="w-100 rounded">
                            <source src="<?php echo htmlspecialchars($current_lesson['video_url']); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-dark rounded text-white">
                            <div class="text-center">
                                <i class="fas fa-video" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="mt-2">Video content coming soon</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Lesson Description -->
                <?php if ($current_lesson['description']): ?>
                <div class="mb-4">
                    <h6 class="fw-bold">Description</h6>
                    <p><?php echo nl2br(htmlspecialchars($current_lesson['description'])); ?></p>
                </div>
                <?php endif; ?>

                <!-- Navigation -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div>
                        <?php if ($prev_lesson): ?>
                            <a href="lesson.php?course=<?php echo $course_id; ?>&lesson=<?php echo $prev_lesson['id']; ?>" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Previous
                            </a>
                        <?php else: ?>
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="fas fa-arrow-left me-2"></i>Previous
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <?php if ($enrollment['progress'] >= 100): ?>
                            <a href="course-details.php?id=<?php echo $course_id; ?>" class="btn btn-success">
                                <i class="fas fa-check me-2"></i>Course Complete!
                            </a>
                        <?php else: ?>
                            <?php if ($next_lesson): ?>
                                <a href="lesson.php?course=<?php echo $course_id; ?>&lesson=<?php echo $next_lesson['id']; ?>" 
                                   class="btn btn-primary">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            <?php else: ?>
                                <a href="course-details.php?id=<?php echo $course_id; ?>" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Finish Course
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Lesson List -->
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-bold"><i class="fas fa-list me-2"></i>Course Content</h6>
                    <div class="list-group">
                        <?php foreach ($all_lessons as $index => $l): ?>
                        <a href="lesson.php?course=<?php echo $course_id; ?>&lesson=<?php echo $l['id']; ?>" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo $l['id'] == $current_lesson['id'] ? 'active' : ''; ?>">
                            <span>
                                <span class="badge <?php echo $l['id'] == $current_lesson['id'] ? 'bg-light text-dark' : 'bg-secondary'; ?> me-2">
                                    <?php echo $index + 1; ?>
                                </span>
                                <?php echo htmlspecialchars($l['title']); ?>
                            </span>
                            <?php if ($l['id'] == $current_lesson['id']): ?>
                                <i class="fas fa-play-circle"></i>
                            <?php endif; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>