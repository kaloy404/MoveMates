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
    <a href="rent.php">Rent</a>
    <a href="#" class="active">Properties</a>
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

    <!-- PROPERTY LIST -->
    <div class="property-list" id="propertyList">
      <!-- Dynamic placeholders (to be generated from database later) -->
      <div class="property-card">
        <img src="../src/images/1.jpg" alt="House 1">
        <div class="property-info">
          <div class="placeholder line1"></div>
          <div class="placeholder line2"></div>
          <div class="placeholder line3"></div>
          <a href="#" class="details-btn">Details</a>
        </div>
      </div>

      <div class="property-card">
        <img src="../src/images/2.jpg" alt="House 2">
        <div class="property-info">
          <div class="placeholder line1"></div>
          <div class="placeholder line2"></div>
          <div class="placeholder line3"></div>
          <a href="#" class="details-btn">Details</a>
        </div>
      </div>

      <div class="property-card">
        <img src="../src/images/3.jpg" alt="House 3">
        <div class="property-info">
          <div class="placeholder line1"></div>
          <div class="placeholder line2"></div>
          <div class="placeholder line3"></div>
          <a href="#" class="details-btn">Details</a>
        </div>
      </div>
    </div>
  </div>

</body>
</html>