<?php
include 'db/db_connect.php';

header("Content-Type: application/json"); // Ensures clean AJAX response

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];

    // Check empty fields
    if (empty($name) || empty($email) || empty($password_raw)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit();
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT user_id FROM users WHERE user_email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists"]);
        exit();
    }

    // Hash password
    $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

    // Insert new user
    $sql = "INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $password_hashed);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Account created successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }
}
?>
