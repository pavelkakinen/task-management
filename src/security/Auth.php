<?php

namespace App\Security;

use App\Repository\Database;

class Auth {
    private const SESSION_KEY = 'authenticated_user';

    public static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(string $username, string $password): bool {
        self::init();

        $config = Database::getConfig();
        $users = $config['auth']['users'] ?? [];

        if (!isset($users[$username])) {
            return false;
        }

        if (!password_verify($password, $users[$username]['password'])) {
            return false;
        }

        $_SESSION[self::SESSION_KEY] = [
            'username' => $username,
            'logged_in_at' => time(),
        ];

        return true;
    }

    public static function logout(): void {
        self::init();
        unset($_SESSION[self::SESSION_KEY]);
        session_destroy();
    }

    public static function isAuthenticated(): bool {
        self::init();
        return isset($_SESSION[self::SESSION_KEY]);
    }

    public static function getUser(): ?array {
        self::init();
        return $_SESSION[self::SESSION_KEY] ?? null;
    }

    public static function requireAuth(): void {
        if (!self::isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['error' => 'Authentication required']);
            exit;
        }
    }
}
