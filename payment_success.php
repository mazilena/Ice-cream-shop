<?php 
session_start();
include 'components/connect.php';

// Razorpay Keys
$razorpay_key = "rzp_test_UPoUFyhQBliE3B"; 
$razorpay_secret = "ulAbWdEpIAWlf1sMzbWqlb9s"; 

// Get Razorpay Payment ID
if (!isset($_GET['payment_id']) || empty($_GET['payment_id'])) {
    die("<h2>❌ Invalid Payment! Payment ID is missing.</h2>");
}
$payment_id = htmlspecialchars($_GET['payment_id']);

// Razorpay API Call
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/payments/" . $payment_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERPWD, $razorpay_key . ":" . $razorpay_secret);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$response = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Handle API Error
if ($error || $http_status !== 200) {
    die("<h2>❌ Payment verification failed! Please try again.</h2><pre>$error</pre>");
}
$payment_data = json_decode($response, true);

// Check Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get User Info
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_email'])) {
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        session_destroy();
        header("Location: login.php");
        exit();
    }

    $_SESSION['user_name'] = $userData['name'];
    $_SESSION['user_email'] = $userData['email'];
}
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Collect Order Details
$address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address'])) : '';
$address_type = isset($_POST['address_type']) ? htmlspecialchars(trim($_POST['address_type'])) : '';
$product_id = isset($_POST['product_id']) ? htmlspecialchars(trim($_POST['product_id'])) : '';
$price = isset($_POST['price']) ? htmlspecialchars(trim($_POST['price'])) : '0.00';
$qty = isset($_POST['qty']) ? htmlspecialchars(trim($_POST['qty'])) : '1';

if (empty($address) || empty($product_id) || empty($price)) {
    die("<h2>❌ Missing required order details!</h2>");
}

// Create Order ID (16-char unique)
$order_id = uniqid('', true);

// Set Status Based on Razorpay Response
$payment_status = ($payment_data['status'] === "captured" || $payment_data['status'] === "authorized") ? 'Paid' : 'Failed';
$order_status = ($payment_status === 'Paid') ? 'Shipped' : 'Cancelled';

// Insert into `orders` table
$insert = $conn->prepare("INSERT INTO orders 
    (id, user_id, seller_id, name, email, address, address_type, method, product_id, price, qty, status, payment_status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$insert->execute([
    $order_id,
    $user_id,
    1, // seller_id static
    $user_name,
    $user_email,
    $address,
    $address_type,
    'Online Payment',
    $product_id,
    $price,
    $qty,
    $order_status,
    $payment_status
]);

// Redirect on success
if ($payment_status === 'Paid') {
    header("Location: order.php");
    exit();
} else {
    echo "<h2>❌ Payment Failed!</h2>";
    echo "<p>Payment ID: " . htmlspecialchars($payment_id) . "</p>";
}
?>
