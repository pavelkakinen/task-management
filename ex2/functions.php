<?php

function getAverageWinterTemp(int $winterStartYear, int $winterEndYear): float {

    $file = fopen(__DIR__ . "/../ex1/data/temperatures-filtered.csv", "r");
    $allTemp = [];

    if (!$file) {
        return 0.0;
    }

    while (!feof($file)) {
        $dict = fgetcsv($file);

        if (empty($dict)) {
            continue;
        }

        $year = intval($dict[0]);
        $month = intval($dict[1]);
        $temperature = floatval($dict[4]);
        if ($year === $winterStartYear && $month === 12) {
            $allTemp[] = $temperature;
        } elseif ($year === $winterEndYear && ($month === 1 || $month === 2)) {
            $allTemp[] = $temperature;
        }
    }

    fclose($file);
    if (empty($allTemp)) {
        return 0;
    }
    $result = (array_sum($allTemp) / count($allTemp));

    return round($result, 2);
}
