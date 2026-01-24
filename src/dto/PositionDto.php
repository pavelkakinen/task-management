<?php

namespace App\Dto;

class PositionDto {
    public ?int $id;
    public string $title;
    public int $employeeCount;

    public function __construct(
        ?int $id = null,
        string $title = '',
        int $employeeCount = 0
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->employeeCount = $employeeCount;
    }

    public static function fromArray(array $data): self {
        return new self(
            $data['id'] ?? null,
            $data['title'] ?? '',
            $data['employeeCount'] ?? 0
        );
    }
}
