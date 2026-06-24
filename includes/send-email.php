<?php
// PHPMailer includes - USE ABSOLUTE PATH
require_once $_SERVER['DOCUMENT_ROOT'] . '/learnhub/vendor/phpmailer/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/learnhub/vendor/phpmailer/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/learnhub/vendor/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendOTPEmail($to, $otp, $name) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'suryareddy5377@gmail.com';
        $mail->Password   = 'rsty ekav wunc ihaa'; // YOUR APP PASSWORD
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Recipients
        $mail->setFrom('suryareddy5377@gmail.com', 'LearnHub');
        $mail->addAddress($to, $name);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = '🔐 LearnHub - Verify Your Email';
        $mail->Body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .container { max-width: 500px; margin: 0 auto; padding: 20px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
                    .header { background: linear-gradient(135deg, #6C3CE1, #EC4899); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                    .otp-code { font-size: 40px; font-weight: bold; color: #6C3CE1; padding: 20px; background: #f0f0ff; text-align: center; border-radius: 10px; letter-spacing: 10px; margin: 20px 0; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>📚 LearnHub</h2>
                    </div>
                    <div style='padding: 20px;'>
                        <h3>Hello $name!</h3>
                        <p>Your OTP for email verification is:</p>
                        <div class='otp-code'>$otp</div>
                        <p>This OTP is valid for <strong>30 minutes</strong>.</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        $mail->AltBody = "Hello $name,\n\nYour OTP is: $otp\n\nValid for 30 minutes.";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>