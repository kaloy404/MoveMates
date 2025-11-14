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
    <h2>Add Property</h2>

    <form class="property-grid" action="addproperty_process.php" method="POST" enctype="multipart/form-data">
    <!-- Upload Box -->
    <div id="previewBox" class="upload-box">
      <img id="imagePreview" class="preview-image">
      
      <div id="uploadLabel" class="upload-label">
        <i class="upload-icon">📤</i>
        <p>Upload a photo</p>
      </div>

      <input type="file" id="imageUpload" name="image" hidden>
    </div>

    <!-- Upload Button -->
    <button id="triggerUpload" class="upload-btn">
      Choose Image
    </button>

    <form class="property-grid">

      <div>
        <label>Property For:</label>
        <select name="listing_type" required>
          <option>Rent</option>
          <option>Sale</option>
        </select>
      </div>

      <div>
        <label>Property Type:</label>
        <select name="property_type">
          <option>House</option>
          <option>Apartment</option>
          <option>Boarding</option>
        </select>
      </div>

      <div>
        <label>Address:</label>
        <input type="text" name="address" placeholder="Enter property address">
      </div>

      <div>
        <label>Phone Number:</label>
        <input type="text" name="phone_number" placeholder="Contact number">
      </div>

      <div>
        <label>Price:</label>
        <input type="number" name="price" placeholder="₱Enter price">
      </div>

      <div class="property-grid-full">
        <label>Description:</label>
        <textarea name="description" placeholder="Describe your property..."></textarea>
      </div>

    </form>

    <button type="submit" class="add-btn">Add Property</button>
  </div> 
</div>
</form>
  <!-- ✅ Image Preview Script -->
  <script>
    const imageUpload = document.getElementById('imageUpload');
    const triggerUpload = document.getElementById('triggerUpload');
    const previewBox = document.getElementById('previewBox');
    const imagePreview = document.getElementById('imagePreview');
    const uploadLabel = document.getElementById('uploadLabel');

    // Open file chooser when button clicked
    triggerUpload.addEventListener('click', () => imageUpload.click());

    // Show preview once file selected
    imageUpload.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreview.src = e.target.result;
          imagePreview.style.display = "block";
          uploadLabel.style.display = "none";
        }
        reader.readAsDataURL(file);
      } else {
        imagePreview.style.display = "none";
        uploadLabel.style.display = "flex";
      }
    });
  </script>

</body>
</html>
