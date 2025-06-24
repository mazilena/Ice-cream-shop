<?php
session_start();
include '../components/connect.php'; // ✅ Correct Path

header('Content-Type: application/json');

// ✅ Check if User is Logged In
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Please login to manage cart."]);
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ Check if `cart_id` is received
if (!isset($_POST['cart_id']) || empty(trim($_POST['cart_id']))) {
    echo json_encode(["status" => "error", "message" => "Invalid cart item."]);
    exit;
}

$cart_id = trim($_POST['cart_id']);

try {
    // ✅ Check if cart item exists for the user
    $check = $conn->prepare("SELECT * FROM cart WHERE id = ? AND user_id = ?");
    $check->execute([$cart_id, $user_id]);

    if ($check->rowCount() > 0) {
        // ✅ Remove the item from cart
        $delete = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $delete->execute([$cart_id, $user_id]);

        echo json_encode(["status" => "success", "message" => "Item removed from cart!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Item not found in cart!"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
exit;
?>
