<?php
include '../components/connect.php';
session_start();
if (isset($_SESSION['seller_id'])) {
    $seller_id = $_SESSION['seller_id'];
} else {
    $seller_id = '';
    header('location:login.php');
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('location:view_product.php');
    exit();
}
$product_id = $_GET['id'];

if (isset($_POST['update'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $status = $_POST['status'];

    $update_product = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, stock = ?, status = ? WHERE id = ?");
    $update_product->execute([$name, $price, $description, $stock, $status, $product_id]);

    $success_msg[] = 'product updated';

    $old_image = $_POST['old_image'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $image;

    $select_image  = $conn->prepare("SELECT * FROM products WHERE image = ? AND seller_id = ? ");
    $select_image->execute([$image, $seller_id]);

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $warning_msg[] = 'image size is too large';
        } elseif ($select_image->rowCount() > 0) {
            $warning_msg[] = 'please rename your image';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            
            if ($old_image != '' && file_exists('../uploaded_files/' . $old_image)) {
                unlink('../uploaded_files/' . $old_image);
            }
            
            $update_image = $conn->prepare("UPDATE products SET image = ? WHERE id = ?");
            $update_image->execute([$image, $product_id]);
    
            $success_msg[] = 'image updated!';
        }
    }
    header('location:view_product.php');
    exit();
}    

if (isset($_POST['delete_image'])) {
    $empty_image = '';
    $product_id = $_POST['product_id'];
    $delete_image = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $delete_image->execute([$product_id]);
    $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);

    if ($fetch_delete_image['image'] != '') {
        unlink('../uploaded_files/' . $fetch_delete_image['image']);
    }
    $unset_image = $conn->prepare("UPDATE products SET image = ? WHERE id = ?");
    $unset_image->execute([$empty_image, $product_id]);
    $success_msg[] = 'image deleted sucessfully';
}

if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $delete_image = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $delete_image->execute([$product_id]);
    $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);

    if ($fetch_delete_image['image'] != '') {
        unlink('../uploaded_files/' . $fetch_delete_image['image']);
    }
    $delete_product = $conn->prepare("DELETE FROM products WHERE id = ?");
    $delete_product->execute([$product_id]);
    $success_msg[] = 'product deleted successfully!';
    header('location:view_product.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Admin Dashboard page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="post-editor">
            <div class="heading">
                <h1>EDIT Product</h1>
                <img src="../image/separator-img.png">
            </div>
            <div class="box-container">
                <?php
                $select_product = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
                $select_product->execute([$product_id, $seller_id]);
                if ($select_product->rowCount() > 0) {
                    while ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <div class="form-container">
                            <form action="" method="post" enctype="multipart/form-data" class="register">
                                <input type="hidden" name="old_image" value="<?= $fetch_product['image']; ?>">
                                <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">
                                <div class="input-field">
                                    <p> Product Status<span>*</span></p>
                                    <select name="status" class="box">
                                        <option value="<?= $fetch_product['status']; ?>" selected> <?= $fetch_product['status']; ?></option>
                                        <option value="active">active </option>
                                        <option value="deactive">deactive </option>
                                    </select>
                                </div>
                                <div class="input-field">
                                    <p> Product name<span>*</span></p>
                                    <input type="text" name="name" value="<?= $fetch_product['name']; ?>" class="box">
                                </div>
                                <div class="input-field">
                                    <p> Product price<span>*</span></p>
                                    <input type="number" name="price" value="<?= $fetch_product['price']; ?>" class="box">
                                </div>
                                <div class="input-field">
                                    <p> Product description<span>*</span></p>
                                    <textarea name="description" class="box"><?= $fetch_product['description']; ?></textarea>
                                </div>
                                <div class="input-field">
                                    <p> Product stock<span>*</span></p>
                                    <input type="number" name="stock" value="<?= $fetch_product['stock']; ?>" class="box">
                                </div>
                                <div class="input-field">
                                    <p> Product image<span>*</span></p>
                                    <input type="file" name="image" accept="image/*" class="box">
                                    <?php if ($fetch_product['image'] != '') { ?>
                                        <img src="../uploaded_files/<?= $fetch_product['image']; ?>" class="image">
                                    <?php } ?>
                                </div>
                                <br><br>
                                <div class="flex-btn">
                                    <input type="submit" name="update" value="update product" class="btn">
                                    <input type="submit" name="delete_product" value="delete product" class="btn">
                                </div>
                            </form>
                        </div>
                    <?php }
                } ?>
            </div>
        </section>
    </div>
</body>
</html>
