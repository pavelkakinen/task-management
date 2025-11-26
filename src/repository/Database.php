<?php

class Database {
    private static ?PDO $instance = null;
    private static bool $connectionNeeded = false;

    private const DB_HOST = 'db.mkalmo.eu';
    private const DB_NAME = 'pavelkakinen';
    private const DB_USER = 'pavelkakinen';
    private const DB_PASS = 'e0c8bf71';

    private function __construct() {}

    public static function markConnectionNeeded(): void {
        self::$connectionNeeded = true;
    }

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s;charset=utf8mb4",
                    self::DB_HOST,
                    self::DB_NAME
                );

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
