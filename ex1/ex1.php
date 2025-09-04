<?php

$numbers = [1, 2, '3', 6, 2, 3, 2, 3];

$count = 0;

// kood tuleb siia
foreach ($numbers as $number) {
    if ($number === 3) {
        $count++;
    }
}

print "Found it $count times";
