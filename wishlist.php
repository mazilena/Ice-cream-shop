<?php
session_start();
include 'components/connect.php';

if (!isset($_SESSION['user_id'])) {
    die("<script>alert('Please login to view wishlist.'); window.location='login.php';</script>");
}

$user_id = $_SESSION['user_id'];
$query = "SELECT p.* FROM wishlist w INNER JOIN products p ON w.product_id = p.id WHERE w.user_id = ?";
$wishlist_items = $conn->prepare($query);
$wishlist_items->execute([$user_id]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>

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

    .navbar a {
        text-decoration: none !important;
        color: inherit;
    }
    
    .navbar a:hover {
        color: #ff4081;
        text-decoration: none !important;
    }

    .main-container {
        width: 85%;
        margin: auto;
        padding: 20px;
    }

    h1 {
        text-align: center;
        color: #ff4081;
        text-decoration: none;
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

    .price {
        color: #ff4081;
        font-weight: bold;
        font-size: 1rem;
        margin: 3px 0;
    }

    .remove-wishlist {
        background: #ff4081;
        color: white;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 8px;
    }

    .remove-wishlist:hover {
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
    <h1>Your Wishlist</h1>
    <button onclick="window.history.go(-1)" class="btn">Back</button>

    <div class="products">
        <?php while ($row = $wishlist_items->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="product-card">
                <img src="uploaded_files/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                <h3><?= htmlspecialchars($row['name']); ?></h3>
                <p><?= htmlspecialchars($row['description']); ?></p>
                <p class="price">₹<?= htmlspecialchars($row['price']); ?></p>
            </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll(".remove-wishlist").forEach(button => {
    button.addEventListener("click", function () {
        let productId = this.getAttribute("data-id");

        fetch('components/remove_wishlist.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert(data.message);
                this.closest(".product-card").remove();  // ✅ Card Remove
            } else {
                alert("Error: " + data.message);  // ✅ Improved error handling
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Error removing product. Please try again.");
        });
    });
});
</script>

</body>
</html>
