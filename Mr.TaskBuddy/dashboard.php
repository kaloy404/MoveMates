<?php
session_start();
include 'db/db_connect.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login-signup.php");
    exit();
}

$userId = $_SESSION['user_id'];
$today = date("Y-m-d");

// ===== NOT STARTED (status_id = 1) =====
$sqlNotStarted = "SELECT COUNT(*) AS count FROM tasks 
                  WHERE user_id = ? AND status_id = 1";
$stmt = $conn->prepare($sqlNotStarted);
$stmt->bind_param("i", $userId);
$stmt->execute();
$not_started = $stmt->get_result()->fetch_assoc()['count'];

// ===== IN PROGRESS (status_id = 2) =====
$sqlProgress = "SELECT COUNT(*) AS count FROM tasks 
                WHERE user_id = ? AND status_id = 2";
$stmt = $conn->prepare($sqlProgress);
$stmt->bind_param("i", $userId);
$stmt->execute();
$in_progress = $stmt->get_result()->fetch_assoc()['count'];

// ===== COMPLETED (status_id = 3) =====
$sqlCompleted = "SELECT COUNT(*) AS count FROM tasks 
                 WHERE user_id = ? AND status_id = 3";
$stmt = $conn->prepare($sqlCompleted);
$stmt->bind_param("i", $userId);
$stmt->execute();
$completed = $stmt->get_result()->fetch_assoc()['count'];

// ===== OVERDUE =====
// overdue = due_date is past AND NOT completed
$sqlOverdue = "SELECT COUNT(*) AS count FROM tasks 
               WHERE user_id = ?
               AND due_date < ?
               AND status_id != 3";
$stmt = $conn->prepare($sqlOverdue);
$stmt->bind_param("is", $userId, $today);
$stmt->execute();
$overdue = $stmt->get_result()->fetch_assoc()['count'];

// ===== TOTAL TASKS =====
$total_tasks = $not_started + $in_progress + $completed + $overdue;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/dashboard.css">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="logo-area">
        <img src="image/logo.png" alt="Logo">
    </div>

    <div class="menu">
        <a href="dashboard.php" class="menu-item active">Dashboard</a>
        <a href="taskboard.php" class="menu-item">Task Board</a>
        <a href="logout.php" class="menu-item logout">Logout</a>
    </div>

</div>

<!-- MAIN -->
<div class="main">

    <!-- TOP BAR -->
    <div class="topbar">
        <h2>Dashboard Overview</h2>
        <input type="text" placeholder="Search...">
    </div>

    <!-- NEW CARDS -->
    <div class="cards">

        <div class="card">
            <h4>Not Started</h4>
            <div class="value"><?php echo $not_started; ?></div>
        </div>

        <div class="card">
            <h4>In Progress</h4>
            <div class="value"><?php echo $in_progress; ?></div>
        </div>

        <div class="card">
            <h4>Completed</h4>
            <div class="value"><?php echo $completed; ?></div>
        </div>

        <div class="card">
            <h4>Overdue</h4>
            <div class="value" style="color:#e74c3c;"><?php echo $overdue; ?></div>
        </div>

    </div>

    <!-- TOTAL TASKS DISPLAY -->
    <div class="total-container">
        <h3>Total Tasks: <?php echo $total_tasks; ?></h3>
    </div>

    <!-- CHART AREA -->
    <div class="chart-box">
        <canvas id="taskChart" height="120"></canvas>
    </div>

</div>


<!-- CHART SCRIPT -->
<script>
const ctx = document.getElementById('taskChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Not Started', 'In Progress', 'Completed', 'Overdue'],
        datasets: [{
            label: 'Task Count',
            data: [
                <?php echo $not_started; ?>,
                <?php echo $in_progress; ?>,
                <?php echo $completed; ?>,
                <?php echo $overdue; ?>
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>
