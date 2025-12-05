/* ============================================
   GLOBAL VARIABLES
============================================ */
let currentTask = null;
let countdownTimer = null;

/* ============================================
   OPEN TASK MODAL
============================================ */
function openTask(task) {
    currentTask = task;

    const modal = document.getElementById("taskDetails-bg");
    modal.style.display = "flex";

    // Fill modal
    document.getElementById("m_title").textContent = task.title;
    document.getElementById("m_description").textContent = task.description;
    document.getElementById("m_user").textContent = task.user_name;
    document.getElementById("m_category").textContent = task.category_name;
    document.getElementById("m_status").textContent = task.status_type;
    document.getElementById("m_due").textContent = task.due_date ?? "None";
    document.getElementById("m_created").textContent = task.created_at;

    // PRIORITY
    const p = document.getElementById("m_priority");
    p.textContent = task.priority;
    p.className = "";
    p.classList.add(task.priority.toLowerCase());

    // ACTION BUTTONS
    updateModalButtons(task);

    // COUNTDOWN (only if due date exists)
    startCountdown(task);
}

/* ============================================
   SHOW CORRECT BUTTON INSIDE MODAL
============================================ */
function updateModalButtons(task) {
    const container = document.getElementById("modalActionButtons");
    container.innerHTML = "";

    if (task.status_id == 3) return; // Completed
    if (task.status_id == 4) return; // Overdue

    if (task.status_id == 1) {
        container.innerHTML = `
            <button class="start-btn" onclick="moveToInProgress(${task.task_id})">
                Start Task
            </button>`;
        return;
    }

    if (task.status_id == 2) {
        container.innerHTML = `
            <button class="done-btn" onclick="markAsDone(${task.task_id})">
                Mark as Done
            </button>`;
    }
}

/* ============================================
   MOVE → IN PROGRESS
============================================ */
function moveToInProgress(taskId) {
    updateStatus(taskId, 2, "in-progress");
}

/* ============================================
   MOVE → COMPLETED
============================================ */
function markAsDone(taskId) {
    updateStatus(taskId, 3, "completed");
}

/* ============================================
   GENERIC STATUS UPDATE FUNCTION
============================================ */
function updateStatus(taskId, newStatus, targetColumnClass) {
    fetch("update_task_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "task_id=" + taskId + "&status_id=" + newStatus
    })
    .then(r => r.text())
    .then(() => {
        closeModal();
        location.reload(); // ensure UI is correct
    });
}

/* ============================================
   AUTO MOVE TO OVERDUE
============================================ */
function autoMoveToOverdue(taskId) {
    fetch("update_task_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "task_id=" + taskId + "&status_id=4"
    }).then(() => location.reload());
}

/* ============================================
   COUNTDOWN TIMER (Due Date Optional)
============================================ */
function startCountdown(task) {
    const box = document.getElementById("countdownBox");
    const timer = document.getElementById("countdown");

    clearTimeout(countdownTimer);

    // No due date → no countdown
    if (!task.due_date || task.due_date === "0000-00-00 00:00:00") {
        box.classList.add("hidden");
        return;
    }

    // Completed or manually overdue → hide countdown
    if (task.status_id == 3 || task.status_id == 4) {
        box.classList.add("hidden");
        return;
    }

    box.classList.remove("hidden");

    function tick() {
        const now = Date.now();
        const due = new Date(task.due_date).getTime();
        
        if (isNaN(due)) {
            box.classList.add("hidden");
            return;
        }

        const diff = due - now;

        if (diff <= 0) {
            timer.textContent = "Expired";
            autoMoveToOverdue(task.task_id);
            return;
        }

        const d = Math.floor(diff / 86400000);
        const h = Math.floor((diff / 3600000) % 24);
        const m = Math.floor((diff / 60000) % 60);
        const s = Math.floor((diff / 1000) % 60);

        timer.textContent = `${d}d ${h}h ${m}m ${s}s`;

        countdownTimer = setTimeout(tick, 1000);
    }

    tick();
}

/* ============================================
   CLOSE MODALS
============================================ */
function closeModal() {
    document.getElementById("taskDetails-bg").style.display = "none";
}

function openCreateModal() {
    document.getElementById("create-bg").style.display = "flex";
}
function closeCreateModal() {
    document.getElementById("create-bg").style.display = "none";
}

/* ============================================
   BACKDROP CLOSE
============================================ */
document.addEventListener("click", (e) => {
    if (e.target.id === "taskDetails-bg") closeModal();
    if (e.target.id === "create-bg") closeCreateModal();
});
