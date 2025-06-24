<?php
include 'components/connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Fix: Replace `??` with `isset()`
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Search Products</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="banner">
    <div class="detail">
        <h1>Search Products</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        <span><a href="home.php">Home</a><i class="bx bx-right-arrow-alt"></i>Search Products</span>
    </div>
</div>

<div class="products">
    <div class="heading">
        <h1>Search Results</h1>
        <img src="image/separator-img.png">
    </div>
    <div class="box-container">
        <?php 
        if (isset($_POST['search_product']) || isset($_POST['search_product_btn'])) {
            $search_products = trim($_POST['search_product']);

            $select_products = $conn->prepare("SELECT * FROM products WHERE name LIKE ? AND status = 'active'");
            $like_search = "%$search_products%";
            $select_products->execute([$like_search]);

            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                    $product_id = $fetch_products['id'];
        ?>
        <form action="components/add_cart.php" method="post" class="box <?= $fetch_products['stock'] == 0 ? 'disabled' : '' ?>">
            <img src="uploaded_files/<?= htmlspecialchars($fetch_products['image']); ?>">

            <?php if ($fetch_products['stock'] > 9) { ?>
                <span class="stock" style="color: green;">In Stock</span>
            <?php } elseif ($fetch_products['stock'] == 0) { ?>
                <span class="stock" style="color: red;">Out of Stock</span>
            <?php } else { ?>
                <span class="stock" style="color: red;">Hurry, only <?= $fetch_products['stock']; ?> left!</span>
            <?php } ?>

            <div class="content">
                <img src="image/shape-19.png" alt="" class="shap">
                <div class="button">
                    <h3 class="name"><?= htmlspecialchars($fetch_products['name']); ?></h3>
                    <div>
                        <button type="submit" name="add_to_cart" formaction="cart.php">
                            <i class="bx bx-cart"></i>
                        </button>
                        <button type="submit" name="add_to_wishlist" formaction="wishlist.php">
                            <i class="bx bx-heart"></i>
                        </button>
                        <a href="view_page.php?pid=<?= $product_id; ?>" class="bx bxs-show"></a>
                    </div>
                </div>
                <p class="price">Price ₹<?= htmlspecialchars($fetch_products['price']); ?></p>
                <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                <div class="flex-btn">
                    <a href="checkout.php?get_id=<?= $product_id; ?>" class="btn">Buy Now</a>
                    <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty box">
                </div>
            </div>
        </form>
        <?php
                }
            } else {
                echo '<div class="empty"><p>No products found!</p></div>';
            }
        } else {
            echo '<div class="empty"><p>Please search for something.</p></div>';
        }
        ?>
    </div>
</div>

<?php include 'components/footer.php'; ?>
<script src="js/user_script.js"></script>
<?php include 'components/alert.php'; ?>

</body>
</html>
