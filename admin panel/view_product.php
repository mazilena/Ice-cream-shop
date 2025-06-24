<?php
header('Content-Type: text/html; charset=UTF-8'); // Encoding issue fix
include '../components/connect.php';
session_start();

if (!isset($_SESSION['seller_id'])) {
    header('location:login.php');
    exit;
}

$seller_id = $_SESSION['seller_id'];

// Handle the status change
if (isset($_POST['update_status'])) {
    $product_id = $_POST['product_id'];
    $new_status = $_POST['status'];

    // Update the product status
    $update_status = $conn->prepare("UPDATE products SET status = ? WHERE id = ? AND seller_id = ?");
    $update_status->execute([$new_status, $product_id, $seller_id]);

    // Redirect to refresh the page
    header("Location: view_product.php");
    exit;
}

// Fetch active products
$select_active_products = $conn->prepare("SELECT * FROM products WHERE seller_id = ? AND status = ?");
$select_active_products->execute([$seller_id, 'active']);
$active_products = $select_active_products->fetchAll(PDO::FETCH_ASSOC);

// Fetch deactive products
$select_deactive_products = $conn->prepare("SELECT * FROM products WHERE seller_id = ? AND status = ?");
$select_deactive_products->execute([$seller_id, 'deactive']);
$deactive_products = $select_deactive_products->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories
$select_categories = $conn->prepare("SELECT DISTINCT category FROM products WHERE seller_id = ?");
$select_categories->execute([$seller_id]);
$categories = $select_categories->fetchAll(PDO::FETCH_ASSOC);

// Category filter logic
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'All';

$query = "SELECT * FROM products WHERE seller_id = ? AND status = 'active'";
$params = [$seller_id];

if ($category_filter !== 'All') {
    $query .= " AND category = ?";
    $params[] = $category_filter;
}

$select_products = $conn->prepare($query);
$select_products->execute($params);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Products</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <style>
        /* Category Filter */
        .filter-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-container select {
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        /* Products Container */
        .products-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        /* Product Card */
        .product-card {
            width: 270px;
            background: white;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
        }

        /* Product Image */
        .product-card img {
            width: 100%;
            max-width: 250px;
            height: 200px;
            object-fit: contain;
            border-radius: 8px;
            background: #f8f8f8;
            padding: 10px;
        }

        /* Product Details */
        .product-card h2 {
            font-size: 18px;
            color: #333;
            margin: 10px 0;
        }

        .product-card p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        /* Action Buttons */
        .product-actions {
            margin-top: 10px;
        }

        .update-btn, .delete-btn {
            display: inline-block;
            padding: 8px 12px;
            font-size: 14px;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }

        .update-btn {
            background: #3498db;
            color: white;
        }

        .delete-btn {
            background: #e74c3c;
            color: white;
        }

        .update-btn:hover {
            background: #2980b9;
        }

        .delete-btn:hover {
            background: #c0392b;
        }
    </style>
    <script>
        function filterProducts() {
            let category = document.getElementById("categoryFilter").value;
            window.location.href = "view_product.php?category=" + category;
        }
    </script>
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>

        <section class="view-products">
            <!-- Dynamic Heading Based on Category -->
            <h1><?= $category_filter === 'All' ? "All Products" : htmlspecialchars($category_filter) ?></h1>

            <!-- Category Filter -->
            <div class="filter-container">
                <label for="categoryFilter"><strong>Filter by Category:</strong></label>
                <select id="categoryFilter" onchange="filterProducts()">
                    <option value="All" <?= $category_filter === 'All' ? 'selected' : '' ?>>All</option>
                    <?php foreach ($categories as $cat) { ?>
                        <option value="<?= htmlspecialchars($cat['category']) ?>" <?= $category_filter == $cat['category'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Products List -->
            <div class="products-container">
                <?php while ($row = $select_products->fetch(PDO::FETCH_ASSOC)) { ?>
                    <div class="product-card">
                        <img src="../uploaded_files/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                        <h2><?= htmlspecialchars($row['name']) ?></h2>
                        <p><strong>Price:</strong> ₹<?= number_format(floatval($row['price']), 2) ?></p>

                        <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        
                        <div class="product-actions">
                            <a href="edit_product.php?id=<?= $row['id'] ?>" class="update-btn">Update</a>
                            <a href="delete_product.php?id=<?= $row['id'] ?>" class="delete-btn" 
   onclick="return confirm('Are you sure you want to delete this product?');">
   Delete
</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    </div>
</body>
</html>



