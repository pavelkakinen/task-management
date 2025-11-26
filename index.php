<?php

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

require_once __DIR__ . '/vendor/tpl.php';

$command = $_GET['command'] ?? 'dashboard';

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


function handleDashboard(): void {
    $employeeRepo = new EmployeeRepository();
    $taskRepo = new TaskRepository();

    $employees = $employeeRepo->findAllWithTaskCount();
    $tasks = $taskRepo->findAllWithEmployees();

    $employeesHtml = '';
    if (empty($employees)) {
        $employeesHtml = '<p>No employees found.</p>';
    } else {
        foreach ($employees as $employee) {
            $employeesHtml .= '<div class="employee-item">';
            $employeesHtml .= '<span class="name">' . htmlspecialchars($employee->getFullName()) . '</span>';
            $employeesHtml .= '<br><span class="position">' . htmlspecialchars($employee->position) . '</span>';
            $employeesHtml .= '<br><span class="task-count">Tasks: <span id="employee-task-count-' . $employee->id . '">' . $employee->taskCount . '</span></span>';
            $employeesHtml .= '</div>';
        }
    }

    $tasksHtml = '';
    if (empty($tasks)) {
        $tasksHtml = '<p>No tasks found.</p>';
    } else {
        foreach ($tasks as $task) {
            $tasksHtml .= '<div class="task">';
            $tasksHtml .= '<div class="title">';
            $tasksHtml .= '<div>' . htmlspecialchars($task->description) . '</div>';

            if ($task->employeeId) {
                $tasksHtml .= '<small>Assigned to: ' . htmlspecialchars($task->getEmployeeFullName()) . '</small>';
            }

            $tasksHtml .= '<div><strong>State: <span id="task-state-' . $task->id . '">';
            $tasksHtml .= $task->getState();
            $tasksHtml .= '</span></strong></div>';
            $tasksHtml .= '</div>';
            $tasksHtml .= '</div>';
        }
    }

    render('dashboard', [
        'employeesHtml' => $employeesHtml,
        'tasksHtml' => $tasksHtml
    ]);
}

