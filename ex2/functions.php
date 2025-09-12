<?php

function getAverageWinterTemp(int $winterStartYear, int $winterEndYear): float {

    $file = fopen(__DIR__ . "/../ex1/temperatures-filtered.csv", "r");
    $totalTemp = 0;
    $count = 0;

    // Winter months: December of start year, January and February of end year
    $winterMonths = [
        [$winterStartYear, 12],  // December of start year
        [$winterEndYear, 1],     // January of end year
        [$winterEndYear, 2]      // February of end year
    ];

    while (!feof($file)) {
        $data = fgetcsv($file);

        if (empty($data)) {
            continue;
        }

        $year = intval($data[0]);
        $month = intval($data[1]);
        $temperature = floatval($data[4]);

        // Check if this record is part of the winter period
        foreach ($winterMonths as $winterMonth) {
            if ($year === $winterMonth[0] && $month === $winterMonth[1]) {
                $totalTemp += $temperature;
                $count++;
                break;
            }
        }
    }

    fclose($file);

    if ($count === 0) {
        return 0.0;
    }

    return round($totalTemp / $count, 2);
}

print getAverageWinterTemp(2021, 2022);
