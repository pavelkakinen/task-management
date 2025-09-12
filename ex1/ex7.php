<?php

function getDaysUnderTemp(int $targetYear, float $targetTemp): float {

    // kood tuleb siia

    $file = fopen(__DIR__ . "/data/temperatures-filtered.csv", "r");
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
    fclose($file);

    return round($counter / 24, 2);
}

$result = getDaysUnderTemp(2021, -5);
echo $result;