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
        <a href="index.php">Dashboard</a> |
        <a href="employee-list.php">Employees</a> |
        <a href="employee-form.php">Add Employee</a> |
        <a href="task-list.php">Tasks</a> |
        <a href="task-form.php">Add Task</a>
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
                        <?php if ($task['employeeId']): ?>
                            <small>Assigned to: <?= htmlspecialchars($task['firstName'] . ' ' . $task['lastName']) ?></small>
                        <?php else: ?>
                            <small>Not assigned</small>
                        <?php endif; ?>
                        <small>Status: <?= getTaskState($task) ?></small>
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