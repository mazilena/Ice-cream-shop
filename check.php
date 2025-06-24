<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'components/connect.php';

if (!isset($_SESSION['user_id'])) {
    die("<script>alert('Please login to proceed.'); window.location='login.php';</script>");
}

$user_id = $_SESSION['user_id'];

// ✅ User ka naam le raha hu
$user_query = $conn->prepare("SELECT name FROM users WHERE id = ?");
$user_query->execute([$user_id]);
$user = $user_query->fetch(PDO::FETCH_ASSOC);
$username = $user ? htmlspecialchars($user['name']) : "Unknown User";

// ✅ Initialize kar raha hu
$products = [];
$total_price = 0;
$invoice_date = date("d M Y, h:i A");

// ✅ "Buy Now" - Single Product Checkout
if (isset($_GET['product_id']) && isset($_GET['qty']) && !isset($_GET['buy_all'])) {
    $product_id = trim($_GET['product_id']);
    $qty = max(1, intval($_GET['qty']));

    $product_query = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $product_query->execute([$product_id]);

    if ($product_query->rowCount() > 0) {
        $product = $product_query->fetch(PDO::FETCH_ASSOC);
        $product['qty'] = $qty;
        $product['total_price'] = $product['price'] * $qty;
        $total_price = $product['total_price'];
        $products[] = $product;
    } else {
        die("<script>alert('Product not found!'); window.location='menu.php';</script>");
    }
} 
// ✅ "Buy All" ka logic yaha handle ho raha hai
else {
    // Agar buy_all=1 hai to pura cart checkout hoga
    $cart_query = $conn->prepare("SELECT p.*, c.qty FROM cart c INNER JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $cart_query->execute([$user_id]);

    if ($cart_query->rowCount() == 0) {
        die("<script>alert('Your cart is empty!'); window.location='menu.php';</script>");
    }

    while ($product = $cart_query->fetch(PDO::FETCH_ASSOC)) {
        $product['total_price'] = $product['price'] * $product['qty'];
        $total_price += $product['total_price'];
        $products[] = $product;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            text-align: center;
            background: #f8f9fa;
        }
        .invoice-container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            text-align: left;
        }
        .product-container {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-right: 15px;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #ff4081;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 1.2rem;
        }
        .btn:hover {
            background: #d6336c;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-header h2 {
            color: #ff4081;
            font-weight: bold;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .total-amount {
            text-align: right;
            font-size: 1.3rem;
            font-weight: bold;
            margin-top: 10px;
        }
        .payment-section {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php include 'components/user_header.php'; ?>

<div class="container mt-4">
    <h2 class="text-center text-primary">Order Invoice</h2>
    <p class="text-center"><strong>Date:</strong> <?= $invoice_date ?></p>
    
    <div class="mb-3">
        <p><strong>Customer:</strong> <?= $username ?></p>
        <p><strong>Order ID:</strong> #<?= rand(100000, 999999) ?></p>
    </div>

    <?php foreach ($products as $product) { ?>
        <div class="d-flex border-bottom py-3">
            <img src="uploaded_files/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="me-3" width="100">
            <div>
                <p><strong><?= htmlspecialchars($product['name']) ?></strong></p>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <p><strong>Price:</strong> ₹<?= number_format($product['price'], 2) ?> <span>(x<?= $product['qty'] ?>)</span></p>
            </div>
        </div>
    <?php } ?>

    <p class="fs-4 text-end"><strong>Total Amount:</strong> ₹<?= number_format($total_price, 2) ?></p>

    <!-- ✅ Checkout Form -->
    <form action="place_order.php" method="POST">
        <input type="hidden" name="total_price" value="<?= $total_price ?>">
        
        <?php if (isset($_GET['product_id']) && isset($_GET['qty'])) { ?>
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($_GET['product_id']) ?>">
            <input type="hidden" name="qty" value="<?= intval($_GET['qty']) ?>">
            <input type="hidden" name="price" value="<?= number_format($product['price'], 2, '.', '') ?>">
        <?php } ?>

        <label for="payment_method"><strong>Select Payment Method:</strong></label>
        <select name="method" id="payment_method" class="form-control" required>
            <option value="COD">Cash on Delivery (COD)</option>
            <option value="Online">Online Payment (Razorpay)</option>
        </select>

        <br>

        <label for="address"><strong>Enter Delivery Address:</strong></label>
        <input type="text" name="address" id="address" class="form-control" required>

        <br>

        <label for="address_type"><strong>Address Type:</strong></label>
        <select name="address_type" id="address_type" class="form-control" required>
            <option value="Home">Home</option>
            <option value="Office">Office</option>
        </select>

        <br>

        <button type="submit" class="btn btn-primary">Place Order</button>
        <button onclick="window.history.go(-1)" class="btn">Back</button>
        
    </form>
</div>

</body>
</html>


