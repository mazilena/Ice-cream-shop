<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include 'components/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = trim($_POST['otp']);

    if (!isset($_SESSION['reset_email'])) {
        session_destroy(); //Ensure session reset
        echo "<script>alert('Session expired! Try again.'); window.location='forgot_password.php';</script>";
        exit();
    }

    $email = $_SESSION['reset_email'];

    //Fetch OTP & expiry from DB
    $stmt = $conn->prepare("SELECT otp, otp_expiry FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        echo "<script>alert('Invalid session! Try again.'); window.location='forgot_password.php';</script>";
        exit();
    }

    $stored_otp = trim($user['otp']);  
    $otp_expiry = $user['otp_expiry'];

    //Convert expiry time to timestamp
    $expiry_timestamp = strtotime($otp_expiry);
    $current_time = time();

    //Debugging - Print OTP Details
    error_log("Stored OTP: " . $stored_otp);
    error_log("Entered OTP: " . $otp);
    error_log("DB Expiry Time: " . $otp_expiry);
    error_log("Current Server Time: " . date('Y-m-d H:i:s', $current_time));

    //Expiry Check
    if ($current_time > $expiry_timestamp) {
        $clear_otp = $conn->prepare("UPDATE users SET otp = NULL, otp_expiry = NULL WHERE email = ?");
        $clear_otp->execute([$email]);

        session_destroy();
        echo "<script>alert('OTP Expired! Request a new one.'); window.location='forgot_password.php';</script>";
        exit();
    }

    //OTP Check
    if ($stored_otp === $otp) {
        $_SESSION['verified_email'] = $email;

        // Clear OTP after successful verification
        $clear_otp = $conn->prepare("UPDATE users SET otp = NULL, otp_expiry = NULL WHERE email = ?");
        $clear_otp->execute([$email]);

        echo "<script>alert('OTP Verified! Set new password.'); window.location='reset_password.php';</script>";
        exit();
    } else {
        //Clear session so user can request a new OTP
        session_destroy();
        echo "<script>alert('Invalid OTP! Request a new one.'); window.location='forgot_password.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: transparent;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            height: 250px;
            max-width: 450px;
        }
        h2 {
            color:rgb(8, 8, 8);
        }
        input {
            width: 70%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #d63384;
            border-radius: 5px;
        }
        button {
            background: #d63384;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #bf236b;
        }
    </style>
</head>
<body style="background: url('image/ice-creem-banner-bg.png') no-repeat ">
    <div class="container">
    <h2>Verify OTP</h2>
    <form method="POST">
        <input type="text" name="otp" required placeholder="Enter OTP" minlength="6" maxlength="6" pattern="\d{6}">
        <button type="submit">Verify</button>
    </form>
</body>
</html>
