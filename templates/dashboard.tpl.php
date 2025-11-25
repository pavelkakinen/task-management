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
        <a href="?command=dashboard" id="dashboard-link">Dashboard</a> |
        <a href="?command=employee_list" id="employee-list-link">Employees</a> |
        <a href="?command=employee_form" id="employee-form-link">Add Employee</a> |
        <a href="?command=task_list" id="task-list-link">Tasks</a> |
        <a href="?command=task_form" id="task-form-link">Add Task</a>
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
                                <span class="name"><?= e($employee->getFullName()) ?></span>
                                <br><span class="position"><?= e($employee->position) ?></span>
                                <br><span class="task-count">Tasks: <span id="employee-task-count-<?= $employee->id ?>"><?= $employee->taskCount ?></span></span>
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
                                    <div><?= e($task->description) ?></div>
                                    <?php if ($task->employeeId): ?>
                                        <small>Assigned to: <?= e($task->getEmployeeFullName()) ?></small>
                                    <?php endif; ?>
                                    <div>
                                        <strong>State:
                                            <span id="task-state-<?= $task->id ?>">
                                                <?= $task->getState() ?>
                                            </span>
                                        </strong>
                                    </div>
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