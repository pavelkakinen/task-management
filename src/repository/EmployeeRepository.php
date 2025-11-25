<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../dto/EmployeeDto.php';

class EmployeeRepository {
    private PDO $pdo;

    public function __construct() {
        Database::markConnectionNeeded();
        $this->pdo = Database::getConnection();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM employees ORDER BY id");
        $employees = [];

        foreach ($stmt->fetchAll() as $row) {
            $employees[] = EmployeeDto::fromArray($row);
        }

        return $employees;
    }

    public function findAllWithTaskCount(): array {
        $sql = "SELECT e.*, COUNT(t.id) as taskCount 
                FROM employees e 
                LEFT JOIN tasks t ON e.id = t.employeeId 
                GROUP BY e.id 
                ORDER BY e.id";

        $stmt = $this->pdo->query($sql);
        $employees = [];

        foreach ($stmt->fetchAll() as $row) {
            $employees[] = EmployeeDto::fromArray($row);
        }

        return $employees;
    }

    public function findById(int $id): ?EmployeeDto {
        $stmt = $this->pdo->prepare("SELECT * FROM employees WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? EmployeeDto::fromArray($row) : null;
    }

    public function save(EmployeeDto $employee): int {
        if ($employee->id) {
            $stmt = $this->pdo->prepare(
                "UPDATE employees SET firstName = ?, lastName = ?, position = ? WHERE id = ?"
            );
            $stmt->execute([
                $employee->firstName,
                $employee->lastName,
                $employee->position,
                $employee->id
            ]);
            return $employee->id;
        } else {
            $stmt = $this->pdo->prepare(
                "INSERT INTO employees (firstName, lastName, position) VALUES (?, ?, ?)"
            );
            $stmt->execute([
                $employee->firstName,
                $employee->lastName,
                $employee->position
            ]);
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM employees WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>