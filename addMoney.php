<?php
include("config.php");

if(isset($_POST['add'])) {
    $userID = $_COOKIE["uid"];
    $moneyToAdd = $_POST['money-value'];
    $location = $_POST['location'];

    $sql = "UPDATE user SET wallet = wallet + ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $moneyToAdd, $userID);
    
    if ($stmt->execute()) {
        header("Location: $location");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
