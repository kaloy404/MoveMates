/* ============================
   TASK DETAILS MODAL
============================ */
let currentTaskId = null;
function openTask(task) {
    let modal = document.getElementById("taskDetails-bg");
    currentTaskId = task.task_id;

    // Insert values into spans
    document.getElementById("m_title").textContent = task.title;
    document.getElementById("m_description").textContent = task.description;
    document.getElementById("m_user").textContent = task.user_name;
    document.getElementById("m_category").textContent = task.category_name;
    document.getElementById("m_status").textContent = task.status_type;
    document.getElementById("m_due").textContent = task.due_date;
    document.getElementById("m_created").textContent = task.created_at;

    // --- PRIORITY HANDLING (only once) ---
    const p = document.getElementById("m_priority");
    p.textContent = task.priority;
    p.classList.remove("high", "medium", "low");
    p.classList.add(task.priority.toLowerCase());
    // -------------------------------------

     const countdownBox = document.getElementById("countdownBox");
    // Show modal
    modal.style.display = "flex";

    // If task is completed, hide countdown
if (task.status_id == 3 || task.status_type === "Completed") {
    countdownBox.classList.add("hidden");
} else {
    countdownBox.classList.remove("hidden");
    startCountdown(task.due_date, task.status_id, task.task_id);

        }
}

function startCountdown(due, status, taskId) {
    let countdownBox = document.getElementById("countdownBox");
    let label = document.getElementById("countdownLabel");
    let timer = document.getElementById("countdown");

    // Hide if task is completed
    if (status == 3 || status == "Completed") {
        countdownBox.classList.add("hidden");
        return;
    }

    if (!due || due === "0000-00-00 00:00:00") {
        countdownBox.classList.add("hidden");
        return;
    }

    countdownBox.classList.remove("hidden");

    function update() {
        let now = new Date().getTime();
        let end = new Date(due).getTime();
        let diff = end - now;

        // If expired → move to OVERDUE column
        if (diff <= 0) {
            timer.textContent = "Expired";
            timer.style.color = "red";

            // Prevent endless loops
            countdownBox.classList.add("hidden");

            // Move to overdue (status_id = 4)
            setTaskToOverdue(taskId);

            return;
        }

        let d = Math.floor(diff / (1000 * 60 * 60 * 24));
        let h = Math.floor((diff / (1000 * 60 * 60)) % 24);
        let m = Math.floor((diff / (1000 * 60)) % 60);
        let s = Math.floor((diff / 1000) % 60);

        timer.textContent = `${d}d ${h}h ${m}m ${s}s`;

        setTimeout(update, 1000);
    }

    update();
}

function moveTaskToOverdue(taskId) {
    fetch("update_task_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "task_id=" + taskId + "&status_id=4"  // 4 = overdue
    })
    .then(res => res.text())
    .then(data => {
        console.log("Moved to overdue:", data);
        location.reload(); 
    });
}



function closeModal() {
    document.getElementById("taskDetails-bg").style.display = "none"; // <-- updated
}


/* ============================
   CREATE TASK MODAL
============================ */
function openCreateModal() {
    document.getElementById("create-bg").style.display = "flex";
}
function closeCreateModal() {
    document.getElementById("create-bg").style.display = "none";
}


/* ============================
   ESCAPE HTML (Security)
============================ */
function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}


/* ============================
   CLOSE MODALS ON BG CLICK
============================ */
document.addEventListener("DOMContentLoaded", () => {

    const modalBG = document.getElementById('taskDetails-bg');  // <-- updated
    const createBG = document.getElementById('create-bg');

    if (modalBG) {
        modalBG.addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
    }

    if (createBG) {
        createBG.addEventListener('click', function (e) {
            if (e.target === this) closeCreateModal();
        });
    }
});

document.getElementById("doneTaskBtn").addEventListener("click", function () {
    updateTaskStatusToCompleted(currentTaskId);
});

function updateTaskStatusToCompleted(taskId) {

    fetch("update_task_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "task_id=" + taskId + "&status_id=3"
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        closeModal();
        location.reload();
    });

}
