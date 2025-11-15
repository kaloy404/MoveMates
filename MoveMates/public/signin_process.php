<?php
include '../db/db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['emailSignIn'];
    $password = $_POST['passwordSignIn'];

    // ✔️ Use prepared statement (security!)
    $sql = "SELECT * FROM accounts WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // ✔️ Verify password
        if (password_verify($password, $row['password'])) {

            // ✔️ Store user login in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['firstName'];  // use correct column
            $_SESSION['email'] = $row['email'];

            echo "<script>alert('Welcome back!'); window.location.href='dashboard.php';</script>";
            exit;
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email not found.'); window.history.back();</script>";
    }
}
?>
