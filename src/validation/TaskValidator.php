<?php

require_once __DIR__ . '/../dto/TaskDto.php';

class TaskValidator {
    public static function validate(TaskDto $task): ?string {
        $description = trim($task->description);

        if (strlen($description) < 5 || strlen($description) > 40) {
            return 'Description must be between 5 and 40 characters.';
        }

        return null;
    }
}
