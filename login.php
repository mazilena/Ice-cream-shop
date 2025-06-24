<?php
session_start();
include 'components/connect.php';

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['pass']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && hash('sha1', $password) === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        header('location:user_dashboard.php');
        exit;
    } else {
        echo "<script>alert('Incorrect email or password.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Login page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <?php include 'components/user_header.php'; ?>

    <div class="banner">
        <div class="detail">
            <h1>Login</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum ea perferendis odio beatae reiciendis<br> culpa laudantium, dicta neque eveniet harum voluptates corporis porro placeat ex?</p>
            <span><A href="home.php">Home</a><i class= "bx bx-right-arrow-alt"></i>Login<span>
        </div>
    </div>

    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data" class="login">
            <h3>Login Now</h3>
            <div class="input-field">
                <p>Your Email <span>*</span></p>
                <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
            </div>
            <div class="input-field">
                <p>Your Password <span>*</span></p>
                <input type="password" name="pass" placeholder="Enter your password" maxlength="50" required class="box">
            </div>
            <p class="link"><a href="fp.php">Forgot password?</a></p>
            <p class="link">Don't have an account? <a href="register.php">Register now</a></p>
            <input type="submit" name="submit" value="Login Now" class="btn">
        </form>
    </div>

    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>


<div class="banner">
        <div class="detail">
            <h1>Login</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum ea perferendis odio beatae reiciendis<br> culpa laudantium, dicta neque eveniet harum voluptates corporis porro placeat ex?</p>
            <span><A href="home.php">Home</a><i class= "bx bx-right-arrow-alt"></i>Login<span>
        </div>
    </div>