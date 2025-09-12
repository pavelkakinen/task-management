<?php

$inputFile = fopen("data/temperatures-sample.csv", "r");
$outputFile = fopen("temperatures-filtered.csv", "w");

while(! feof($inputFile)) {
    $dict = fgetcsv($inputFile);


    // kood tuleb siia
    if (empty($dict)) {
        continue;
    }

    $year = $dict[0];
    if ($year == '2004' || $year == '2022') {
        $month = $dict[1];
        $day = $dict[2];
        $hour = $dict[3];
        $temperature = $dict[9];

        $hour = str_replace(':00', '', $hour);

        fputcsv($outputFile, [$year, $month, $day, $hour, $temperature]);
    }

}

fclose($inputFile);
fclose($outputFile);

