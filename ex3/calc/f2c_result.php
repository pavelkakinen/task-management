<?php

require_once "functions.php";

$input = $_POST['temperature'] ?? null;

if ($input === null || $input == "") {
    $message = 'Insert temperature';
} elseif (!is_numeric($input)) {
    $message = 'Temperature must be an integer';
} else {
    $result = f2c(floatval($input));

    $message = sprintf("%d degrees in Celsius is %d degrees in Fahrenheit", $input, $result);
}


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Fahrenheit to Celsius</title>
</head>
<body>

    <nav>
        <a href="index.html" id="c2f">Celsius to Fahrenheit</a> |
        <a href="f2c.html" id="f2c">Fahrenheit to Celsius</a>
    </nav>

    <main>

        <h3>Fahrenheit to Celsius</h3>

        <em><?php print $message ?></em>

<!--        <em>Insert temperature</em> /<br>-->
<!--        <em>Temperature must be an integer</em> /<br>-->
<!--        <em>x degrees in Fahrenheit is y degrees in Celsius</em>-->

    </main>

</body>
</html>
