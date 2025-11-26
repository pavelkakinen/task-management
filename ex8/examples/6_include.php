<?php

require '../vendor/autoload.php';

$data = ['subTemplatePath' => '6_items_fragment.latte',
         'items' => [1, 2, 3]
        ];

$latte = new Latte\Engine;
$latte->render('tpl/6_include.latte', $data);
