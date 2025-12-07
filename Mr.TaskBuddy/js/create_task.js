document.addEventListener("DOMContentLoaded", () => {

    const createForm = document.getElementById("createForm");
    const titleInput = document.querySelector("#title");

    if (!createForm || !titleInput) {
        console.error("Create form or title input not found.");
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

            /* -----------------------------------
               DUPLICATE TITLE HANDLING
            ----------------------------------- */
            if (data.status === "error" && data.duplicate) {

                // Remove old error state (IMPORTANT)
                titleInput.classList.remove("input-error", "shake");

                // Force reflow so animation restarts
                void titleInput.offsetWidth;

                // Add back the error styles
                titleInput.classList.add("input-error", "shake");

                // Remove only shake animation after done
                setTimeout(() => titleInput.classList.remove("shake"), 400);

                showErrorAlert(data.message);
                return;
            }

            /* -----------------------------------
               GENERAL ERRORS
            ----------------------------------- */
            if (data.status === "error") {
                showErrorAlert(data.message);
                return;
            }

            /* -----------------------------------
               SUCCESS
            ----------------------------------- */
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
