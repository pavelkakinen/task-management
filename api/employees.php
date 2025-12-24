<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../src/repository/Database.php';
require_once __DIR__ . '/../src/repository/EmployeeRepository.php';
require_once __DIR__ . '/../src/dto/EmployeeDto.php';
require_once __DIR__ . '/../src/validation/EmployeeValidator.php';

$repo = new EmployeeRepository();

// GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        // Получить одного сотрудника
        $employee = $repo->findById((int)$_GET['id']);
        echo json_encode($employee);
    } else {
        // Получить всех
        $employees = $repo->findAll();
        echo json_encode($employees);
    }
}

// POST
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $employee = new EmployeeDto(
        $data['id'] ?? null,
        $data['firstName'] ?? '',
        $data['lastName'] ?? '',
        $data['position'] ?? ''
    );

    $error = EmployeeValidator::validate($employee);

    if ($error) {
        http_response_code(400);
        echo json_encode(['error' => $error]);
    } else {
        $id = $repo->save($employee);
        echo json_encode(['id' => $id, 'success' => true]);
    }
}

// DELETE
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;

    if ($id) {
        $repo->delete($id);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'ID is required']);
    }
}