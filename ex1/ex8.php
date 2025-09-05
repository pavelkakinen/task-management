<?php

function getDaysUnderTempDictionary(float $targetTemp): array {
    $inputFile = fopen('data/temperatures-filtered.csv', "r");
    $result = [];

    while (! feof($inputFile)) {
        $dict = fgetcsv($inputFile);

        if (empty($dict)) {
            continue;
        }

        $year = $dict[0];
        $temp = $dict[4];

        if (floatval($temp) <= $targetTemp) {
            increaseThisYearCounter($result, $year);
        }
    }

    foreach ($result as $key => $value) {
        $result[$key] = round($value / 24, 2);
    }

    return $result;
}

function increaseThisYearCounter(array &$result, string $year): void {
    if (!isset($result[$year])) {
        $result[$year]++;
    }
}


function dictToString(array $dict): string {

    $result = [];

    foreach ($dict as $key => $value) {
        $result[] = "$key => $value";
    }
    $result = join(', ', $result);

    return "[$result]";
}


print dictToString(getDaysUnderTempDictionary(5.1));