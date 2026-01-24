<?php

namespace App\Repository;

use App\Dto\EmployeeDto;
use PDO;

class EmployeeRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function findAll(): array {
        $sql = "SELECT e.*, p.title as positionTitle
                FROM employees e
                LEFT JOIN positions p ON e.positionId = p.id
                ORDER BY e.id";

        $stmt = $this->pdo->query($sql);
        $employees = [];

        foreach ($stmt->fetchAll() as $row) {
            $employees[] = EmployeeDto::fromArray($row);
        }

        return $employees;
    }

    public function findAllWithTaskCount(): array {
        $sql = "SELECT e.*, p.title as positionTitle, COUNT(t.id) as taskCount
                FROM employees e
                LEFT JOIN positions p ON e.positionId = p.id
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
        $sql = "SELECT e.*, p.title as positionTitle
                FROM employees e
                LEFT JOIN positions p ON e.positionId = p.id
                WHERE e.id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? EmployeeDto::fromArray($row) : null;
    }

    public function save(EmployeeDto $employee): int {
        if ($employee->id) {
            $stmt = $this->pdo->prepare(
                "UPDATE employees SET firstName = ?, lastName = ?, positionId = ? WHERE id = ?"
            );
            $stmt->execute([
                $employee->firstName,
                $employee->lastName,
                $employee->positionId,
                $employee->id
            ]);
            return $employee->id;
        } else {
            $stmt = $this->pdo->prepare(
                "INSERT INTO employees (firstName, lastName, positionId) VALUES (?, ?, ?)"
            );
            $stmt->execute([
                $employee->firstName,
                $employee->lastName,
                $employee->positionId
            ]);
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function updatePicture(int $id, ?string $picture): void {
        $stmt = $this->pdo->prepare("UPDATE employees SET picture = ? WHERE id = ?");
        $stmt->execute([$picture, $id]);
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM employees WHERE id = ?");
        $stmt->execute([$id]);
    }
}
