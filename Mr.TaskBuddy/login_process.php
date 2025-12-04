<?php
session_start();
include 'db/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL (Correct Column Names)
    $query = "SELECT * FROM users WHERE user_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // User found?
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check password (no hashing)
        if (password_verify($password, $user['user_password'])) {

            // Store session
            $_SESSION['user_id'] = $user['user_id']; 
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['user_email'] = $user['user_email'];

            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid Password";
            exit();
        }
    } else {
        echo "Email not found";
        exit();
    }
}
?>
