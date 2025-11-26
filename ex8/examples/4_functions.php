<?php

require '../vendor/autoload.php';

$data = ['position' => 'position.manager'];

$latte = new Latte\Engine;
$latte->render('tpl/4_functions.latte', $data);

function codeToLabel(string $code) {
    return match($code) {
        'position.manager' => 'Manager',
        'position.developer' => 'Developer',
        'position.designer' => 'Designer'
    };
}
