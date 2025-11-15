<?php
session_start();
include '../db/db_connect.php';

$property_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch property
$sql = "SELECT * FROM properties WHERE id = ? AND owner_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $property_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Property not found");
}

// Update form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $listing_type  = $_POST['listing_type'];
    $property_type = $_POST['property_type'];
    $address       = $_POST['address'];
    $phone_number  = $_POST['phone_number'];
    $price         = $_POST['price'];
    $description   = $_POST['description'];

    // Query updates
    $sql = "UPDATE properties SET listing_type=?, property_type=?, address=?, phone_number=?, price=?, description=? 
            WHERE id=? AND owner_id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssissi",
        $listing_type, 
        $property_type, 
        $address, 
        $phone_number, 
        $price, 
        $description, 
        $property_id,
        $user_id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Property updated!'); window.location.href='properties.php';</script>";
    } else {
        echo "Update failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MoveMates | Add Property</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="shortcut icon" href="../src/icons/home.png">
  <link rel="stylesheet" href="css/sideandtopBar.css">
  <link rel="stylesheet" href="css/addProperties.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
</head>
<body>

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="logo">
      <i class="fa-solid fa-house"></i>
      <span>MoveMates</span>
    </div>
    <a href="home.php" class="logout">Logout</a>
  </header>

  <!-- SIDEBAR -->
  <div class="sidebar">
    <img src="../src/icons/monkey.png" alt="Profile" class="profile-pic">
    <p class="user-name"><strong>John Carl Monacillo</strong></p>
    <p class="user-phone">+631111111111</p>
      <a href="dashboard.php">Buy</a>
      <a href="rent.php">Rent</a>
      <a href="properties.php" class="active">Properties</a>
      <a href="profile.php">Profile</a>
</div>

<div class="content">
  <div class="property-form-container">
    <h2>Update Property</h2>

    <form method="POST" enctype="multipart/form-data" class="property-grid">
<!-- IMAGE UPLOAD SECTION -->
<div class="upload-section">

  <!-- PREVIEW BOX -->
  <div id="previewBox" class="upload-box">
    <img id="imagePreview" class="preview-image">

    <div id="uploadLabel" class="upload-label">
      <i class="upload-icon">📤</i>
      <p>Upload a photo</p>
    </div>

    <input type="file" id="imageUpload" name="image" hidden>
  </div>

  <!-- CHOOSE IMAGE BUTTON -->
  <button id="triggerUpload" type="button" class="upload-btn">Choose Image</button>

</div>

      <!-- PROPERTY FIELDS -->
      <div>
        <label>Property For:</label>
        <select name="listing_type" required>
          <option value="Rent" <?= $row['listing_type']=="Rent"?"selected":"" ?>>Rent</option>
          <option value="Sale" <?= $row['listing_type']=="Sale"?"selected":"" ?>>Sale</option>
        </select>
      </div>

      <div>
        <label>Property Type:</label>
        <select name="property_type">
          <option value="House" <?= $row['listing_type']=="House"?"selected":"" ?>>House</option>
          <option value="Apartment" <?= $row['listing_type']=="Apartment"?"selected":"" ?>>Apartment</option>
          <option value="Room" <?= $row['listing_type']=="Room"?"selected":"" ?>>Room</option>
        </select>
      </div>

      <div>
        <label>Address:</label>
        <input type="text" name="address" value="<?= $row['address']; ?>">
      </div>

      <div>
        <label>Phone Number:</label>
        <input type="text" name="phone_number" value="<?= $row['phone_number']; ?>">
      </div>

      <div>
        <label>Price:</label>
        <input type="number" name="price" value="<?= $row['price']; ?>">
      </div>

      <div class="property-grid-full">
        <label>Description:</label>
        <textarea name="description"><?= $row['description']; ?></textarea>
      </div>

      <!-- ✔️ FIXED: SUBMIT BUTTON INSIDE FORM -->
      <button type="submit" class="add-btn">Update</button>

    </form>

  </div> 
</div>