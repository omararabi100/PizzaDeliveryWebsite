<?php
    function getItemIndex($itemName, $array) {
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i]['name'] == $itemName) {
                return $i;
            }
        }
    }
    function itemExist($itemName, $array) {
        foreach ($array as $item) {
            if($item['name'] == $itemName) {
                return true;
            }    
        }
        return false;
    }
    function getTotalPrice($cart) {
        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['price']*$item['qty']);
        }
        return $total;
    }
    function getOrderItemsString($cart){
        $items="";
        foreach ($cart as $item) {
            $items .= $item["name"]. " (" .$item['price']. "/- x " .$item["qty"]. "),";
        }
        $items=rtrim($items, ',');
        return $items;
    }
    function getCartSize($cart) {
        $size = 0;
        foreach ($cart as $item) {
            $size+=$item['qty'];
        }
        return $size;
    }
    function getUserWallet($userId) {
        include "config.php";
        
        $sql = "SELECT wallet FROM user WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $wallet = $row['wallet'];
            return $wallet;
        } else {
            return null;
        }
    }
?>