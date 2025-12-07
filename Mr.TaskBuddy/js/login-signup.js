const container = document.getElementById('container');
const switchBtn = document.getElementById('switch-btn');
const title = document.getElementById('form-title');
const nameField = document.getElementById('nameField');
const mainForm = document.getElementById('mainForm');
const submitBtn = document.getElementById("submitBtn");
const forgotLink = document.getElementById("openForgot");

let signupMode = false;

/* ==========================================================
   SWITCH LOGIN / SIGNUP MODE
========================================================== */
switchBtn.addEventListener('click', () => {
  signupMode = !signupMode;
  container.classList.toggle('signup-mode');

  title.textContent = signupMode ? 'Sign Up' : 'Login';
  switchBtn.textContent = signupMode ? 'Go to Login' : 'Go to Signup';

  nameField.style.display = signupMode ? 'block' : 'none';
  forgotLink.style.display = signupMode ? 'none' : 'block';
  mainForm.action = signupMode ? "signup_process.php" : "login_process.php";
  mainForm.reset();
});
  


/* ==========================================================
   ERROR EFFECT (RED BORDER + SHAKE + REMOVE AFTER 1.5 SEC)
========================================================== */
function addErrorEffect(inputs) {
    inputs.forEach(input => {
        input.classList.add("input-error", "shake");

        setTimeout(() => input.classList.remove("shake"), 500);
        setTimeout(() => input.classList.remove("input-error"), 1500);
    });
}

/* ==========================================================
   GENERIC FIELD VALIDATION (ONLY CHECK VISIBLE INPUTS)
========================================================== */
function validateFields() {
    let valid = true;

    document.querySelectorAll("#mainForm input").forEach(input => {
        input.classList.remove("input-error", "shake");

        if (input.offsetParent !== null && input.value.trim() === "") {
            addErrorEffect([input]);
            valid = false;
        }
    });

    return valid;
}

/* ==========================================================
   SEPARATE SUCCESS & ERROR ALERTS
========================================================== */
function showSuccess(message) {
    let alertBox = document.createElement("div");
    alertBox.className = "dropdown-alert success-alert";
    alertBox.innerText = message;

    document.body.appendChild(alertBox);

    setTimeout(() => alertBox.classList.add("show"), 10);
    setTimeout(() => {
        alertBox.classList.remove("show");
        setTimeout(() => alertBox.remove(), 300);
    }, 3000);
}

function showError(message) {
    let alertBox = document.createElement("div");
    alertBox.className = "dropdown-alert error-alert";
    alertBox.innerText = message;

    document.body.appendChild(alertBox);

    setTimeout(() => alertBox.classList.add("show"), 10);
    setTimeout(() => {
        alertBox.classList.remove("show");
        setTimeout(() => alertBox.remove(), 300);
    }, 1500);
}

/* ==========================================================
   EMAIL TYPO DETECTION HELPERS
========================================================== */
function levenshteinDistance(a, b) {
    const matrix = [];

    for (let i = 0; i <= b.length; i++) matrix[i] = [i];
    for (let j = 0; j <= a.length; j++) matrix[0][j] = j;

    for (let i = 1; i <= b.length; i++) {
        for (let j = 1; j <= a.length; j++) {
            matrix[i][j] = Math.min(
                matrix[i - 1][j] + 1,
                matrix[i][j - 1] + 1,
                matrix[i - 1][j - 1] + (a[j - 1] === b[i - 1] ? 0 : 1)
            );
        }
    }
    return matrix[b.length][a.length];
}

// ------------------------------
// EMAIL DOMAIN VALIDATION
// ------------------------------
function hasEmailTypo(email) {
    const allowedDomains = ["gmail.com", "yahoo.com", "outlook.com", "hotmail.com"];

    let domain = email.split("@")[1];
    if (!domain) return true;

    domain = domain.toLowerCase();

    // Exact match = valid
    if (allowedDomains.includes(domain)) {
        return false;
    }

    // Typo near an allowed domain (distance ≤ 2)
    return allowedDomains.some(correct =>
        levenshteinDistance(domain, correct) <= 2
    );
}



/* ==========================================================
   REAL-TIME EMAIL WARNING
========================================================== */
const emailInput = document.getElementById("email");

emailInput.addEventListener("input", function () {
    let value = this.value.trim();
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    this.classList.remove("input-error");

    if (value !== "") {
       // Only validate AFTER user types a full domain (has @ and a dot)
            if (value.includes("@")) {
            let parts = value.split("@");

            if (parts[1].includes(".")) {
                // FULL EMAIL → check domain typo
            if (!emailPattern.test(value) || hasEmailTypo(value)) {
            this.classList.add("input-error");
        }
    }
}
                    }
});


