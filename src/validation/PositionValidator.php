<?php

namespace App\Validation;

use App\Dto\PositionDto;

class PositionValidator {
    public static function validate(PositionDto $position): ?string {
        $title = trim($position->title);

        if (mb_strlen($title, 'UTF-8') < 2 || mb_strlen($title, 'UTF-8') > 100) {
            return 'Position title must be between 2 and 100 characters.';
        }

        return null;
    }
}
