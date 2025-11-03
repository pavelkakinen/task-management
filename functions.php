<?php
require_once 'db.php';

// Employee functions
function getEmployees() {
    $pdo = getDbConnection();
    $stmt = $pdo->query("SELECT * FROM employees ORDER BY id");
    return $stmt->fetchAll();
}

function getEmployeesWithTaskCount() {
    $pdo = getDbConnection();
    $sql = "SELECT e.*, COUNT(t.id) as taskCount 
            FROM employees e 
            LEFT JOIN tasks t ON e.id = t.employeeId 
            GROUP BY e.id 
            ORDER BY e.id";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function getEmployeeById($id) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addEmployee($firstName, $lastName, $position) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("INSERT INTO employees (firstName, lastName, position) VALUES (?, ?, ?)");
    $stmt->execute([$firstName, $lastName, $position]);
    return $pdo->lastInsertId();
}

function updateEmployee($id, $firstName, $lastName, $position) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("UPDATE employees SET firstName = ?, lastName = ?, position = ? WHERE id = ?");
    $stmt->execute([$firstName, $lastName, $position, $id]);
}

function deleteEmployee($id) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->execute([$id]);
}

// Task functions
function getTasks() {
    $pdo = getDbConnection();
    $stmt = $pdo->query("SELECT * FROM tasks ORDER BY id");
    return $stmt->fetchAll();
}

function getTasksWithEmployees() {
    $pdo = getDbConnection();
    $sql = "SELECT t.*, e.firstName, e.lastName 
            FROM tasks t 
            LEFT JOIN employees e ON t.employeeId = e.id 
            ORDER BY t.id";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function getTaskById($id) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addTask($description, $employeeId = null, $isCompleted = false) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("INSERT INTO tasks (description, employeeId, isCompleted) VALUES (?, ?, ?)");
    $stmt->execute([$description, $employeeId, $isCompleted ? 1 : 0]);
    return $pdo->lastInsertId();
}

function updateTask($id, $description, $employeeId = null, $isCompleted = false) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("UPDATE tasks SET description = ?, employeeId = ?, isCompleted = ? WHERE id = ?");
    $stmt->execute([$description, $employeeId, $isCompleted ? 1 : 0, $id]);
}

function deleteTask($id) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
}

function getTaskState($task) {
    if ($task['isCompleted']) {
        return 'Closed';
    } elseif ($task['employeeId']) {
        return 'Pending';
    } else {
        return 'Open';
    }
}
?>