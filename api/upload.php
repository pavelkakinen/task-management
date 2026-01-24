<?php

require_once __DIR__ . '/../src/autoload.php';

use App\Repository\Database;
use App\Repository\EmployeeRepository;
use App\Security\Auth;
use App\Security\CsrfToken;
use App\Security\InputSanitizer;

// Initialize session for auth/CSRF
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
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

/**
 * Check if mutation is allowed (auth + CSRF)
 */
function checkMutationAllowed(): void {
    global $config;

    $requireAuth = $config['auth']['require_auth'] ?? false;

    if ($requireAuth) {
        Auth::requireAuth();

        // For file uploads, CSRF token comes from POST data
        $token = $_POST['csrf_token'] ?? '';
        if (!CsrfToken::validate($token)) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid or missing CSRF token']);
            exit;
        }
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }

    checkMutationAllowed();

    $employeeId = InputSanitizer::sanitizeId($_POST['employeeId'] ?? null);

    if (!$employeeId) {
        http_response_code(400);
        echo json_encode(['error' => 'Employee ID is required']);
        exit;
    }

    $repo = new EmployeeRepository();
    $employee = $repo->findById($employeeId);

    if (!$employee) {
        http_response_code(404);
        echo json_encode(['error' => 'Employee not found']);
        exit;
    }

    // Handle file upload
    if (!isset($_FILES['picture']) || $_FILES['picture']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => 'No file uploaded or upload error']);
        exit;
    }

    $file = $_FILES['picture'];

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!in_array($mimeType, $allowedTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type. Allowed: JPG, PNG, GIF, WebP']);
        exit;
    }

    // Validate file size (max 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        http_response_code(400);
        echo json_encode(['error' => 'File too large. Maximum size: 2MB']);
        exit;
    }

    // Generate unique filename
    $extension = match($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        default => 'jpg'
    };
    $filename = 'emp_' . $employeeId . '_' . time() . '.' . $extension;
    $uploadPath = __DIR__ . '/../uploads/' . $filename;

    // Delete old picture if exists
    if ($employee->picture) {
        $oldPath = __DIR__ . '/../uploads/' . $employee->picture;
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save file']);
        exit;
    }

    // Update database
    $repo->updatePicture($employeeId, $filename);

    echo json_encode(['success' => true, 'picture' => $filename]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
    error_log($e->getMessage());
}
