<?php
include "db/db_connect.php";

$task_id = $_POST['task_id'];
$status_id = $_POST['status_id'];

$sql = "UPDATE tasks SET status_id = ? WHERE task_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $status_id, $task_id);

if ($stmt->execute()) {
    echo "Task Updated";
} else {
    echo "Error updating task";
}
?>
