<?php
include '../components/connect.php';
session_start();

$seller_id = isset($_SESSION['seller_id']) ? $_SESSION['seller_id'] : '';

if (!$seller_id) {
    header('location:login.php');
    exit;
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch image path to delete from folder
    $select_product = $conn->prepare("SELECT image FROM products WHERE id = ? AND seller_id = ?");
    $select_product->execute([$product_id, $seller_id]);
    $product = $select_product->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $image_path = '../uploaded_files/' . $product['image'];
        
        // Delete product from database
        $delete_product = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
        $delete_product->execute([$product_id, $seller_id]);

        // Delete image file from server
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Redirect with success message
        $_SESSION['message'] = "Product deleted successfully!";
        header('location: view_product.php');
        exit;
    } else {
        $_SESSION['message'] = "Product not found!";
        header('location: view_product.php');
        exit;
    }
} else {
    $_SESSION['message'] = "Invalid request!";
    header('location: view_product.php');
    exit;
}
?>
