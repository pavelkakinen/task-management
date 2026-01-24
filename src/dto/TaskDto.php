<?php

namespace App\Dto;

class TaskDto {
    public ?int $id;
    public string $description;
    public ?int $employeeId;
    public bool $isCompleted;
    public ?string $employeeFirstName;
    public ?string $employeeLastName;

    public function __construct(
        ?int $id = null,
        string $description = '',
        ?int $employeeId = null,
        bool $isCompleted = false,
        ?string $employeeFirstName = null,
        ?string $employeeLastName = null
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->employeeId = $employeeId;
        $this->isCompleted = $isCompleted;
        $this->employeeFirstName = $employeeFirstName;
        $this->employeeLastName = $employeeLastName;
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['description'] ?? '',
            $data['employeeId'] ?? null,
            (bool)($data['isCompleted'] ?? false),
            $data['firstName'] ?? null,
            $data['lastName'] ?? null
        );
    }
}
