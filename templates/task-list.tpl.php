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
        <a href="?command=dashboard" id="dashboard-link">Dashboard</a> |
        <a href="?command=employee_list" id="employee-list-link">Employees</a> |
        <a href="?command=employee_form" id="employee-form-link">Add Employee</a> |
        <a href="?command=task_list" id="task-list-link">Tasks</a> |
        <a href="?command=task_form" id="task-form-link">Add Task</a>
    </nav>
</header>

<main>
    <?php if ($success): ?>
        <div id="message-block" class="success"><?= e($success) ?></div>
    <?php endif; ?>

    <h2>Tasks (<?= count($tasks) ?>)</h2>

    <?php if (empty($tasks)): ?>
        <p>No tasks found.</p>
    <?php else: ?>
        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
                <li>
                    <div>
                        <div data-task-id="<?= $task->id ?>">
                            <?= e($task->description) ?>
                        </div>
                        <?php if ($task->employeeId): ?>
                            <small>Assigned to: <?= e($task->getEmployeeFullName()) ?></small>
                        <?php else: ?>
                            <small>Not assigned</small>
                        <?php endif; ?>
                        <small>Status: <span id="task-state-<?= $task->id ?>"><?= $task->getState() ?></span></small>
                    </div>
                    <a href="?command=task_form&id=<?= $task->id ?>"
                       id="task-edit-link-<?= $task->id ?>" class="edit-link">Edit</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p><a href="?command=task_form">Add New Task</a></p>
</main>
</body>
</html>