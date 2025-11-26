<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../dto/TaskDto.php';

class TaskRepository {
    private PDO $pdo;

    public function __construct() {
        Database::markConnectionNeeded();
        $this->pdo = Database::getConnection();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM tasks ORDER BY id");
        $tasks = [];

        foreach ($stmt->fetchAll() as $row) {
            $tasks[] = TaskDto::fromArray($row);
        }

        return $tasks;
    }

    public function findAllWithEmployees(): array {
        $sql = "SELECT t.*, e.firstName, e.lastName 
                FROM tasks t 
                LEFT JOIN employees e ON t.employeeId = e.id 
                ORDER BY t.id";

        $stmt = $this->pdo->query($sql);
        $tasks = [];

        foreach ($stmt->fetchAll() as $row) {
            $tasks[] = TaskDto::fromArray($row);
        }

        return $tasks;
    }

    public function findById(int $id): ?TaskDto {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? TaskDto::fromArray($row) : null;
    }

    public function save(TaskDto $task): int {
        if ($task->id) {
            $stmt = $this->pdo->prepare(
                "UPDATE tasks SET description = ?, employeeId = ?, isCompleted = ? WHERE id = ?"
            );
            $stmt->execute([
                $task->description,
                $task->employeeId,
                $task->isCompleted ? 1 : 0,
                $task->id
            ]);
            return $task->id;
        } else {
            $stmt = $this->pdo->prepare(
                "INSERT INTO tasks (description, employeeId, isCompleted) VALUES (?, ?, ?)"
            );
            $stmt->execute([
                $task->description,
                $task->employeeId,
                $task->isCompleted ? 1 : 0
            ]);
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
    }
}
