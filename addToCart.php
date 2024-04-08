<?php
    include "functions.php";
    if(isset($_POST['add_to_cart'])){
        session_start();
        $quantity = $_POST['qty'];
        if(isset($_SESSION["cart"])) {
            $cart = $_SESSION["cart"];
        } else {
            $cart = array();
        }

        $pizza_name = $_POST['pizza-name'];
        if(itemExist($pizza_name, $cart)) {
            $itemIndex =  getItemIndex($pizza_name, $cart);
            $cart[ $itemIndex ] ['qty'] += $quantity;
        } else {
            $price = $_POST['price'];
            $img_name = $_POST['img-name'];
            $item = array("name" => $pizza_name, "price" => $price, "qty" => $quantity, "img-name" => $img_name);
            array_push($cart, $item);
        }
        $_SESSION["cart"] = $cart;
    }
    header("Location: menu.php");
?>