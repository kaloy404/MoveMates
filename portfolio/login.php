<?php
session_start();
include 'db/db_connect.php';

// PROCESS LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['uname'];
    $password = $_POST['psw'];

    $sql = "SELECT * FROM users WHERE user_name='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Instead of immediate PHP redirect, show a delay page
        $_SESSION['login_success'] = true; // flag success for JS
    } else {
        $_SESSION['login_error'] = true;  // store error to show AFTER redirect
        header("Location: login.php");     // redirect to avoid resubmission
        exit();
    }

    $conn->close();
}

$errorClass = "";
$showError = false;

// SHOW ERROR ONLY ONCE AFTER REDIRECT
if (!empty($_SESSION['login_error'])) {
    $errorClass = "input-error";
    $showError = true;
    unset($_SESSION['login_error']); // remove error so refresh shows nothing
}

// CHECK FOR SUCCESS FLAG
$loginSuccess = false;
if (!empty($_SESSION['login_success'])) {
    $loginSuccess = true;
    unset($_SESSION['login_success']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Login</title>
    <link rel='stylesheet' href='css/login.css'>
</head>
<body>

<video autoplay muted loop class="myVideo">
    <source src="img/bg.mp4" type="video/mp4">
</video>

<div class="ai_container">
    <img src="img/ai-unscreen.gif" alt="ai">
</div>

<div class="form-title">
    <p id="status-text">Initiating Access...</p>
</div>

<div class="loading-overlay" id="loading">
    <div class="loader">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <p id="loading-text">Processing...</p>
</div>


<form method="POST" id="loginForm">
    <div class="container">

        <label><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="uname" 
               class="<?php echo $errorClass; ?>" required>

        <label><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" 
               class="<?php echo $errorClass; ?>" required>

        <button type="submit">Login</button>

    </div>
</form>

<script>
// SHOW ERROR IF LOGIN FAILED
<?php if($showError): ?>
document.getElementById("status-text").innerText = "Access Denied";

// Remove red border after 3 seconds
setTimeout(() => {
    document.querySelectorAll('.input-error')
        .forEach(el => el.classList.remove('input-error'));
}, 1000);
<?php endif; ?>

// SUBMIT FORM: show loading overlay
document.getElementById("loginForm").addEventListener("submit", function(e) {
    document.getElementById("loading").style.display = "flex";
});

// IF LOGIN SUCCESSFUL: delay redirect
<?php if($loginSuccess): ?>
document.getElementById("status-text").innerText = "Access Granted";
document.getElementById("loading").style.display = "flex";

// delay 2 seconds before redirect
setTimeout(() => {
    window.location.href = "index.php";
}, 5000);
<?php endif; ?>
</script>

</body>
</html>
