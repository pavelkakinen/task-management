<?php

require_once __DIR__ . '/../src/autoload.php';

use App\Repository\Database;
use App\Repository\EmployeeRepository;
use App\Repository\TaskRepository;
use App\Security\Auth;

// Initialize session
Auth::init();

// CORS handling
$config = Database::getConfig();
$allowedOrigins = $config['cors']['allowed_origins'] ?? [];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Credentials: true');
} elseif (empty($origin)) {
    // Same-origin request
} else {
    header('Access-Control-Allow-Origin: null');
}

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }

    $employeeRepo = new EmployeeRepository();
    $taskRepo = new TaskRepository();

    $employees = $employeeRepo->findAll();
    $result = [];

    foreach ($employees as $employee) {
        $tasks = $taskRepo->findByEmployeeId($employee->id);
        $result[] = [
            'id' => $employee->id,
            'firstName' => $employee->firstName,
            'lastName' => $employee->lastName,
            'positionTitle' => $employee->positionTitle,
            'picture' => $employee->picture,
            'tasks' => array_map(function($task) {
                return [
                    'id' => $task->id,
                    'description' => $task->description,
                    'isCompleted' => $task->isCompleted
                ];
            }, $tasks)
        ];
    }

    echo json_encode($result);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
    error_log($e->getMessage());
}
