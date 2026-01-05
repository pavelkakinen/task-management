<?php

require_once 'Color.php';

const ERROR_MESSAGE = 'Tingimuste kinnitamine on kohustuslik!';

$colors = [ 'red' => new Color('red', 'Punane', 'my-red'),
    'green' => new Color('green', 'Rohenline', 'my-green'),
    'blue' => new Color('blue', 'Sinine', 'my-blue')];


$subTemplatePath = 'form.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $command = $_POST['cmd'] ?? '';
    $color = $_POST['color'] ?? '';

    if ($command == 'select' && $color != '') {
        $errorMessage = '';
        $subTemplatePath = 'confirm.php';
    }

    if ($command == 'forward') {
        $conditions = $_POST['conditions'] ?? '';
        if ($conditions == '') {
            $errorMessage = ERROR_MESSAGE;
            $subTemplatePath = 'confirm.php';
        } else {
            // header
            header('Location: ' . 'index.php?color=' . urlencode($colors[$color]->className));
            exit();
        }
    }
}
// GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['color'])) {
    $className = $_GET['color'];
    $subTemplatePath = 'final.php';
}

include('main.php');