<?php
    include 'components/connect.php';
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('location:login.php');
        exit;
    }
    $user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        nav a {
            font-family: 'Times New Roman', Times, serif;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .orders-container {
            max-width: 1200px;
            margin: 30px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            justify-content: center;
        }
        .order-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            width: 100%;
            max-width: 500px;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .order-details {
            font-size: 14px;
            padding-top: 10px;
        }
        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .delivered { background: #28a745; color: white; }
        .canceled { background: #dc3545; color: white; }
        .in-progress { background: #ffc107; color: black; }
        .product-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding-top: 10px;
        }
        .product-item {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        .view-order {
            display: inline-block;
            padding: 8px 12px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 10px;
            transition: 0.3s;
        }
        .view-order:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'components/user_header.php'; ?>
    <h1 style="text-align: center;">My Orders</h1>
    <div class="orders-container">
        <?php
        $select_orders = $conn->prepare("
            SELECT o.dates, o.status, o.payment_status, o.method, 
                GROUP_CONCAT(p.name SEPARATOR '||') AS product_names,
                GROUP_CONCAT(p.price SEPARATOR '||') AS product_prices,
                GROUP_CONCAT(o.qty SEPARATOR '||') AS quantities,
                GROUP_CONCAT(p.image SEPARATOR '||') AS product_images
            FROM orders o 
            JOIN products p ON o.product_id = p.id 
            WHERE o.user_id = ?
            GROUP BY o.dates
            ORDER BY o.dates DESC
        ");  
        
        $select_orders->execute([$user_id]);
        $orders = $select_orders->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($orders)) {
            echo "<p style='color: red; text-align: center;'>No orders found.</p>";
        } else {
            foreach ($orders as $order) {
                $product_names = explode('||', $order['product_names']);
                $product_prices = explode('||', $order['product_prices']);
                $quantities = explode('||', $order['quantities']);
                $product_images = explode('||', $order['product_images']);
        ?>
                <div class="order-card">
                    <div class="order-header">
                        <span>Order Date: <?= htmlspecialchars($order['dates']) ?></span>
                        <span class="status <?= strtolower(str_replace(' ', '-', $order['status'])) ?>">
                            <?= htmlspecialchars($order['status']) ?>
                        </span>
                    </div>
                    <div class="order-details">
                        <p><strong>Payment Status:</strong> <?= htmlspecialchars($order['payment_status']) ?></p>
                        <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['method']) ?></p>
                        <div class="product-container">
                            <?php for ($i = 0; $i < count($product_names); $i++) { ?>
                                <div class="product-item">
                                    <img src="uploaded_files/<?= htmlspecialchars($product_images[$i]) ?>" alt="Product Image" class="product-image">
                                    <div>
                                        <p><strong><?= htmlspecialchars($product_names[$i]) ?></strong></p>
                                        <p>Qty: <?= htmlspecialchars($quantities[$i]) ?></p>
                                        <p>Price: ₹<?= number_format($product_prices[$i], 2) ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>
