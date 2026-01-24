<?php

require_once __DIR__ . '/../src/autoload.php';

use App\Repository\Database;
use App\Repository\PositionRepository;
use App\Dto\PositionDto;
use App\Validation\PositionValidator;
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
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
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

        if (!CsrfToken::validateFromHeader()) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid or missing CSRF token']);
            exit;
        }
    }
}

try {
    $repo = new PositionRepository();

    // GET - Retrieve positions
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['id'])) {
            $id = InputSanitizer::sanitizeId($_GET['id']);
            if ($id === null) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid ID']);
                exit;
            }

            $position = $repo->findById($id);
            if ($position === null) {
                http_response_code(404);
                echo json_encode(['error' => 'Position not found']);
            } else {
                echo json_encode($position);
            }
        } else {
            $positions = $repo->findAll();
            echo json_encode($positions);
        }
    }

    // POST - Create or update position
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        checkMutationAllowed();

        $data = json_decode(file_get_contents('php://input'), true);

        $position = new PositionDto(
            InputSanitizer::sanitizeId($data['id'] ?? null),
            InputSanitizer::sanitizePosition($data['title'] ?? '')
        );

        $error = PositionValidator::validate($position);

        if ($error) {
            http_response_code(400);
            echo json_encode(['error' => $error]);
        } else {
            $id = $repo->save($position);
            echo json_encode(['id' => $id, 'success' => true]);
        }
    }

    // DELETE - Remove position
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        checkMutationAllowed();

        $data = json_decode(file_get_contents('php://input'), true);
        $id = InputSanitizer::sanitizeId($data['id'] ?? null);

        if ($id) {
            $deleted = $repo->delete($id);
            if ($deleted) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Cannot delete position with assigned employees']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Valid ID is required']);
        }
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
    error_log($e->getMessage());
}
