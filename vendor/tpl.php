<?php
require_once __DIR__ . '/autoload.php';

use Latte\Engine;

function render(string $template, array $data = []): void {
    static $latte = null;

    if ($latte === null) {
        $latte = new Engine;
        $latte->setTempDirectory(__DIR__ . '/../temp');
    }

    $templatePath = __DIR__ . '/../templates/' . $template . '.latte';
    $latte->render($templatePath, $data);
}