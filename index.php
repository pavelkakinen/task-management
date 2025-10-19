<?php
require_once 'functions.php';

$employees = getEmployees();
$tasks = getTasks();
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
        <a href="index.php" id="dashboard-link">Dashboard</a> |
        <a href="employee-list.php" id="employee-list-link">Employees</a> |
        <a href="employee-form.php" id="employee-form-link">Add Employee</a> |
        <a href="task-list.php" id="task-list-link">Tasks</a> |
        <a href="task-form.php" id="task-form-link">Add Task</a>
    </nav>

    <main id="dashboard-page">
        <div id="dash-layout">
            <div class="content-card">
                <div class="content-card-header">Employees</div>
                <div class="content-card-content">
                    <?php foreach ($employees as $employee): ?>
                        <div class="employee-item">
                            <span class="name"><?= htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']) ?></span>
                            <br><span class="position"><?= htmlspecialchars($employee['position']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">Tasks</div>
                <div class="content-card-content">
                    <?php foreach ($tasks as $task): ?>
                        <div class="task">
                            <div class="title">
                                <div><?= htmlspecialchars($task['description']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
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