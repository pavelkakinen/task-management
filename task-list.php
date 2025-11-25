<?php
require_once 'functions.php';

$tasks = getTasksWithEmployees();
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="task-list-page">
<header>
    <nav>
        <a href="?page=index.php" id="dashboard-link">Dashboard</a>
        <a href="?page=employee-list" id="employee-list-link">Employees</a>
        <a href="?page=employee-form" id="employee-form-link">Add Employee</a>
        <a href="?page=task-list" id="task-list-link">Tasks</a>
        <a href="?page=task-form" id="task-form-link">Add Task</a>
    </nav>
</header>

<main>
    <?php if ($success): ?>
        <div id="message-block" class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <h2>Tasks (<?= count($tasks) ?>)</h2>

    <?php if (empty($tasks)): ?>
        <p>No tasks found.</p>
    <?php else: ?>
        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
                <li>
                    <div>
                        <div data-task-id="<?= $task['id'] ?>">
                            <?= htmlspecialchars($task['description']) ?>
                        </div>
                        <small>Status: <span id="task-state-<?= $task['id'] ?>"><?= getTaskState($task) ?></span></small>
                    </div>
                    <a href="task-form.php?id=<?= $task['id'] ?>"
                       id="task-edit-link-<?= $task['id'] ?>" class="edit-link">Edit</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p><a href="task-form.php">Add New Task</a></p>
</main>
</body>
</html>