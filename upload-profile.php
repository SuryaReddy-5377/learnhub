<?php
require_once 'config/database.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if ($file_error !== 0) {
        $error = 'Error uploading file!';
    } elseif ($file_size > 5 * 1024 * 1024) {
        $error = 'File size too large! Maximum 5MB allowed.';
    } elseif (!in_array($file_ext, $allowed_exts)) {
        $error = 'Invalid file type! Allowed: JPG, JPEG, PNG, GIF, WEBP';
    } else {
        $upload_dir = 'assets/uploads/profiles/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $new_file_name = 'user_' . $user_id . '_' . time() . '.' . $file_ext;
        $upload_path = $upload_dir . $new_file_name;
        
        if (move_uploaded_file($file_tmp, $upload_path)) {
            $stmt = mysqli_prepare($conn, "SELECT profile_pic FROM users WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $old_pic = mysqli_fetch_assoc($result)['profile_pic'];
            mysqli_stmt_close($stmt);
            
            if ($old_pic && $old_pic !== 'default.png' && file_exists($upload_dir . $old_pic)) {
                unlink($upload_dir . $old_pic);
            }
            
            $stmt = mysqli_prepare($conn, "UPDATE users SET profile_pic = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $new_file_name, $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                header('Location: profile.php?success=1');
                exit();
            } else {
                $error = 'Failed to update database!';
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Failed to upload file!';
        }
    }
}

if ($error) {
    header('Location: profile.php?error=' . urlencode($error));
    exit();
}
?>