<?php
    include 'components/connect.php' ;
    session_start();
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }
    else {
        $user_id = '';
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - about us page</title>
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
            <h1>About Us</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum ea perferendis odio beatae reiciendis<br> culpa laudantium, dicta neque eveniet harum voluptates corporis porro placeat ex?</p>
            <span><A href="home.php">Home</a><i class= "bx bx-right-arrow-alt"></i>About us<span>
        </div>
    </div>
    <div class="chef">
        <div class="box-container">
            <div class="box">
                <div class="heading">
                    <span>Alex Doe</span>
                    <h1>Masterchef</h1>
                    <img src="image/separator-img.png">
                </div>
                <p>Maria is a Roman-born pastry chef who spent 15 year in his city Rome perfectly his craft and exceptioal creation Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eum distinctio sint maxime quis velit modi veniam, tempora temporibus? Amet beatae iste nam veniam nihil itaque ipsa eius similique laborum doloribus.</p>
                <div class="flex-btn">
                    <a href="" class="btn"> Explore our menu</a>
                    <a href="menu.php" class="btn"> Visit our shop</a>
                </div>
            </div>
            <div class="box">
                <img src="image/ceaf.png" class="img">
            </div>
        </div>
    </div>

    <!-- chef  section start -->
     <div class="story">
        <div class="heading">
            <h1>Our story</h1>
            <img src="image/separator-img.png">
        </div>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Accusamus ullam voluptatem natus aut<br>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aspernatur voluptates soluta voluptatum iste ex maxime,<br> ipsum totam iusto laboriosam officiis? dolore itaque assumenda deserunt quo laudantium unde est<br>, possimus aperiam? At magnam ipsa mollitia unde consequuntur officiis?</p>
        <a href="menu.php" class="btn">Our services</a>
     </div>
     <div class="container">
        <div class="box-container">
            <div class="img-box">
                <img src="image/about.png" >
            </div>
            <div class="box">
                <div class="heading">
                    <h1>Taking Ice cream To new Height</h1>
                    <img src="image/separator-img.png">
                </div>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ex vero vel dicta tempore quas! Repellendus laudantium numquam perspiciatis omnis soluta nostrum repudiandae eligendi iusto ex? Vel enim, dolorem quibusdam quisquam optio accusantium, cum distinctio autem eum consequatur, quis suscipit ex! Expedita hic aperiam cum illo!</p>
                <a href="" class="btn">Learn More</a>
            </div>
        </div>
     </div>

     <!-- story end section -->
    <div class="team">
        <div class="heading">
            <span>Our team</span>
            <h1>Quality & passion with our services</h1>
            <img src="image/separator-img.png">
        </div>
        <div class="box-container">
            <div class="box">
                <img src="image/team-1.jpg" class="img">
                <div class="content">
                    <img src="image/shape-19.png" alt="" class="shap">
                    <h2>Raphal Johnson</h2>
                    <p>Coffee Chef</p>
                </div>
            </div>
            <div class="box">
                <img src="image/team-2.jpg" class="img">
                <div class="content">
                    <img src="image/shape-19.png" alt="" class="shap">
                    <h2>Rupali Johnson</h2>
                    <p>pastry Chef</p>
                </div>
            </div>
            <div class="box">
                <img src="image/team-3.jpg" class="img">
                <div class="content">
                    <img src="image/shape-19.png" alt="" class="shap">
                    <h2>Rohan Mittal</h2>
                    <p>baked Chef</p>
                </div>
            </div>
        </div>
    </div>

    <!-- team section end -->
    <div class="standers">
        <div class="detail">
            <div class="heading">
                <h1>Our Standers</h1>
                <img src="image/separator-img.png">
            </div>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, nesciunt.</p>
            <i class="bx bxs-heart"></i>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, nesciunt.</p>
            <i class="bx bxs-heart"></i>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, nesciunt.</p>
            <i class="bx bxs-heart"></i>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, nesciunt.</p>
            <i class="bx bxs-heart"></i>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, nesciunt.</p>
            <i class="bx bxs-heart"></i>
        </div>
    </div>
    <!-- stand section end -->
    <div class="testimonial">
        <div class="heading">
            <h1>testimonial</h1>
            <img src="image/separator-img.png">
        </div>
        <div class="testimonal-container">
            <div class="slide-row" id="slide">
                <div class="slide-col">
                    <div class="user-text">
                        <p>Zen Lorem ipsum dolor, sit amet consectetur adipisicing Lorem ipsum dolor, sit amet consectetur adipisicing elit. Reprehenderit quos deleniti, culpa voluptates sequi veritatis a </p>
                        <h2>Zen</h2>
                        <p>Author</p>
                    </div>
                    <div class="user-img">
                        <img src="image/testimonial (1).jpg">
                    </div>
                </div>
                <div class="slide-col">
                    <div class="user-text">
                        <p>Zen Lorem ipsum dolor, sit amet consectetur adipisicing Lorem ipsum dolor, sit amet consectetur adipisicing elit. Reprehenderit quos deleniti, culpa voluptates sequi veritatis a </p>
                        <h2>Zen</h2>
                        <p>Author</p>
                    </div>
                    <div class="user-img">
                        <img src="image/testimonial (2).jpg">
                    </div>
                </div>
                <div class="slide-col">
                    <div class="user-text">
                        <p>Zen Lorem ipsum dolor, sit amet consectetur adipisicing Lorem ipsum dolor, sit amet consectetur adipisicing elit. Reprehenderit quos deleniti, culpa voluptates sequi veritatis a </p>
                        <h2>Zen</h2>
                        <p>Author</p>
                    </div>
                    <div class="user-img">
                        <img src="image/testimonial (3).jpg">
                    </div>
                </div>
                <div class="slide-col">
                    <div class="user-text">
                        <p>Zen Lorem ipsum dolor, sit amet consectetur adipisicing Lorem ipsum dolor, sit amet consectetur adipisicing elit. Reprehenderit quos deleniti, culpa voluptates sequi veritatis a </p>
                        <h2>Zen</h2>
                        <p>Author</p>
                    </div>
                    <div class="user-img">
                        <img src="image/testimonial (4).jpg">
                    </div>
                </div>
            </div>
        </div>
        <div class="indicator">
            <span class="btn1 active"></span>
            <span class="btn1"></span>
            <span class="btn1"></span>
            <span class="btn1"></span>
        </div>
    </div>



    <!-- testimonial section end -->
     <div class="mission">
        <div class="box-container">
            <div class="box">
                <div class="heading">
                    <h1>Our mission</h1>
                    <img src="image/separator-img.png">
                </div>
                <div class="detail">
                    <div class="img-box">
                        <img src="image/mission.webp">
                    </div>
                    <div>
                        <h2>Mexicons chocolate</h2>
                        <p>Layers of shaped marshmallow candies - bunnies, chicks, and simple flowers - make a memorable gift in a beribboned box</p>
                    </div>
                </div>
                <div class="detail">
                    <div class="img-box">
                        <img src="image/mission1.webp">
                    </div>
                    <div>
                        <h2>Vanilla with honey</h2>
                        <p>Layers of shaped marshmallow candies - bunnies, chicks, and simple flowers - make a memorable gift in a beribboned box</p>
                    </div>
                </div>
                <div class="detail">
                    <div class="img-box">
                        <img src="image/mission0.jpg">
                    </div>
                    <div>
                        <h2>pappermint chip</h2>
                        <p>Layers of shaped marshmallow candies - bunnies, chicks, and simple flowers - make a memorable gift in a beribboned box</p>
                    </div>
                </div>
                <div class="detail">
                    <div class="img-box">
                        <img src="image/mission2.webp">
                    </div>
                    <div>
                        <h2>Raspberry sorbaty</h2>
                        <p>Layers of shaped marshmallow candies - bunnies, chicks, and simple flowers - make a memorable gift in a beribboned box</p>
                    </div>
                </div>
            </div>
            <div class="box">
                <img src="image/form.png" alt="" class="img">
            </div> 
        </div>
     </div>
    <!-- mission section end -->
    <?php include 'components/footer.php';?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script src="js/user_script.js"></script>

    <?php 
        include 'components/alert.php';
    ?>
</body>
</html>