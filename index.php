<?php
require_once 'functions.php';

$employees = getEmployeesWithTaskCount();
$tasks = getTasksWithEmployees();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="dashboard-page">
<div id="root">
    <nav>
        <a href="index.php" id="dashboard-link">Dashboard</a>
        <a href="employee-list.php" id="employee-list-link">Employees</a>
        <a href="employee-form.php" id="employee-form-link">Add Employee</a>
        <a href="task-list.php" id="task-list-link">Tasks</a>
        <a href="task-form.php" id="task-form-link">Add Task</a>
    </nav>

    <main id="dashboard-page">
        <div id="dash-layout">
            <div class="content-card">
                <div class="content-card-header">Employees</div>
                <div class="content-card-content">
                    <?php if (empty($employees)): ?>
                        <p>No employees found.</p>
                    <?php else: ?>
                        <?php foreach ($employees as $employee): ?>
                            <div class="employee-item">
                                <span class="name"><?= htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']) ?></span>
                                <br><span class="position"><?= htmlspecialchars($employee['position']) ?></span>
                                <br><span class="task-count">Tasks: <span id="employee-task-count-<?= $employee['id'] ?>"><?= $employee['taskCount'] ?></span></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">Tasks</div>
                <div class="content-card-content">
                    <?php if (empty($tasks)): ?>
                        <p>No tasks found.</p>
                    <?php else: ?>
                        <?php foreach ($tasks as $task): ?>
                            <div class="task">
                                <div class="title">
                                    <div><?= htmlspecialchars($task['description']) ?></div>
                                    <?php if ($task['employeeId']): ?>
                                        <small>Assigned to: <?= htmlspecialchars($task['firstName'] . ' ' . $task['lastName']) ?></small>
                                    <?php endif; ?>
                                    <div><strong>State: <span id="task-state-<?= $task['id'] ?>"><?= getTaskState($task) ?></span></strong></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
        icd0007 Sample Application
    </footer>
</div>
</body>
</html>