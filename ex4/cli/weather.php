<?php

// some hard coded values
// so that we can start reading without adding first
$temperatures = [8.9, 6.2, 7.1, 9.8];


$opts = getopt('', ['command:', 'value:', 'state:']);

$command = $opts['command'] ?? null;
$value = $opts['value'] ?? null;
$state = $opts['state'] ?? null;


if ($state) {
    $temperatures = unserialize(urldecode($state));
}

if ($command === 'min') {
    print min($temperatures) . PHP_EOL;
} else if ($command === 'add') {
    $temperatures[] = floatval($value);
    print urlencode(serialize($temperatures));
} else if ($command === 'max') {
    print max($temperatures) . PHP_EOL;
}
