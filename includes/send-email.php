<?php
// PHPMailer - works on both localhost and InfinityFree
$_base = dirname(__FILE__) . '/../vendor/phpmailer/';
if (!file_exists($_base . 'PHPMailer.php')) {
    // fallback for InfinityFree absolute path
    $_base = $_SERVER['DOCUMENT_ROOT'] . '/learnhub/vendor/phpmailer/';
}
require_once $_base . 'Exception.php';
require_once $_base . 'PHPMailer.php';
require_once $_base . 'SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendOTPEmail($to, $otp, $name) {
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug  = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'suryareddy5377@gmail.com';
        $mail->Password   = 'rsty ekav wunc ihaa';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('suryareddy5377@gmail.com', 'LearnHub');
        $mail->addAddress($to, $name);

        $mail->isHTML(true);
        $mail->Subject = '🔐 LearnHub - Verify Your Email';
        $mail->Body = "
        <html><head>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f4f4; }
            .container { max-width: 500px; margin: 30px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
            .header { background: linear-gradient(135deg, #6C3CE1, #EC4899); color: white; padding: 24px; text-align: center; }
            .body { padding: 28px; }
            .otp-code { font-size: 42px; font-weight: bold; color: #6C3CE1; padding: 20px; background: #f0f0ff; text-align: center; border-radius: 10px; letter-spacing: 12px; margin: 20px 0; }
            .footer { text-align: center; font-size: 12px; color: #999; padding: 16px; }
        </style>
        </head><body>
        <div class='container'>
            <div class='header'><h2>📚 LearnHub</h2><p>Email Verification</p></div>
            <div class='body'>
                <h3>Hello $name! 👋</h3>
                <p>Your one-time OTP for email verification is:</p>
                <div class='otp-code'>$otp</div>
                <p>⏳ This OTP is valid for <strong>30 minutes</strong>.</p>
                <p>If you didn't register, please ignore this email.</p>
            </div>
            <div class='footer'>© LearnHub — Do not reply to this email.</div>
        </div>
        </body></html>";
        $mail->AltBody = "Hello $name,\n\nYour OTP is: $otp\n\nValid for 30 minutes.\n\n— LearnHub";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("LearnHub Email Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>