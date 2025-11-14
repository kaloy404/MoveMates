<?php
include '../db/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['emailSignIn'];
    $password = $_POST['passwordSignIn'];

    $sql = "SELECT * FROM accounts WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['first_name'];
            echo "<script>alert('Welcome back!'); window.location.href='dashboard.php';</script>";
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email not found.'); window.history.back();</script>";
    }
}
?>
