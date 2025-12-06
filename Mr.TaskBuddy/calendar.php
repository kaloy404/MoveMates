<?php
include 'db/db_connect.php';


$tasks_flat = [];

$result = $conn->query("
    SELECT title, due_date, priority
    FROM Tasks 
    WHERE user_id = $userId
    AND due_date IS NOT NULL
    AND due_date <> '0000-00-00 00:00:00'
    AND status_id IN (1,2)  -- ONLY Not Started & In Progress

");
while ($row = $result->fetch_assoc()) {
    $tasks_flat[] = $row;
}
?>

<!-- Calendar Popup -->
<div id="calendarModal" class="calendar-modal">
    <div class="calendar-box">
        <span class="close-calendar">&times;</span>

        <div class="calendar-header">
            <button id="prevMonth">&lt;</button>
            <h2 id="calendarMonth"></h2>
            <button id="nextMonth">&gt;</button>
        </div>

        <div class="calendar-weekdays">
            <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div>
            <div>Thu</div><div>Fri</div><div>Sat</div>
        </div>

        <div id="calendarDays" class="calendar-days"></div>
    </div>
</div>
