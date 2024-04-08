<?php
    include "config.php";
    include "functions.php";
    
    if (isset( $_POST['order'] )) {
        if (!isset($_COOKIE["username"])) {
            header("Location: index.php?err=notLoggedIn");
            exit();
        } else {
            if(session_id() == '') {
                session_start();
            }
            $cart = $_SESSION["cart"];
            $username = $_COOKIE["username"];
            
            $userID = $_COOKIE["uid"];
            $date = date("Y-m-d");
            $number = $_POST["number"];
            $address = $_POST["address1"] . ", " . $_POST["address2"];
            $paymentMethod = $_POST["payment-method"];
            $orderItems = getOrderItemsString($cart);
            $price = getTotalPrice($cart);
            
            if ($paymentMethod == "wallet") {
                $walletBalance = getUserWallet($userID);

                if ($walletBalance < $price) {
                    header("Location: orders.php?err=insufficientBalance");
                    exit();
                }
                
                $newWalletBalance = $walletBalance - $price;
                $updateWalletSql = "UPDATE user SET wallet = ? WHERE id = ?";
                $updateWalletStmt = $conn->prepare($updateWalletSql);
                if (!$updateWalletStmt) {
                    echo "Error: " . $conn->error;
                    exit();
                }
                $updateWalletStmt->bind_param("di", $newWalletBalance, $userID);
                if (!$updateWalletStmt->execute()) {
                    echo "Error: " . $updateWalletStmt->error;
                    exit();
                }
                $updateWalletStmt->close();
            }

            $sql = "INSERT INTO orders (userID, date, number, address, paymentMethod, orderItems, price) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                echo "Error: " . $conn->error;
                exit();
            }

            $stmt->bind_param("issssss", $userID, $date, $number, $address, $paymentMethod, $orderItems, $price);

            if ($stmt->execute()) {
                $_SESSION["cart"] = array();
                header("Location: orders.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }

?>