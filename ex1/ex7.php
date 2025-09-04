<?php

function getDaysUnderTemp(int $targetYear, float $targetTemp): float {

    // kood tuleb siia

    $file = fopen("data/temperatures-filtered.csv", "r");
    $counter = 0;

    while(! feof($file)) {
        $dict = fgetcsv($file);

        if (empty($dict)) {
            continue;
        }

        if ($targetYear === intval($dict[0]) && floatval($dict[4]) <= $targetTemp) {
            $counter++;
        }

    }

    return round($counter / 24, 2);
}

print getDaysUnderTemp(2021, -10);