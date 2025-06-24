<?php 
    $connectFile = __DIR__ . '/../components/connect.php';
    if (!file_exists($connectFile)) {
        die("Error: connect.php not found at $connectFile");
    }
    include $connectFile; 
    header('Content-Type: text/html; charset=utf-8');

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
        $order_id = $_POST['id'];
        $status = $_POST['status'];
        $payment_status = $_POST['payment_status'];
        
        $update_order = $conn->prepare("UPDATE orders SET status = ?, payment_status = ? WHERE id = ?");
        $update_order->execute([$status, $payment_status, $order_id]);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            background-color: #f8f9fa;
        }

        .main-container a {
            text-decoration: none !important;
        }

        .orders-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            max-width: 1300px;
            margin: 20px auto;
            padding: 20px;
        }

        .order-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
            transition: 0.3s;
            text-align: left;
        }

        .order-header {
            font-size: 20px;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
            color: #da6285;
        }

        .order-details p {
            margin: 8px 0;
            font-size: 16px;
        }

        .product-list {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }

        select, button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 15px;
        }

        .update-btn {
            background: #da6285;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 12px;
        }

        .update-btn:hover {
            background: #da6285;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="user-container">
            <div class="heading text-center">
                <h1>Orders</h1>
                <img src="../image/separator-img.png">
            </div>
            <div class="container mt-5">
                <div class="orders-container">
                    <?php
                    // Fetch orders grouped by user_id and order date
                    $select_orders = $conn->prepare("
                        SELECT 
                            o.id AS order_id, 
                            o.user_id, 
                            o.name AS user_name, 
                            o.email AS user_email, 
                            o.dates AS order_date, 
                            o.method AS payment_method, 
                            o.status, 
                            o.payment_status, 
                            p.name AS product_name,  
                            o.qty AS quantity,        
                            (p.price * o.qty) AS total_price  
                        FROM orders o
                        JOIN products p ON o.product_id = p.id
                        ORDER BY o.dates DESC
                    ");
                    $select_orders->execute();
                    $orders = $select_orders->fetchAll(PDO::FETCH_ASSOC);

                    $grouped_orders = [];

                    foreach ($orders as $order) {
                        $key = $order['user_id'] . '-' . $order['order_date'];
                        
                        if (!isset($grouped_orders[$key])) {
                            $grouped_orders[$key] = [
                                'id' => $order['order_id'],
                                'user_name' => htmlspecialchars($order['user_name']),
                                'user_email' => htmlspecialchars($order['user_email']),
                                'dates' => htmlspecialchars($order['order_date']),
                                'method' => htmlspecialchars($order['payment_method']),
                                'status' => htmlspecialchars($order['status']),
                                'payment_status' => htmlspecialchars($order['payment_status']),
                                'items' => []
                            ];
                        }

                        $grouped_orders[$key]['items'][] = [
                            'product_name' => htmlspecialchars($order['product_name']),
                            'price' => htmlspecialchars($order['total_price']),
                            'qty' => htmlspecialchars($order['quantity'])
                        ];
                    }

                    if (empty($grouped_orders)) {
                        echo "<p style='color: red; text-align: center;'>No valid orders found.</p>";
                    } else {
                        foreach ($grouped_orders as $group) {
                    ?>
                        <div class="order-card">
                            <div class="order-header">
                                <span>Order Date: <?= $group['dates'] ?></span>
                            </div>
                            <div class="order-details">
                                <p><strong>User:</strong> 
                                    <?= $group['user_name'] ?> 
                                    <?= !empty($group['user_email']) ? '('.$group['user_email'].')' : '' ?>
                                </p>
                                <p><strong>Payment Method:</strong> <?= $group['method'] ?></p>
                                <p><strong>Status:</strong> <?= $group['status'] ?></p>
                                <p><strong>Payment Status:</strong> <?= $group['payment_status'] ?></p>
                                
                                <div class="product-list">
                                    <strong>Products:</strong>
                                    <?php foreach ($group['items'] as $item) { ?>
                                        <p><strong><?= $item['product_name'] ?></strong> - ₹<?= $item['price'] ?> | Quantity: <?= $item['qty'] ?></p>
                                    <?php } ?>
                                </div>

                                <form method="post">
                                    <input type="hidden" name="id" value="<?= $group['id'] ?>">
                                    <label>Update Status:</label>
                                    <select name="status">
                                        <option value="Pending" <?= $group['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="In Progress" <?= $group['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="Shipped" <?= $group['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                                    </select>
                                    <label>Payment Status:</label>
                                    <select name="payment_status">
                                        <option value="Pending" <?= $group['payment_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Paid" <?= $group['payment_status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                                    </select>
                                    <button type="submit" class="update-btn">Update</button>
                                </form>
                            </div>
                        </div>
                    <?php }} ?>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
