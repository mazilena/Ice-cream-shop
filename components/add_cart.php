<?php
session_start();

// ✅ Check if database connection file exists
if (file_exists('connect.php')) {
    include 'connect.php';
} else {
    echo json_encode(["status" => "error", "message" => "Database connection file missing!"]);
    exit;
}

header('Content-Type: application/json');

// ✅ Ensure User is Logged In
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Please login to add items to cart."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// ✅ Debugging Logs
error_log("🛒 RAW JSON RECEIVED: " . $rawData);

// ✅ JSON Validation
if (!$data || json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON Format!"]);
    exit;
}

// ✅ Validate & Process Data
$product_id = isset($data['product_id']) ? trim($data['product_id']) : ''; // VARCHAR को ट्रिम करके स्टोर करें
$price = isset($data['price']) ? floatval($data['price']) : 0;
$qty = isset($data['qty']) ? intval($data['qty']) : 1;

if (empty($product_id)) {
    echo json_encode(["status" => "error", "message" => "Invalid product ID"]);
    exit;
}

try {
    // ✅ Check if product already in cart
    $check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $check->execute([$user_id, $product_id]);

    if ($check->rowCount() > 0) {
        // ✅ Update quantity if item exists
        $update = $conn->prepare("UPDATE cart SET qty = qty + ? WHERE user_id = ? AND product_id = ?");
        $update->execute([$qty, $user_id, $product_id]);
        echo json_encode(["status" => "success", "message" => "Cart updated!"]);
    } else {
        // ✅ Insert new item if not exists
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, price, qty) VALUES (?, ?, ?, ?)");
        $insert->execute([$user_id, $product_id, $price, $qty]);
        echo json_encode(["status" => "success", "message" => "Added to cart!"]);
    }
} catch (PDOException $e) {
    error_log("❌ Database Error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
exit;
?>
