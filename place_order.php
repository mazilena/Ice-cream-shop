<?php 
session_start();
include 'components/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch user details
$getUser = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$getUser->execute([$user_id]);
$user = $getUser->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: login.php");
    exit();
}

$user_name = $user['name'];
$user_email = $user['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address']);
    $address_type = $_POST['address_type'];
    $payment_method = $_POST['method'];
    $order_status = 'in progress';
    $payment_status = 'Pending';
    $seller_id = 1;
    $total_price = $_POST['total_price'];

    if ($payment_method == "COD") {
        // ✅ COD Order Processing
        if (!empty($_POST['product_id']) && !empty($_POST['price']) && !empty($_POST['qty'])) {
            $insertOrder = $conn->prepare("INSERT INTO orders 
                (id, user_id, seller_id, name, email, address, address_type, method, product_id, price, qty, status, payment_status) 
                VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $insertOrder->execute([
                $user_id, $seller_id, $user_name, $user_email, $address, $address_type,
                $payment_method, $_POST['product_id'], $_POST['price'], $_POST['qty'], $order_status, $payment_status
            ]);

            header("Location: order.php");
            exit();
        }
    } else {
        // ✅ Razorpay Payment Processing (Improved)
        $razorpay_key = "rzp_test_UPoUFyhQBliE3B"; 
        ?>

        <form id="paymentForm" action="order_success.php" method="POST">
            <input type="hidden" name="payment_id" id="payment_id">
            <input type="hidden" name="address" value="<?= htmlspecialchars($address) ?>">
            <input type="hidden" name="address_type" value="<?= htmlspecialchars($address_type) ?>">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($_POST['product_id']) ?>">
            <input type="hidden" name="price" value="<?= htmlspecialchars($_POST['price']) ?>">
            <input type="hidden" name="qty" value="<?= htmlspecialchars($_POST['qty']) ?>">
        </form>

        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            var options = {
                'key': '<?= $razorpay_key ?>',
                'amount': '<?= $total_price * 100 ?>',
                'currency': 'INR',
                'name': 'Blue Sky Summer',
                'description': 'Order Payment',
                'handler': function (response) {
                    document.getElementById('payment_id').value = response.razorpay_payment_id;
                    document.getElementById('paymentForm').submit();  // ✅ Auto-submit after successful payment
                },
                'prefill': {
                    'name': '<?= $user_name ?>',
                    'email': '<?= $user_email ?>'
                },
                'theme': {
                    'color': '#3399cc'
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        </script>
        <?php
        exit();
    }
}
?>
