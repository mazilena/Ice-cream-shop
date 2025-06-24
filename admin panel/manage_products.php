<?php
include '../components/connect.php';
session_start();
if (isset($_SESSION['seller_id'])) {
    $seller_id = $_SESSION['seller_id'];
} else {
    header('location:login.php');
    exit;
}

// Fetch active products with proper debugging
$select_active_products = $conn->prepare("SELECT * FROM products WHERE seller_id = ? AND status = 'active'");
$select_active_products->execute([$seller_id]);
$active_products = $select_active_products->fetchAll(PDO::FETCH_ASSOC);


$select_deactive_products = $conn->prepare("SELECT * FROM products WHERE seller_id = ? AND status = ?");
$select_deactive_products->execute([$seller_id, 'deactive']);
$deactive_products = $select_deactive_products->fetchAll(PDO::FETCH_ASSOC);

// Handle the status change
if (isset($_POST['update_status'])) {
    $product_id = $_POST['product_id'];
    $new_status = $_POST['status'];

    // Update the product status
    $update_status = $conn->prepare("UPDATE products SET status = ? WHERE id = ?");
    $update_status->execute([$new_status, $product_id]);

    header("Location: view_product.php"); // Refresh page to see changes
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <style>
        /* Custom Table Styling */
        .product-table {
            width: 100%;
            border-collapse: collapse;
        }
        .product-table, .product-table th, .product-table td {
            border: 1px solid #ddd;
        }
        .product-table th, .product-table td {
            padding: 10px;
            text-align: center;
        }
        .product-table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
        /* Custom pink color for table header */
        .product-table th {
            color: black;
            font-size: 25px; /* Slightly larger font for header */
        }
        .product-table td {
            font-size: 18px;
        }
        /* Stripe effect for odd rows */
        .product-table tbody tr:nth-child(odd) {
            background-color: #f9f9f9; /* Lighter shade for odd rows */
        }
        /* Hover effect for rows */
        .product-table tbody tr:hover {
            background-color: #e91e63;
        }
        .product-actions {
            display: flex;
            justify-content: center;
        }
        .update-btn, .delete-btn {
            margin: 0 5px;
            padding: 5px 10px;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .delete-btn {
            background-color: #e74c3c;
        }
        .table {
            border-radius: 35px;
            overflow: hidden; /* To ensure the rounded corners look smooth */
            border: 1px solid #ddd; /* Light border around the table */
        }
    </style>
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>

        <section class="view-products">
            <h1>Active Products</h1>
            <!-- Active Products Table -->
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Change Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($active_products as $product) { ?>
                        <tr>
                            <td><img src="../uploaded_files/<?= $product['image']; ?>" alt="Product Image"></td>
                            <td><?= htmlspecialchars($product['name']); ?></td>
                            <td><?= htmlspecialchars($product['category']); ?></td>
                            <td>₹<?= number_format(floatval($product['price']), 2); ?></td>
                            <td>
                                <form action="view_product.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                    <select name="status">
                                        <option value="active" <?= $product['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="deactive" <?= $product['status'] === 'deactive' ? 'selected' : ''; ?>>Deactive</option>
                                    </select>
                                    <button type="submit" name="update_status" class="update-btn">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <h1>Deactive Products</h1>
            <!-- Deactive Products Table -->
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Change Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deactive_products as $product) { ?>
                        <tr>
                            <td><img src="../uploaded_files/<?= $product['image']; ?>" alt="Product Image"></td>
                            <td><?= htmlspecialchars($product['name']); ?></td>
                            <td><?= htmlspecialchars($product['category']); ?></td>
                            <td>₹<?= number_format(floatval($product['price']), 2); ?></td>
                            <td>
                                <form action="view_product.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                    <select name="status">
                                        <option value="active" <?= $product['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="deactive" <?= $product['status'] === 'deactive' ? 'selected' : ''; ?>>Deactive</option>
                                    </select>
                                    <button type="submit" name="update_status" class="update-btn">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
