<?php
session_start();
require 'Composer/vendor/autoload.php'; // Update this path to the actual location of autoload.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include 'components/connect.php';
function sendOTP($recipientEmail) {
    $otp = rand(100000, 999999); // Generate 6-digit OTP
    $_SESSION['otp'] = $otp; // Store OTP in session for later verification
    $mail = new PHPMailer(true);

    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username   = 'anauthordesigner@gmail.com'; // Change this
        $mail->Password   = 'almv jctr lhpi caab'; // Change this
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient settings
        $mail->setFrom('anauthordesigner@gmail.com', 'Icecream shop');
        $mail->addAddress($recipientEmail);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP code is: <b>$otp</b>";
        $mail->AltBody = "Your OTP code is: $otp";

        $mail->send();
        return true; // OTP sent successfully
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false; // Hide detailed error from the user
    }
}

$resendMessage = ''; // JavaScript alert message for resend OTP
if (isset($_GET['resend']) && $_GET['resend'] === 'true') {
    if (isset($_SESSION['email'])) {
        if (sendOTP($_SESSION['email'])) {
            $resendMessage = 'OTP has been resent to your email.';
        } else {
            $resendMessage = 'Failed to resend OTP. Please try again later.';
        }
    } else {
        $resendMessage = 'Session expired. Please start the process again.';
    }
}

// Handle OTP verification
$otpMessage = ''; // JavaScript alert message for OTP validation
if (isset($_POST['otp'])) {
    $otp = $_POST['otp'];
    if ($otp == $_SESSION['otp']) {
        echo "<script>
                alert('OTP Verification Successfully!');
                window.location.href='rp.php';
                </script>";
    } else {
        $otpMessage = 'Invalid OTP. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: url('image/ice-creem-banner-bg.png') no-repeat center center/cover;
        }
        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            color: #d63384;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #d63384;
            border-radius: 8px;
            outline: none;
            transition: border 0.3s;
        }
        .form-group input:focus {
            border-color: #bf236b;
            box-shadow: 0 0 8px rgba(214, 51, 132, 0.3);
        }
        button {
            background: #d63384;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            transition: background 0.3s;
        }
        button:hover {
            background: #bf236b;
        }
        .text-muted {
            margin-top: 15px;
            font-size: 14px;
        }
        .text-muted a {
            color: #d63384;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verify OTP</h2>
        <form method="POST">
            <div class="form-group">
                <label for="otp">Enter OTP</label>
                <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
            </div>
            <button type="submit" name="verify_otp">Verify OTP</button>
        </form>
        <div class="text-muted">
            <p>Didn’t receive OTP? <a href="?resend=true">Resend OTP</a></p>
        </div>
    </div>
</body>
</html>
