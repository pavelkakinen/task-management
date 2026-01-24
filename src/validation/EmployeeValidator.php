<?php

namespace App\Validation;

use App\Dto\EmployeeDto;

class EmployeeValidator {
    public static function validate(EmployeeDto $employee): ?string {
        $firstName = trim($employee->firstName);
        $lastName = trim($employee->lastName);

        if (mb_strlen($firstName, 'UTF-8') < 1 || mb_strlen($firstName, 'UTF-8') > 21) {
            return 'First name must be between 1 and 21 characters.';
        }

        if (mb_strlen($lastName, 'UTF-8') < 2 || mb_strlen($lastName, 'UTF-8') > 22) {
            return 'Last name must be between 2 and 22 characters.';
        }

        return null;
    }
}
