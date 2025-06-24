<?php
session_start();
$all_product_ids = [];
$all_qtys = [];

include 'components/connect.php';

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("<script>alert('Please login to view cart.'); window.location='login.php';</script>");
}

$user_id = $_SESSION['user_id'];

// ✅ Ensure database connection is valid
if (!$conn) {
    die("<script>alert('Database connection failed!'); window.location='menu.php';</script>");
}

// ✅ Prepare & execute cart query
$query = "SELECT p.*, c.qty, c.id as cart_id, c.product_id 
          FROM cart c 
          INNER JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = ?";

$cart_items = $conn->prepare($query);
if (!$cart_items) {
    die("<script>alert('SQL Error: " . $conn->errorInfo()[2] . "'); window.location='menu.php';</script>");
}

$cart_items->execute([$user_id]);

// ✅ Fetch data properly
$cart_data = $cart_items->fetchAll(PDO::FETCH_ASSOC);

if (!$cart_data) {
    die("<script>alert('No items found in cart!'); window.location='menu.php';</script>");
}

// ✅ Initialize total amount
$total_amount = 0;

foreach ($cart_data as $item) {
    $all_product_ids[] = $item['product_id']; // Fix: Using product_id instead of id
    $all_qtys[] = $item['qty'];
    $total_amount += $item['price'] * $item['qty']; // Calculate total amount
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background: url('./uploaded_files/image/ice-creem-banner-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        .main-container {
            width: 85%;
            margin: auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #ff4081;
        }
        .products {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 35px;
            justify-content: center;
        }
        .product-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 12px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 10px;
        }
        .product-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .price {
            color: #ff4081;
            font-weight: bold;
            font-size: 1rem;
            margin: 3px 0;
        }
        .remove-cart, .buy-now {
            background: #ff4081;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 8px;
        }
        .remove-cart:hover {
            background: darkred;
        }
        .buy-now {
            background: #28a745;
        }
        .buy-now:hover {
            background: #218838;
        }
        .cart-summary {
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .checkout-btn {
            background: #ff4081;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            margin-top: 10px;
            border: none;
        }
        .checkout-btn:hover {
            background: darkred;
        }
        @media (max-width: 900px) {
            .products {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 600px) {
            .products {
                grid-template-columns: repeat(1, 1fr);
            }
        }
    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="main-container">
    <h1>Your Cart</h1>
    <button onclick="window.history.go(-1)" class="btn">Back</button>

    <div class="products">
        <?php foreach ($cart_data as $row) { ?>
            <div class="product-card">
                <img src="uploaded_files/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                <h3 class="product-title"><?= htmlspecialchars($row['name']); ?></h3>
                
                <p class="price">
                    ₹<?= number_format($row['price'], 2); ?>
                    <?php if ($row['qty'] > 1) { ?>
                        <span style="color: gray; font-size: 0.9rem;">(<?= $row['qty']; ?>)</span>
                    <?php } ?>
                </p>

                <button class="remove-cart" data-id="<?= htmlspecialchars($row['cart_id']); ?>">Remove</button>
                <button class="buy-now" data-product-id="<?= htmlspecialchars($row['product_id']); ?>" data-qty="<?= htmlspecialchars($row['qty']); ?>">Buy Now</button>
            </div>
        <?php } ?>
    </div>

    <div class="cart-summary">
        <h2>Total Amount: ₹<span id="totalAmount"><?= number_format($total_amount, 2); ?></span></h2>
        
    </div>
</div>

<script>
    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.addEventListener("click", function () {
            let productId = this.dataset.id.trim();
            let price = parseFloat(this.dataset.price);
            let quantity = parseInt(document.getElementById("quantity_" + productId).value);

            fetch('components/add_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: productId, price: price, qty: quantity })
            })
            .then(response => response.json())
            .then(data => alert(data.message))
            .catch(error => console.error("Error:", error));
        });
    });

    document.querySelectorAll(".remove-cart").forEach(button => {
        button.addEventListener("click", function () {
            let cartId = this.getAttribute("data-id");

            fetch('components/remove_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `cart_id=${encodeURIComponent(cartId)}`
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") {
                    location.reload();
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });

    document.querySelectorAll(".buy-now").forEach(button => {
        button.addEventListener("click", function () {
            let productId = this.getAttribute("data-product-id");
            let quantity = this.getAttribute("data-qty");

            // Ensure URL encoding is properly done
            let url = new URL('http://localhost/icecream_shop/check.php');
            url.searchParams.append('product_id', productId);
            url.searchParams.append('qty', quantity);

            window.location.href = url.toString();
        });
    });

    document.querySelector(".btn-primary").addEventListener("click", function (event) {
        event.preventDefault(); // Default link action rokna hai

        let productIds = [];
        let quantities = [];

        document.querySelectorAll(".buy-now").forEach(button => {
            productIds.push(button.getAttribute("data-product-id"));
            quantities.push(button.getAttribute("data-qty"));
        });

        if (productIds.length > 0) {
            let url = new URL('http://localhost/icecream_shop/check.php');

            productIds.forEach((id, index) => {
                url.searchParams.append('product_id[]', id);
                url.searchParams.append('qty[]', quantities[index]);
            });

            window.location.href = url.toString();
        } else {
            alert("Your cart is empty!");
        }
    });
</script>
</body>
</html>

