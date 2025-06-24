<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'components/connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit();
}

// ✅ Get JSON Input
$data = json_decode(file_get_contents('php://input'), true);

// ✅ Validate Input
if (!isset($_SESSION['user_id']) || empty($data['products']) || !isset($data['payment_method'])) {
    echo json_encode(["success" => false, "message" => "Invalid order details"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$products = $data['products'];
$total_price = (float) $data['total_price'];
$payment_method = $data['payment_method'];
$order_date = date('Y-m-d H:i:s');
$order_status = "in progress";
$payment_status = "pending";

// ✅ Generate formatted Order ID & User ID
$order_count = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$order_id = 'O' . str_pad($order_count + 1, 3, '0', STR_PAD_LEFT);
$formatted_user_id = $user_id; // ❌ 'U'

// ✅ Insert Order into Database
$order_query = $conn->prepare("INSERT INTO orders (id, user_id, method, price, status, payment_status, dates) VALUES (?, ?, ?, ?, ?, ?, ?)");
$order_query->execute([$order_id, $formatted_user_id, $payment_method, $total_price, $order_status, $payment_status, $order_date]);

if ($order_query) {
    // ✅ Insert Order Details
    $orderDetailsQuery = $conn->prepare("INSERT INTO order_details (order_id, user_id, product_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
    foreach ($products as $product) {
        $orderDetailsQuery->execute([$order_id, $formatted_user_id, $product['id'], $product['qty'], $product['total_price']]);
    }

    // ✅ If cart order, clear cart
    if (isset($data['buy_cart']) && $data['buy_cart']) {
        $clear_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $clear_cart->execute([$user_id]);
    }

    echo json_encode(["success" => true, "message" => "Order placed successfully!", "order_id" => $order_id]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to place order"]);
}
?>
