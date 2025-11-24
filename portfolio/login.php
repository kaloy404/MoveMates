<?php
session_start();
include 'db/db_connect.php';

$status = ""; // success, error, or empty
$old_uname = "";
$old_psw   = "";

// PROCESS LOGIN
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['uname'];
    $password = $_POST['psw'];

    $sql = "SELECT * FROM users WHERE user_name='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Successful login → redirect to index.php
        $_SESSION['login_status'] = "success";
        $_SESSION['old_uname'] = $username;
        $_SESSION['old_psw'] = $password;
        header("Location: login.php"); // JS will handle the delay and redirect
        exit();
    } else {
        // Failed login → store old inputs
        $_SESSION['login_status'] = "error";
        $_SESSION['old_uname'] = $username;
        $_SESSION['old_psw'] = $password;
        header("Location: login.php");
        exit();
    }

    $conn->close();
}

// AFTER REDIRECT — GET STATUS AND OLD INPUTS
$status = $_SESSION['login_status'] ?? '';

// Only populate old inputs if login failed
if ($status === "error") {
    $old_uname = $_SESSION['old_uname'] ?? '';
    $old_psw   = $_SESSION['old_psw'] ?? '';
}

// Clear the session values so refresh won't retrigger login processing
unset($_SESSION['login_status'], $_SESSION['old_uname'], $_SESSION['old_psw']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Login</title>
    <link rel='stylesheet' href='css/login.css'>
    <link rel="icon" href="img/ai-unscreen.gif">
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
        <input type="text" placeholder="Enter Username" name="uname"  value="<?= htmlspecialchars($old_uname) ?>" required>

        <label><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" value="<?= htmlspecialchars($old_psw) ?>" required>

        <button type="submit">Login</button>

    </div>
</form>
<script>
    let loginStatus = "<?php echo $status; ?>";
</script>
<script src="js/login.js"></script>

</body>
</html>
