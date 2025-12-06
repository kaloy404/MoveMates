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
    updateStatus(taskId, 2, "in-progress");
    showSuccessAlert("Task Started!!!");
    setTimeout(() => {
        location.reload();
    }, 3000); 
}
/* ============================================
   MOVE → COMPLETED
============================================ */
function markAsDone(taskId) {
    updateStatus(taskId, 3, "completed");
    showSuccessAlert("Task Completed");
    setTimeout(() => {
        location.reload();
    }, 3000); 
    
}
/* ============================================
   AUTO MOVE TO OVERDUE
============================================ */
function autoMoveToOverdue(taskId) {
    updateStatus(taskId, 4); 
    showErrorAlert("1 Task Overdue");
    setTimeout(() => {
        location.reload();
    }, 3000); 
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

        // 1️⃣ Find the task card in the DOM
        const card = document.querySelector(`.task[data-task-id='${taskId}']`);

        if (!card) {
            console.error("Task card not found:", taskId);
            return;
        }

        // 2️⃣ Find target column — FIXED
        const targetColumn = document.querySelector(`.column[data-status='${newStatus}']`);

        if (!targetColumn) {
            console.error("Target column not found:", newStatus);
            return;
        }

        // 3️⃣ Move card into the column
        targetColumn.appendChild(card);

        // 🔥 Update the stored task object inside the card
        let taskData = JSON.parse(card.dataset.task);
        taskData.status_id = newStatus;
        card.dataset.task = JSON.stringify(taskData);


        // 4️⃣ Update due date display
        const due = card.querySelector(".due");

        if (newStatus == 3) {
            if (due) due.remove(); // Completed → no due date
        }
        else if (newStatus == 4) {
            if (due) {
                due.classList.add("overdue");
                due.innerText = "Overdue";
            } else {
                card.insertAdjacentHTML("beforeend", `<div class="due overdue">Overdue!</div>`);
            }
        }
        closeModal();   
        // 5️⃣ Animation
        card.classList.add("moved");
        setTimeout(() => card.classList.remove("moved"), 300);
    });
}

function addTaskToBoard(task) {

    const column = document.querySelector(`[data-status='${task.status_id}']`);
    if (!column) return;

    const card = document.createElement("div");
    card.classList.add("task");
    card.setAttribute("data-task-id", task.task_id);

    // 🔥 STORE FULL TASK FOR THE MODAL
    card.dataset.task = JSON.stringify(task);

    let dueHTML = "";

    if (task.status_id == 3) {
        dueHTML = "";
    } 
    else if (task.status_id == 4) {
        dueHTML = `<div class="due overdue">Overdue!</div>`;
    } 
    else {
        dueHTML = task.due_date ? `<div class="due">Due on ${task.due_date}</div>` : "";
    }

    card.innerHTML = `
        <div class="task-title">${task.title}</div>
        ${dueHTML}
    `;
    column.appendChild(card);
   

}

document.addEventListener("DOMContentLoaded", () => {
    const createForm = document.getElementById("createForm");

    if (!createForm) {
        console.error("Create Form not found.");
        return;
    }

    createForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(createForm);

        fetch("create_task_process.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            console.log("Create Task Response:", data);

            if (data.status === "success") {

                addTaskToBoard(data.task);
                showSuccessAlert("Successfully created a task!");
                closeCreateModal(); 
                setTimeout(() => {
                   location.reload();
                      }, 3000); 
            } else {
                showErrorAlert(data.message || "Failed to create task.");
            }
        })
        .catch(err => {
            console.error("Create Task Error:", err);
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

