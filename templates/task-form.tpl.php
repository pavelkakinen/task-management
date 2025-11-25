<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
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
    <?= $errorMessage ?>
    <?= $formHtml ?>

    <p><a href="?command=task_list">Back to Task List</a></p>
</main>
</body>
</html>