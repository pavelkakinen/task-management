<?php

class EmployeeDto {
    public ?int $id;
    public string $firstName;
    public string $lastName;
    public string $position;
    public int $taskCount;

    public function __construct(
        ?int $id = null,
        string $firstName = '',
        string $lastName = '',
        string $position = '',
        int $taskCount = 0
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->position = $position;
        $this->taskCount = $taskCount;
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['firstName'] ?? '',
            $data['lastName'] ?? '',
            $data['position'] ?? '',
            $data['taskCount'] ?? 0
        );
    }

    public function getFullName(): string {
        return $this->firstName . ' ' . $this->lastName;
    }
}
?>