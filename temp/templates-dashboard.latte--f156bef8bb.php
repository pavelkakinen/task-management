<?php

use Latte\Runtime as LR;

/** source: /Users/pavelkakinen/icd0007/vendor/../templates/dashboard.latte */
final class Template_f156bef8bb extends Latte\Runtime\Template
{
	public const Source = '/Users/pavelkakinen/icd0007/vendor/../templates/dashboard.latte';


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '<!DOCTYPE html>
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
                    ';
		echo $employeesHtml /* line 24 */;
		echo '
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">Tasks</div>
                <div class="content-card-content">
                    ';
		echo $tasksHtml /* line 31 */;
		echo '
                </div>
            </div>
        </div>
    </main>

    <footer>
        icd0007 Sample Application
    </footer>
</div>
</body>
</html>';
	}
}
