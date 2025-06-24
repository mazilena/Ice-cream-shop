<?php
session_start();
include 'connect.php';
header('Content-Type: application/json');

// JSON data fetch karna
$data = json_decode(file_get_contents('php://input'), true);

// Debugging log file
file_put_contents("debug_log.txt", "Received data: " . print_r($data, true) . "\n", FILE_APPEND);

// User logged in check
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Please login to manage wishlist."]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if product_id is provided
if (!isset($data['product_id']) || empty($data['product_id'])) {
    echo json_encode(["status" => "error", "message" => "Invalid product ID."]);
    exit;
}

$product_id = trim($data['product_id']); // Trim spaces

// Debugging product ID check
file_put_contents("debug_log.txt", "Product ID Received: $product_id\n", FILE_APPEND);

// Product validation check
$product_check = $conn->prepare("SELECT product_id FROM products WHERE product_id = ?");
$product_check->execute([$product_id]);

if ($product_check->rowCount() == 0) {
    echo json_encode(["status" => "error", "message" => "Product does not exist."]);
    file_put_contents("debug_log.txt", "Product not found in 'products' table\n", FILE_APPEND);
    exit;
}

// Wishlist check
$wishlist_check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
$wishlist_check->execute([$user_id, $product_id]);

if ($wishlist_check->rowCount() > 0) {
    // Remove from wishlist
    $delete = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $delete->execute([$user_id, $product_id]);
    
    echo json_encode(["status" => "success", "message" => "Removed from wishlist!"]);
    file_put_contents("debug_log.txt", "Removed product ID: $product_id from wishlist\n", FILE_APPEND);
} else {
    // Add to wishlist
    $insert = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $insert->execute([$user_id, $product_id]);
    
    echo json_encode(["status" => "success", "message" => "Added to wishlist!"]);
    file_put_contents("debug_log.txt", "Added product ID: $product_id to wishlist\n", FILE_APPEND);
}

exit;
?>
