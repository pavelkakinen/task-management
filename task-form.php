<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description'] ?? '');
    $estimate = (int)($_POST['estimate'] ?? 0);

    if (!empty($description) && $estimate >= 1 && $estimate <= 5) {
        $data = urlencode($description) . '|' . $estimate . PHP_EOL;
        file_put_contents('tasks.txt', $data, FILE_APPEND | LOCK_EX);

        header('Location: task-list.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
</head>
<body id="task-form-page">

<table border="0" width="100%">
    <td></td>
    <td width="690px">
        <table border="0" width="100%">
            <tr>
                <td>
                    <a href="index.php" id="dashboard-link">Dashboard</a> |
                    <a href="employee-list.php" id="employee-list-link">Employees</a> |
                    <a href="employee-form.php" id="employee-form-link">Add Employee</a> |
                    <a href="task-list.php" id="task-list-link">Tasks</a> |
                    <a href="task-form.php" id="task-form-link">Add Task</a>
                </td>
            </tr>

            <tr>
                <td>
                    <table border="0" width="100%">
                        <tr>
                            <td width="34%">
                                <form method="post">
                                    <table border="1" width="100%">
                                        <tr>
                                            <td>Add Task</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table border="0" width="100%">
                                                    <td></td>
                                                    <td width="450px">
                                                        <table border="0" width="100%">
                                                            <tr>
                                                                <td colspan="2">
                                                                    <br>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="30%" align="right">Description:</td>
                                                                <td>
                                                                    <textarea name="description" style="width: 100%; box-sizing: border-box;"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right">Estimate:</td>
                                                                <td>
                                                                    <input type="radio" name="estimate" value="1" checked>1
                                                                    <input type="radio" name="estimate" value="2">2
                                                                    <input type="radio" name="estimate" value="3">3
                                                                    <input type="radio" name="estimate" value="4">4
                                                                    <input type="radio" name="estimate" value="5">5
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right">Assigned to:</td>
                                                                <td>
                                                                    <select style="width: 100%; box-sizing: border-box">
                                                                        <option></option>
                                                                        <option value="o1">Daisy Smith</option>
                                                                        <option value="o2">James Adams</option>
                                                                        <option value="o3">Mary Brown</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr><td><br></td></tr>
                                                            <tr>
                                                                <td colspan="2" align="right">
                                                                    <button type="submit" name="submitButton">Save</button>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td></td>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
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
                    <hr>icd0007 Sample Application
                </td>
            </tr>
        </table>
    </td>
    <td></td>
</table>

</body>
</html>