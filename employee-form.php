<?php
require_once 'functions.php';

$id = $_GET['id'] ?? null;
$employee = $id ? getEmployeeById($id) : null;
$firstName = $employee['firstName'] ?? ($_POST['firstName'] ?? '');
$lastName = $employee['lastName'] ?? ($_POST['lastName'] ?? '');
$position = $employee['position'] ?? ($_POST['position'] ?? '');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deleteButton'])) {
        deleteEmployee($id);
        header('Location: employee-list.php?success=Employee deleted successfully!');
        exit;
    }

    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $position = trim($_POST['position']);

    // Validation
    if (strlen($firstName) < 1 || strlen($firstName) > 21) {
        $error = 'First name must be between 1 and 21 characters.';
    } elseif (strlen($lastName) < 2 || strlen($lastName) > 22) {
        $error = 'Last name must be between 2 and 22 characters.';
    } else {
        if ($id) {
            updateEmployee($id, $firstName, $lastName, $position);
            header('Location: employee-list.php?success=Employee updated successfully!');
            exit;
        } else {
            addEmployee($firstName, $lastName, $position);
            header('Location: employee-list.php?success=Employee added successfully!');
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
    <title><?= $id ? 'Edit Employee' : 'Add Employee' ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="employee-form-page">
<header>
    <nav>
        <a href="?page=index.php" id="dashboard-link">Dashboard</a>
        <a href="?page=employee-list" id="employee-list-link">Employees</a>
        <a href="?page=employee-form" id="employee-form-link">Add Employee</a>
        <a href="?page=task-list" id="task-list-link">Tasks</a>
        <a href="?page=task-form" id="task-form-link">Add Task</a>
    </nav>
</header>

<main>
    <?php if ($error): ?>
        <div id="error-block" class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName"
                   value="<?= htmlspecialchars($firstName) ?>" required>
        </div>

        <div>
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName"
                   value="<?= htmlspecialchars($lastName) ?>" required>
        </div>

        <div>
            <label for="position">Position:</label>
            <input type="text" id="position" name="position"
                   value="<?= htmlspecialchars($position) ?>">
        </div>

        <div>
            <label for="picture">Picture:</label>
            <input type="file" id="picture" name="picture" accept="image/*">
        </div>

        <div>
            <button name="submitButton" id="submitButton" type="submit"><?= $id ? 'Update Employee' : 'Add Employee' ?></button>

            <?php if ($id): ?>
                <button type="submit" name="deleteButton" class="delete">Delete Employee</button>
            <?php endif; ?>
        </div>
    </form>

    <p><a href="employee-list.php">Back to Employee List</a></p>
</main>
</body>
</html>