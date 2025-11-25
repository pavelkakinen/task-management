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
                    <?= $employeesHtml ?>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">Tasks</div>
                <div class="content-card-content">
                    <?= $tasksHtml ?>
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