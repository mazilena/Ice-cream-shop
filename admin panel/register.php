<?php 
    include '../components/connect.php';

    if (isset($_POST['submit'])) 
    {
        $id = unique_id();
        $name = $_POST['name'];
        $name = filter_var($name );

        $email = $_POST['email'];
        $email = filter_var($email );

        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass );

        $cpass = sha1($_POST['cpass']);
        $cpass = filter_var($cpass );

        $image = $_FILES['image']['name'];
        $image = filter_var($image );
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = unique_id().'.'.$ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/'.$rename;

        $select_seller = $conn->prepare("SELECT * FROM `sellers` WHERE Email = ?");
        $select_seller->execute([$email]);

        if ($select_seller->rowCount() > 0) 
        {
            $warning_msg[] = 'Email already exists';
        }
        else 
        {
            if ($pass != $cpass) 
            {
                $warning_msg[] = 'Confirm password not matched';
            }
            else
            {
                if (move_uploaded_file($image_tmp_name, $image_folder)) {
                    $insert_seller = $conn->prepare("INSERT INTO `sellers` (id, name, email, password, image) VALUES (?, ?, ?, ?, ?)");
                    $insert_seller->execute([$id, $name, $email, $pass, $rename]);
                    $success_msg[] = 'New seller registered! Please log in now';
                } else {
                    $warning_msg[] = 'Failed to upload image';
                }
            }
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
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <h3> Register now</h3>
            <div class="flex">
                <div class="col">
                    <div class="input-field">
                        <p>your name <span>*</span></p>
                        <input type="text" name="name" placeholder="enter your name" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>your email <span>*</span></p>
                        <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">
                    </div>
                </div>
                <div class="col">
                    <div class="input-field">
                        <p>your password <span>*</span></p>
                        <input type="password" name="pass" placeholder="enter your password" maxlength="50" required class="box">
                    </div>
                    <div class="input-field">
                        <p>comfirm password  <span>*</span></p>
                        <input type="password" name="cpass" placeholder="confirm your password " maxlength="50" required class="box">
                    </div>
                </div>
            </div>
            <div class="input-field">
                    <p>your profile <span>*</span></p>
                    <input type="file" name="image" accept="image/*" required class="box">
            </div>
            <p class="link">already have an account? <a href="login.php">login now</a></p>
            <input type="submit" name="submit" value="register now" class="btn">
        </form>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script src="../js/script.js"></script>

    <?php 
        include '../components/alert.php';
    ?>
</body>
</html>