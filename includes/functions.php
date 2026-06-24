<?php
// ============================================================
// LEARNHUB - HELPER FUNCTIONS
// ============================================================

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Check if user is instructor
function isInstructor() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'instructor';
}

// Check if user is student
function isStudent() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'student';
}

// Get user data by ID
function getUser($id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

// Get user by email
function getUserByEmail($email) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

// Generate OTP
function generateOTP() {
    return rand(100000, 999999);
}

// Get cart count
function getCartCount($user_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    return $result['total'] ?? 0;
}

// Get cart items
function getCartItems($user_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "
        SELECT c.*, b.title, b.author, b.price, b.image 
        FROM cart c 
        JOIN books b ON c.book_id = b.id 
        WHERE c.user_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

// Get cart total
function getCartTotal($user_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "
        SELECT SUM(c.quantity * b.price) as total 
        FROM cart c 
        JOIN books b ON c.book_id = b.id 
        WHERE c.user_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    return $result['total'] ?? 0;
}

// Get course enrollments count
function getEnrollmentsCount($course_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM enrollments WHERE course_id = ? AND status = 'active'");
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    return $result['count'] ?? 0;
}

// Get user enrollments
function getUserEnrollments($user_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "
        SELECT e.*, c.title, c.image, c.level, u.first_name, u.last_name
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        JOIN users u ON c.instructor_id = u.id
        WHERE e.user_id = ?
        ORDER BY e.enrolled_at DESC
    ");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

// Redirect function
function redirect($url) {
    if (headers_sent()) {
        echo '<script>window.location.href="' . $url . '";</script>';
        exit();
    } else {
        header('Location: ' . $url);
        exit();
    }
}

// Sanitize input
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Format price
function formatPrice($price) {
    return '₹' . number_format($price, 2);
}

// Time ago function
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . 'm ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . 'h ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . 'd ago';
    } else {
        return date('d M Y', $timestamp);
    }
}
?>