<?php
session_start();
include '../db/db_connect.php';

$property_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Get image path first
$sql = "SELECT image_url FROM properties WHERE id = ? AND owner_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $property_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Property not found");
}

$row = $result->fetch_assoc();
$imagePath = $row['image_url'];

// Delete property from database
$deleteSQL = "DELETE FROM properties WHERE id = ? AND owner_id = ?";
$deleteStmt = $conn->prepare($deleteSQL);
$deleteStmt->bind_param("ii", $property_id, $user_id);

if ($deleteStmt->execute()) {

    // Remove image file
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    echo "<script>alert('Property deleted successfully'); window.location.href='properties.php';</script>";
} else {
    echo "Error deleting property.";
}
?>
