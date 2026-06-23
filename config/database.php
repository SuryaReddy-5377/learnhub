<?php
// Database Configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'learnhub_db';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect function
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
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}
?>