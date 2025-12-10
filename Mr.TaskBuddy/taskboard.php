<?php 
session_start();
include 'db/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login-signup.php");
    exit();
}

$userId = $_SESSION['user_id'];

/* ---------------------------------------------
   ENSURE DEFAULT CATEGORIES EXIST
--------------------------------------------- */
$conn->query("
    INSERT IGNORE INTO Categories (category_id, category_name) VALUES
    (1, 'Work'),
    (2, 'School'),
    (3, 'Personal'),
    (4, 'Health'),
    (5, 'Finance'),
    (6, 'Errands'),
    (7, 'Others')
");

/* ---------------------------------------------
   ENSURE DEFAULT STATUSES EXIST
--------------------------------------------- */
$conn->query("
    INSERT IGNORE INTO Status (status_id, status_type) VALUES
    (1, 'Not Started'),
    (2, 'In Progress'),
    (3, 'Completed')
");


/* LOAD USERS */
$users = [];
$result = $conn->query("SELECT user_id, user_name FROM Users ORDER BY user_name ASC");
while ($row = $result->fetch_assoc()) {
    $users[$row['user_id']] = $row['user_name'];
}

/* LOAD CATEGORIES */
$categories = [];
$result = $conn->query("SELECT category_id, category_name FROM Categories ORDER BY category_name ASC");
while ($row = $result->fetch_assoc()) {
    $categories[$row['category_id']] = $row['category_name'];
}

/* LOAD STATUSES */
$statuses = [];
$result = $conn->query("SELECT status_id, status_type FROM Status ORDER BY status_id ASC");
while ($row = $result->fetch_assoc()) {
    $statuses[$row['status_id']] = $row['status_type'];
}

/* ---------------------------------------------
   LOAD TASKS GROUPED BY STATUS
--------------------------------------------- */
$tasks = [
    "not-started" => [],
    "in-progress" => [],
    "completed"   => [],
    "overdue"     => []
];

$sql = "
    SELECT t.*, 
           u.user_name,
           c.category_name,
           s.status_type
    FROM Tasks t
    LEFT JOIN Users u ON u.user_id = t.user_id
    LEFT JOIN Categories c ON c.category_id = t.category_id
    LEFT JOIN Status s ON s.status_id = t.status_id
    WHERE t.user_id = $userId
    ORDER BY 
        (t.due_date IS NULL OR t.due_date = '0000-00-00 00:00:00') ASC,
        t.due_date ASC,
        t.created_at DESC
";

$result = $conn->query($sql);
$now = date("Y-m-d H:i:s");

while ($row = $result->fetch_assoc()) {

    $hasDue = !empty($row['due_date']) && $row['due_date'] !== "0000-00-00 00:00:00";

    /* -------------------------------------------------------
       AUTO OVERDUE ONLY IF:
       - Task has a due date
       - Due date is past
       - Status is Not Started (1) or In Progress (2)
       ------------------------------------------------------- */
    if ($hasDue && $row['due_date'] < $now && ($row['status_id'] == 1 || $row['status_id'] == 2)) {

        $tasks['overdue'][] = $row;
        continue;
    }

    /* ------------ NORMAL STATUS HANDLING ------------ */
    if ($row['status_id'] == 1) {
        $tasks['not-started'][] = $row;
    }
    elseif ($row['status_id'] == 2) {
        $tasks['in-progress'][] = $row;
    }
    elseif ($row['status_id'] == 3) {
        $tasks['completed'][] = $row;
    }
    elseif ($row['status_id'] == 4) {
        $tasks['overdue'][] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Taskboard</title>
  <link rel="icon" href="image/logo.png">
  <link rel="stylesheet" href="css/taskboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>

</head>

<body>
<!-- SIDEBAR -->
<div class="sidebar">
    <div class="logo-area">
        <img src="image/logo.png" alt="Logo">
    </div>

   <div class="menu">
       <a href="dashboard.php" class="menu-item ">
    <i data-lucide="layout-dashboard"></i>
    <span>Dashboard</span>
</a>

<a href="taskboard.php" class="menu-item active">
    <i data-lucide="square-check"></i>
    <span>Taskboard</span>
</a>

<a href="logout.php" class="menu-item logout">
    <i data-lucide="log-out"></i>
    <span>Logout</span>
</a>

    </div>

</div>

<!-- MAIN CONTENT -->
<div class="main">

<div class="task-header">
    <h2>Taskboard</h2>

    <div class="task-actions">
        <button class="calendar-btn"><i class="fa-solid fa-calendar-days"></i></button>
        <button class="create-task-btn" onclick="openCreateModal()">
            <i class="fa-solid fa-plus"></i> Create Task
        </button>
        <button class="filter-btn" onclick="toggleCategoryFilter()">
    <i class="fa-solid fa-bars"></i>
</button>

<div id="categoryFilter" class="category-filter">
    <select id="categorySelect" onchange="filterByCategory()">
        <option value="all">All Categories</option>
        <?php foreach ($categories as $cid => $cname): ?>
            <option value="<?= $cid ?>"><?= htmlspecialchars($cname) ?></option>
        <?php endforeach; ?>
    </select>
</div>

    </div>
</div>


<div class="board">

    <!-- NOT STARTED -->
    <div class="column" data-status="1">
        <div class="column-header pending">Not Started</div>

        <?php foreach ($tasks['not-started'] as $t): ?>
            <div class="task"
                 data-task-id="<?= $t['task_id'] ?>"
                 data-task='<?= json_encode($t, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
                 onclick='openTask(JSON.parse(this.dataset.task))'>
                <div class="task-title"><?= htmlspecialchars($t['title']) ?></div>
                <?php if (!empty($t['due_date']) && $t['due_date'] !== "0000-00-00 00:00:00"): ?>
    <div class="due">Due on <?= date('d M', strtotime($t['due_date'])) ?></div>
<?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>



    <!-- IN PROGRESS -->
    <div class="column" data-status="2">
        <div class="column-header in-progress">In Progress</div>

        <?php foreach ($tasks['in-progress'] as $t): ?>
            <div class="task"
                 data-task-id="<?= $t['task_id'] ?>"
                 data-task='<?= json_encode($t, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
                 onclick='openTask(JSON.parse(this.dataset.task))'>
                <div class="task-title"><?= htmlspecialchars($t['title']) ?></div>
                <?php if (!empty($t['due_date']) && $t['due_date'] !== "0000-00-00 00:00:00"): ?>
    <div class="due">Due on <?= date('d M', strtotime($t['due_date'])) ?></div>
<?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>




    <!-- COMPLETED -->
    <div class="column" data-status="3">
        <div class="column-header completed">Completed</div>

        <?php foreach ($tasks['completed'] as $t): ?>
            <div class="task"
                 data-task-id="<?= $t['task_id'] ?>"
                 data-task='<?= json_encode($t, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
                 onclick='openTask(JSON.parse(this.dataset.task))'>
                <div class="task-title"><?= htmlspecialchars($t['title']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>



    <!-- OVERDUE -->
    <div class="column" id="overdueColumn" data-status="4">
        <div class="column-header overdue">Overdue</div>

        <?php foreach ($tasks['overdue'] as $t): ?>
            <div class="task"
                 data-task-id="<?= $t['task_id'] ?>"
                 data-task='<?= json_encode($t, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
                 onclick='openTask(JSON.parse(this.dataset.task))'>
                <div class="task-title"><?= htmlspecialchars($t['title']) ?></div>
                <div class="due">Overdue!</div>
            </div>
        <?php endforeach; ?>
    </div>

</div> <!-- end board -->

</div> <!-- end main -->


<script>
    lucide.createIcons();
</script>
<!-- MODALS -->
<?php include 'task_modals.php'; ?>
<?php include 'calendar.php'; ?>
<script>
   const tasksFromPHP = <?= json_encode($tasks_flat); ?>;
</script>
<script src="js/taskboard.js"></script>
</body>
</html>
