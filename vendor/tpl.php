<?php
require_once __DIR__ . '/autoload.php';

use Latte\Engine;

function render(string $template, array $data = []): void {
    static $latte = null;

    if ($latte === null) {
        $latte = new Engine;
        $latte->setTempDirectory('/tmp/latte-icd0007');  // ← Системная папка!
    }

    $templatePath = __DIR__ . '/../templates/' . $template . '.latte';
    $latte->render($templatePath, $data);
}