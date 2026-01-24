<?php

require_once __DIR__ . '/../src/autoload.php';

use App\Security\Auth;
use App\Security\CsrfToken;
use App\Repository\Database;

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
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    $path = $_GET['action'] ?? '';

    // GET /api/auth.php?action=status - Check auth status and get CSRF token
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $path === 'status') {
        $isAuthenticated = Auth::isAuthenticated();
        $csrfToken = CsrfToken::get() ?? CsrfToken::generate();

        echo json_encode([
            'authenticated' => $isAuthenticated,
            'user' => $isAuthenticated ? Auth::getUser() : null,
            'csrfToken' => $csrfToken,
        ]);
    }

    // POST /api/auth.php?action=login - Login
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $path === 'login') {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if (Auth::login($username, $password)) {
            $csrfToken = CsrfToken::generate();
            echo json_encode([
                'success' => true,
                'user' => Auth::getUser(),
                'csrfToken' => $csrfToken,
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid username or password']);
        }
    }

    // POST /api/auth.php?action=logout - Logout
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $path === 'logout') {
        Auth::logout();
        echo json_encode(['success' => true]);
    }

    else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
    error_log($e->getMessage());
}
