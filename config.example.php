<?php

/**
 * Application Configuration Example
 *
 * Copy this file to config.php and update with your credentials.
 * NEVER commit config.php to version control.
 */

return [
    'db' => [
        'host' => 'localhost',
        'name' => 'your_database',
        'user' => 'your_username',
        'pass' => 'your_password',
    ],
    'cors' => [
        'allowed_origins' => [
            'http://localhost',
            'http://localhost:8080',
            'http://127.0.0.1',
            // Add your production domain here
            // 'https://your-domain.com',
        ],
    ],
    'auth' => [
        // Users array: username => ['password' => password_hash('...', PASSWORD_DEFAULT)]
        // Generate password hash: php -r "echo password_hash('your_password', PASSWORD_DEFAULT);"
        'users' => [
            'admin' => [
                'password' => '$2y$10$HASH_YOUR_PASSWORD_HERE',
            ],
        ],
        'require_auth' => true, // Set to true to require login for mutations
    ],
];
