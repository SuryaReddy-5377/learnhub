<?php
require_once 'includes/send-email.php';

// Test with your email
$result = sendOTPEmail('suryareddy5377@gmail.com', '123456', 'Test User');

if ($result) {
    echo "✅ Email sent successfully!";
} else {
    echo "❌ Email failed! Check PHPMailer setup.";
}
?>