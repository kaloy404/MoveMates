<?php
header("Content-Type: application/json");
session_start();
include 'db/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$userId = $_SESSION['user_id'];

/* ----------------------------
   VALIDATE REQUIRED FIELDS
----------------------------- */
$required = ["title", "description", "priority", "category_id", "status_id"];

foreach ($required as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === "") {
        echo json_encode([
            "status" => "error",
            "message" => "Missing field: $field"
        ]);
        exit;
    }
}

/* ----------------------------
   CLEAN INPUTS
----------------------------- */
$title       = trim($_POST['title']);
$description = trim($_POST['description']);
$priority    = trim($_POST['priority']);
$category    = intval($_POST['category_id']);
$status      = intval($_POST['status_id']);
$due         = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

/* ----------------------------
   SQL INSERT (7 columns)
----------------------------- */
$stmt = $conn->prepare("
    INSERT INTO tasks (user_id, title, description, priority, category_id, status_id, due_date)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

/* due_date is allowed to be null */
$stmt->bind_param(
    "isssiis",
    $userId,
    $title,
    $description,
    $priority,
    $category,
    $status,
    $due
);

/* ----------------------------
   EXECUTE AND RETURN JSON
----------------------------- */
if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "success",
        "task" => [
            "id" => $stmt->insert_id,
            "title" => $title,
            "description" => $description,
            "priority" => $priority,
            "category_id" => $category,
            "status_id" => $status,
            "due_date" => $due
        ]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
