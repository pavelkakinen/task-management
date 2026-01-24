<?php

namespace App\Dto;

class EmployeeDto {
    public ?int $id;
    public string $firstName;
    public string $lastName;
    public ?int $positionId;
    public ?string $positionTitle;
    public int $taskCount;
    public ?string $picture;

    public function __construct(
        ?int $id = null,
        string $firstName = '',
        string $lastName = '',
        ?int $positionId = null,
        ?string $positionTitle = null,
        int $taskCount = 0,
        ?string $picture = null
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->positionId = $positionId;
        $this->positionTitle = $positionTitle;
        $this->taskCount = $taskCount;
        $this->picture = $picture;
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['firstName'] ?? '',
            $data['lastName'] ?? '',
            $data['positionId'] ?? null,
            $data['positionTitle'] ?? ($data['title'] ?? null),
            $data['taskCount'] ?? 0,
            $data['picture'] ?? null
        );
    }
}
