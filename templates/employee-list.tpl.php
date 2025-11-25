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

    <h2>Employees (<?= count($employees) ?>)</h2>

    <?php if (count($employees) === 0): ?>
        <p>No employees found.</p>
    <?php else: ?>
        <ul class="employee-list">
            <?php foreach ($employees as $employee): ?>
                <li>
                    <div data-employee-id="<?= $employee->id ?>">
                        <?= e($employee->getFullName()) ?>
                    </div>
                    - <?= e($employee->position) ?>
                    <a href="?command=employee_form&id=<?= $employee->id ?>"
                       id="employee-edit-link-<?= $employee->id ?>" class="edit-link">Edit</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p><a href="?command=employee_form">Add New Employee</a></p>
</main>
</body>
</html>