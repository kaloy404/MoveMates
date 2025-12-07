<?php
include 'db/db_connect.php';

header("Content-Type: application/json");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// 1. Validation
if ($email === '' || $password === '') {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

// 2. Check if email exists
$check = $conn->prepare("SELECT user_id FROM Users WHERE user_email = ?");
$check->bind_param("s", $email);
$check->execute();
$res = $check->get_result();

if ($res->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Email not found"]);
    exit;
}

// 3. Hash new password
$hashedPass = password_hash($password, PASSWORD_DEFAULT);

// 4. Update password
$update = $conn->prepare("UPDATE Users SET user_password = ? WHERE user_email = ?");
$update->bind_param("ss", $hashedPass, $email);

if (!$update->execute()) {
    echo json_encode(["status" => "error", "message" => "Failed to update password"]);
    exit;
}

// 5. Success response
echo json_encode(["status" => "success", "message" => "Password updated successfully"]);
exit;
