<?php 
    include '../components/connect.php';
    session_start();
    
    if (!isset($_SESSION['seller_id'])) {
        header('location:login.php');
        exit;
    }

    $seller_id = $_SESSION['seller_id'];

    // Fetch categories from database
    function getCategories($conn) {
        $categories = [];
        $fetch_categories = $conn->prepare("SELECT * FROM categories");
        $fetch_categories->execute();
        while ($row = $fetch_categories->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row['category'];
        }
        return $categories;
    }

    // Handle adding a new category
    if (isset($_POST['add_category'])) {
        $new_category = trim($_POST['new_category']);

        if (!empty($new_category)) {
            try {
                // Check if category already exists
                $check_category = $conn->prepare("SELECT COUNT(*) FROM categories WHERE category = ?");
                $check_category->execute([$new_category]);
                $category_exists = $check_category->fetchColumn();

                if ($category_exists == 0) {
                    // Insert new category
                    $insert_category = $conn->prepare("INSERT INTO categories (category) VALUES (?)");
                    $insert_category->execute([$new_category]);

                    if ($insert_category->rowCount() > 0) {
                        $success_msg = "Category added successfully!";
                    } else {
                        $error_msg = "Something went wrong! Please try again.";
                    }
                } else {
                    $warning_msg = "Category already exists!";
                }
            } catch (PDOException $e) {
                $error_msg = "Database error: " . $e->getMessage();
            }
        } else {
            $warning_msg = "Category name cannot be empty!";
        }
    }

    // Handle category deletion
    if (isset($_POST['delete_category'])) {
        $category = $_POST['category'];

        if (!empty($category)) {
            try {
                // Delete category
                $delete_category = $conn->prepare("DELETE FROM categories WHERE category = ?");
                $delete_category->execute([$category]);

                if ($delete_category->rowCount() > 0) {
                    $success_msg = "Category deleted successfully!";
                } else {
                    $error_msg = "Something went wrong! Please try again.";
                }
            } catch (PDOException $e) {
                $error_msg = "Database error: " . $e->getMessage();
            }
        }
    }

    // Fetch updated categories list
    $categories = getCategories($conn);

    // Handle adding or updating a product
    if (isset($_POST['publish']) || isset($_POST['draft']) || isset($_POST['update'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $stock = $_POST['stock'];
        $category = $_POST['category'];
        $status = isset($_POST['publish']) ? 'active' : 'deactive';
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/' . $image;

        if (!empty($image)) {
            move_uploaded_file($image_tmp_name, $image_folder);
        }

        // Generate new product_id
        $query = "SELECT product_id FROM products ORDER BY product_id DESC LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $last_product = $stmt->fetch(PDO::FETCH_ASSOC);
        $last_product_id = $last_product ? $last_product['product_id'] : 'P000';

        $numeric_part = (int)substr($last_product_id, 1);
        $new_product_id = 'P' . str_pad($numeric_part + 1, 3, '0', STR_PAD_LEFT);

        if (isset($_POST['update'])) {
            $update_product = $conn->prepare("UPDATE products SET name=?, price=?, image=?, stock=?, category=?, status=?, description=? WHERE id=? AND seller_id=?");
            $update_product->execute([$name, $price, $image, $stock, $category, $status, $description, $_POST['product_id'], $seller_id]);
        } else {
            $insert_product = $conn->prepare("INSERT INTO products (id, product_id, seller_id, name, price, image, stock, category, status, description) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_product->execute([$new_product_id, $new_product_id, $seller_id, $name, $price, $image, $stock, $category, $status, $description]);
        }

        header('location: view_product.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Admin Add Product</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        body, input, select, textarea, button {
            font-family: 'Times New Roman', Times, serif;
        }
        /* Only increase width of Add Category and Delete Category buttons */
        .post-editor input[type="submit"] {
    width:  70%;
}
.form-container {
    display: flex
;
    align-items: center;
    justify-content: center;
    flex-direction: row;
    min-height: 30vh;
    padding: 4% 0;
    position: relative;
}
    </style>
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>

        <section class="post-editor">
            <div class="heading">
                <h1>Add Product</h1>
                <img src="../image/separator-img.png">
            </div>

            <div class="form-container">
                <form action="" method="post" enctype="multipart/form-data" class="register">
                    <input type="hidden" name="product_id" value="<?php echo isset($_GET['edit']) ? $_GET['edit'] : ''; ?>">

                    <!-- Product Form -->
                    <div class="input-field">
                        <p>Product Name<span>*</span></p>
                        <input type="text" name="name" maxlength="100" placeholder="Add product name" required class="box">
                    </div>

                    <div class="input-field">
                        <p>Product Price<span>*</span></p>
                        <input type="number" name="price" maxlength="11" placeholder="Add product price" required class="box">
                    </div>

                    <div class="input-field">
                        <p>Stock Quantity<span>*</span></p>
                        <input type="number" name="stock" maxlength="11" placeholder="Enter stock quantity" required class="box">
                    </div>

                    <div class="input-field">
                        <p>Product Category<span>*</span></p>
                        <select name="category" id="category" required class="box">
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $cat) { ?>
                                <option value="<?= $cat ?>"><?= $cat ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="input-field">
                        <p>Product Description</p>
                        <textarea name="description" rows="4" placeholder="Enter product description" class="box"></textarea>
                    </div>

                    <div class="input-field">
                        <p>Upload Product Image<span>*</span></p>
                        <input type="file" name="image" accept="image/*" required class="box">
                    </div>

                    <div class="flex-btn">
                        <input type="submit" name="publish" value="Add Product" class="btn">
                        <input type="submit" name="draft" value="Save as Draft" class="btn">
                        <input type="reset" value="Cancel" class="btn">
                    </div>
                </form>
            </div>

            <!-- Manage Categories Form (inside the same section) -->
            <div class="heading">
                <h2>Manage Categories</h2>
                <img src="../image/separator-img.png">
            <div class="form-container">
                <!-- Add New Category Form -->
                <form action="" method="post" enctype="multipart/form-data" class="add-category-form">
                    <div class="input-field">
                        <p>Add New Category</p>
                        <input type="text" name="new_category" id="new_category" placeholder="Enter new category" class="box">
                    </div>
                    <input type="submit" name="add_category" value="Add Category" class="btn">
                </form>

                <!-- Category Deletion Form -->
                <form action="" method="post" class="delete-category-form">
                    <div class="input-field">
                        <p>Select Category to Delete</p>
                        <select name="category" id="category_to_delete" required class="box">
                            <option value="">Select category to delete</option>
                            <?php foreach ($categories as $category) { ?>
                                <option value="<?= $category ?>"><?= $category ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <input type="submit" name="delete_category" value="Delete Category" class="btn delete-btn">
                </form>
            </div>
        </section>
    </div>
</body>
</html>
