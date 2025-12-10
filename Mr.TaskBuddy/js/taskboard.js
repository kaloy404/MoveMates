
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
    const stamp = document.getElementById("statusStamp");

    container.innerHTML = "";
    stamp.style.display = "none";

    // COMPLETED
    if (task.status_id == 3) {
        stamp.src = "image/4.png";
        stamp.style.display = "block";
        return;
    }

    // OVERDUE
    if (task.status_id == 4) {
        stamp.src = "image/3.png";
        stamp.style.display = "block";
        return;
    }

    // NOT STARTED
    if (task.status_id == 1) {
        container.innerHTML = `
            <button class="start-btn" onclick="moveToInProgress(${task.task_id})">
                Start Task
            </button>
        `;
        return;
    }

    // IN PROGRESS
    if (task.status_id == 2) {
        container.innerHTML = `
            <button class="done-btn" onclick="markAsDone(${task.task_id})">
                Mark as Done
            </button>
        `;
    }
}

/* ============================================
   MOVE → IN PROGRESS
============================================ */
function moveToInProgress(taskId) {
    updateStatus(taskId, 2, "in-progress", "Task started.");
    setTimeout(() => {
                location.reload();
                 }, 3000);
}

/* ============================================
   MOVE → COMPLETED
============================================ */
function markAsDone(taskId) {
    updateStatus(taskId, 3, "completed", "Task marked as done!");
    setTimeout(() => {
                location.reload();
                 }, 3000);
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
   GENERIC STATUS UPDATE FUNCTION
============================================ */
function updateStatus(taskId, newStatus) {
    fetch("update_task_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "task_id=" + taskId + "&status_id=" + newStatus
    })
    .then(r => r.text())
    .then(() => {
        closeModal();

        const taskCard = document.querySelector(`.task[data-task-id='${taskId}']`);
        const columns = document.querySelectorAll(".column");

        if (!taskCard) return;

        // Fade out animation
        taskCard.style.transition = "0.3s";
        taskCard.style.opacity = "0";

        setTimeout(() => {
            // Remove the card from current column
            taskCard.remove();

            // Insert card to the correct column
            if (newStatus == 1) columns[0].appendChild(taskCard);  // Not started
            if (newStatus == 2) columns[1].appendChild(taskCard);  // In progress
            if (newStatus == 3) columns[2].appendChild(taskCard);  // Completed

            // Fade in animation
            taskCard.style.opacity = "1";

            // Success Alerts
            if (newStatus == 2) showSuccessAlert("Task Started!");
            if (newStatus == 3) showSuccessAlert("Task Completed!");

        }, 250);
    });
}



document.addEventListener("DOMContentLoaded", () => {
    const createForm = document.getElementById("createForm");
    if (!createForm) return;

    createForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(createForm);

        fetch("create_task_process.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === "error" && data.duplicate) {
                showErrorAlert(data.message);
                return;
            }

            if (data.status === "error") {
                showErrorAlert(data.message);
                return;
            }

            if (data.status === "success") {
                showSuccessAlert("Task Created!");
                closeCreateModal();
                setTimeout(() => location.reload(), 600);
            }

        })
        .catch(err => {
            console.error(err);
            showErrorAlert("Something went wrong.");
        });
    });
});

function showSuccessAlert(message) {
    let alertBox = document.createElement("div");
    alertBox.className = "dropdown-alert success-alert";
    alertBox.innerText = message;

    document.body.appendChild(alertBox);

    requestAnimationFrame(() => alertBox.classList.add("show"));

    setTimeout(() => {
        alertBox.classList.remove("show");
        setTimeout(() => alertBox.remove(), 300);
    }, 2500);
}

