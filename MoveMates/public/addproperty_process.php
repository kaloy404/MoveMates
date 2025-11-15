<?php
session_start();
include '../db/db_connect.php'; 

// Ensure user MUST be logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in to add properties.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $listing_type  = $_POST['listing_type'];
    $property_type = $_POST['property_type'];
    $address       = $_POST['address'];
    $phone_number  = $_POST['phone_number'];
    $price         = $_POST['price'];
    $description   = $_POST['description'];
    $owner_id      = $_SESSION['user_id'];  // <<< REAL OWNER ID

    // IMAGE UPLOAD
    $imageName = $_FILES['image']['name'];
    $imageTmp  = $_FILES['image']['tmp_name'];

    $uploadDir  = "uploads/";
    $imagePath  = $uploadDir . time() . "_" . $imageName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!move_uploaded_file($imageTmp, $imagePath)) {
        die("Image upload failed");
    }

    // INSERT INTO DATABASE
    $sql = "INSERT INTO properties 
        (owner_id, listing_type, property_type, address, phone_number, price, description, image_url, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssiss",
        $owner_id,
        $listing_type,
        $property_type,
        $address,
        $phone_number,
        $price,
        $description,
        $imagePath
    );

    if ($stmt->execute()) {
        echo "<script>alert('Property Added Successfully!'); window.location.href='addProperties.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
