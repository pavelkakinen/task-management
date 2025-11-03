<?php

require_once 'OrderLine.php';
require_once 'OrderLineRepository.php';

$lines = file('orders.txt');

$orderLines = [];
foreach ($lines as $line) {

    [$name, $price, $inStock] = explode(';', trim($line));

    printf('name: %s, price: %s; in stock: %s' . PHP_EOL,
        $name, $price, $inStock);
}
