<?php

use Latte\Runtime as LR;

/** source: /Users/pavelkakinen/icd0007/vendor/../templates/employee-form.latte */
final class Template_f3d90a14aa extends Latte\Runtime\Template
{
	public const Source = '/Users/pavelkakinen/icd0007/vendor/../templates/employee-form.latte';


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>';
		echo $pageTitle /* line 6 */;
		echo '</title>
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
    ';
		echo $errorMessage /* line 21 */;
		echo '
    ';
		echo $formHtml /* line 22 */;
		echo '

    <p><a href="?command=employee_list">Back to Employee List</a></p>
</main>
</body>
</html>';
	}
}
