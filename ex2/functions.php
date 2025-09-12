<?php

function getAverageWinterTemp(int $winterStartYear, int $winterEndYear): float {

    $file = fopen(__DIR__ . "/../ex1/data/temperatures-filtered.csv", "r");
    $allTemp = [];

    if (empty($file)) {
        print 'file is empty';
    }

    while (!feof($file)) {
        $dict = fgetcsv($file);

        $year = $dict[0];
        $month = $dict[1];
        $temperature = $dict[4];
        var_dump($year, $month, $temperature);
        if ($year == $winterStartYear && $month == 12) {
            $allTemp[] = $temperature;
        } elseif ($year == $winterEndYear && $month == 1 || $month == 2) {
            $allTemp[] = $temperature;
        }
    }
    fclose($file);
    $result = array_sum($allTemp) / count($allTemp);

    return round($result, 2);
}

print getAverageWinterTemp(2021, 2022);