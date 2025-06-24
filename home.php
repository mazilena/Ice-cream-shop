<?php
    include 'components/connect.php' ;
    session_start();
    if (isset($_SESSION['id'])) {
        $user_id = $_SESSION['id'];
    }
    else {
        $id = '';
        
    }
?>
<?php 
        include 'components/user_header.php';
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - home page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

    
    <!-- slider  section start -->
     <div class="slider-container">
        <div class="slider">
            <div class="slideBox active">
                <div class="textBox">
                    <h1>we pride ourselfs on <br> exceptional flavors</h1>
                    <a href="menu.php" class="btn">shop now</a>
                </div>
                <div class="imgBox">
                    <img src="image/slider.jpg">
                </div>
            </div>
            <div class="slideBox">
                <div class="textBox">
                    <h1>cold treats are my kind  <br> of comfort food</h1>
                    <a href="menu.php" class="btn">shop now</a>
                </div>
                <div class="imgBox">
                    <img src="image/slider0.jpg">
                </div>
            </div>
        </div>
        <ul class="controls">
            <li onclick="nextSlider();" class="next"><i class="bx bx-right-arrow-alt"></i> </li>
            <li onclick="prevSlider();" class="prev"><i class="bx bx-left-arrow-alt"></i> </li>
        </ul>
     </div>

     <!-- slider section end -->
      <div class="service">
        <div class="box-container">
            <!-- service item box -->
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="image/services.png" class="img1">
                        <img src="image/services (1).png" class="img2">
                    </div>
                </div>
                <div class="detail">
                    <h4>delivery</h4>
                    <span>100% secure</span>
                </div>
            </div>
            <!-- service item box -->
             <!-- service item box -->
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="image/services (2).png" class="img1">
                        <img src="image/services (3).png" class="img2">
                    </div>
                </div>
                <div class="detail">
                    <h4>payment</h4>
                    <span>100% secure</span>
                </div>
            </div>
            <!-- service item box -->
             <!-- service item box -->
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="image/services (5).png" class="img1">
                        <img src="image/services (6).png" class="img2">
                    </div>
                </div>
                <div class="detail">
                    <h4>support</h4>
                    <span>24*7 hours</span>
                </div>
            </div>
            <!-- service item box -->
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="image/services (7).png" class="img1">
                        <img src="image/services (8).png" class="img2">
                    </div>
                </div>
                <div class="detail">
                    <h4>gift services</h4>
                    <span>support gift services</span>
                </div>
            </div>
            <!-- service item box -->
            <!-- service item box -->
             <!-- service item box -->
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="image/service.png" class="img1">
                        <img src="image/service (1).png" class="img2">
                    </div>
                </div>
                <div class="detail">
                    <h4>returns</h4>
                    <span>24*7 free return</span>
                </div>
            </div>
            <!-- service item box -->
             <!-- service item box -->
            <div class="box">
                <div class="icon">
                    <div class="icon-box">
                        <img src="image/services.png" class="img1">
                        <img src="image/services (1).png" class="img2">
                    </div>
                </div>
                <div class="detail">
                    <h4>deliver</h4>
                    <span>100% secure</span>
                </div>
            </div>
            <!-- service item box -->
        </div>
      </div>
      <!-- service section end -->
       <div class="categories">
            <div class="heading">
                <h1>categories features</h1>
                <img src="image/separator-img.png">
            </div>
            <div class="box-container">
                <div class="box">
                    <img src="image/categories.jpg">
                    <a href="menu.php" class="btn">coconuts</a>
                </div>
                <div class="box">
                    <img src="image/categories0.jpg">
                    <a href="menu.php" class="btn">chocolate</a>
                </div>
                <div class="box">
                    <img src="image/categories2.jpg">
                    <a href="menu.php" class="btn">strawberry</a>
                </div>
                <div class="box">
                    <img src="image/categories1.jpg">
                    <a href="menu.php" class="btn">corn</a>
                </div>
            </div>
       </div>
       <!--  categories section end  -->
        <img src="image/menu-banner.jpg" class="menu-banner">
        <div class="taste">
            <div class="heading">
                <span>Taste</span>
                <h1>buy any ice cream @ get one free</h1>
                <img src="image/separator-img.png">
            </div>
            <div class="box-container">
                <div class="box">
                    <img src="image/taste.webp">
                    <div class="detail">
                        <h2>Natural sweetness</h2>
                        <h1>Vanilla</h1>
                    </div>
                </div>
                <div class="box">
                    <img src="image/taste0.webp">
                    <div class="detail">
                        <h2>Natural sweetness</h2>
                        <h1>Matcha</h1>
                    </div>
                </div>
                <div class="box">
                    <img src="image/taste1.webp">
                    <div class="detail">
                        <h2>Natural sweetness</h2>
                         <h1>Blueberry</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- taste section end -->
          <div class="ice-container">
            <div class="overlay"></div>
            <div class="detail">
                <h1>Ice cream is cheaper than<br> threapy for stress.</h1>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit.<br> Dolores neque cupiditate amet, quos repellendus voluptates cumque vitae minus quia.<br> Dolor ipsum ex iste dicta debitis nulla ea libero quasi eveniet.</p>
                <a href="menu.php" class="btn">Shop now</a>
            </div>
          </div>
          <!-- container section end -->
           <div class="taste2">
                <div class="t-banner">
                    <div class="overlay"></div>
                    <div class="detail">
                        <h1>Find your taste of desserts.</h1>
                        <p>Treat them to a delicious treat and send them some luck o the Irish too!</p>
                        <a href="menu.php" class="btn">Shop now</a>
                    </div>
                </div>
                <div class="box-container">
                    <div class="box">
                        <div class="box-overlay"></div>
                        <img src="image/type4.jpg">
                        <div class="box-detail fadeIn-bottom">
                            <h1>Strawberry</h1>
                            <p>Find your taste of desserts</p>
                            <a href="menu.php" class="btn">Explore more</a>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-overlay"></div>
                        <img src="image/type.avif">
                        <div class="box-detail fadeIn-bottom">
                            <h1>Strawberry</h1>
                            <p>Find your taste of desserts</p>
                            <a href="menu.php" class="btn">Explore more</a>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-overlay"></div>
                        <img src="image/type1.png">
                        <div class="box-detail fadeIn-bottom">
                            <h1>Strawberry</h1>
                            <p>Find your taste of desserts</p>
                            <a href="menu.php" class="btn">Explore more</a>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-overlay"></div>
                        <img src="image/type2.png">
                        <div class="box-detail fadeIn-bottom">
                            <h1>Strawberry</h1>
                            <p>Find your taste of desserts</p>
                            <a href="menu.php" class="btn">Explore more</a>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-overlay"></div>
                        <img src="image/type0.avif">
                        <div class="box-detail fadeIn-bottom">
                            <h1>Strawberry</h1>
                            <p>Find your taste of desserts</p>
                            <a href="menu.php" class="btn">Explore more</a>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-overlay"></div>
                        <img src="image/type4.jpg">
                        <div class="box-detail fadeIn-bottom">
                            <h1>Strawberry</h1>
                            <p>Find your taste of desserts</p>
                            <a href="menu.php" class="btn">Explore more</a>
                        </div>
                    </div>
                </div>
           </div>
           <!-- taste2 section end -->
            <div class="flavor">
                <div class="box-container">
                    <img src="image/left-banner2.webp">
                    <div class="detail">
                        <h1>Hot Deals ! Sale Up To <span>20% off</span></h1>
                        <p>expires</p>
                        <a href="menu.php" class="btn">Shop now</a>

                    </div>
                </div>
            </div>
            <!-- flavor section end -->
            <div class="usage">
                <div class="heading">
                    <h1>how it works</h1>
                    <img src="image/separator-img.png">
                </div>
                <div class="row">
                    <div class="box-container">
                        <div class="box">
                            <img src="image/icon.avif">
                            <div class="detail">
                                <h3>scoop ice-cream</h3>
                                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Minima itaque nam, distinctio, voluptate tempora eum minus optio molestiae perspiciatis harum saepe reiciendis quasi rem iusto modi perferendis. Dicta, voluptatum eum.</p>
                            </div>
                        </div>
                        <div class="box">
                            <img src="image/icon0.avif">
                            <div class="detail">
                                <h3>scoop ice-cream</h3>
                                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Minima itaque nam, distinctio, voluptate tempora eum minus optio molestiae perspiciatis harum saepe reiciendis quasi rem iusto modi perferendis. Dicta, voluptatum eum.</p>
                            </div>
                        </div>
                        <div class="box">
                            <img src="image/icon1.avif">
                            <div class="detail">
                                <h3>scoop ice-cream</h3>
                                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Minima itaque nam, distinctio, voluptate tempora eum minus optio molestiae perspiciatis harum saepe reiciendis quasi rem iusto modi perferendis. Dicta, voluptatum eum.</p>
                            </div>
                        </div>
                    </div>
                    <img src="image/sub-banner.png" class="divider">
                    <div class="box-container">
                        <div class="box">
                            <img src="image/icon2.avif">
                            <div class="detail">
                                <h3>scoop ice-cream</h3>
                                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Minima itaque nam, distinctio, voluptate tempora eum minus optio molestiae perspiciatis harum saepe reiciendis quasi rem iusto modi perferendis. Dicta, voluptatum eum.</p>
                            </div>
                        </div>
                        <div class="box">
                            <img src="image/icon3.avif">
                            <div class="detail">
                                <h3>scoop ice-cream</h3>
                                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Minima itaque nam, distinctio, voluptate tempora eum minus optio molestiae perspiciatis harum saepe reiciendis quasi rem iusto modi perferendis. Dicta, voluptatum eum.</p>
                            </div>
                        </div>
                        <div class="box">
                            <img src="image/icon4.avif">
                            <div class="detail">
                                <h3>scoop ice-cream</h3>
                                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Minima itaque nam, distinctio, voluptate tempora eum minus optio molestiae perspiciatis harum saepe reiciendis quasi rem iusto modi perferendis. Dicta, voluptatum eum.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- usage section end -->
            <div calss="pride">
                <div calss="detail">
                    <h1>We Pride Ourselves On<br> Exceptional Flavours.</h1>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit.<br> At tempora harum numquam. Veniam non distinctio aut sint ad explicabo sunt?<p>
                    <a href="menu.php" class="btn">Shop now</a>
                </div>
            </div>
            <!-- pride section end -->
             <?php include 'components/footer.php';?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script src="js/user_script.js"></script>

    <?php 
        include 'components/alert.php';
    ?>
</body>
</html>