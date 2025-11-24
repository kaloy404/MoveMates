document.addEventListener("DOMContentLoaded", () => {
    const statusText = document.getElementById("status-text");
    const form = document.getElementById("loginForm");
    const loading = document.getElementById("loading");


    
    // ---- after redirect ----
    if (loginStatus === "success") {
        // Step 1: Show "Processing..." immediately
        statusText.innerText = "Processing...";

        // Step 2: After 3 seconds, show "Access Granted"
        setTimeout(() => {
            statusText.innerText = "Access Granted";

            // Step 3: After 2 seconds, proceed to index.php
            setTimeout(() => {
                window.location.href = "index.php";
            }, 2000);

        }, 3000);

    } else if (loginStatus === "error") {
        // Step 1: Show "Processing..." immediately
        statusText.innerText = "Processing...";
        loading.style.display = "none";
        // Step 2: After 3 seconds, show "Access Denied" + shake + red border
        setTimeout(() => {
            statusText.innerText = "Access Denied";
            form.classList.add("shake");

            const inputs = document.querySelectorAll("input[type='text'], input[type='password']");
            inputs.forEach(i => i.classList.add("input-error"));

            // Step 3: After 1 second, reset form appearance
            setTimeout(() => {
                form.classList.remove("shake");
                inputs.forEach(i => i.classList.remove("input-error"));
                statusText.innerText = "Initiating Access...";
            }, 1000);

        }, 3000);
    }
});
