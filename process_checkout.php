<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'components/connect.php';

if (!isset($_SESSION['user_id'])) {
    die("<script>alert('Please login to proceed.'); window.location='login.php';</script>");
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_query = $conn->prepare("SELECT id, name FROM users WHERE id = ?");
$user_query->execute([$user_id]);
$user = $user_query->fetch(PDO::FETCH_ASSOC);
$username = $user ? htmlspecialchars($user['name']) : "Unknown User";

// Ensure user_id is stored in "U001" format
$formatted_user_id = isset($user['id']) ? $user['id'] : 'U' . str_pad($user_id, 3, '0', STR_PAD_LEFT);

// Initialize invoice variables
$products = [];
$total_price = 0;
$invoice_date = date("d M Y, h:i A");

// Fetch products
if (!empty($_GET['product_id']) && !empty($_GET['quantity'])) {
    $product_id = trim($_GET['product_id']);
    $quantity = (int)$_GET['quantity'];

    $product_query = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
    $product_query->execute([$product_id]);

    $product = $product_query->fetch(PDO::FETCH_ASSOC);
    $product['qty'] = $quantity;
    $product['total_price'] = $product['price'] * $quantity;
    $total_price = $product['total_price'];
    $products[] = $product;
} elseif (!empty($_GET['buy_cart'])) {
    $cart_query = $conn->prepare("SELECT p.id, p.name, p.price, p.image, c.qty FROM cart c 
                                 INNER JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $cart_query->execute([$user_id]);
    $products = $cart_query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as &$product) {
        $product['total_price'] = $product['price'] * $product['qty'];
        $total_price += $product['total_price'];
    }
}

// Generate Order ID
$order_count = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$order_id = 'O' . str_pad($order_count + 1, 3, '0', STR_PAD_LEFT);

// Process Checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid data received",
            "received_data" => file_get_contents("php://input")
        ]);
        exit;
    }
    

    $payment_method = $data['payment_method'] ?? '';
    $payment_id = $data['payment_id'] ?? null;

    try {
        $conn->beginTransaction();
        $orderInsertQuery = $conn->prepare("INSERT INTO orders (id, user_id, method, price, status, payment_status, dates) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $orderInsertQuery->execute([$order_id, $user_id, $payment_method, $total_price, "in progress", "pending", date('Y-m-d')]);

        $orderDetailsQuery = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) 
                                             VALUES (?, ?, ?, ?)");
        foreach ($data['products'] as $product) {
            $orderDetailsQuery->execute([$order_id, $product['id'], $product['qty'], $product['total_price']]);
        }

        if ($payment_method === "cod") {
            $conn->prepare("UPDATE orders SET payment_status = 'COD' WHERE id = ?")->execute([$order_id]);
        }

        $conn->commit();
        echo json_encode(["success" => true, "message" => "Order placed successfully!", "order_id" => $order_id]);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(["success" => false, "message" => "Order failed: " . $e->getMessage()]);
    }
}
?>
