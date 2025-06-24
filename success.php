<?php
session_start();
include 'components/connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['checkout_data'])) {
    header("Location: order.php");
    exit();
}

if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];
    $checkout_data = $_SESSION['checkout_data'];
    
    // ✅ Insert Order Details in Database
    $insertOrder = $conn->prepare("INSERT INTO orders 
        (id, user_id, name, email, address, address_type, method, product_id, price, qty, dates, status, payment_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'in progress', 'completed')");

    $order_id = "ORD" . time(); // Unique order ID generator
    $insertOrder->execute([
        $order_id,
        $_SESSION['user_id'],
        $_SESSION['user_name'],
        $_SESSION['user_email'],
        $checkout_data['address'],
        $checkout_data['address_type'],
        $checkout_data['method'],
        $checkout_data['product_id'],
        $checkout_data['price'],
        $checkout_data['qty']
    ]);

    echo "<h1>Payment Successful!</h1>";
    echo "<p>Your payment was successful. Payment ID: $payment_id</p>";
} else {
    echo "<h1>Payment Failed</h1>";
    echo "<p>There was an issue with your payment. Please try again later.</p>";
}
?>
