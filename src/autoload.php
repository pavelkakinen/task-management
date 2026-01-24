<?php

/**
 * Simple PSR-4 autoloader for App namespace
 */
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';

    // Check if class uses our namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get relative class name
    $relativeClass = substr($class, $len);

    // Convert namespace to file path
    // App\Dto\EmployeeDto -> dto/EmployeeDto.php
    $file = $baseDir . strtolower(str_replace('\\', '/', dirname(str_replace('\\', '/', $relativeClass))))
          . '/' . basename(str_replace('\\', '/', $relativeClass)) . '.php';

    // Simpler approach: just lowercase the directory part
    $parts = explode('\\', $relativeClass);
    $className = array_pop($parts);
    $dirPath = implode('/', array_map('strtolower', $parts));
    $file = $baseDir . ($dirPath ? $dirPath . '/' : '') . $className . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