function showErrorAlert(message) {
    let alertBox = document.createElement("div");
    alertBox.className = "dropdown-alert error-alert";
    alertBox.innerText = message;

    document.body.appendChild(alertBox);

    requestAnimationFrame(() => alertBox.classList.add("show"));

    setTimeout(() => {
        alertBox.classList.remove("show");
        setTimeout(() => alertBox.remove(), 300);
    }, 2000);
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

/* ============================================
   FILTER ACCORDING TO THEIR CATEGORY
============================================ */
function toggleCategoryFilter() {
    const filterBox = document.getElementById("categoryFilter");
    filterBox.style.display = filterBox.style.display === "block" ? "none" : "block";
}

function filterByCategory() {
    let selected = document.getElementById("categorySelect").value;
    let tasks = document.querySelectorAll(".task");

    tasks.forEach(task => {
        let taskData = JSON.parse(task.dataset.task);
        let taskCategory = taskData.category_id;

        if (selected === "all" || selected == taskCategory) {
            task.style.display = "block";
        } else {
            task.style.display = "none";
        }
    });
}

document.querySelector(".calendar-btn").onclick = () => {
    document.getElementById("calendarModal").style.display = "flex";
};

document.querySelector(".close-calendar").onclick = () => {
    document.getElementById("calendarModal").style.display = "none";
};

window.onclick = (e) => {
    if (e.target.id === "calendarModal") {
        document.getElementById("calendarModal").style.display = "none";
    }
};

/* ---------------------------
   CALENDAR FUNCTION
--------------------------- */

/* --------------------------
   OPEN CALENDAR
-------------------------- */
document.querySelector(".calendar-btn").onclick = () => {
    document.getElementById("calendarModal").style.display = "flex";
};

let date = new Date();

/* --------------------------
   PRIORITY CLASS MAP
-------------------------- */
function getPriorityClass(priority) {
    if (!priority) return "";
    switch (priority.toLowerCase()) {
        case "high": return "high-task";
        case "medium": return "medium-task";
        case "low": return "low-task";
        default: return "";
    }
}

/* --------------------------
   LOAD CALENDAR UI
-------------------------- */
function loadCalendar(tasks) {
    const monthNames = [
        "January","February","March","April","May","June",
        "July","August","September","October","November","December"
    ];

    const year = date.getFullYear();
    const month = date.getMonth();
    const today = new Date();

    document.getElementById("calendarMonth").innerText =
        `${monthNames[month]} ${year}`;

    const firstDay = new Date(year, month, 1).getDay();
    const totalDays = new Date(year, month + 1, 0).getDate();

    let daysHtml = "";

    for (let i = 0; i < firstDay; i++) daysHtml += "<div></div>";

    for (let day = 1; day <= totalDays; day++) {

        const formatted = `${year}-${String(month + 1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;

        const todaysTasks = tasks.filter(t => t.due_date.startsWith(formatted));

        let boxClass = "";
        let dotsHtml = "";

        if (todaysTasks.length > 0) {

            const priorityOrder = { High: 3, Medium: 2, Low: 1 };

            const priorities = [...new Set(todaysTasks.map(t => t.priority))];

            priorities.sort((a, b) => priorityOrder[b] - priorityOrder[a]);

            const highest = priorities[0];
            const second = priorities[1];
            const third = priorities[2];

            if (highest === "High") boxClass = "high-task";
            else if (highest === "Medium") boxClass = "medium-task";
            else boxClass = "low-task";
            // Build dots inside a container
let dotItems = [];

if (second) {
    let dotClass =
        second === "High" ? "dot-high" :
        second === "Medium" ? "dot-medium" :
        "dot-low";
    dotItems.push(`<span class="task-dot ${dotClass}"></span>`);
}

if (third) {
    let dotClass =
        third === "High" ? "dot-high" :
        third === "Medium" ? "dot-medium" :
        "dot-low";
    dotItems.push(`<span class="task-dot ${dotClass}"></span>`);
}

if (dotItems.length > 0) {
    // Add .single class when only 1 dot
    const dotClassName = dotItems.length === 1 ? "dot-container single" : "dot-container";
    dotsHtml = `<div class="${dotClassName}">${dotItems.join("")}</div>`;
}

        }

        const isToday =
            year === today.getFullYear() &&
            month === today.getMonth() &&
            day === today.getDate();

        daysHtml += `
            <div class="${boxClass} ${isToday ? "today" : ""}">
        <span class="day-number">${day}</span>
        ${dotsHtml}
    </div>
        `;
    }

    document.getElementById("calendarDays").innerHTML = daysHtml;
}


/* --------------------------
   NEXT / PREV MONTH BUTTONS
-------------------------- */
document.getElementById("prevMonth").onclick = () => {
    date.setMonth(date.getMonth() - 1);
    loadCalendar(tasksFromPHP);
};

document.getElementById("nextMonth").onclick = () => {
    date.setMonth(date.getMonth() + 1);
    loadCalendar(tasksFromPHP);
};

/* --------------------------
   INITIAL CALENDAR LOAD
-------------------------- */
loadCalendar(tasksFromPHP);

/* --------------------------
   CLOSE ON BACKDROP CLICK
-------------------------- */
document.getElementById("calendarModal").addEventListener("click", (e) => {
    if (e.target.id === "calendarModal") {
        e.target.style.display = "none";
    }
});

