<?php

require_once __DIR__ . '/../dto/EmployeeDto.php';

class EmployeeValidator {
    public static function validate(EmployeeDto $employee): ?string {
        $firstName = trim($employee->firstName);
        $lastName = trim($employee->lastName);

        if (strlen($firstName) < 1 || strlen($firstName) > 21) {
            return 'First name must be between 1 and 21 characters.';
        }

        if (strlen($lastName) < 2 || strlen($lastName) > 22) {
            return 'Last name must be between 2 and 22 characters.';
        }

        return null;
    }
}
?>