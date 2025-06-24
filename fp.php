<?php
session_start();
require 'Composer/vendor/autoload.php'; // Update this path to the actual location of autoload.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include 'components/connect.php';

function sendOTP($recipientEmail) {
    $otp = rand(100000, 999999);  // Generate 6-digit OTP
    $_SESSION['otp'] = $otp;  // Store OTP in session for later verification
    $mail = new PHPMailer(true);

    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'anauthordesigner@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'almv jctr lhpi caab'; // Replace with your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient settings
        $mail->setFrom('anauthordesigner@gmail.com', 'icecreamshop');
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

if (isset($_POST['email'])) {
    $email = $_POST['email'];
   // $uname = $_POST['name'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Use PDO to check if the email exists in the database
        $query = "SELECT email FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) { // If email exists
            if (sendOTP($email)) {
                $_SESSION['email'] = $email;
                echo "<script>alert('OTP sent successfully! Please check your email.'); window.location.href='ov.php';</script>";
            } else {
                echo "<script>alert('Failed to send OTP. Please try again later.');</script>";
            }
        } else {
            echo "<script>alert('Username and email address not registered. Please use a valid username & email.');</script>";
        }
    } else {
        echo "<script>alert('Invalid email address. Please enter a valid one.');</script>";
    }
}

// Remove mysqli_close($conn); since PDO does not require manual closing

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('image/ice-creem-banner-bg.png') no-repeat center center/cover;
            margin: 0;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h1 {
            color: #d63384;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .form-group label {
            text-align: left;
            display: block;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #d63384;
            border-radius: 8px;
            outline: none;
            transition: border 0.3s;
        }

        .form-control:focus {
            border-color: #bf236b;
            box-shadow: 0 0 8px rgba(214, 51, 132, 0.3);
        }

        .btn-primary {
            background: #d63384;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            width: 100%;
            transition: background 0.3s;
        }

        .btn-primary:hover {
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
        <h1>Forgot Password</h1>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" autocomplete="off" placeholder="Enter Your Email Address" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Send OTP</button>
        </form>
        <div class="text-muted">
            <p>Remembered your password? <a href="login.php">Login</a></p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
