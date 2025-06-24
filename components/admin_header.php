<style>
    .navbar li {
        padding: 12px;
    }
    .welcome-msg {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-right: 20px;
    }
</style>

<header>
    <div class="logo">
        <img src="../image/logo.png" width="150">
    </div>

    <div class="right">
        <!-- Welcome Message -->
        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }        
        include_once '../components/connect.php';

        if (isset($_SESSION['seller_id'])) {
            $seller_id = $_SESSION['seller_id'];
            $select_seller = $conn->prepare("SELECT * FROM sellers WHERE id = ?");
            $select_seller->execute([$seller_id]);
            $fetch_seller = $select_seller->fetch(PDO::FETCH_ASSOC);

            if ($select_seller->rowCount() > 0) {
                echo '<span class="welcome-msg">Welcome, ' . htmlspecialchars($fetch_seller['name']) . '</span>';
            }
        }
        ?>

        <div id="user-btn">
            <div class="toggle-btn"><i class="fa-solid fa-user"></i></div>
        </div>
    </div>

    <div class="profile-detail">
        <?php
        if ($select_seller->rowCount() > 0) {
            ?>
            <div class="profile">
                <img src="../uploaded_files/<?= htmlspecialchars($fetch_seller['image']); ?>" class="logo-img" width="100">
                <p><?= htmlspecialchars($fetch_seller['name']); ?></p>
                <div class="flex-btn">
                    <a href="profile.php" class="btn">Profile</a>
                    <a href="../components/admin_logout.php" onclick="return confirm('Logout from this website?');" class="btn">Logout</a>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</header>

<div class="sidebar-container">
    <div class="sidebar">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM sellers WHERE id= ?");
        $select_profile->execute([$seller_id]);

        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="profile">
            <img src="../uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" class="logo-img" width="100">
            <p><?= htmlspecialchars($fetch_profile['name']); ?></p>
        </div>

        <h5>menu</h5>
        <div class="navbar" style="min-height: 44vh;">
            <ul>
                <li><a href="dashboard.php"><i class="fa-solid fa-house"></i>dashboard</a></li>
                <li><a href="add_products.php"><i class="fa-solid fa-bag-shopping"></i>add products</a></li>
                <li><a href="view_product.php"><i class="fa-solid fa-id-card"></i>view product</a></li>
                <li><a href="user_accounts.php"><i class="fa-solid fa-user"></i>accounts</a></li>
                <li><a href="report.php"><i class="fa fa-file" aria-hidden="true"></i>Report</a></li>
                <li><a href="user_logout.php" onclick="return confirm('Logout from this website?');"><i class="fa-solid fa-right-from-bracket"></i>logout</a></li>
            </ul>
        </div>

        <h5>find us</h5>
        <div class="social-links">
            <i class="fa-brands fa-facebook"></i>
            <i class="fa-brands fa-instagram"></i>
            <i class="fa-brands fa-linkedin"></i>
            <i class="fa-brands fa-twitter"></i>
            <i class="fa-brands fa-pinterest"></i>
        </div>
    </div>
</div>