/* ==========================================================
   AJAX SIGNUP REQUEST + SPECIFIC VALIDATION
========================================================== */
function submitSignup() {

    let nameValue = nameField.value.trim();
    let emailValue = emailInput.value.trim();
    let passValue = password.value.trim();

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // EMPTY FIELDS
    if (!validateFields()){

        showError("All fields are required.");
        addErrorEffect([emailInput, password, nameField]);
        return;
    } 

    // EMAIL FORMAT CHECK
    if (!emailPattern.test(emailValue)) {
        showError("Please enter a valid email address.");
        addErrorEffect([emailInput]);
        return;
    }

    // EMAIL DOMAIN VALIDATION
    if (hasEmailTypo(emailValue)) {
        showError("Unsupported or invalid email domain.");
        addErrorEffect([emailInput]);
        return;
    }


    // PASSWORD LENGTH CHECK
    if (passValue.length < 6) {
        showError("Password must be at least 6 characters.");
        addErrorEffect([password]);
        return;
    }

    // --- SEND REQUEST ---
    const formData = new FormData(mainForm);

    fetch("signup_process.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (data.status === "success") {
            showSuccess(data.message);
            mainForm.reset();
        } else {
            showError(data.message);
            addErrorEffect([emailInput]);
        }
    })
    .catch(() => showError("Server Error"));
}

/* ==========================================================
   AJAX LOGIN REQUEST
========================================================== */
function submitLogin() {

    if (!validateFields()){

        showError("All fields are required.");
        addErrorEffect([emailInput, password]);
        return;
    } 

    const formData = new FormData(mainForm);

    fetch("login_process.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {

        if (data.trim() === "success") {
            window.location.href = "dashboard.php";
        }

        else if (data.trim() === "invalid") {
            showError("Invalid email or password.");

            addErrorEffect([
                document.getElementById("email"),
                document.getElementById("password")
            ]);
        }

        else {
            showError("Error: " + data);
        }
    });
}

/* ==========================================================
   SUBMIT BUTTON HANDLER
========================================================== */
submitBtn.addEventListener("click", function (e) {
    e.preventDefault();
    signupMode ? submitSignup() : submitLogin();
});

/* ==========================================================
   FORGOT PASSWORD MODAL OPEN / CLOSE
========================================================== */       
const openForgot = document.getElementById("openForgot");
const closeForgot = document.getElementById("forgotClose");

openForgot.addEventListener("click", (e) => {
    e.preventDefault();
    mainForm.reset();
    forgotModal.style.display = "flex";  // SHOW MODAL
});

closeForgot.addEventListener("click", () => {
    forgotModal.style.display = "none";  // HIDE MODAL
    forgotModal.querySelectorAll("input").forEach(input => input.value = "");
});


/* ==========================================================
   FORGOT PASSWORD SUBMIT
========================================================== */
document.getElementById("forgotSubmit").addEventListener("click", () => {

    let email = document.getElementById("forgotEmail").value.trim();
    let newPass = document.getElementById("forgotNew").value.trim();
    let confirmPass = document.getElementById("forgotConfirm").value.trim();

    if (email === "" || newPass === "" || confirmPass === "") {
        showError("All fields are required.");
        addErrorEffect([
            document.getElementById("forgotEmail"),
            document.getElementById("forgotNew"),
            document.getElementById("forgotConfirm")
            ]);

        return;
    }

    if (newPass !== confirmPass) {
        showError("Passwords do not match.");
        addErrorEffect([
            document.getElementById("forgotNew"),
            document.getElementById("forgotConfirm")
            ]);
        return;
    }

    if (newPass.length < 6) {
        showError("Password must be at least 6 characters.");
        addErrorEffect([
            document.getElementById("forgotNew"),
            document.getElementById("forgotConfirm")
            ]);
        return;
    }

    let fd = new FormData();
    fd.append("email", email);
    fd.append("password", newPass);

    fetch("forgot-process.php", {
        method: "POST",
        body: fd
    })
    .then(res => res.json())
    .then(data => {

        if (data.status === "success") {
            showSuccess(data.message);
            forgotModal.style.display = "none";
        } else {
            showError(data.message);
              if (data.message.toLowerCase().includes("email")) {
                addErrorEffect([document.getElementById("forgotEmail")]);
        }
    }       
    })
    .catch(() => {
        showError("Server error.");
    });
});

/* ==========================================================
   SHOW / HIDE PASSWORD
========================================================== */
document.querySelectorAll("#togglePassword").forEach(icon => {
    icon.addEventListener("click", () => {
        const input = icon.previousElementSibling;

        input.type = input.type === "password" ? "text" : "password";

        icon.textContent = input.type === "password" ? "🙈" : "👁️";
    });
});
