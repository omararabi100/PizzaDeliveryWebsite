<?php
    include "functions.php";
    session_start();
    if(isset($_POST['delete_item'])){
        $pizza_name = $_POST['pizza-name'];
        $cart = $_SESSION["cart"];
        
        $itemIndex =  getItemIndex($pizza_name, $cart);
        unset($cart[ $itemIndex ]);
        $_SESSION["cart"] = $cart;
    }
    $location = $_POST['location'];
    header("Location: $location");
    exit();
    
?>