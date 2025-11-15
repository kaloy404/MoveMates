<?php
session_start();
include '../db/db_connect.php';

// Get only RENT listings
$sql = "SELECT * FROM properties WHERE listing_type = 'Rent' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>MoveMates Property Rental</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="shortcut icon" href="../src/icons/home.png">
    <link rel="stylesheet" href="css/sideandtopBar.css">
    <link rel="stylesheet" href="css/dashboard.css">

</head>
<body>

  <!-- HEADER -->
  <div class="topbar">
    <div class="logo">
      <i class="fa-solid fa-house"></i>
      MoveMates
    </div>
    <a href="home.php">Logout</a>
  </div>

  <!-- SIDEBAR -->
  <div class="sidebar">
    <img src="../src/icons/monkey.png" alt="Profile">
    <p><strong>John Carl Monacillo</strong></p>
    <p>+631111111111</p>
    <a href="dashboard.php">Buy</a>
    <a href="#" class="active">Rent</a>
    <a href="properties.php">Properties</a>
    <a href="profile.php">Profile</a>
  </div>

  <!-- MAIN CONTENT -->
  <div class="content">
    <div class="content-header">
      <h1><i class="fa-solid fa-city"></i> Properties</h1>
      <div class="search-bar">
        <input type="text" placeholder="Search...">
        <i class="fa-solid fa-magnifying-glass"></i>
      </div>
    </div>

   <div class="property-list" id="propertyList">

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
        <div class="property-card">
            <img src="<?= $row['image_url']; ?>" alt="Property Image">

            <div class="property-info">
                <p><strong><?= $row['property_type']; ?></strong></p>
                <p><?= $row['address']; ?></p>
                <p>₱ <?= number_format($row['price']); ?></p>

                <a href="property_details.php?id=<?= $row['id']; ?>" class="details-btn">Details</a>
            </div>
        </div>
<?php
    }
} else {
    echo "<p>No rental properties available.</p>";
}
?>
</div>


</body>
</html>