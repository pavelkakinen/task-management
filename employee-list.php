<?php
require_once 'functions.php';

$employees = getEmployees();
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="employee-list-page">
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

    <h2>Employees (<?= count($employees) ?>)</h2>

    <?php if (count($employees) === 0): ?>
        <p>No employees found.</p>
    <?php else: ?>
        <ul class="employee-list">
            <?php foreach ($employees as $employee): ?>
                <li>
                    <div data-employee-id="<?= $employee['id'] ?>">
                        <?= htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']) ?>
                    </div>
                    - <?= htmlspecialchars($employee['position']) ?>
                    <a href="employee-form.php?id=<?= $employee['id'] ?>"
                       id="employee-edit-link-<?= $employee['id'] ?>" class="edit-link">Edit</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p><a href="employee-form.php">Add New Employee</a></p>
</main>
</body>
</html>