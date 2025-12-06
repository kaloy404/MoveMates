<?php
session_start();
include 'db/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare query
    $query = "SELECT * FROM users WHERE user_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows !== 1) {
        echo "invalid"; // email not found
        exit();
    }

    $user = $result->fetch_assoc();

    // Verify password
    if (!password_verify($password, $user['user_password'])) {
        echo "invalid"; // wrong password
        exit();
    }

    // Login success → create session
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_name'] = $user['user_name'];
    $_SESSION['user_email'] = $user['user_email'];

    echo "success"; // AJAX redirect will handle dashboard
    exit();
}
?>
