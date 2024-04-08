<?php
    include "functions.php";
    if(isset($_POST['update_qty'])){
        session_start();
        $quantity = $_POST['qty'];
        $pizza_name = $_POST['pizza-name'];
        $cart = $_SESSION["cart"];

        $itemIndex =  getItemIndex($pizza_name, $cart);
        $cart[ $itemIndex ] ['qty'] = $quantity;
        $_SESSION["cart"] = $cart;
    }
    $location = $_POST['location'];
    header("Location: $location?success=1");
?>