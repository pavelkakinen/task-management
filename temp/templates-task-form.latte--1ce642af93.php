<?php

use Latte\Runtime as LR;

/** source: /Users/pavelkakinen/icd0007/vendor/../templates/task-form.latte */
final class Template_1ce642af93 extends Latte\Runtime\Template
{
	public const Source = '/Users/pavelkakinen/icd0007/vendor/../templates/task-form.latte';


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
    ';
		echo $errorMessage /* line 21 */;
		echo '
    ';
		echo $formHtml /* line 22 */;
		echo '

    <p><a href="?command=task_list">Back to Task List</a></p>
</main>
</body>
</html>';
	}
}
