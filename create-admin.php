<?php
require_once 'config/database.php';

// Delete existing admin
mysqli_query($conn, "DELETE FROM users WHERE email = 'admin@learnhub.com'");

// Insert new admin with password: Admin@123
$password = password_hash('Admin@123', PASSWORD_DEFAULT);
$sql = "INSERT INTO users (first_name, last_name, email, password, role) 
        VALUES ('Admin', 'LearnHub', 'admin@learnhub.com', '$password', 'admin')";

if (mysqli_query($conn, $sql)) {
    echo "✅ Admin created successfully!<br>";
    echo "📧 Email: <strong>admin@learnhub.com</strong><br>";
    echo "🔑 Password: <strong>Admin@123</strong><br><br>";
    echo "<a href='login.php' class='btn btn-primary'>Go to Login</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}
?>