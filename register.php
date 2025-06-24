<?php
    include './components/connect.php';

    if (isset($_POST['submit'])) 
    {
        $id = unique_id();
        $name = $_POST['fullname'];
        $email = $_POST['email'];
        $pass = sha1($_POST['password']);
        $cpass = sha1($_POST['con_password']);

        // File upload handling
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = $_FILES['image']['name'];
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $rename = unique_id() . '.' . $ext;
            $image_size = $_FILES['image']['size'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_folder = './uploaded_files/' . $rename;

            if ($image_size > 2000000) {
                $warning_msg[] = 'Image size is too large';
            }
        } else {
            $rename = '';
        }

        // Check if the email already exists
        // Check if the email already exists in the users table
        $check_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $check_email->execute([$email]);

        if ($check_email->rowCount() > 0) {
            $warning_msg[] = 'Email already exists! Please use a different email.';
            } else {
            if ($pass != $cpass) {
                $warning_msg[] = 'Confirm password does not match';
            } else {
        // Insert data into database
                if ($rename && move_uploaded_file($image_tmp_name, $image_folder)) {
                    $insert_seller = $conn->prepare("INSERT INTO `users` (id, name, email, password, image) VALUES (?, ?, ?, ?, ?)");
                    $insert_seller->execute([$id, $name, $email, $pass, $rename]);
                    $success_msg[] = 'New seller registered! Please log in now';
                } elseif (!$rename) {
                    $insert_seller = $conn->prepare("INSERT INTO `users` (id, name, email, password) VALUES (?, ?, ?, ?)");
                    $insert_seller->execute([$id, $name, $email, $pass]);
                    $success_msg[] = 'New user registered! Please log in now';
                } else {
                    $warning_msg[] = 'Failed to upload image';
                }
            }
}

    }
?>

<!DOCTYPE html>
<html lang="en" ng-app="registerApp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- AngularJS Library -->
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>

    <style>
        .error {
            color: red;
            font-size: 0.9em;
        }

        input.ng-invalid.ng-touched {
            border-color: red;
        }

        input.ng-valid.ng-touched {
            border-color: green;
        }
    </style>
</head>
<body ng-controller="RegisterController"> <?php 
        include 'components/user_header.php';
    ?>
    <div class="container">
        <h1 class="text-center mt-5">Register Form</h1>

        <!-- Show success or error messages -->
        <?php
            if (isset($success_msg)) {
                foreach ($success_msg as $msg) {
                    echo "<div class='alert alert-success'>$msg</div>";
                }
            }
            if (isset($warning_msg)) {
                foreach ($warning_msg as $msg) {
                    echo "<div class='alert alert-danger'>$msg</div>";
                }
            }
        ?>

        <form class="reg" name="registerForm" ng-submit="submitForm(registerForm)" action="" method="POST" enctype="multipart/form-data" novalidate>
            <!-- Fullname -->
            <div class="mb-3">
                <label for="fullname" class="form-label">Fullname</label>
                <input type="text" class="form-control" id="fullname" name="fullname" ng-model="user.fullname" ng-required="true" ng-minlength="3" ng-maxlength="30" />
                <span class="error" ng-show="registerForm.fullname.$touched && registerForm.fullname.$invalid">
                    Fullname is required and must be between 3 to 30 characters.
                </span>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" ng-model="user.email" ng-required="true" ng-pattern="/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/" />
                <span class="error" ng-show="registerForm.email.$touched && registerForm.email.$invalid">
                    Enter a valid email address.
                </span>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" ng-model="user.password" ng-required="true" ng-minlength="6" />
                <span class="error" ng-show="registerForm.password.$touched && registerForm.password.$invalid">
                    Password is required and must be at least 6 characters long.
                </span>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="con_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="con_password" name="con_password" ng-model="user.con_password" ng-required="true" ng-minlength="6" />
                <span class="error" ng-show="registerForm.con_password.$touched && registerForm.con_password.$invalid">
                    Confirm Password is required and must be at least 6 characters long.
                </span>
                <span class="error" ng-show="registerForm.con_password.$touched && user.con_password !== user.password">
                    Passwords do not match.
                </span>
            </div>

            <!-- Checkbox -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1" name="terms" ng-model="user.terms" ng-required="true">
                <label class="form-check-label" for="exampleCheck1">I agree to the terms</label>
                <span class="error" ng-show="registerForm.terms.$touched && registerForm.terms.$invalid">
                    You must agree to the terms.
                </span>
            </div>

            <!-- Profile Upload -->
            <div class="input-field mb-3">
                <label for="image" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" />
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" name="submit" ng-disabled="registerForm.$invalid">Submit</button>
        </form>
    </div>

    <!-- AngularJS Script -->
    <script>
        var app = angular.module('registerApp', []);

        app.controller('RegisterController', ['$scope', function ($scope) {
            $scope.user = {};  // Store user input

            $scope.submitForm = function (form) {
                if (form.$valid && $scope.user.password === $scope.user.con_password) {
                    return true;  // The form will be submitted via PHP
                } else {
                    alert("Please fill out the form correctly.");
                }
            };
        }]);
    </script>  <?php include 'components/footer.php';?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script src="js/user_script.js"></script>

<?php 
    include 'components/alert.php';
?>
  
</body>
</html>
