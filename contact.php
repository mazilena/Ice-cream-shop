<?php
    include 'components/connect.php' ;
    session_start();
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }
    else {
        $user_id = '';
        
    }


    //sending message
    if (isset($_POST['send_message'])) {
        if ($user_id != '') {
            
            $id = unique_id();
            $name = $_POST['name'];

            $email = $_POST['email'];

            $subject = $_POST['subject'];

            $message = $_POST['message'];

            $verify_message = $conn->prepare("SELECT * FROM message WHERE user_id = ? AND name = ? AND email = ? AND subject = ? AND message = ?");
            $verify_message->execute([$user_id, $name, $email, $subject, $message]);

            if ($verify_message->rowCount() > 0) {
                $warning_msg[] = 'message already exist';
            }else{
                $insert_mesaage = $conn->prepare("INSERT INTO message (id, user_id, name, email, subject, message) VALUES (?,?,?,?,?,?)");
                $insert_mesaage->execute([$id, $user_id, $name, $email, $subject, $message]);

                $success_msg[] = 'comment inserted successfully';
            }
        }else{
            $warning_msg[] = 'please login first';
        }
    }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - contact us page</title>
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
            <h1>Contact Us</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum ea perferendis odio beatae reiciendis<br> culpa laudantium, dicta neque eveniet harum voluptates corporis porro placeat ex?</p>
            <span><A href="home.php">Home</a><i class= "bx bx-right-arrow-alt"></i>Contact us<span>
        </div>
    </div>
    <div class="services">
        <div class="heading">
            <h1>Our Services</h1>
            <p>Just A Few Click to make the reservation Online for Saving Your Time And Money</p>
            <img src="image/separator-img.png">
        </div>
        <div class="box-container">
            <div class="box">
                <img src="image/0.png">
                <div>
                    <h1> Free shipping fast</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio, fuga?</p>
                </div>
            </div>
            <div class="box">
                <img src="image/1.png">
                <div>
                    <h1>money back & guarantee</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio, fuga?</p>
                </div>
            </div>
            <div class="box">
                <img src="image/2.png">
                <div>
                    <h1> Online support 24/7</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio, fuga?</p>
                </div>
            </div>
        </div>
    </div>
    <div class="form-container">
        <div class="heading">
            <h1>Drop us a line</h1>
            <p>Just A Few Click To Make The Reservation Online For Saving Your Time And Money.</p>
            <img src="image/separator-img.png">
        </div>
        <form action="" method="post" class="register">
            <div class="input-feild"> 
                <label>Name <sup>*</sup></label>
                <input type="text" name="name" required placeholder="enter your name " class="box">
            </div>
            <div class="input-feild"> 
                <label>Email <sup>*</sup></label>
                <input type="email" name="email" required placeholder="enter your email" class="box">
            </div>
            <div class="input-feild"> 
                <label>Subject <sup>*</sup></label>
                <input type="text" name="subject" required placeholder="reason..." class="box">
            </div>
            <div class="input-feild"> 
                <label>comment <sup>*</sup></label>
                <textarea name="message" cols="30" rows="10" required placeholder="" class="box"></textarea>
            </div>
            <button type="submit" name="send_message" class="btn">send message</button>
        </form>
    </div>
    <div class="address">
        <div class="heading">
            <h1>Our Contact Details</h1>
            <p>Just A Few Click To Make The Reservation Online For Saving Your Time And Money.</p>
            <img src="image/separator-img.png">
        </div>
        <div class="box-container">
            <div class="box">
                <i class="bx bxs-map-alt"></i>
                <div>
                    <h4>Address</h4>
                    <p>1093 Marigold, Coral Way <br> Miami, Florida, 33169</p>
                </div>
            </div>
            <div class="box">
                <i class="bx bxs-phone-incoming"></i>
                <div>
                    <h4>Phone No.</h4>
                    <p>3345665169</p>
                    <p>3345665169</p>
                </div>
            </div>
            <div class="box">
                <i class="bx bxs-envelope"></i>
                <div>
                    <h4>Email</h4>
                    <p>anushkakhic9@gmail.com</p>
                    <p>anushkakhic9@gmail.com</p>
                </div>
            </div>
        </div>
    </div>







    <?php include 'components/footer.php';?>
    <script src="js/user_script.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>