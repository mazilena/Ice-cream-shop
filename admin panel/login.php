<?php 
    include '../components/connect.php';
    session_start();
    if (isset($_POST['submit'])) 
    {
        $email = filter_var($_POST['email']);
        $pass = filter_var($_POST['pass']);
        $pass = sha1($pass);

        $select_seller = $conn->prepare("SELECT * FROM sellers WHERE email = ? AND password = ?");
        $select_seller->execute([$email, $pass]);
        $row = $select_seller->fetch(PDO::FETCH_ASSOC);

        if ($select_seller->rowCount() > 0) {
            $_SESSION['seller_id']=$row['id'];
           
            header('location:dashboard.php');
        } else {
            $warning_msg[] = 'Incorrect email or password';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - seller registeration page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data" class="login">
            <h3> Login now</h3>
            <div class="input-field">
                <p>your email <span>*</span></p>
                <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">
            </div>
            <div class="input-field">
                <p>your password <span>*</span></p>
                <input type="password" name="pass" placeholder="enter your password" maxlength="50" required class="box">
            </div>
            
                <input type="submit" name="submit" value="login now" class="btn">
        </form>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script src="../js/script.js"></script>

    <?php 
        include '../components/alert.php';
    ?>
</body>
</html>