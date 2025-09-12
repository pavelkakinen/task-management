<?php

require_once '../ex1/ex7.php'; // use existing code
require_once '../ex1/ex8.php';
require_once 'functions.php'; // separate functions from main program

$opts = getopt('c:y:t:', ['command:', 'year:', 'temp:']);

$command = $opts['command'] ?? $opts['c'] ?? null;
$year = $opts['year'] ?? $opts['y'] ?? null;
$temp = $opts['temp'] ?? $opts['t'] ?? null;


if ($command === 'days-under-temp') {
    // validate that required parameters are provided
    // if not show error and exit
    // calculate result using getDaysUnderTemp()
    // print result
    if ($year && $temp) {
        echo getDaysUnderTemp((int)$year, (float)$temp);
    } else {
        showError('Not required parameters: year or temp');
    }

} else if ($command === 'days-under-temp-dict') {
    // validate that required parameters are provided
    // if not show error and exit
    // calculate result using getDaysUnderTempDictionary()
    // print result
    if ($temp) {
        echo getDaysUnderTempDictionary((float)$temp);
    } else {
        showError('Not required parameters: temp');
    }
} else {
    showError('command is missing or is unknown');
}

function showError(string $message): void {
    fwrite(STDERR, $message . PHP_EOL);
    exit(1);
}
