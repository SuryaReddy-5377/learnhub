<?php
require_once 'config/database.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$course_id = intval($_GET['course'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($course_id <= 0) {
    redirect('courses.php');
}

// Check if already enrolled
$stmt = mysqli_prepare($conn, "SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $user_id, $course_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    $_SESSION['error'] = 'You are already enrolled in this course!';
    header('Location: course-details.php?id=' . $course_id);
    exit();
}
mysqli_stmt_close($stmt);

// Check if course exists
$stmt = mysqli_prepare($conn, "SELECT id, title, price FROM courses WHERE id = ? AND status = 'published'");
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$course = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$course) {
    redirect('courses.php');
}

// Enroll user
$stmt = mysqli_prepare($conn, "INSERT INTO enrollments (user_id, course_id, status, progress) VALUES (?, ?, 'active', 0)");
mysqli_stmt_bind_param($stmt, "ii", $user_id, $course_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Update course enrolled count
mysqli_query($conn, "UPDATE courses SET enrolled_students = enrolled_students + 1 WHERE id = $course_id");

$_SESSION['success'] = 'Successfully enrolled in ' . $course['title'] . '!';
header('Location: course-details.php?id=' . $course_id);
exit();
?>