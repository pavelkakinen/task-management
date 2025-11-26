<?php

function render(string $template, array $data = []): void {
    $templatePath = __DIR__ . '/../templates/' . $template . '.tpl.html';

    if (!file_exists($templatePath)) {
        throw new Exception("Template not found: $template");
    }

    extract($data);

    ob_start();

    include $templatePath;

    $content = ob_get_clean();

    echo $content;
}

function escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function e(string $value): string {
    return escape($value);
}
?>