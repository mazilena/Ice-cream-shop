<?php
session_start();
include 'components/connect.php';

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$fetch_profile_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$fetch_profile_query->execute([$user_id]);
$fetch_profile = $fetch_profile_query->fetch(PDO::FETCH_ASSOC);

// Fetch orders count
$select_orders = $conn->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$select_orders->execute([$user_id]);
$total_orders = $select_orders->fetchColumn();

// Fetch wishlist count
$select_wishlist = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
$select_wishlist->execute([$user_id]);
$total_wishlist = $select_wishlist->fetchColumn();

// Greeting Logic
date_default_timezone_set("Asia/Kolkata");
$hour = date("H");
$greeting = ($hour < 12) ? "Good Morning, " : (($hour < 17) ? "Good Afternoon, " : "Good Evening, ");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
          /* ✅ Remove underline from header links */
    .navbar a {
        text-decoration: none !important; /* Remove underline */
        color: inherit; /* Keep default text color */
        font-family: 'Times New Roman', Times, serif;
    }
    
    .navbar a:hover {
        color: #ff4081; /* Hover color */
        text-decoration: none !important; /* Ensure no underline */
    }
         .dashboard {
            font-family: 'Times New Roman', Times, serif;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        .main-container {
            width: 85%;
            margin: auto;
            padding: 20px;
        }
    </style>
    
</head>
<body>
    <?php include 'components/user_header.php'; ?>
    <div class="dashboard">
        <div class="user-info">
            <h2><?= $greeting . htmlspecialchars($fetch_profile['name']); ?> 👋</h2>
            <p>Welcome to your dashboard!</p>
            <img src="uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" class="profile-img">
            <a href="update.php" class="btn">Update Profile</a>
        </div>
        <div class="dashboard-links">
            <div class="box">
                <i class="bx bxs-heart"></i>
                <h3><?= $total_wishlist; ?> Wishlist Items</h3>
                <a href="wishlist.php" class="btn">View Wishlist</a>
            </div>
            <div class="box">
                <i class="bx bxs-cart"></i>
                <h3> Orders</h3>
                <a href="order.php" class="btn">View Orders</a>
            </div>
            <div class="box">
                <i class="bx bxs-food-menu"></i>
                <h3>Explore Menu</h3>
                <a href="menu.php" class="btn">Go to Menu</a>
            </div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>

<link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .dashboard {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .user-info {
            padding: 20px;
            text-align: center;
        }
        .user-info h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 10px 0;
            border: 3px solid #ddd;
        }
        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #ff6b6b;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn:hover {
            background: #ff4757;
        }
        .dashboard-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .box {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        .box:hover {
            transform: translateY(-5px);
        }
        .box i {
            font-size: 40px;
            color: #ff6b6b;
            margin-bottom: 10px;
        }
        .box h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }
        .box .btn {
            display: block;
            width: 100%;
            text-align: center;
        }
    </style>