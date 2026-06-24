<?php
header('Content-Type: application/json');
require_once 'config/database.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $otp = trim($_POST['otp'] ?? '');
    
    if (empty($email) || empty($otp)) {
        $response['message'] = 'Email and OTP are required!';
    } else {
        // Verify OTP
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND otp = ? AND otp_expires > NOW()");
        mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Update user as verified
            $stmt2 = mysqli_prepare($conn, "UPDATE users SET is_verified = 1, otp = NULL, otp_expires = NULL WHERE email = ?");
            mysqli_stmt_bind_param($stmt2, "s", $email);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);
            
            $response['success'] = true;
            $response['message'] = 'Email verified successfully!';
        } else {
            // Check if OTP exists but expired
            $stmt2 = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND otp = ?");
            mysqli_stmt_bind_param($stmt2, "ss", $email, $otp);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_store_result($stmt2);
            
            if (mysqli_stmt_num_rows($stmt2) > 0) {
                $response['message'] = 'OTP has expired! Please request a new one.';
            } else {
                $response['message'] = 'Invalid OTP! Please try again.';
            }
            mysqli_stmt_close($stmt2);
        }
        mysqli_stmt_close($stmt);
    }
}

echo json_encode($response);
?>