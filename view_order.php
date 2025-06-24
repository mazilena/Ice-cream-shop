<?php
    include 'components/connect.php' ;
    session_start();
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }
    else {
        $user_id = '';
        
    }
    
    if (isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
    }else{
        $get_id = '';
        //header('location:order.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Order Detail page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <?php 
        include 'components/user_header.php';
    ?>
    <div class="banner">
        <div class="detail">
            <h1>orders detail</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum ea perferendis odio beatae reiciendis<br> culpa laudantium, dicta neque eveniet harum voluptates corporis porro placeat ex?</p>
            <span><A href="home.php">Home</a><i class= "bx bx-right-arrow-alt"></i>orders detail<span>
        </div>
    </div>
    <div class="orders-detail">
        <div class="heading">
            <h1>My orders detail</h1>
            <p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi, expedita labore impedit rem culpa omnis!</p>
            <img src="image/separator-img.png">
        </div>
        <div class="box-container">
            <?php 
                $grand_total = 0;
                $select_orders = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
                $select_orders->execute([$get_id]);

                if ($select_orders->rowCount() > 0) {
                    
                    while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
                        $select_products = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
                        $select_products->execute([$fetch_orders['product_id']]);
                        if ($select_products->rowCount() > 0) {
                            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
                                $sub_total = ($fetch_orders['price']* $fetch_orders['qty']);
                                $grand_total += $sub_total;
                           
            ?>
            <div class="box">
                <div class="col">
                    <img src="uploaded_files/<?= $fetch_products['image'] ?>" class="image">
                    <p class="price"><?= $fetch_products['price'];?></p>
                    <h3 class="name"><?= $fetch_products['name'];?></h3>
                    <p class="grand-total"> total amount payable :<span><?= $grand_total; ?></span></p>
                </div>
                <div class="col">
                    <p class="title">Billing Address</p>
                    <p class="user"><i class="bi bi-person-bounding-box"></i><?= $fetch_order['name'];?></p>
                    <p class="user"><i class="bi bi-phone"></i><?= $fetch_order['number'];?></p>
                    <p class="user"><i class="bi bi-envelope"></i><?= $fetch_order['email'];?></p>
                    <p class="user"><i class="bi bi-pin-map-fill"></i><?= $fetch_order['address'];?></p>
                    <p class="status" style="color:<?php if($fetch_orders['status'] == 'delivered'){echo "green";}elseif($fetch_orders['status'] == 'canceled'){echo "red";}else{echo "orange";}?>"><?= $fetch_orders['status']; ?></p>
                    <?php if($fetch_orders['status']== 'canceled')?>
                </div>
            </div>
            <?php 
                            }
                        }
                    }
                }
            ?>
        </div>
    </div>

    

    

    <?php include 'components/footer.php';?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js"></script>
    <?php 
        include 'components/alert.php';
    ?>
</body>
</html>