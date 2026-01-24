<?php

namespace App\Repository;

use PDO;
use PDOException;
use RuntimeException;

class Database {
    private static ?PDO $instance = null;
    private static array $config = [];

    private function __construct() {}

    private static function loadConfig(): array {
        if (empty(self::$config)) {
            $configPath = __DIR__ . '/../../config.php';
            if (!file_exists($configPath)) {
                throw new RuntimeException('Config file not found. Copy config.example.php to config.php');
            }
            self::$config = require $configPath;
        }
        return self::$config;
    }

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            try {
                $config = self::loadConfig();
                $db = $config['db'];

                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s;charset=utf8mb4",
                    $db['host'],
                    $db['name']
                );

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                self::$instance = new PDO($dsn, $db['user'], $db['pass'], $options);
            } catch (PDOException $e) {
                throw new RuntimeException('Database connection failed: ' . $e->getMessage(), 0, $e);
            }
        }

        return self::$instance;
    }

    public static function getConfig(): array {
        return self::loadConfig();
    }
}
