<?php
include '../db/db_connect.php'; // your DB connection

// Make sure form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Collect form data
    $listing_type  = $_POST['listing_type'];
    $property_type = $_POST['property_type'];
    $address       = $_POST['address'];
    $phone_number  = $_POST['phone_number'];
    $price         = $_POST['price'];
    $description   = $_POST['description'];
    $owner_id      = 1; // temporary - you will use session later

    // 2. Handle image upload
    $imageName = $_FILES['image']['name'];
    $imageTmp  = $_FILES['image']['tmp_name'];

    $uploadDir  = "uploads/";
    $imagePath  = $uploadDir . time() . "_" . $imageName;

    // Create uploads folder if not exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move file
    if (!move_uploaded_file($imageTmp, $imagePath)) {
        die("Image upload failed");
    }

    // 3. Insert into database
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
        echo "<script>alert('Property Added Successfully!'); window.location.href='properties.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
