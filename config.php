<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pizza_db";

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(session_id() == '') {
        session_start();
    }
    if(!isset($_SESSION["cart"])){
        $_SESSION["cart"] = array();
    }
?>