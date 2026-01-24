<?php

namespace App\Security;

class InputSanitizer {
    /**
     * Sanitize a string for safe storage and display
     */
    public static function sanitizeString(string $input): string {
        // Trim whitespace
        $input = trim($input);

        // Remove null bytes
        $input = str_replace("\0", '', $input);

        // Remove control characters except newlines and tabs
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);

        return $input;
    }

    /**
     * Sanitize a name (first name, last name)
     */
    public static function sanitizeName(string $input): string {
        $input = self::sanitizeString($input);

        // Names should only contain letters, spaces, hyphens, apostrophes
        // Allow Unicode letters for international names
        $input = preg_replace('/[^\p{L}\s\-\']/u', '', $input);

        // Collapse multiple spaces
        $input = preg_replace('/\s+/', ' ', $input);

        return trim($input);
    }

    /**
     * Sanitize a position/title
     */
    public static function sanitizePosition(string $input): string {
        $input = self::sanitizeString($input);

        // Allow letters, numbers, spaces, common punctuation
        $input = preg_replace('/[^\p{L}\p{N}\s\-\.,&\/\(\)]/u', '', $input);

        // Collapse multiple spaces
        $input = preg_replace('/\s+/', ' ', $input);

        return trim($input);
    }

    /**
     * Sanitize a task description
     */
    public static function sanitizeDescription(string $input): string {
        $input = self::sanitizeString($input);

        // Allow letters, numbers, spaces, common punctuation
        $input = preg_replace('/[^\p{L}\p{N}\s\-\.,!?:;\(\)\'\"]/u', '', $input);

        // Collapse multiple spaces
        $input = preg_replace('/\s+/', ' ', $input);

        return trim($input);
    }

    /**
     * Sanitize an integer ID
     */
    public static function sanitizeId($input): ?int {
        if ($input === null || $input === '') {
            return null;
        }

        $id = filter_var($input, FILTER_VALIDATE_INT);

        if ($id === false || $id < 1) {
            return null;
        }

        return $id;
    }
}
