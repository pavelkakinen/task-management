<?php
require_once 'functions.php';

$id = $_GET['id'] ?? null;
$task = $id ? getTaskById($id) : null;
$description = $task['description'] ?? ($_POST['description'] ?? '');
$employeeId = $task['employeeId'] ?? ($_POST['employeeId'] ?? '');
$isCompleted = $task['isCompleted'] ?? (isset($_POST['isCompleted']) ? 1 : 0);

$employees = getEmployees();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deleteButton'])) {
        deleteTask($id);
        header('Location: task-list.php?success=Task deleted successfully!');
        exit;
    }

    $description = trim($_POST['description']);
    $employeeId = $_POST['employeeId'] !== '' ? $_POST['employeeId'] : null;
    $isCompleted = isset($_POST['isCompleted']) ? 1 : 0;

    // Validation
    if (strlen($description) < 5 || strlen($description) > 40) {
        $error = 'Description must be between 5 and 40 characters.';
    } else {
        if ($id) {
            updateTask($id, $description, $employeeId, $isCompleted);
            header('Location: task-list.php?success=Task updated successfully!');
            exit;
        } else {
            addTask($description, $employeeId, $isCompleted);
            header('Location: task-list.php?success=Task added successfully!');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Edit Task' : 'Add Task' ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="task-form-page">
<header>
    <nav>
        <a href="index.php">Dashboard</a> |
        <a href="employee-list.php">Employees</a> |
        <a href="employee-form.php">Add Employee</a> |
        <a href="task-list.php">Tasks</a> |
        <a href="task-form.php">Add Task</a>
    </nav>
</header>

<main>
    <?php if ($error): ?>
        <div id="error-block" class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($description) ?></textarea>
        </div>
        
        <div>
            <label for="estimate">Estimate:</label>
            <input type="radio" name="estimate" checked="checked">1
            <input type="radio" name="estimate" checked="checked">2
            <input type="radio" name="estimate" checked="checked">3
            <input type="radio" name="estimate" checked="checked">4
            <input type="radio" name="estimate" checked="checked">5
        </div>

        <div>
            <label for="employeeId">Assigned to:</label>
            <select id="employeeId" name="employeeId">
                <option value="">-- Not assigned --</option>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?= $employee['id'] ?>"
                            <?= $employeeId == $employee['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="isCompleted">
                <input type="checkbox" id="isCompleted" name="isCompleted"
                        <?= $isCompleted ? 'checked' : '' ?>>
                Task Completed
            </label>
        </div>

        <div>
            <button name="submitButton" type="submit"><?= $id ? 'Update Task' : 'Add Task' ?></button>

            <?php if ($id): ?>
                <button type="submit" name="deleteButton" class="delete">Delete Task</button>
            <?php endif; ?>
        </div>
    </form>

    <p><a href="task-list.php">Back to Task List</a></p>
</main>
</body>
</html>