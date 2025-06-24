<?php
session_start();
include 'connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Please login to manage wishlist."]);
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($data['product_id']) || empty($data['product_id'])) {
    echo json_encode(["status" => "error", "message" => "Invalid product ID."]);
    exit;
}

$product_id = trim($data['product_id']);

$wishlist_check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
$wishlist_check->execute([$user_id, $product_id]);

if ($wishlist_check->rowCount() > 0) {
    $delete = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $delete->execute([$user_id, $product_id]);
    
    echo json_encode(["status" => "success", "message" => "Product removed from wishlist."]);
} else {
    echo json_encode(["status" => "error", "message" => "Product not found in wishlist."]);
}
exit;
?>
