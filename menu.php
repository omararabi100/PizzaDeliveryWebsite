<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza Shop Website Design</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <?php include "functions.php"; session_start();?>    
    <header class="header">

        <section class="flex">

            <a href="index.php" class="logo">Pizza Paradiso</a>

            <nav class="navbar">
                <a href="index.php">Home</a>
                <a href="index.php#about">About</a>
                <a href="#menu">Menu</a>
                <a href="orders.php">Order</a>
                <a href="index.php#faq">FAQs</a>
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
                                <input type="hidden" name="location" value="menu.php">
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
                            <input type="hidden" name="location" value="menu.php">
                            <input type="hidden" name="pizza-name" value="'.$item["name"].'">
                            <button type="submit" name="delete_item" class="fas fa-times delete-item"></button>
                        </form>
                        <img src="images/'.$item["img-name"].'" alt="">
                        <div class="content">
                            <p><span>($'.$item["price"].') </span>'.$item["name"].'</p>
                            <form action="updateQuantity.php" method="post">
                                <input type="hidden" name="location" value="menu.php">
                                <input type="hidden" name="pizza-name" value="'.$item["name"].'">
                                <input type="number" class="qty" name="qty" min="1" value='.$item["qty"].' max="100">
                                <button type="submit" class="fas fa-edit" name="update_qty"></button>
                            </form>
                        </div>
                        </div>';    
                    }
                    if (count( $_SESSION['cart']) > 0 ){
                        echo '<a href="orders.php" class="btn">Order Now</a>';
                    } else {
                        echo '<a href="menu.php" class="btn">Empty Cart. Buy Now</a>';
                    }
                    
                }
            ?>
        </div>

        </section>

    </div>

    <!-- MENU SECTION -->
    <section id="menu" class="menu">

        <h1 class="heading">our menu</h1>

        <div class="box-container">

            <div class="box">
                <div class="price">$<span>2</span>/-</div>
                <img src="images/pepperoni.png" alt="">
                <div class="name">Pepperoni Pizza</div>
                <form action="addToCart.php" method="post">
                    <input type="hidden" name="pizza-name" value="Pepperoni Pizza">
                    <input type="hidden" name="price" value="2">
                    <input type="hidden" name="img-name"  value="pepperoni.png">
                    <input type="number" min="1" max="100" value="1" class="qty" name="qty">
                    <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                </form>
            </div>

            <div class="box">
                <div class="price">$<span>4</span>/-</div>
                <img src="images/4-cheese-mushroom.jpg" alt="">
                <div class="name">4 Cheese Mushroom Pizza</div>
                <form action="addToCart.php" method="post">
                    <input type="hidden" name="pizza-name" value="4 Cheese Mushroom Pizza">
                    <input type="hidden" name="price" value="4">
                    <input type="hidden" name="img-name"  value="4-cheese-mushroom.jpg">
                    <input type="number" min="1" max="100" value="1" class="qty" name="qty">
                    <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                </form>
            </div>

            <div class="box">
                <div class="price">$<span>2</span>/-</div>
                <img src="images/cheddar-cheese.jpg" alt="">
                <div class="name">Cheddar Cheese Pizza</div>
                <form action="addToCart.php" method="post">
                    <input type="hidden" name="pizza-name" value="Cheddar Cheese Pizza">
                    <input type="hidden" name="price" value="2">
                    <input type="hidden" name="img-name"  value="cheddar-cheese.jpg">
                    <input type="number" min="1" max="100" value="1" class="qty" name="qty">
                    <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                </form>
            </div>

            <div class="box">
                <div class="price">$<span>3</span>/-</div>
                <img src="images/mascarpone-mushroom.png" alt="">
                <div class="name">Mascarpone and Mushroom</div>
                <form action="addToCart.php" method="post">
                    <input type="hidden" name="pizza-name" value="Mascarpone and Mushroom">
                    <input type="hidden" name="price" value="3">
                    <input type="hidden" name="img-name"  value="mascarpone-mushroom.png">
                    <input type="number" min="1" max="100" value="1" class="qty" name="qty">
                    <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                </form>
            </div>

            <div class="box">
                <div class="price">$<span>2</span>/-</div>
                <img src="images/mushroom.png" alt="">
                <div class="name">Mushroom Pizza</div>
                <form action="addToCart.php" method="post">
                    <input type="hidden" name="pizza-name" value="Mushroom Pizza">
                    <input type="hidden" name="price" value="2">
                    <input type="hidden" name="img-name"  value="mushroom.png">
                    <input type="number" min="1" max="100" value="1" class="qty" name="qty">
                    <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                </form>
            </div>

            <div class="box">
                <div class="price">$<span>4</span>/-</div>
                <img src="images/pepperoni-with-extra-cheese.jpg" alt="">
                <div class="name">Pepperoni with Extra Cheese</div>
                <form action="addToCart.php" method="post">
                    <input type="hidden" name="pizza-name" value="Pepperoni with Extra Cheese">
                    <input type="hidden" name="price" value="4">
                    <input type="hidden" name="img-name"  value="pepperoni-with-extra-cheese.jpg">
                    <input type="number" min="1" max="100" value="1" class="qty" name="qty">
                    <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                </form>
            </div>

            <div class="box">
                <div class="price">$<span>2</span>/-</div>
                <img src="images/haloum.jpg" alt="">
                <div class="name">Haloum Pizza</div>
                <form action="addToCart.php" method="post">
                    <input type="hidden" name="pizza-name" value="Haloum Pizza">
                    <input type="hidden" name="price" value="2">
                    <input type="hidden" name="img-name"  value="haloum.jpg">
                    <input type="number" min="1" max="100" value="1" class="qty" name="qty">
                    <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                </form>
            </div>

            <div class="box">
                <div class="price">$<span>3</span>/-</div>
                <img src="images/mushroom-olives.jpg" alt="">
                <div class="name">Mushroom and Olives</div>
                <form action="addToCart.php" method="post">
                    <input type="hidden" name="pizza-name" value="Mushroom and Olives">
                    <input type="hidden" name="price" value="3">
                    <input type="hidden" name="img-name"  value="mushroom-olives.jpg">
                    <input type="number" min="1" max="100" value="1" class="qty" name="qty">
                    <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                </form>
            </div>

            <div class="box">
                <div class="price">$<span>4</span>/-</div>
                <img src="images/tomato-cheese.jpg" alt="">
                <div class="name">Tomato Cheese Pizza</div>
                <form action="addToCart.php" method="post">
                    <input type="hidden" name="pizza-name" value="Tomato Cheese Pizza">
                    <input type="hidden" name="price" value="4">
                    <input type="hidden" name="img-name"  value="tomato-cheese.jpg">
                    <input type="number" min="1" max="100" value="1" class="qty" name="qty">
                    <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                </form>
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

</body>
</html>