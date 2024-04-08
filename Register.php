<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

    include "config.php";
    if (isset($_POST['register'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['pass'];
        $password = $_POST['cpass'];
        $wallet = 0;

        $check_query = "SELECT * FROM user WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            header("Location: index.php?err=emailTaken");
            exit();
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
            $sql = "INSERT INTO user (name, email, password, wallet) VALUES ('$name', '$email', '$hashed_password', $wallet)";
            $stmt = $conn->prepare($sql);
            
            if (!$stmt) {
                echo "Error: " . $conn->error;
                exit();
            }

            if ($stmt->execute()) {
                $id = $stmt->insert_id; // Get the ID of the inserted user
                $expire = time() + (86400);
                setcookie("username", $name, $expire, "/");
                setcookie("uid", $id, $expire, "/");
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
?>

