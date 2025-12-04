<?php
include 'db/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // UPDATED column names
    $sql = "INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
         echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
