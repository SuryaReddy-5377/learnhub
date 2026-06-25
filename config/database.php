<?php
// =============================================
// LearnHub - Database Configuration
// Works on both localhost (VS Code) & InfinityFree
// =============================================

// --- CHANGE THESE FOR YOUR ENVIRONMENT ---
define('DB_HOST', 'sql202.infinityfree.com');   // InfinityFree host
define('DB_USER', 'if0_42259619');
define('DB_PASS', 'wDz3G3qOVz');
define('DB_NAME', 'if0_42259619_learnhub_db');
// -----------------------------------------

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper Functions
function redirect($url) {
    header('Location: ' . $url);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function getUser($id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}
?>