<?php

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/src/dto/',
        __DIR__ . '/src/repository/',
        __DIR__ . '/src/validation/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load template engine
require_once __DIR__ . '/vendor/tpl.php';

// Get command parameter
$command = $_GET['command'] ?? 'dashboard';

// Route to appropriate handler
switch ($command) {
    case 'dashboard':
        handleDashboard();
        break;

    case 'employee_list':
        handleEmployeeList();
        break;

    case 'employee_form':
        handleEmployeeForm();
        break;

    case 'task_list':
        handleTaskList();
        break;

    case 'task_form':
        handleTaskForm();
        break;

    default:
        header('Location: ?command=dashboard');
        exit;
}

// ============= HANDLERS =============

function handleDashboard(): void {
    $employeeRepo = new EmployeeRepository();
    $taskRepo = new TaskRepository();

    $employees = $employeeRepo->findAllWithTaskCount();
    $tasks = $taskRepo->findAllWithEmployees();

    render('dashboard', [
        'employees' => $employees,
        'tasks' => $tasks
    ]);
}

function handleEmployeeList(): void {
    $employeeRepo = new EmployeeRepository();
    $employees = $employeeRepo->findAll();
    $success = $_GET['success'] ?? '';

    render('employee-list', [
        'employees' => $employees,
        'success' => $success
    ]);
}

function handleEmployeeForm(): void {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $error = '';
    $employee = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle DELETE
        if (isset($_POST['deleteButton']) && $id) {
            $employeeRepo = new EmployeeRepository();
            $employeeRepo->delete($id);
            header('Location: ?command=employee_list&success=' . urlencode('Employee deleted successfully!'));
            exit;
        }

        // Handle SAVE
        $employee = new EmployeeDto(
            $id,
            $_POST['firstName'] ?? '',
            $_POST['lastName'] ?? '',
            $_POST['position'] ?? ''
        );

        $error = EmployeeValidator::validate($employee);

        if (!$error) {
            $employeeRepo = new EmployeeRepository();
            $employeeRepo->save($employee);
            $message = $id ? 'Employee updated successfully!' : 'Employee added successfully!';
            header('Location: ?command=employee_list&success=' . urlencode($message));
            exit;
        }
    } else {
        // Load existing employee for edit
        if ($id) {
            $employeeRepo = new EmployeeRepository();
            $employee = $employeeRepo->findById($id);
        } else {
            $employee = new EmployeeDto();
        }
    }

    render('employee-form', [
        'employee' => $employee,
        'error' => $error
    ]);
}

function handleTaskList(): void {
    $taskRepo = new TaskRepository();
    $tasks = $taskRepo->findAllWithEmployees();
    $success = $_GET['success'] ?? '';

    render('task-list', [
        'tasks' => $tasks,
        'success' => $success
    ]);
}

function handleTaskForm(): void {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $error = '';
    $task = null;

    // Get employees list (always needed for the form)
    $employeeRepo = new EmployeeRepository();
    $employees = $employeeRepo->findAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle DELETE
        if (isset($_POST['deleteButton']) && $id) {
            $taskRepo = new TaskRepository();
            $taskRepo->delete($id);
            header('Location: ?command=task_list&success=' . urlencode('Task deleted successfully!'));
            exit;
        }

        // Handle SAVE
        $task = new TaskDto(
            $id,
            $_POST['description'] ?? '',
            !empty($_POST['employeeId']) ? (int)$_POST['employeeId'] : null,
            isset($_POST['isCompleted'])
        );

        // Note: We intentionally ignore 'estimate' and any other unknown POST fields
        // This is for security - only accept known fields;

        $error = TaskValidator::validate($task);

        if (!$error) {
            $taskRepo = new TaskRepository();
            $taskRepo->save($task);
            $message = $id ? 'Task updated successfully!' : 'Task added successfully!';
            header('Location: ?command=task_list&success=' . urlencode($message));
            exit;
        }
    } else {
        // Load existing task for edit
        if ($id) {
            $taskRepo = new TaskRepository();
            $task = $taskRepo->findById($id);
        } else {
            $task = new TaskDto();
        }
    }

    render('task-form', [
        'task' => $task,
        'employees' => $employees,
        'error' => $error
    ]);
}
?>