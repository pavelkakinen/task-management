<?php

ini_set('display_errors', '1');

require_once '../ex1/ex7.php';
require_once '../ex2/functions.php';

//var_dump($_GET);
//var_dump($_POST);

$command = $_POST['command'] ?? 'show-form';
$page = $_GET['page'] ?? 'days-under-temp';

if ($command === 'show-form') {

    if ($page === 'days-under-temp') {
        include 'pages/days-under-temp.php';
    } elseif ($page === 'avg-winter-temp') {
        include 'pages/avg-winter-temp.php';
    }

} else if ($command === 'days-under-temp') {

    $year = $_POST['year'] ?? '2021';
    $temp = $_POST['temp'] ?? 0;

    $daysUnderTemp = @getDaysUnderTemp((int)$year, (float)$temp);

    $message = "Days under temp: $daysUnderTemp";

    include 'pages/result.php';

} else if ($command === 'avg-winter-temp') {

    $years = $_POST['year'] ?? '2021/2022';

    $startYear = intval(explode('/', $years)[0]);
    $endYear = intval(explode('/', $years)[1]);

    $avgWinterTemp = @getAverageWinterTemp($startYear, $endYear);

    $message = $avgWinterTemp;

    include 'pages/result.php';
    exit();

} else {
    throw new Error('unknown command: ' . $command);
}

