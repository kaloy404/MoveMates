<!-- ============================
     TASK DETAILS MODAL
============================ -->
<!-- ============================
     TASK DETAILS MODAL
============================ -->
<div id="taskDetails-bg" class="details-bg" style="display:none;">
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
             <!-- Dynamic Buttons Section (Start / Done) -->
             <div id="modalActionButtons" ></div>
        </div>
    <img id="statusStamp" class="status-stamp" style="display:none;">
    </div>
</div>



<!-- ============================
     CREATE TASK MODAL
============================ -->
<div class="modal-bg create-bg" id="create-bg" style="display:none;">
    <div class="modal">
        <h3>Create Task</h3>

        <form class="create-form" id="createForm">
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
