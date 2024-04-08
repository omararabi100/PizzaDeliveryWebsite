<?php
    if (isset( $_POST['log-out'] )) {
        setcookie("username", "", time()-3600, "/");
        setcookie("uid", "", time()-3600, "/");
        if (isset($_SESSION["cart"])) {
            unset($_SESSION["cart"]);
        }
        $_SESSION["cart"] = array();
        header('Location: index.php' );
    }

?>