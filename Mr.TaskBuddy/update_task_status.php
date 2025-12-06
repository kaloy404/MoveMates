<?php
// update_task_status.php
header("Content-Type: application/json");
include "db/db_connect.php";

$task_id  = isset($_POST['task_id']) ? (int)$_POST['task_id'] : 0;
$status_id = isset($_POST['status_id']) ? (int)$_POST['status_id'] : 0;

if ($task_id <= 0 || $status_id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid parameters"
    ]);
    exit;
}

$sql = "UPDATE tasks SET status_id = ? WHERE task_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $status_id, $task_id);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "Task Updated",
        "task_id" => $task_id,
        "status_id" => $status_id
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "Error updating task"
    ]);
}
