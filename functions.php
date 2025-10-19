<?php
function getEmployees() {
    if (!file_exists('employees.json')) {
        return [];
    }
    $data = file_get_contents('employees.json');
    return json_decode($data, true) ?: [];
}

function getEmployeeById($id) {
    $employees = getEmployees();
    foreach ($employees as $employee) {
        if ($employee['id'] == $id) {
            return $employee;
        }
    }
    return null;
}

function addEmployee($firstName, $lastName, $position) {
    $employees = getEmployees();
    $id = uniqid();
    $employees[] = [
        'id' => $id,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'position' => $position
    ];
    file_put_contents('employees.json', json_encode($employees, JSON_PRETTY_PRINT));
    return $id;
}

function updateEmployee($id, $firstName, $lastName, $position) {
    $employees = getEmployees();
    foreach ($employees as &$employee) {
        if ($employee['id'] == $id) {
            $employee['firstName'] = $firstName;
            $employee['lastName'] = $lastName;
            $employee['position'] = $position;
            break;
        }
    }
    file_put_contents('employees.json', json_encode($employees, JSON_PRETTY_PRINT));
}

function deleteEmployee($id) {
    $employees = getEmployees();
    $newEmployees = [];
    foreach ($employees as $employee) {
        if ($employee['id'] != $id) {
            $newEmployees[] = $employee;
        }
    }
    file_put_contents('employees.json', json_encode($newEmployees, JSON_PRETTY_PRINT));
}

function getTasks() {
    if (!file_exists('tasks.json')) {
        return [];
    }
    $data = file_get_contents('tasks.json');
    return json_decode($data, true) ?: [];
}

function getTaskById($id) {
    $tasks = getTasks();
    foreach ($tasks as $task) {
        if ($task['id'] == $id) {
            return $task;
        }
    }
    return null;
}

function addTask($description) {
    $tasks = getTasks();
    $id = uniqid();
    $tasks[] = [
        'id' => $id,
        'description' => $description
    ];
    file_put_contents('tasks.json', json_encode($tasks, JSON_PRETTY_PRINT));
    return $id;
}

function updateTask($id, $description) {
    $tasks = getTasks();
    foreach ($tasks as &$task) {
        if ($task['id'] == $id) {
            $task['description'] = $description;
            break;
        }
    }
    file_put_contents('tasks.json', json_encode($tasks, JSON_PRETTY_PRINT));
}

function deleteTask($id) {
    $tasks = getTasks();
    $newTasks = [];
    foreach ($tasks as $task) {
        if ($task['id'] != $id) {
            $newTasks[] = $task;
        }
    }
    file_put_contents('tasks.json', json_encode($newTasks, JSON_PRETTY_PRINT));
}
?>