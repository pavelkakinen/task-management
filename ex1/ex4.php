<?php

use function Sodium\add;

$input = '[1, 4, 2, 0]';
function stringToIntegerList(string $input): array
{
    // kood tuleb siia
    $cleanString = str_replace(['[', ']', ' '], '', $input);
    $explodedString = explode(',', $cleanString);

    $result = [];
    foreach ($explodedString as $item) {
        $result[] = intval($item);
    }
    return $result;
}

// check that the restored list is the same as the input list.
// var_dump($list === [1, 4, 2, 0]); // should print "bool(true)"