function handleEmployeeList(): void {
    $employeeRepo = new EmployeeRepository();
    $employees = $employeeRepo->findAll();
    $success = $_GET['success'] ?? '';

    $successMessage = '';
    if ($success) {
        $successMessage = '<div id="message-block" class="success">' . htmlspecialchars($success) . '</div>';
    }

    $employeeListHtml = '<h2>Employees (' . count($employees) . ')</h2>';

    if (count($employees) === 0) {
        $employeeListHtml .= '<p>No employees found.</p>';
    } else {
        $employeeListHtml .= '<ul class="employee-list">';

        foreach ($employees as $employee) {
            $employeeListHtml .= '<li>';
            $employeeListHtml .= '<div data-employee-id="' . $employee->id . '">';
            $employeeListHtml .= htmlspecialchars($employee->getFullName());
            $employeeListHtml .= '</div>';
            $employeeListHtml .= ' - ' . htmlspecialchars($employee->position);
            $employeeListHtml .= ' <a href="?command=employee_form&id=' . $employee->id . '" ';
            $employeeListHtml .= 'id="employee-edit-link-' . $employee->id . '" class="edit-link">Edit</a>';
            $employeeListHtml .= '</li>';
        }

        $employeeListHtml .= '</ul>';
    }

    render('employee-list', [
        'successMessage' => $successMessage,
        'employeeListHtml' => $employeeListHtml
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
        if ($id) {
            $employeeRepo = new EmployeeRepository();
            $employee = $employeeRepo->findById($id);
        } else {
            $employee = new EmployeeDto();
        }
    }

    $errorMessage = '';
    if ($error) {
        $errorMessage = '<div id="error-block" class="error">' . htmlspecialchars($error) . '</div>';
    }

    $formHtml = '<form method="POST">';

    $formHtml .= '<div>';
    $formHtml .= '<label for="firstName">First Name:</label>';
    $formHtml .= '<input type="text" id="firstName" name="firstName" ';
    $formHtml .= 'value="' . htmlspecialchars($employee->firstName) . '" required>';
    $formHtml .= '</div>';

    $formHtml .= '<div>';
    $formHtml .= '<label for="lastName">Last Name:</label>';
    $formHtml .= '<input type="text" id="lastName" name="lastName" ';
    $formHtml .= 'value="' . htmlspecialchars($employee->lastName) . '" required>';
    $formHtml .= '</div>';

    $formHtml .= '<div>';
    $formHtml .= '<label for="position">Position:</label>';
    $formHtml .= '<input type="text" id="position" name="position" ';
    $formHtml .= 'value="' . htmlspecialchars($employee->position) . '">';
    $formHtml .= '</div>';

    $formHtml .= '<div>';
    $formHtml .= '<label for="picture">Picture:</label>';
    $formHtml .= '<input type="file" id="picture" name="picture" accept="image/*">';
    $formHtml .= '</div>';

    $formHtml .= '<div>';
    $formHtml .= '<button name="submitButton" id="submitButton" type="submit">';
    $formHtml .= $id ? 'Update Employee' : 'Add Employee';
    $formHtml .= '</button>';

    if ($id) {
        $formHtml .= ' <button type="submit" name="deleteButton" id="deleteButton" class="delete">';
        $formHtml .= 'Delete Employee';
        $formHtml .= '</button>';
    }

    $formHtml .= '</div>';
    $formHtml .= '</form>';

    render('employee-form', [
        'pageTitle' => $id ? 'Edit Employee' : 'Add Employee',
        'errorMessage' => $errorMessage,
        'formHtml' => $formHtml
    ]);
}

function handleTaskList(): void {
    $taskRepo = new TaskRepository();
    $tasks = $taskRepo->findAllWithEmployees();
    $success = $_GET['success'] ?? '';

    $successMessage = '';
    if ($success) {
        $successMessage = '<div id="message-block" class="success">' . htmlspecialchars($success) . '</div>';
    }

    $taskListHtml = '<h2>Tasks (' . count($tasks) . ')</h2>';

    if (empty($tasks)) {
        $taskListHtml .= '<p>No tasks found.</p>';
    } else {
        $taskListHtml .= '<ul class="task-list">';

        foreach ($tasks as $task) {
            $taskListHtml .= '<li>';
            $taskListHtml .= '<div>';
            $taskListHtml .= '<div data-task-id="' . $task->id . '">';
            $taskListHtml .= htmlspecialchars($task->description);
            $taskListHtml .= '</div>';

            if ($task->employeeId) {
                $taskListHtml .= '<small>Assigned to: ' . htmlspecialchars($task->getEmployeeFullName()) . '</small>';
            } else {
                $taskListHtml .= '<small>Not assigned</small>';
            }

            $taskListHtml .= '<small>Status: <span id="task-state-' . $task->id . '">' . $task->getState() . '</span></small>';
            $taskListHtml .= '</div>';
            $taskListHtml .= '<a href="?command=task_form&id=' . $task->id . '" ';
            $taskListHtml .= 'id="task-edit-link-' . $task->id . '" class="edit-link">Edit</a>';
            $taskListHtml .= '</li>';
        }

        $taskListHtml .= '</ul>';
    }

    render('task-list', [
        'successMessage' => $successMessage,
        'taskListHtml' => $taskListHtml
    ]);
}

function handleTaskForm(): void {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $error = '';
    $task = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['deleteButton']) && $id) {
            $taskRepo = new TaskRepository();
            $taskRepo->delete($id);
            header('Location: ?command=task_list&success=' . urlencode('Task deleted successfully!'));
            exit;
        }

        $task = new TaskDto(
            $id,
            $_POST['description'] ?? '',
            !empty($_POST['employeeId']) ? (int)$_POST['employeeId'] : null,
            isset($_POST['isCompleted'])
        );

        $error = TaskValidator::validate($task);

        if (!$error) {
            $taskRepo = new TaskRepository();
            $taskRepo->save($task);
            $message = $id ? 'Task updated successfully!' : 'Task added successfully!';
            header('Location: ?command=task_list&success=' . urlencode($message));
            exit;
        }
    } else {
        if ($id) {
            $taskRepo = new TaskRepository();
            $task = $taskRepo->findById($id);
        } else {
            $task = new TaskDto();
        }
    }

    $errorMessage = '';
    if ($error) {
        $errorMessage = '<div id="error-block" class="error">' . htmlspecialchars($error) . '</div>';
    }
    $pageTitle = $id ? 'Edit Task' : 'Add Task';

    $employeeRepo = new EmployeeRepository();
    $employees = $employeeRepo->findAll();

    $formHtml = '<form method="POST">';
    $formHtml .= '<div>';
    $formHtml .= '<label for="description">Description:</label>';
    $formHtml .= '<textarea id="description" name="description">';
    $formHtml .= htmlspecialchars($task->description);
    $formHtml .= '</textarea>';
    $formHtml .= '</div>';
    $formHtml .= '<div>';
    $formHtml .= '<label for="estimate">Estimate:</label>';
    $formHtml .= '<input type="hidden" id="estimate" name="estimate" value="">';
    $formHtml .= '</div>';
    $formHtml .= '<div>';
    $formHtml .= '<label for="employeeId">Assign to Employee</label>';
    $formHtml .= '<select id="employeeId" name="employeeId">';
    $formHtml .= '<option value="">--- Not assigned ---</option>';
    foreach ($employees as $employee) {
        $selected = $employee->id === $task->employeeId ? ' selected' : '';
        $formHtml .= '<option value="' . $employee->id . '"' . $selected . '>';
        $formHtml .= htmlspecialchars($employee->getFullName());
        $formHtml .= '</option>';
    }
    $formHtml .= '</select>';
    $formHtml .= '</div>';
    $formHtml .= '<div>';
    $formHtml .= '<label for="isCompleted">';
    $formHtml .= '<input type="checkbox" id="isCompleted" name="isCompleted" ';
    if ($task->isCompleted) {
        $formHtml .= 'checked';
    }
    $formHtml .= '>';
    $formHtml .= 'Task Completed';
    $formHtml .= '</label>';
    $formHtml .= '</div>';
    $formHtml .= '<div>';
    $formHtml .= '<button name="submitButton" id="submitButton" type="submit">';
    $formHtml .= $id ? 'Update Task' : 'Add Task';
    $formHtml .= '</button>';

    if ($id) {
        $formHtml .= ' <button type="submit" name="deleteButton" id="deleteButton" class="delete">';
        $formHtml .= 'Delete Task';
        $formHtml .= '</button>';
    }
    $formHtml .= '</div>';
    $formHtml .= '</form>';



    render('task-form', [
        'pageTitle' => $pageTitle,
        'formHtml' => $formHtml,
        'errorMessage' => $errorMessage
    ]);
}
