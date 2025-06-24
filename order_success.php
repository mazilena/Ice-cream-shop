<?php
session_start();
include 'components/connect.php';

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '') {
    echo "<script>alert('Please login to place your order.'); window.location='login.php';</script>";
    exit();
}

// ✅ Session values
$user_id = $_SESSION['user_id'];
$name    = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$email   = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// ✅ Get POST values
$payment_id   = isset($_POST['payment_id']) ? $_POST['payment_id'] : '';
$address      = isset($_POST['address']) ? $_POST['address'] : '';
$address_type = isset($_POST['address_type']) ? $_POST['address_type'] : '';
$product_id   = isset($_POST['product_id']) ? $_POST['product_id'] : '';
$price        = isset($_POST['price']) ? $_POST['price'] : '';
$qty          = isset($_POST['qty']) ? $_POST['qty'] : '';
$method       = 'Online Payment';
$status       = 'in progress';
$payment_status = 'Paid';

// ✅ Validate
if ($payment_id == '' || $address == '' || $address_type == '' || $product_id == '' || $price == '' || $qty == '') {
    echo "<script>alert('Missing required fields.'); window.location='checkout.php';</script>";
    exit();
}

// ✅ Create order ID (max 20 chars)
$order_id = substr(uniqid('ORD'), 0, 20); // e.g., ORD661453a3a1a23

try {
    $insertOrder = $conn->prepare("INSERT INTO orders 
        (id, user_id, seller_id, name, email, address, address_type, method, product_id, price, qty, status, payment_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $insertOrder->execute(array(
        $order_id,
        $user_id,
        1, // default seller_id
        $name,
        $email,
        $address,
        $address_type,
        $method,
        $product_id,
        $price,
        $qty,
        $status,
        $payment_status
    ));

    header("Location: success.php");
    exit();
} catch (PDOException $e) {
    error_log("Order Insert Error: " . $e->getMessage());
    echo "<script>alert('Order placement failed. Please try again.'); window.location='checkout.php';</script>";
    exit();
}
?>
