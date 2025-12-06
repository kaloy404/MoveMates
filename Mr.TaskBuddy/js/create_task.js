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

                showSuccessAlert("Task Created!");

                // Close modal
                closeCreateModal();
                

                // Reload page to show correct tasks
                setTimeout(() => location.reload(), 600);

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