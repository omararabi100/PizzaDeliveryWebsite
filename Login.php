<?php 
    include "config.php";
    if ($_POST['login']) {
        $email = $_POST['email'];
        $password = $_POST['pass'];

        $sql = "SELECT id, name, password FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Bind variables to the result set
        $stmt->bind_result($id, $name, $hashed_password);

        // Fetch the results
        $stmt->fetch();
    
        if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
            // Login successful
            $expire = time() + (86400);
            setcookie("username", $name, $expire, "/");
            setcookie("uid", $id, $expire, "/");
            session_start();
            if (!isset($_SESSION["cart"])) {
                $_SESSION["cart"] = array();
            }
            header("Location: index.php");
            exit();
        } else {
            header("Location: index.php?err=loginErr");
            exit();
        }

        $stmt->close();
    }
?>  
