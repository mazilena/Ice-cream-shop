<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'components/connect.php';

if (!isset($conn)) {
    die("<p style='color:red;'>Database connection error!</p>");
}

// ✅ Dynamic Category Fetch
$category_query = "SELECT DISTINCT category FROM products WHERE status = 'active'";
$category_stmt = $conn->prepare($category_query);
$category_stmt->execute();
$categories = $category_stmt->fetchAll(PDO::FETCH_COLUMN);

// ✅ Category Filter Logic
$category_filter = isset($_GET['category']) ? filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING) : 'All';

$query = "SELECT * FROM products WHERE status = 'active'";
$params = [];

if ($category_filter !== 'All') {
    $query .= " AND category = ?";
    $params[] = $category_filter;
}

try {
    $select_products = $conn->prepare($query);
    $select_products->execute($params);
} catch (PDOException $e) {
    die(json_encode(["status" => "error", "message" => "Error fetching products: " . $e->getMessage()]));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Menu</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        /* Custom styles here */
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
        .menu h1 {
            text-align: center;
            color: #ff4081;
        }
        .filter-container {
            text-align: center;
            margin-bottom: 15px;
        }
        .filter-container select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .products {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 55px;
            justify-content: center;
        }
        .product-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 12px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            height: auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            border-radius: 10px;
        }
        .product-card h2 {
            color: #333;
            font-size: 1rem;
            margin: 3px 0;
        }
        .price {
            color: #ff4081;
            font-weight: bold;
            font-size: 1rem;
            margin: 3px 0;
        }
        .description {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 5px;
        }
        .quantity-selector button {
            background: none;
            border: none;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            color: #ff4081;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quantity-selector button:hover {
            color: #e91e63;
            transform: scale(1.2);
        }
        .quantity-selector input {
            width: 40px;
            text-align: center;
            border: none;
            font-size: 1.2rem;
            font-weight: bold;
            background: transparent;
        }
        
        .action-buttons {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.action-buttons .btn {
    position: relative;
    width: 140px; /* Both buttons same width */
    padding: 10px;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    font-family: 'Times New Roman', Times, serif;
    text-align: center;
    transition: all 0.4s ease-in-out;
    overflow: hidden;
}

/* Buy Now (Normal Button) */
.buy-now {
    background: white;
    color: #ff4081;
    border: 2px solid #ff4081;
}

/* Add to Cart (With Buy Now Effect) */
.add-to-cart {
    background: white;
    color: #ff4081;
    border: 2px solid #ff4081;
    position: relative;
    overflow: hidden;
}

.add-to-cart::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: #ff4081;
    transition: left 0.4s ease-in-out;
    z-index: 0;
}

.add-to-cart:hover::before {
    left: 0;
}

.add-to-cart span {
    position: relative;
    z-index: 1;
    transition: color 0.4s ease-in-out;
}

.add-to-cart:hover span {
    color: white;
}

        .wishlist-btn {
            background: transparent;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #ff4081;
        }
        .wishlist:hover {
            color: #e91e63;
            opacity: 0.7;
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
        }
        .close-btn {
            float: right;
            font-size: 20px;
            cursor: pointer;
        }
        .login-btn {
            display: inline-block;
            padding: 10px;
            background: #ff4081;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="banner">
        <div class="detail">
            <h1>Our Shop</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum ea perferendis odio beatae reiciendis<br> culpa laudantium, dicta neque eveniet harum voluptates corporis porro placeat ex?</p>
            <span><a href="home.php">Home</a><i class="bx bx-right-arrow-alt"></i>Our Shop</span>
        </div>
    </div>

<div class="main-container">
    <section class="menu">
        <h1>Our Menu</h1>
        <div class="filter-container">
    <label for="categoryFilter"><strong>Filter by Category:</strong></label>
    <select id="categoryFilter" onchange="filterProducts()">
        <option value="All" <?= $category_filter === 'All' ? 'selected' : '' ?>>All</option>
        <?php foreach ($categories as $category) { ?>
            <option value="<?= urlencode($category) ?>" <?= $category_filter == $category ? 'selected' : '' ?>>
                <?= htmlspecialchars($category) ?>
            </option>
        <?php } ?>
    </select>
</div>


        <div class="products">
            <?php while ($row = $select_products->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="product-card">
                    <img src="uploaded_files/<?= $row['image']; ?>" alt="<?= $row['name']; ?>" class="product-image">
                    <h3><?= $row['name']; ?></h3>
                    <p>₹<?= $row['price']; ?></p>
                    <p><?= $row['description']; ?></p>

                    <div class="quantity-selector">
                        <button class="quantity-decrease" data-id="<?= $row['id']; ?>">-</button>
                        <input type="number" id="quantity_<?= $row['id']; ?>" value="1" min="1">
                        <button class="quantity-increase" data-id="<?= $row['id']; ?>">+</button>
                    </div>
                    <div class="action-buttons">
                    <button class="btn buy-now" data-id="<?= $row['id']; ?>" data-price="<?= $row['price']; ?>">Buy Now</button>
                        <button class="btn add-to-cart" data-id="<?= $row['id']; ?>">Add to Cart</button>
                        <button class="wishlist-btn" data-id="<?= $row['id']; ?>">♡</button>

                        
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
</div>

<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Login Required</h2>
        <p>You need to login or register to continue.</p>
        <a href="login.php" class="login-btn">Login Now</a>
    </div>
</div>

<script>
document.querySelectorAll(".buy-now").forEach(button => {
    button.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent form submission
        let productId = this.getAttribute("data-id");
        let quantity = document.getElementById("quantity_" + productId)?.value || 1;
        let checkoutUrl = `check.php?product_id=${encodeURIComponent(productId)}&qty=${encodeURIComponent(quantity)}`;
        window.location.href = checkoutUrl;
    });
});




    document.querySelectorAll(".add-to-cart").forEach(button => {
    button.addEventListener("click", function () {
        let productId = this.dataset.id.trim(); // ✅ Trim it, cuz it's product_id VARCHAR
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


    document.querySelectorAll(".quantity-increase").forEach(button => {
        button.addEventListener("click", function () {
            let productId = this.getAttribute("data-id");
            let quantityInput = document.getElementById("quantity_" + productId);
            if (quantityInput) quantityInput.value = parseInt(quantityInput.value) + 1;
        });
    });

    document.querySelectorAll(".quantity-decrease").forEach(button => {
        button.addEventListener("click", function () {
            let productId = this.getAttribute("data-id");
            let quantityInput = document.getElementById("quantity_" + productId);
            if (quantityInput && quantityInput.value > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        });
    });
    document.querySelectorAll(".wishlist-btn").forEach(button => {
    button.addEventListener("click", function (event) {
        event.preventDefault();

        let productId = this.dataset.id.trim();

        fetch('components/add_wishlist.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            console.log("Response Data:", data); // 👈 Debugging ke liye
            alert(data.message);
        })
        .catch(error => console.error("Error:", error));
    });
});


function filterProducts() {
    let category = document.getElementById("categoryFilter").value;
    window.location.href = "menu.php?category=" + category;
}

function closeModal() {
    document.getElementById('loginModal').style.display = 'none';
}
</script>

</body>
</html>

