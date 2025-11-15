<?php
session_start();
include '../db/db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch properties owned by logged-in user
$sql = "SELECT * FROM properties WHERE owner_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
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
    <link rel="stylesheet" href="css/properties.css">
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
    <p><strong><?= $_SESSION['name']; ?></strong></p>
    <p>+63<?= $_SESSION['phone_number'] ?? "***********"; ?></p>
    <a href="dashboard.php">Buy</a>
    <a href="rent.php">Rent</a>
    <a href="#" class="active">Properties</a>
    <a href="profile.php">Profile</a>
  </div>

  <!-- MAIN CONTENT -->
  <div class="content">
    <div class="content-header">
      <h1><i class="fa-solid fa-city"></i> My Properties</h1>

      <div class="search-bar">
        <input type="text" placeholder="Search..." id="searchInput">
        <i class="fa-solid fa-magnifying-glass"></i>
      </div>

      <a href="addProperties.php" class="add-btn">Add Property</a>
    </div>

    <!-- PROPERTY LIST -->
    <div class="property-list" id="propertyList">

<?php
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
?>
      <div class="property-card">
        <img src="<?= $row['image_url']; ?>" alt="Property">

        <div class="property-info">
          <p class="property-title"><?= $row['property_type']; ?> - <?= $row['listing_type']; ?></p>
          <p class="property-address"><?= $row['address']; ?></p>
          <p class="property-price">₱ <?= number_format($row['price']); ?></p>

          <div class="btn-group">
            <a href="updateProperty.php?id=<?= $row['id']; ?>" class="update-btn">Update</a>
            <a href="deleteProperty.php?id=<?= $row['id']; ?>" class="delete-btn"
               onclick="return confirm('Delete this property?');">
               Delete
            </a>
          </div>
        </div>
      </div>
<?php
  }
} else {
  echo "<p style='text-align:center; width:100%; font-size:18px;'>No properties found.</p>";
}
?>

    </div>
  </div>

</body>
</html>
