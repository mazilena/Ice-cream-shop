<?php
// Database connection include
include '../components/connect.php';

// Function to get count from database
function getCount($query) {
    global $conn;
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['count'] : 0;
}

// Fetching all data dynamically
$total_products = getCount("SELECT COUNT(*) AS count FROM products");
$total_active_products = getCount("SELECT COUNT(*) AS count FROM products WHERE status = 'active'");
$total_deactive_products = getCount("SELECT COUNT(*) AS count FROM products WHERE status = 'deactive'");
$total_orders = getCount("SELECT COUNT(*) AS count FROM orders");
$total_confirm_orders = getCount("SELECT COUNT(*) AS count FROM orders WHERE status = 'confirmed'");
$total_canceled_orders = getCount("SELECT COUNT(*) AS count FROM orders WHERE status = 'canceled'");
$total_messages = getCount("SELECT COUNT(*) AS count FROM message"); 
$total_users = getCount("SELECT COUNT(*) AS count FROM users");
$total_sellers = getCount("SELECT COUNT(*) AS count FROM sellers");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
   .box-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 cards per row */
    gap: 20px;
}

.box {
    background: #fff;
    border: 2px solid #ff4081;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    height: 270px; /* Fixed height */
    width: 250px;  /* Fixed width */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.box h3 {
    font-size: 2rem;
    color: #ff4081;
    margin-bottom: 5px;
}

.box p {
    color: #333;
    font-size: 1.1rem;
    margin-bottom: 15px;
    word-wrap: break-word;      /* Long text ko break karke naye line pe le aayega */
    white-space: normal;         /* Text ko wrap hone ki permission milegi */
    overflow: hidden;            /* Overflow prevent karega */
}

.box .btn {
    color: black;
    padding: 8px 15px;
    border-radius: 20px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.box .btn:hover {
    background: #e91e63;
}

@media (max-width: 900px) {
    .box-container {
        grid-template-columns: repeat(2, 1fr); /* 2 cards per row on medium screens */
    }
}

@media (max-width: 600px) {
    .box-container {
        grid-template-columns: repeat(1, 1fr); /* 1 card per row on small screens */
    }
}

    </style>
</head>
<body>

    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>

        <section class="dashboard">
            <div class="heading">
                <h1>dashboard</h1>
                <img src="../image/separator-img.png">
            </div>

            <div class="box-container">
                <div class="box">
                    <h3><?= $total_products; ?></h3>
                    <p>products</p>
                    <a href="add_products.php" class="btn">add product</a>
                </div>

                <div class="box">
                    <h3><?= $total_active_products; ?></h3>
                    <p>total active products</p>
                    <a href="manage_products.php" class="btn">active product</a>
                </div>

                <div class="box">
                    <h3><?= $total_deactive_products; ?></h3>
                    <p>total deactive products</p>
                    <a href="manage_products.php" class="btn">deactive product</a>
                </div>

                <div class="box">
                    <h3><?= $total_orders; ?></h3>
                    <p>total orders placed</p>
                    <a href="admin_order.php" class="btn">total orders</a>
                </div>

            <!-- <div class="box">
                    <h3><?= $total_confirm_orders; ?></h3>
                    <p>total confirmed orders</p>
                    <a href="admin_order.php" class="btn">confirm orders</a>
                </div>

                <div class="box">
                    <h3><?= $total_canceled_orders; ?></h3>
                    <p>total canceled orders</p>
                    <a href="admin_order.php" class="btn">canceled orders</a>
                </div> -->

                <div class="box">
                    <h3><?= $total_messages; ?></h3>
                    <p>unread messages</p>
                    <a href="admin_message.php" class="btn">see messages</a>
                </div>

                <div class="box">
                    <h3><?= $total_users; ?></h3>
                    <p>users account</p>
                    <a href="user_accounts.php" class="btn">see users</a>
                </div>

                <div class="box">
                    <h3><?= $total_sellers; ?></h3>
                    <p>sellers account</p>
                    <a href="seller.php" class="btn">see sellers</a>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>
</html>



