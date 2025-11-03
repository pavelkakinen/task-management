<?php

require '../vendor/autoload.php';

$data = ['message' => 'Success',
         'error' => null,
         'showBlock' => false
        ];

$latte = new Latte\Engine;
$latte->render('tpl/2_condition.latte', $data);
