<?php

$sampleData = [
    ['type' => 'apple', 'weight' => 0.21],
    ['type' => 'orange', 'weight' => 0.18],
    ['type' => 'pear', 'weight' => 0.16],
    ['type' => 'apple', 'weight' => 0.22],
    ['type' => 'orange', 'weight' => 0.15],
    ['type' => 'pear', 'weight' => 0.19],
    ['type' => 'apple', 'weight' => 0.09],
    ['type' => 'orange', 'weight' => 0.24],
    ['type' => 'pear', 'weight' => 0.13],
    ['type' => 'apple', 'weight' => 0.25],
    ['type' => 'orange', 'weight' => 0.08],
    ['type' => 'pear', 'weight' => 0.20],
];

function getAverageWeightsByType(array $list): array {

    // kood tuleb siia
    $fruitWeightsByType = [];

    foreach ($list as $item) {
        addWeightToArray($fruitWeightsByType, $item['type'], $item['weight']);
    }

    $result = [];
    foreach ($fruitWeightsByType as $type => $weights) {
        $result[$type] = calculateAverage($weights);
    }

    return $result;
}

function addWeightToArray(array &$fruitWeightsByType, string $type, float $weight): void {
    $fruitWeightsByType[$type][] = $weight;
}

function calculateAverage(array $list): float {
    return round(array_sum($list) / count($list), 2);
}

// testimiseks
print_r(getAverageWeightsByType($sampleData));
