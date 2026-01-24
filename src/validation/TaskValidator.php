<?php

namespace App\Validation;

use App\Dto\TaskDto;

class TaskValidator {
    public static function validate(TaskDto $task): ?string {
        $description = trim($task->description);

        if (mb_strlen($description, 'UTF-8') < 5 || mb_strlen($description, 'UTF-8') > 200) {
            return 'Description must be between 5 and 200 characters.';
        }

        return null;
    }
}
