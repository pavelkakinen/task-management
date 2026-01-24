<?php

namespace App\Security;

class CsrfToken {
    private const TOKEN_NAME = 'csrf_token';
    private const TOKEN_LENGTH = 32;

    public static function generate(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(self::TOKEN_LENGTH));
        $_SESSION[self::TOKEN_NAME] = $token;

        return $token;
    }

    public static function get(): ?string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION[self::TOKEN_NAME] ?? null;
    }

    public static function validate(string $token): bool {
        $storedToken = self::get();

        if ($storedToken === null || $token === '') {
            return false;
        }

        return hash_equals($storedToken, $token);
    }

    public static function validateFromHeader(): bool {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        return self::validate($token);
    }
}
