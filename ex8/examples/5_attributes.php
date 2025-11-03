<?php

require '../vendor/autoload.php';

$data = ['success' => true,
         'error' => false,
         'gender' => 'F'
        ];


$latte = new Latte\Engine;
$latte->render('tpl/5_attributes.latte', $data);
