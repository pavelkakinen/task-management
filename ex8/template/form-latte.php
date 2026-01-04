<?php

require '../vendor/autoload.php';

$errors = ['Pealkiri peab olema 2 kuni 10 märki', 'Hinne peab olema määratud'];

$data = [
    'errors' => $errors,
    'title' => 'Head First HTML and CSS',
    'gradeValue' => 4,
    'isRead' => true,
    'isEditForm' => false
];


$latte = new Latte\Engine;

$latte->render('form.latte', $data);
