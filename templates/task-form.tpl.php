<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $task->id ? 'Edit Task' : 'Add Task' ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="task-form-page">
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
    <?php if ($error): ?>
        <div id="error-block" class="error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= e($task->description) ?></textarea>
        </div>

        <input type="hidden" id="estimate" name="estimate" value="">

        <div>
            <label for="employeeId">Assign to Employee:</label>
            <select id="employeeId" name="employeeId">
                <option value="">-- Not assigned --</option>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?= $employee->id ?>"
                        <?= $task->employeeId == $employee->id ? 'selected' : '' ?>>
                        <?= e($employee->getFullName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="isCompleted">
                <input type="checkbox" id="isCompleted" name="isCompleted"
                    <?= $task->isCompleted ? 'checked' : '' ?>>
                Task Completed
            </label>
        </div>

        <div>
            <button name="submitButton" id="submitButton" type="submit">
                <?= $task->id ? 'Update Task' : 'Add Task' ?>
            </button>

            <?php if ($task->id): ?>
                <button type="submit" name="deleteButton" id="deleteButton" class="delete">
                    Delete Task
                </button>
            <?php endif; ?>
        </div>
    </form>

    <p><a href="?command=task_list">Back to Task List</a></p>
</main>
</body>
</html>