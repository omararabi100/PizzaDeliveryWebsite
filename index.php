<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza Shop Website Design</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <?php include "functions.php"; session_start(); ?>
    <!-- header section  -->

    <header class="header">

        <section class="flex">

            <a href="#home" class="logo">Pizza Paradiso</a>

            <nav class="navbar">
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="menu.php">Menu</a>
                <a href="orders.php">Order</a>
                <a href="#faq">FAQs</a>
            </nav>
            <div class="flex">

                <?php
                    if(isset($_COOKIE["username"])) {
                        echo '<div class="icons" id="username-btn"><div><span>'.$_COOKIE["username"].'</span></div></div>';
                    }
                    else {
                        echo '<div class="icons" id="username-btn"></div>';
                    }
                ?>
                
                <div class="icons">
                    <div id="menu-btn" class="fas fa-bars"></div>
                    <div id="user-btn" class="fas fa-user"></div>
                    <div id="order-btn" class="fas fa-box"></div>
                    <div id="cart-btn" class="fas fa-shopping-cart"><span><?php echo "(".getCartSize($_SESSION["cart"]).")"?></span></div>
                </div>
            </div>

        </section>

    </header>

    <?php
        if(isset($_GET["err"]) && $_GET["err"] == "emailTaken") {
            echo '<div class="user-account active">';
        } else if (isset($_GET["err"]) && $_GET["err"] == "confirmPass") {
            echo '<div class="user-account active">';
        } else if (isset($_GET["err"]) && $_GET["err"] == "loginErr") {
            echo '<div class="user-account active">';
        } else if (isset($_GET["err"]) && $_GET["err"] == "notLoggedIn") {
            echo '<div class="user-account active">';
        }
        else echo '<div class="user-account">'
    ?>
    
        <section>

            <div id="close-account"><span>&#215;</span></div>
            
            <?php
                if (isset($_GET["err"]) && $_GET["err"] == "notLoggedIn") {
                    echo '<div class="user"><p><span>You are not logged in to do the following transaction. Please log in to continue with your purchase.</span></p></div>';
                }
                if (isset($_COOKIE["username"])){
                    echo "<h2>Welcome back, ". $_COOKIE['username'] . "</h2>";
                    echo "<h3>Wallet: $". getUserWallet($_COOKIE["uid"]) ."</h3>";
                    if (isset($_SESSION['cart'])) {
                        if (count($_SESSION['cart']) > 0) {
                            echo '<div class="display-orders">';
                                foreach ($_SESSION['cart'] as $item) {
                                    echo '<p>'.$item["name"].' <span>( $'.$item["price"].'/- x '.$item["qty"].' )</span></p>';
                                }
                            echo '</div>';
                        }
                    }
                    echo '
                        <div class="flex">
                            <form action="addMoney.php" method="post">
                                <h3>Manage Wallet</h3>
                                <div class="inputBox">
                                    <h4>Card Type</h4>
                                    <select name="payment-method" class="box">
                                        <option value="credit card">Credit Card</option>
                                        <option value="paytm">Paytm</option>
                                        <option value="paypal">Paypal</option>
                                    </select>
                                </div>
                                <input type="password" name="card-type" required class="box" placeholder="Card Code" maxlength="50">
                                <input type="number" name="money-value" required class="box" placeholder="Enter Value"
                                    maxlength="20">
                                <input type="hidden" name="location" value="index.php">
                                <input type="submit" value="Add Funds" name="add" class="btn">
                            </form>
                        </div>
                        <div class="flex"><form action="Logout.php" method="post"><input type="submit" value="Log Out" name="log-out" class="btn logout-btn"></form></div>
                    ';
                    
                } else {
                    echo '
                        <div class="flex">

                            <form action="Login.php" method="post">
                                <h3>login now</h3>
                                <input type="email" name="email" required class="box" placeholder="Email" maxlength="50">
                                <input type="password" name="pass" required class="box" placeholder="Password"
                                    maxlength="20">
                                <input type="submit" value="login now" name="login" class="btn">';
                                if(isset($_GET["err"]) && $_GET["err"] == "loginErr") {
                                    echo '<div class="user"><p><span>Invalid Email or Password</span></p></div>';
                                }
                            echo '</form>
            
                            <form action="Register.php" method="post">
                                <h3>register now</h3>
                                <input type="text" name="name" required class="box" placeholder="Username" maxlength="20">
                                <input type="email" name="email" required class="box" placeholder="Email" maxlength="50">
                                <input type="password" name="pass" required class="box" placeholder="Password"
                                    maxlength="20">
                                <input type="password" name="cpass" required class="box" placeholder="Confirm Password"
                                    maxlength="20">
                                <input type="submit" value="register now" name="register" class="btn">';
                                if(isset($_GET["err"]) && $_GET["err"] == "emailTaken") {
                                    echo '<div class="user"><p><span>Email Already Exists</span></p></div>';
                                } else if (isset($_GET["err"]) && $_GET["err"] == "confirmPass") {
                                    echo '<div class="user"><p><span>Passwords Dont Match</span></p></div>';
                                }
                            echo'</form>
            
                        </div>
                    ';
                }
            ?>

        </section>

    </div>

    <div class="my-orders">

        <section>

            <div id="close-orders"><span>&#215;</span></div>

            <?php
                include("config.php");
                if (isset($_COOKIE["uid"]) && $_COOKIE["uid"] != NULL) {

                    $query = "SELECT * FROM orders WHERE userID ='$_COOKIE[uid]'";
                    $result = mysqli_query($conn, $query);
                    $rowcount = mysqli_num_rows($result);
                    if ($rowcount == 0) {
                        echo '<a href="orders.php" class="btn">No Orders. Order Now</a>';
                    } else {
                        $username = $_COOKIE["username"];
                        echo '<h3 class="title">My Orders</h3>';
                        while ($row = mysqli_fetch_array ($result)) {
                            echo '
                                <div class="box">
                                    <p>placed on : <span>'.$row["date"].'</span> </p>
                                    <p>name : <span>'.$username.'</span> </p>
                                    <p>number : <span>'.$row["number"].'</span></p>
                                    <p>address : <span>'.$row["address"].'</span></p>
                                    <p>payment method : <span>'.$row["paymentMethod"].'</span></p>
                                    <p>your orders : <span>'.$row["orderItems"].'</span></p>
                                    <p>total price : <span>$'.$row["price"].'/-</span></p>
                                    <p>payment status : <span style="color: var(--red);">pending</span> </p>
                                </div>
                            ';
                        }
                    }
                }
                else {
                    echo '<a href="orders.php" class="btn">No Orders. Order Now</a>';
                }
            ?>

        </section>

    </div>

    <?php
        if (isset($_GET["success"]) && $_GET["success"] == 1) {
                echo '<div class="shopping-cart active">';
            }
        else echo '<div class="shopping-cart">'
    ?>

        <section>

        <div id="close-cart"><span>&#215;</span></div>

        <div class="cart-container">
            <?php
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        echo '<div class="box">
                        <form action="deleteItem.php" method="post">
                            <input type="hidden" name="location" value="index.php">
                            <input type="hidden" name="pizza-name" value="'.$item["name"].'">
                            <button type="submit" name="delete_item" class="fas fa-times delete-item"></button>
                        </form>
                        <img src="images/'.$item["img-name"].'" alt="">
                        <div class="content">
                            <p><span>($'.$item["price"].') </span>'.$item["name"].'</p>
                            <form action="updateQuantity.php" method="post">
                                <input type="hidden" name="location" value="index.php">
                                <input type="hidden" name="pizza-name" value="'.$item["name"].'">
                                <input type="number" class="qty" name="qty" min="1" value='.$item["qty"].' max="100">
                                <button type="submit" class="fas fa-edit" name="update_qty"></button>
                            </form>
                        </div>
                        </div>';    
                    }
                    if (count( $_SESSION['cart']) > 0 && isset($_SESSION['cart'])){
                        echo '<a href="orders.php" class="btn">Order Now</a>';
                    } else {
                        echo '<a href="menu.php" class="btn">Empty Cart. Buy Now</a>';
                    }
                    
                }
            ?>
        </div>

        </section>

    </div>

    <div class="home-bg">

        <section class="home" id="home">

            <div class="slide-container">

                <div class="slide active">
                    <div class="image">
                        <img src="images/pepperoni.png" alt="">
                    </div>
                    <div class="content">
                        <h3>homemade Pepperoni Pizza</h3>
                        <div class="fas fa-angle-left" onclick="prev()"></div>
                        <div class="fas fa-angle-right" onclick="next()"></div>
                    </div>
                </div>

                <div class="slide">
                    <div class="image">
                        <img src="images/mushroom.png" alt="">
                    </div>
                    <div class="content">
                        <h3>Pizza With Mushrooms</h3>
                        <div class="fas fa-angle-left" onclick="prev()"></div>
                        <div class="fas fa-angle-right" onclick="next()"></div>
                    </div>
                </div>

                <div class="slide">
                    <div class="image">
                        <img src="images/mascarpone-mushroom.png" alt="">
                    </div>
                    <div class="content">
                        <h3>Mascarpone And Mushrooms</h3>
                        <div class="fas fa-angle-left" onclick="prev()"></div>
                        <div class="fas fa-angle-right" onclick="next()"></div>
                    </div>
                </div>

            </div>

        </section>

    </div>

    <!-- about section   -->

    <section class="about" id="about">

        <h1 class="heading">About Us</h1>

        <div class="box-container">

            <div class="box">
                <img src="images/about-1.svg" alt="">
                <h3>Made with Love</h3>
                <p>Indulge in our pizzas crafted with passion and care. Each ingredient is selected with love, ensuring every bite is a delight. Taste the difference that dedication makes. It's pizza made with love, just for you dear customer.</p>
            </div>

            <div class="box">
                <img src="images/about-2.svg" alt="">
                <h3>30 Minutes Delivery</h3>
                <p>Experience the speed of our delivery service. We understand that hunger waits for no one, so we guarantee your pizza will be at your doorstep in 30 minutes or less. Quick, convenient, and delicious – that's our promise to you.</p>
            </div>

            <div class="box">
                <img src="images/about-3.svg" alt="">
                <h3>Share with Freinds</h3>
                <p>Spread the joy of pizza with your friends and loved ones. Our pizzas are perfect for sharing moments of laughter and bonding. Whether it's a casual get-together or a big celebration, our pizzas bring people together. Share the happiness today!</p>
            </div>

        </div>

    </section>

    

    

    <!-- faq section   -->

    <section class="faq" id="faq">

        <h1 class="heading">FAQs: Frequently Asked Questions</h1>

        <div class="accordion-container">

            <div class="accordion">
                <div class="accordion-heading">
                    <span>How does it work?</span>
                    <i class="fas fa-angle-down"></i>
                </div>
                <p class="accrodion-content">
                    Our ordering process is simple and convenient. You can easily navigate through our menu, select your favorite pizza, customize it according to your preferences, and proceed to checkout. Once your order is confirmed, our team will start preparing your pizza and deliver it to your doorstep in no time.
                </p>
            </div>

            <div class="accordion">
                <div class="accordion-heading">
                    <span>How long does it take for delivery?</span>
                    <i class="fas fa-angle-down"></i>
                </div>
                <p class="accrodion-content">
                    Our delivery time varies depending on your location and the current order volume. However, we strive to deliver your pizza as quickly as possible. On average, you can expect your order to arrive within 30 minutes or less. Rest assured, we prioritize timely delivery to ensure your satisfaction.
                </p>
            </div>

            <div class="accordion">
                <div class="accordion-heading">
                    <span>Can I order for huge parties?</span>
                    <i class="fas fa-angle-down"></i>
                </div>
                <p class="accrodion-content">
                    Absolutely! We offer party-size pizzas that are perfect for feeding a large group of people. Whether you're hosting a birthday party, office gathering, or any other event, our party-size pizzas will satisfy everyone's cravings. Simply place your order in advance, and we'll ensure that your party is a success.
                </p>
            </div>

            <div class="accordion">
                <div class="accordion-heading">
                    <span>How much protein it contains?</span>
                    <i class="fas fa-angle-down"></i>
                </div>
                <p class="accrodion-content">
                    Our pizzas are crafted using high-quality ingredients, including protein-rich toppings such as meats and cheeses. While the exact protein content may vary depending on your choice of toppings, you can rest assured that our pizzas provide a delicious and satisfying source of protein to fuel your body.
                </p>
            </div>


            <div class="accordion">
                <div class="accordion-heading">
                    <span>Is it cooked with oil?</span>
                    <i class="fas fa-angle-down"></i>
                </div>
                <p class="accrodion-content">
                    We understand the importance of healthy cooking methods, which is why we minimize the use of oil in our cooking process. Our pizzas are prepared using techniques that preserve their authentic flavors without relying heavily on oil. You can enjoy our pizzas guilt-free, knowing that they are cooked with your health in mind.
                </p>
            </div>

        </div>

    </section>


    <!-- footer section   -->

    <section class="footer">

        <div class="box-container">

            <div class="box">
                <i class="fas fa-phone"></i>
                <h3>Phone Number</h3>
                <p>+123-456-7890</p>
                <p>+111-222-3333</p>
            </div>

            <div class="box">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Our Address</h3>
                <p>Lebanon, Beirut - Hamra St.</p>
                <p>Lebanon, Mt Lebanon - Aramoun</p>
            </div>

            <div class="box">
                <i class="fas fa-clock"></i>
                <h3>Opening Hours</h3>
                <p>10:00 AM to 2:00 AM</p>
                <p>&#12288;</p>
            </div>

            <div class="box">
                <i class="fas fa-envelope"></i>
                <h3>Email Address</h3>
                <p>contact@pizzaria.com</p>
                <p>customerservice@pizzaria.com</p>
            </div>

        </div>
    </section>
    <script src="js/script.js"></script>
    <script>
        function next() {
        slides[index].classList.remove('active');
        index = (index + 1) % slides.length;
        slides[index].classList.add('active');
    }
    
    function prev() {
        slides[index].classList.remove('active');
        index = (index - 1 + slides.length) % slides.length;
        slides[index].classList.add('active');
    }
    </script>

</body>

</html>