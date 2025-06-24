<?php
session_start();

if (isset($_POST['reset_password'])) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (strlen($newPassword) < 6) {
        echo "<script>alert('Password must be at least 6 characters long.');</script>";
    } elseif ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        include 'components/connect.php';

        // SHA1 hashing for compatibility
        $hashedPassword = sha1($newPassword);

        $email = $_SESSION['email'];
        $query = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $conn->prepare($query);
        $updateSuccess = $stmt->execute([
            ':password' => $hashedPassword,
            ':email'    => $email
        ]);

        if ($updateSuccess) {
            echo "<script>
                    alert('Password reset successfully! Please log in with your new password.');
                    window.location.href='login.php';
                  </script>";
        } else {
            echo "<script>alert('Error updating password. Please try again.');</script>";
        }

        session_destroy(); // Password reset hone ke baad session destroy hota hai
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" placeholder="Enter New Password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <button type="submit" name="reset_password">Reset Password</button>
        </form>
    </div>
</body>
</html>
