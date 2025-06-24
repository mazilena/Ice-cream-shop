<?php 
session_start();
include 'components/connect.php';

// ✅ Razorpay API Keys
$razorpay_key = "rzp_test_UPoUFyhQBliE3B"; 
$razorpay_secret = "ulAbWdEpIAWlf1sMzbWqlb9s"; 

// ✅ URL se Payment ID lo
if (!isset($_GET['payment_id'])) {
    die("❌ Invalid Payment! Payment ID missing.");
}

$payment_id = htmlspecialchars($_GET['payment_id']);

// ✅ Razorpay API se payment verify karo (cURL)
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

$payment_data = json_decode($response, true);

if ($error) {
    echo "<h2>❌ cURL Error: $error</h2>";
    exit();
}

if ($http_status != 200 || !isset($payment_data['status'])) {
    echo "<h2>❌ API Error: Payment Verification Failed!</h2>";
    echo "<p>HTTP Status Code: $http_status</p>";
    exit();
}

// ✅ Session Data Check - Redirect if Not Logged In
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ✅ Session Data Assignment
$user_id = $_SESSION['user_id'];

// ✅ Backup Logic: Fetch user details if session data is incomplete
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_email'])) {
    $fetchUser = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $fetchUser->execute([$user_id]);
    $userData = $fetchUser->fetch(PDO::FETCH_ASSOC);

    $_SESSION['user_name'] = isset($userData['name']) ? $userData['name'] : 'Unknown User';
    $_SESSION['user_email'] = isset($userData['email']) ? $userData['email'] : 'no-email@example.com';
}

// ✅ Assign Corrected Data
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// ✅ POST Data Handling with Fallbacks
$address = isset($_POST['address']) ? $_POST['address'] : '';
$address_type = isset($_POST['address_type']) ? $_POST['address_type'] : '';
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '0';
$qty = isset($_POST['qty']) ? $_POST['qty'] : '1';


// ✅ Payment Status Verification & Database Update Logic
if ($payment_data['status'] === "captured" || $payment_data['status'] === "authorized") {
    $payment_status = 'Paid';
    $order_status = 'in progress';
} else {
    $payment_status = 'Failed';
    $order_status = 'cancelled';
}

$insertOrder = $conn->prepare("INSERT INTO orders 
    (id, user_id, seller_id, name, email, , address, address_type, method, product_id, price, qty, status, payment_status) 
    VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$insertOrder = $conn->prepare("INSERT INTO orders 
    (id, user_id, seller_id, name, email, address, address_type, method, product_id, price, qty, status, payment_status) 
    VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$insertOrder->execute([
    $user_id,
    1, // seller_id
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


if ($payment_status === 'Paid') {
    echo "<h2>✅ Payment Successful! 🎉</h2>";
    echo "<p>Payment ID: " . htmlspecialchars($payment_id) . "</p>";
    header("Location: order.php");
    exit();
} else {
    echo "<h2>❌ Payment Failed!</h2>";
    echo "<p>Payment ID: " . htmlspecialchars($payment_id) . "</p>";
}
?>
