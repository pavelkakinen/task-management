<?php

$numbers = [1, 2, '3', 6, 2, 3, 2, 3];

$count = 0;

foreach ($numbers as $number) {
    if ($number === 3) {
        $count++;
    }
}

print "Found it $number times";
