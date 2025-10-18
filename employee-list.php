<?php
function getEmployees() {
    if (!file_exists('employees.txt')) {
        echo "<!-- File employees.txt does not exist -->";
        return [];
    }

    $employees = [];
    $lines = file('employees.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    echo "<!-- Found " . count($lines) . " lines in employees.txt -->";

    foreach ($lines as $line) {
        echo "<!-- Processing line: " . htmlspecialchars($line) . " -->";
        $parts = explode('|', $line);
        if (count($parts) === 2) {
            $employees[] = [
                    'firstName' => urldecode($parts[0]),
                    'lastName' => urldecode($parts[1])
            ];
            echo "<!-- Added employee: " . urldecode($parts[0]) . " " . urldecode($parts[1]) . " -->";
        }
    }
    return $employees;
}

$employees = getEmployees();
echo "<!-- Total employees: " . count($employees) . " -->";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body id="employee-list-page">

<table border="0" width="100%">
    <td></td>
    <td width="690px">
        <table border="0" width="100%">
            <tr>
                <td><a href="index.html" id="dashboard-link">Dashboard</a> | <a href="employee-list.html" id="employee-list-link">Employees</a> | <a href="employee-form.php" id="employee-form-link">Add Employee</a> | <a href="task-list.php" id="task-list-link">Tasks</a> | <a href="task-form.php" id="task-form-link">Add Task</a></td>
            </tr>

            <tr>
                <td>
                    <table border="0" width="100%">
                        <tr>
                            <td width="34%">
                                <table border="1" width="100%">
                                    <tr>
                                        <td>Employees</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php if (count($employees) > 0): ?>
                                                <?php foreach ($employees as $employee): ?>
                                                    <table border="1" width="100%">
                                                        <tr>
                                                            <td width="15%" height="70px"><img src=""></td>
                                                            <td valign="top">
                                                                <strong><?= htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']) ?></strong><br>
                                                                Position
                                                            </td>
                                                            <td valign="top" align="right"><u>Edit</u></td>
                                                        </tr>
                                                    </table>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p>No employees found</p>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="3" height="100px"></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <hr>icd0007 Sample Application</td>
            </tr>
        </table>
    </td>
    <td></td>
</table>

</body>
</html>