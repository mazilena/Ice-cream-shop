<?php 
    include '../components/connect.php' ;
    session_start();
    if (isset($_SESSION['seller_id'])) {
        $seller_id = $_SESSION['seller_id'];
    }
    else {
        $seller_id = '';
        header('location:login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Registered User Page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJX3Y8fQ8KJk6Af16lgjRfgKsdzjq4HpFwXQm21eYrrN5v6+dWGbV6rgc6GJ" crossorigin="anonymous">
    
    <!-- Custom Styling for Table -->
    <style>
        /* Custom Table Styling */
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            padding: 12px; /* Added padding for better spacing */
            font-size: 16px; /* Increased font size */
        }

        .table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        /* Custom pink color for table header */
        .table th {
            color: black;
            font-size: 25px; /* Slightly larger font for header */
        }
        .table td {
            font-size: 18px;
        }

        /* Stripe effect for odd rows */
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9; /* Lighter shade for odd rows */
        }

        /* Hover effect for rows */
        .table-hover tbody tr:hover {
            background-color: #e91e63;
        }

        /* Increase column width */
        .table th, .table td {
            width: 20%; /* Adjust width */
        }

        /* Image column width */
        .table td img {
            width: 60px; /* Slightly larger image size */
            height: 60px;
        }

        /* Add rounded corners to the table */
        .table {
            border-radius: 35px;
            overflow: hidden; /* To ensure the rounded corners look smooth */
            border: 1px solid #ddd; /* Light border around the table */
        }
    </style>
</head>
<body>

    <div class="main-container">
        <?php 
            include '../components/admin_header.php';
        ?>
        <section class="user-container">
            <div class="heading">
                <h1>Registered Users</h1>
                <img src="../image/separator-img.png">
            </div>
            
            <div class="container">
                <!-- User Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>User Email</th>
                                <th>Profile Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $select_users = $conn->prepare("SELECT * FROM users ");
                                $select_users->execute();

                                if ($select_users->rowCount() > 0) {
                                    while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
                                        $user_id = $fetch_users['id'];
                            ?>
                            <tr>
                                <td><?= $fetch_users['name']; ?></td>
                                <td><?= $fetch_users['email']; ?></td>
                                <td><img src="../uploaded_files/<?= $fetch_users['image']; ?>" alt="User Image"></td>
                            </tr>
                            <?php
                                    }
                                } else {
                                    echo '
                                        <tr>
                                            <td colspan="3" class="text-center">No users registered</td>
                                        </tr>
                                    ';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybN8TbKpxt6rmPp5Z+MmzzFvRZC9ffJ0l4PcuVrYIfQVp5bX5r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-cpHTybk6EhdI6xRS9Afm8mdhG8k8UQXHj9tt6ZFE6V3FNmAqzS1MbCCah9eEfwWe" crossorigin="anonymous"></script>

    <script src="../js/admin_script.js"></script>
    
    <?php 
        include '../components/alert.php';
    ?>
</body>
</html>
