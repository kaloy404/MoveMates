<?php
session_start();
include 'db/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login-signup.php"); // same as your dashboard
    exit();
}

$userId = $_SESSION['user_id']; // same variable name as dashboard
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


/* ---------------------------------------------
   LOAD USERS
--------------------------------------------- */
$users = [];
$result = $conn->query("SELECT user_id, user_name FROM Users ORDER BY user_name ASC");
while ($row = $result->fetch_assoc()) {
    $users[$row['user_id']] = $row['user_name'];
}

/* ---------------------------------------------
   LOAD CATEGORIES
--------------------------------------------- */
$categories = [];
$result = $conn->query("SELECT category_id, category_name FROM Categories ORDER BY category_name ASC");
while ($row = $result->fetch_assoc()) {
    $categories[$row['category_id']] = $row['category_name'];
}

/* ---------------------------------------------
   LOAD STATUSES
--------------------------------------------- */
$statuses = [];
$result = $conn->query("SELECT status_id, status_type FROM Status ORDER BY status_id ASC");
while ($row = $result->fetch_assoc()) {
    $statuses[$row['status_id']] = $row['status_type'];
}

/* ---------------------------------------------
   HANDLE CREATE TASK
--------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create_task') {

    $title        = $_POST['title'];
    $description  = $_POST['description'];
    $priority     = $_POST['priority'];
    $user_id      = $userId;  // <-- FIXED
    $category_id  = $_POST['category_id'];
    $status_id    = $_POST['status_id'];
    $due_date     = $_POST['due_date'];

    $stmt = $conn->prepare("
        INSERT INTO Tasks 
        (user_id, category_id, status_id, title, description, priority, due_date, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param(
        "iiissss",
        $user_id, $category_id, $status_id,
        $title, $description, $priority, $due_date
    );

    if ($stmt->execute()) {
        echo "<script>alert('Task created successfully!'); window.location='taskboard.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error creating task: " . $conn->error . "');</script>";
    }
}


/* ---------------------------------------------
   HANDLE STATUS UPDATE
--------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update_status') {
    $task_id = $_POST['task_id'];
    $new_status = $_POST['new_status']; // 1 = Not Started, 2 = In Progress, 3 = Completed

    $stmt = $conn->prepare("UPDATE Tasks SET status_id = ? WHERE task_id = ?");
    $stmt->bind_param("ii", $new_status, $task_id);

    if ($stmt->execute()) {
        echo "<script>window.location='taskboard.php';</script>";
        exit;
    }
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
    ORDER BY t.created_at DESC
";

$result = $conn->query($sql);
$now = date("Y-m-d H:i:s");

while ($row = $result->fetch_assoc()) {

    if ($row['due_date'] < $now && $row['status_id'] != 3) {
        $tasks['overdue'][] = $row;
    } elseif ($row['status_id'] == 1) {
        $tasks['not-started'][] = $row;
    } elseif ($row['status_id'] == 2) {
        $tasks['in-progress'][] = $row;
    } elseif ($row['status_id'] == 3) {
        $tasks['completed'][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/taskboard.css">
</head>
<body>
<!-- SIDEBAR -->
<div class="sidebar">

    <div class="logo-area">
        <img src="image/logo.png" alt="Logo">
    </div>

    <div class="menu">
        <a href="dashboard.php" class="menu-item">Dashboard</a>
        <a href="#.php" class="menu-item">Task Board</a>
        <a href="signin.php" class="menu-item logout">Logout</a>
    </div>

</div>


<!-- MAIN CONTENT -->
<div class="main">

<div class="task-header">
    <h2>Taskboard</h2>
    <div class="task-actions">
        <button class="calendar-btn"><i class="fa-solid fa-calendar-days"></i></button>
        <button class="create-task-btn"  onclick="openCreateModal()">
            <i class="fa-solid fa-plus"></i> Create Task</button>
    </div>
</div>


    <div class="board">
      <!-- NOT STARTED -->
      <div class="column">
        <div class="column-header pending">Not Started</div>

        <?php if (!empty($tasks['not-started'])): ?>
          <?php foreach ($tasks['not-started'] as $t): ?>
            <div class="task" onclick='openTask(<?= json_encode($t, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
              <div class="task-title"><?= htmlspecialchars($t['title']) ?></div>
              <?php if (!empty($t['due_date'])): ?>
                <div class="due">Due on <?= date('d M', strtotime($t['due_date'])) ?></div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

      </div>

      <!-- IN PROGRESS -->
      <div class="column">
        <div class="column-header in-progress">In Progress</div>

        <?php if (!empty($tasks['in-progress'])): ?>
          <?php foreach ($tasks['in-progress'] as $t): ?>
            <div class="task" onclick='openTask(<?= json_encode($t, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
              <div class="task-title"><?= htmlspecialchars($t['title']) ?></div>
              <?php if (!empty($t['due_date'])): ?>
                <div class="due">Due on <?= date('d M', strtotime($t['due_date'])) ?></div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

      </div>

      <!-- COMPLETED -->
      <div class="column">
        <div class="column-header completed">Completed</div>

        <?php if (!empty($tasks['completed'])): ?>
          <?php foreach ($tasks['completed'] as $t): ?>
            <div class="task" onclick='openTask(<?= json_encode($t, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
              <div class="task-title"><?= htmlspecialchars($t['title']) ?></div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

      </div>

      <!-- OVERDUE -->
      <div class="column">
        <div class="column-header overdue">Overdue</div>

        <?php if (!empty($tasks['overdue'])): ?>
          <?php foreach ($tasks['overdue'] as $t): ?>
            <div class="task" onclick='openTask(<?= json_encode($t, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
              <div class="task-title"><?= htmlspecialchars($t['title']) ?></div>
              <div class="due">Overdue!</div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

      </div>
    </div>
  </div>

<!-- TASK DETAILS MODAL -->
<div id="taskDetails-bg" class="details-bg">
    <div class="task-details-modal">
        <h2 class="details-title">Task Details</h2>

        <div class="detail-row"><strong>Title:</strong> <span id="m_title"></span></div>
        <div class="detail-row"><strong>Description:</strong> <span id="m_description"></span></div>
        <div class="detail-row"><strong>Priority:</strong> <span id="m_priority"></span></div>
        <div class="detail-row"><strong>Category:</strong> <span id="m_category"></span></div>
        <div class="detail-row"><strong>Status:</strong> <span id="m_status"></span></div>
        <div class="detail-row"><strong>User:</strong> <span id="m_user"></span></div>
        <div class="detail-row"><strong>Due:</strong> <span id="m_due"></span></div>
        <div class="detail-row"><strong>Created:</strong> <span id="m_created"></span></div>

        <!-- Countdown -->
        <div id="countdownBox" class="countdown hidden">
            <span id="countdownLabel">Time Left:</span>
            <span id="countdown"></span>
        </div>

        <div class="modal-buttons">
    <button class="details-close-btn" onclick="closeModal()">Close</button>
    <button class="done-btn" id="doneTaskBtn">Done</button>
</div>


    </div>
</div>



 <!-- CREATE TASK MODAL -->
<div class="modal-bg create-bg" id="create-bg" style="display:none;">
    <div class="modal">
      <h3>Create Task</h3>

      <form class="create-form" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <input type="hidden" name="action" value="create_task">

        <div class="row">
          <label>Title</label>
          <input type="text" name="title" required>
        </div>

        <div class="row">
          <label>Description</label>
          <textarea name="description" rows="3" required></textarea>
        </div>

        <div class="row">
          <label>Priority</label>
          <select name="priority" required>
            <option value="" disabled selected>Select Priority</option>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
          </select>
        </div>

        <div class="row">
          <label>Category</label>
          <select name="category_id" required>
            <option value="" disabled selected>Select Category</option>
            <?php foreach ($categories as $id => $name): ?>
              <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="row">
          <label>Status</label>
          <select name="status_id" required>
            <option value="" disabled selected>Select Status</option>
            <?php foreach ($statuses as $id => $name): ?>
              <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
            <?php endforeach; ?>
            </select>
        </div>

        <div class="row">
          <label>Due Date</label>
          <input type="datetime-local" name="due_date">
        </div>

        <div class="btn-container">
          <button class="btn-cancel" type="button" onclick="closeCreateModal()">Cancel</button>
          <button type="submit">Create</button>
        </div>
      </form>
    </div>
</div>

<script src="js/taskboard.js"></script>

</body>
</html>