<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="header">
    <section class="flex">
        <a href="home.php" class="logo"><img src="image/logo.png" width="130px"></a>
        <nav class="navbar">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="home.php">home</a>
                <a href="menu.php">shop</a>
                <a href="order.php">view orders</a>
                <a href="user_dashboard.php">dashboard</a>
            <?php else: ?>
                <a href="home.php">home</a>
                <a href="about-us.php">about us</a>
                <a href="menu.php">shop</a>
                <a href="order.php">order</a>
                <a href="contact.php">contact us</a>
            <?php endif; ?>
        </nav>    
        <form action="search_product.php" method="post" class="search-form">
            <input type="text" name="search_product" placeholder="search products ...." required maxlength="100">
            <button type="submit" class="bx bx-search-alt-2" id="search_product_btn"></button>
        </form>
        
        <div class="icons">
            <div class="bx bx-list-plus" id="menu-btn"></div>
            <div class="bx bx-search-alt-2" id="search-btn"></div>

            <!-- Wishlist Icon with Dynamic Count -->
            <a href="wishlist.php">
                <i class="bx bx-heart"></i>
            </a>

            <!-- Cart Icon with Dynamic Count -->
            <a href="cart.php">
                <i class="bx bx-cart"></i>
            </a>

            <div class="bx bxs-user" id="user-btn"></div>
        </div>
        
        <div class="profile-detail">
            <?php
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $select_profile->execute([$user_id]);

                if ($select_profile->rowCount() > 0) {
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
                    <img src="uploaded_files/<?= $fetch_profile['image']; ?>">
                    <h3 style="margin-bottom: 1rem;"><?= $fetch_profile['name']; ?></h3>
                    <div class="flex-btn">
                        <a href="user_dashboard.php" class="btn">view profile</a>
                        <a href="components/user_logout.php" onclick="return confirm('logout from this website');" class="btn">logout</a>
                    </div>
            <?php 
                }
            } else { ?>
                <h3 style="margin-bottom: 1rem;">please login or register</h3>
                <div class="flex-btn">
                    <a href="login.php" class="btn">login</a>
                    <a href="register.php" class="btn">register</a>
                </div>
            <?php } ?>
        </div>
    </section>
</header>

<script>

</script>
