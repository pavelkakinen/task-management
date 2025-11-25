<?php
/**
 * Migration script to transfer data from JSON files to MySQL database
 * Run this file ONCE after setting up the database
 */

require_once 'db.php';

echo "Starting migration...\n";

try {
    $pdo = getConnection();

    // Migrate employees
    if (file_exists('employees.json')) {
        $employeesJson = file_get_contents('employees.json');
        $employees = json_decode($employeesJson, true);

        if ($employees) {
            echo "Migrating " . count($employees) . " employees...\n";

            $stmt = $pdo->prepare("INSERT INTO employees (firstName, lastName, position) VALUES (?, ?, ?)");

            $oldToNewIds = []; // Map old IDs to new IDs

            foreach ($employees as $employee) {
                $stmt->execute([
                    $employee['firstName'],
                    $employee['lastName'],
                    $employee['position'] ?? ''
                ]);

                $newId = $pdo->lastInsertId();
                $oldToNewIds[$employee['id']] = $newId;

                echo "  Migrated: {$employee['firstName']} {$employee['lastName']} (old ID: {$employee['id']}, new ID: {$newId})\n";
            }

            echo "Employees migrated successfully!\n\n";
        }
    } else {
        echo "No employees.json file found. Skipping employees migration.\n\n";
    }

    // Migrate tasks
    if (file_exists('tasks.json')) {
        $tasksJson = file_get_contents('tasks.json');
        $tasks = json_decode($tasksJson, true);

        if ($tasks) {
            echo "Migrating " . count($tasks) . " tasks...\n";

            $stmt = $pdo->prepare("INSERT INTO tasks (description, employeeId, isCompleted) VALUES (?, ?, ?)");

            foreach ($tasks as $task) {
                // Since old tasks didn't have employeeId, set it to NULL
                $stmt->execute([
                    $task['description'],
                    null,
                    0
                ]);

                $newId = $pdo->lastInsertId();
                echo "  Migrated: {$task['description']} (old ID: {$task['id']}, new ID: {$newId})\n";
            }

            echo "Tasks migrated successfully!\n\n";
        }
    } else {
        echo "No tasks.json file found. Skipping tasks migration.\n\n";
    }

    echo "Migration completed successfully!\n";
    echo "You can now delete the employees.json and tasks.json files if you want.\n";

} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
?>