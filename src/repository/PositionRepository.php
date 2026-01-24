<?php

namespace App\Repository;

use App\Dto\PositionDto;
use PDO;

class PositionRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function findAll(): array {
        $sql = "SELECT p.*, COUNT(e.id) as employeeCount
                FROM positions p
                LEFT JOIN employees e ON p.id = e.positionId
                GROUP BY p.id
                ORDER BY p.title";

        $stmt = $this->pdo->query($sql);
        $positions = [];

        foreach ($stmt->fetchAll() as $row) {
            $positions[] = PositionDto::fromArray($row);
        }

        return $positions;
    }

    public function findById(int $id): ?PositionDto {
        $sql = "SELECT p.*, COUNT(e.id) as employeeCount
                FROM positions p
                LEFT JOIN employees e ON p.id = e.positionId
                WHERE p.id = ?
                GROUP BY p.id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? PositionDto::fromArray($row) : null;
    }

    public function getEmployeeCount(int $id): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM employees WHERE positionId = ?");
        $stmt->execute([$id]);
        return (int)$stmt->fetchColumn();
    }

    public function save(PositionDto $position): int {
        if ($position->id) {
            $stmt = $this->pdo->prepare(
                "UPDATE positions SET title = ? WHERE id = ?"
            );
            $stmt->execute([
                $position->title,
                $position->id
            ]);
            return $position->id;
        } else {
            $stmt = $this->pdo->prepare(
                "INSERT INTO positions (title) VALUES (?)"
            );
            $stmt->execute([
                $position->title
            ]);
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function delete(int $id): bool {
        // Check if position has employees
        if ($this->getEmployeeCount($id) > 0) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM positions WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    }
}
