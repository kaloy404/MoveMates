<?php
include '../db/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        die("Passwords do not match!");
    }

    // Encrypt password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into your accounts table
    $sql = "INSERT INTO accounts (first_name, last_name, email, phone_number, password)
            VALUES ('$firstName', '$lastName', '$email', '$phone', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Account created successfully!'); window.location.href='home.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
