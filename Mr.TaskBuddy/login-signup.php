<?php
include 'db/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login-Signup</title>
  <link rel="icon" href="image/logo.png">
  <link rel="stylesheet" href="css/login-signup.css">
</head>
<body>

  <div class="container" id="container">
    <div class="forms" id="forms">
      <h2 id="form-title">Login</h2>

      <!-- FORM START -->
      <form id="mainForm" method="POST" action="login_process.php">

        <input type="text" id="nameField" name="name" placeholder="Name" style="display:none;" />

        <input type="text" id="email" name="email" placeholder="Email" required />

        <div class="password-wrapper">
  <input type="password" id="password" name="password" placeholder="Password" required />
  <i id="togglePassword">🙈</i>
</div>
<div class="forgot-pass-wrapper">
   <a href="#" id="openForgot" class="forgot-link">Forgot Password?</a>
</div>

       <button class="btn" type="submit" id="submitBtn">Submit</button>


      </form>
      <!-- FORM END -->

      <button class="switch-btn" id="switch-btn">Go to Signup</button>
    </div>

    <div class="panel" id="panel">
      <img src="image/logo.png" alt="Panel Image" class="panel-img">
    </div>
  </div>


<div id="forgotModal" class="forgot-modal">
    <div class="forgot-box">
        <h3>Reset Password</h3>

        <input type="email" id="forgotEmail" placeholder="Enter your email">
        <div class="password-wrapper">
        <input type="password" id="forgotNew" name="password"placeholder="New Password">
        <i id="togglePassword">🙈</i>
        </div>
        <div class="password-wrapper">
        <input type="password" id="forgotConfirm" name="password" placeholder="Confirm Password">
        <i id="togglePassword">🙈</i>
        </div>
         <div class="forgot-buttons">
            <button id="forgotSubmit" class="primary-btn">Update Password</button>
            <button id="forgotClose" class="cancel-btn">Cancel</button>
        </div>
    </div>
</div>


<script src="js/login-signup.js"></script>

</body>
</html>
