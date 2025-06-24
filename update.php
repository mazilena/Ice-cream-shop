<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$fetch_profile_query = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$fetch_profile_query->execute([$user_id]);
$fetch_profile = $fetch_profile_query->fetch(PDO::FETCH_ASSOC);

if (!$fetch_profile) {
    die("User profile not found!");
}

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Name Update
    if (!empty($name) && $name !== $fetch_profile['name']) {
        $update_name = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
        $update_name->execute([$name, $user_id]);
        $success_msg[] = 'Username updated successfully';
    }

    // Email Update
    if (!empty($email) && $email !== $fetch_profile['email']) {
        $select_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $select_email->execute([$email]);

        if ($select_email->rowCount() > 0) {
            $warning_msg[] = 'Email already exists';
        } else {
            $update_email = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
            $update_email->execute([$email, $user_id]);
            $success_msg[] = 'Email updated successfully';
        }
    }

    // Image Upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = uniqid().'.'.$ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_files/'.$rename;

        if ($image_size > 2000000) {
            $warning_msg[] = 'Image size is too large';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $update_image = $conn->prepare("UPDATE users SET image = ? WHERE id = ?");
            $update_image->execute([$rename, $user_id]);

            // Delete previous image
            if (!empty($fetch_profile['image']) && file_exists('uploaded_files/'.$fetch_profile['image'])) {
                unlink('uploaded_files/'.$fetch_profile['image']);
            }
            $success_msg[] = 'Profile image updated successfully';
        }
    }

    // Password Update
    if (!empty($_POST['old_pass']) && !empty($_POST['new_pass']) && !empty($_POST['cpass'])) {
        $old_pass = sha1($_POST['old_pass']);
        $new_pass = sha1($_POST['new_pass']);
        $cpass = sha1($_POST['cpass']);

        if ($old_pass !== $fetch_profile['password']) {
            $warning_msg[] = 'Old password not matched!';
        } elseif ($new_pass !== $cpass) {
            $warning_msg[] = 'New password and Confirm password do not match!';
        } else {
            $update_pass = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_pass->execute([$new_pass, $user_id]);
            $success_msg[] = 'Password updated successfully';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - Blue Sky Summer</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <?php include 'components/user_header.php'; ?>

    <div class="banner">
        <div class="detail">
            <h1>Update Profile</h1>
            <p>Update your details and manage your account here.</p>
            <span><a href="home.php">Home</a> <i class="bx bx-right-arrow-alt"></i> Update Profile</span>
        </div>
    </div>

    <section class="form-container">
        <div class="heading">
            <h1>Update Profile Details</h1>
            <img src="image/separator-img.png">
        </div>
        <form action="" method="post" enctype="multipart/form-data" class="register">
            <div class="img-box">
                <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="Profile Image">
            </div>
            <div class="flex">
                <div class="col">
                    <div class="input-field">
                        <p>Your Name <span>*</span></p>
                        <input type="text" name="name" placeholder="Enter your name" value="<?= $fetch_profile['name']; ?>" class="box">
                    </div>
                    <div class="input-field">
                        <p>Your Email <span>*</span></p>
                        <input type="email" name="email" placeholder="Enter your email" value="<?= $fetch_profile['email']; ?>" class="box">
                    </div>
                    <div class="input-field">
                        <p>Select Profile Picture <span>*</span></p>
                        <input type="file" name="image" accept="image/*" class="box">
                    </div>
                </div>
                <div class="col">
                    <div class="input-field">
                        <p>Old Password<span>*</span></p>
                        <input type="password" name="old_pass" placeholder="Enter your old password" class="box">
                    </div>
                    <div class="input-field">
                        <p>New Password<span>*</span></p>
                        <input type="password" name="new_pass" placeholder="Enter your new password" class="box">
                    </div>
                    <div class="input-field">
                        <p>Confirm Password<span>*</span></p>
                        <input type="password" name="cpass" placeholder="Confirm your new password" class="box">
                    </div>
                </div>
            </div>
            <input type="submit" name="submit" value="Update Profile" class="btn">
              <!-- Logout & Reset Password -->
        <div class="dashboard-links">
           
            <div class="box">
                <i class="bx bxs-log-out"></i>
                <a href="components/user_logout.php" class="btn">Logout</a>
            </div><br>
            <!-- Cancel Button -->
<div class="box">
    <i class="bx bxs-arrow-back"></i>
    <a href="user_dashboard.php" class="btn">Go to Dashboard</a>
</div>
        </form>

      

        </div>
    </section>

    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>
