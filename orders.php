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
    <?php include "functions.php"; session_start(); ?>

    <header class="header">

        <section class="flex">

            <a href="index.php" class="logo">Pizza Paradiso</a>

            <nav class="navbar">
                <a href="index.php#home">Home</a>
                <a href="index.php#about">About</a>
                <a href="menu.php">Menu</a>
                <a href="#order">Order</a>
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
        else echo '<div class="user-account">';
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
                                <input type="hidden" name="location" value="orders.php">
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
                            <input type="hidden" name="location" value="orders.php">
                            <input type="hidden" name="pizza-name" value="'.$item["name"].'">
                            <button type="submit" name="delete_item" class="fas fa-times delete-item"></button>
                        </form>
                        <img src="images/'.$item["img-name"].'" alt="">
                        <div class="content">
                            <p><span>($'.$item["price"].') </span>'.$item["name"].'</p>
                            <form action="updateQuantity.php" method="post">
                                <input type="hidden" name="location" value="orders.php">
                                <input type="hidden" name="pizza-name" value="'.$item["name"].'">
                                <input type="number" class="qty" name="qty" min="1" value='.$item["qty"].' max="100">
                                <button type="submit" class="fas fa-edit" name="update_qty"></button>
                            </form>
                        </div>
                        </div>';    
                    }
                    if (count( $_SESSION['cart']) > 0 ){
                        echo '<a href="#order" class="btn">Order Now</a>';
                    } else {
                        echo '<a href="menu.php" class="btn">Empty Cart. Buy Now</a>';
                    }
                    
                }
            ?>
        </div>

        </section>

    </div>


    <section class="order" id="order">

    <h1 class="heading">order now</h1>

    <form action="addOrder.php" method="post">
        
        <div class="flex">
            <div class="inputBox">
                <span>Wallet:</span>
                <?php
                    if(isset($_COOKIE["uid"])) {
                        $walletPlaceholder = "$".getUserWallet($_COOKIE["uid"]);
                        echo '<input type="text" name="number" class="box" required placeholder="'.$walletPlaceholder.'" readonly>';
                    }
                    else {
                        echo '<input type="text" name="number" class="box" required placeholder="$0" readonly>';
                    }
                ?>
            </div>
            <div class="inputBox">
                <span>Total Price:</span>
                <?php
                    if(isset($_SESSION["cart"])) {
                        $pricePlaceholder = "$".getTotalPrice($_SESSION["cart"]);
                        echo '<input type="text" name="number" class="box" required placeholder="'.$pricePlaceholder.'" readonly>';
                    }
                ?>
            </div>
        </div>

        <?php
            echo '<div class="display-orders">';
            if (isset($_SESSION['cart']) && count($_SESSION["cart"]) > 0) {
                foreach ($_SESSION['cart'] as $item) {
                    echo '<p>'.$item["name"].' <span>( $'.$item["price"].'/- x '.$item["qty"].' )</span></p>';
                }
            }
            else {
                echo '<a href="menu.php" class="btn">Empty Cart. Buy Now</a>';
            }
            echo '</div>';
        ?>

        <div class="flex">
            <div class="inputBox">
                <span>Number:</span>
                <input type="number" name="number" class="box" required placeholder="Enter your mobile number" min="0">
            </div>
            <div class="inputBox">
                <span>payment method</span>
                <select name="payment-method" class="box">
                    <option value="cash-on-delivery">Cash on Delivery</option>
                    <option value="wallet">Wallet</option>
                </select>
            </div>
            <div class="inputBox">
                <span>Address Line 1:</span>
                <input type="text" name="address1" class="box" required placeholder="Example: Flat Number" maxlength="50">
            </div>
            <div class="inputBox">
                <span>Address Line 2:</span>
                <input type="text" name="address2" class="box" required placeholder="Example: Street Name"
                    maxlength="50">
            </div>
        </div>

        <input type="submit" value="order now" class="btn" name="order">
        <?php
            if(isset($_GET["err"]) && $_GET["err"] == "insufficientBalance") {
                echo '<div class="error"><p><span>Insufficient balance for current transaction</span></p></div>';
            }
        ?>

    </form>

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