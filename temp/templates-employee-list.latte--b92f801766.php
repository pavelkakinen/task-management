<?php

use Latte\Runtime as LR;

/** source: /Users/pavelkakinen/icd0007/vendor/../templates/employee-list.latte */
final class Template_b92f801766 extends Latte\Runtime\Template
{
	public const Source = '/Users/pavelkakinen/icd0007/vendor/../templates/employee-list.latte';


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '<!DOCTYPE html>
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
    ';
		echo $successMessage /* line 21 */;
		echo '
    ';
		echo $employeeListHtml /* line 22 */;
		echo '

    <p><a href="?command=employee_form">Add New Employee</a></p>
</main>
</body>
</html>';
	}
}
