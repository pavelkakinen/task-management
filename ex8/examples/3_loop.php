<?php

require '../vendor/autoload.php';

$data = ['items' => [1, 2, 3],
         'errors' => ['e1', 'e2', 'e3']
        ];

$latte = new Latte\Engine;
$latte->render('tpl/3_loop.latte', $data);
