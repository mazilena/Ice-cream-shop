<?php
include '../components/connect.php';
session_start();

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ✅ Default where clause
$whereClause = " WHERE 1=1 ";
$params = [];

// ✅ Filters Handling
if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $whereClause .= " AND DATE(o.dates) BETWEEN :from_date AND :to_date";
    $params[':from_date'] = $_GET['from_date'];
    $params[':to_date'] = $_GET['to_date'];
}

if (!empty($_GET['month'])) {
    $whereClause .= " AND MONTH(o.dates) = :month";
    $params[':month'] = (int) $_GET['month'];
}

if (!empty($_GET['year'])) {
    $whereClause .= " AND YEAR(o.dates) = :year";
    $params[':year'] = (int) $_GET['year'];
}

if (!empty($_GET['category'])) {
    $whereClause .= " AND p.category = :category";
    $params[':category'] = $_GET['category'];
}

// ✅ Sorting Handling
$orderBy = " ORDER BY o.dates DESC"; // Default: Latest Orders

if (!empty($_GET['sort_by'])) {
    if ($_GET['sort_by'] == "highest_sold") {
        $orderBy = " ORDER BY total_sold DESC"; 
    } elseif ($_GET['sort_by'] == "lowest_sold") {
        $orderBy = " ORDER BY total_sold ASC"; 
    }
}

// ✅ Query Execution (Fixed JOIN & Grouping Issue)
$query = "SELECT 
            o.id AS order_id,
            u.name AS customer_name,
            (o.qty * CAST(o.price AS DECIMAL(10,2))) AS total_amount,
            o.dates AS order_date,
            p.name AS product_name,
            CAST(o.price AS DECIMAL(10,2)) AS price,
            p.category,
            p.image,
            SUM(o.qty) AS total_sold
          FROM orders o
          LEFT JOIN products p ON o.product_id = p.product_id
          LEFT JOIN users u ON o.user_id = u.id
          $whereClause
          GROUP BY o.id, u.name, total_amount, o.dates, 
                   p.name, o.price, p.category, p.image
          HAVING p.category IS NOT NULL AND p.category <> ''
                 AND p.name IS NOT NULL AND p.name <> ''
                 AND p.image IS NOT NULL AND p.image <> ''
          $orderBy";


$select_products = $conn->prepare($query);

// ✅ Bind Parameters
foreach ($params as $key => $value) {
    $select_products->bindValue($key, $value);
}

$select_products->execute();

// ✅ Fetch Data & Display
if ($select_products->rowCount() > 0) {
    echo '<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>Order Date</th>
            <th>Category</th>
            <th>Price</th>
            <th>Product</th>
            <th>Sold Qty</th>
            <th>Total Amount</th>
            <th>Image</th>
        </tr>
    </thead>
    <tbody>';

    while ($product = $select_products->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . date('d-m-Y', strtotime($product['order_date'])) . "</td>
                <td>{$product['product_name']}</td>
                <td>₹{$product['price']}</td>
                <td>{$product['category']}</td>
                <td>{$product['total_sold']}</td>
                <td>₹{$product['total_amount']}</td>
                <td>
                    <img src='../uploaded_files/{$product['image']}' 
                         width='60' height='60' 
                         onerror=\"this.src='../images/placeholder.png';\">
                </td>
            </tr>";
    }

    echo '</tbody></table>';

} else {
    echo "<p class='text-center text-danger'>No Sales Data Found</p>";
}
?>
