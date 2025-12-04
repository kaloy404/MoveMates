<?php
include 'db/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login-Signup</title>
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
        <input type="password" id="password" name="password" placeholder="Password" required />

       <button class="btn" type="submit" id="submitBtn">Submit</button>


      </form>
      <!-- FORM END -->

      <button class="switch-btn" id="switch-btn">Go to Signup</button>
    </div>

    <div class="panel" id="panel">
      <img src="image/logo.png" alt="Panel Image" class="panel-img">
    </div>
  </div>

  <script>
    const container = document.getElementById('container');
    const switchBtn = document.getElementById('switch-btn');
    const title = document.getElementById('form-title');
    const nameField = document.getElementById('nameField');
    const mainForm = document.getElementById('mainForm');

    let signupMode = false;

    switchBtn.addEventListener('click', () => {
      signupMode = !signupMode;
      container.classList.toggle('signup-mode');

      title.textContent = signupMode ? 'Sign Up' : 'Login';
      switchBtn.textContent = signupMode ? 'Go to Login' : 'Go to Signup';

      nameField.style.display = signupMode ? 'block' : 'none';

      // change action of form
      mainForm.action = signupMode ? "signup_process.php" : "login_process.php";
    });

    function submitForm() {
    const formData = new FormData(mainForm);

    fetch(mainForm.action, {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {

        if (data.trim() === "success") {
            showAlert("You have successfully signed up!");
            mainForm.reset(); // optional clear fields after signup
        } else {
            showAlert("Error: " + data);
        }

    });
}
const submitBtn = document.getElementById("submitBtn");

submitBtn.addEventListener("click", function (e) {
    e.preventDefault();

    // SIGNUP MODE → AJAX
    if (signupMode) {
        submitForm();
    } 
    // LOGIN MODE → normal form submit
    else {
        mainForm.submit();
    }
});


// DROPDOWN ALERT
function showAlert(message) {
    let alertBox = document.createElement("div");
    alertBox.className = "dropdown-alert";
    alertBox.innerText = message;

    document.body.appendChild(alertBox);

    setTimeout(() => {
        alertBox.classList.add("show");
    }, 10);

    setTimeout(() => {
        alertBox.classList.remove("show");
        setTimeout(() => alertBox.remove(), 300);
    }, 3000);
}

  </script>

</body>
</html>
