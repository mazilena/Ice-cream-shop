<?php
include '../components/connect.php';
session_start();

if (isset($_SESSION['seller_id'])) {
    $seller_id = $_SESSION['seller_id'];
} else {
    $seller_id = '';
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Account</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background: #f8f9fa;
            
        }
        .table th, .table td { text-align: center; vertical-align: middle; padding: 12px; }
        .table th { color: black; font-size: 25px; }
        .table td { font-size: 18px; }
        .table-striped tbody tr:nth-child(odd) { background-color: #f9f9f9; }
        .table-hover tbody tr:hover { background-color: #e91e63; color: #fff; }
        .table { border-radius: 35px; overflow: hidden; border: 1px solid #ddd; }
        .img-thumbnail { width: 70px; height: 70px; object-fit: cover; border-radius: 50%; }
    </style>
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="user-container">
            <div class="heading">
                <h1>Seller Accounts</h1>
                <img src="../image/separator-img.png">
            </div>

            <div class="container">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select_sellers = $conn->prepare("SELECT * FROM sellers");
                            $select_sellers->execute();

                            if ($select_sellers->rowCount() > 0) {
                                while ($fetch_sellers = $select_sellers->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td><?= $fetch_sellers['id']; ?></td>
                                <td><?= htmlspecialchars($fetch_sellers['name']); ?></td>
                                <td><?= htmlspecialchars($fetch_sellers['email']); ?></td>
                                
                            </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center">No seller accounts found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
