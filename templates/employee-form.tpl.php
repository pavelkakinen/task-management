<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $employee->id ? 'Edit Employee' : 'Add Employee' ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="employee-form-page">
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
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName"
                   value="<?= e($employee->firstName) ?>" required>
        </div>

        <div>
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName"
                   value="<?= e($employee->lastName) ?>" required>
        </div>

        <div>
            <label for="position">Position:</label>
            <input type="text" id="position" name="position"
                   value="<?= e($employee->position) ?>">
        </div>

        <div>
            <label for="picture">Picture:</label>
            <input type="file" id="picture" name="picture" accept="image/*">
        </div>

        <div>
            <button name="submitButton" id="submitButton" type="submit">
                <?= $employee->id ? 'Update Employee' : 'Add Employee' ?>
            </button>

            <?php if ($employee->id): ?>
                <button type="submit" name="deleteButton" id="deleteButton" class="delete">
                    Delete Employee
                </button>
            <?php endif; ?>
        </div>
    </form>

    <p><a href="?command=employee_list">Back to Employee List</a></p>
</main>
</body>
</html>