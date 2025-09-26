<?php

$command = 'php weather.php --command add --value 3.3';

$programState = trim(shell_exec($command));


// $programState should contain (among other things) the added value 3.3


$command = 'php weather.php --command min --state ' . $programState;

$output = trim(shell_exec($command));

print($output . PHP_EOL); // should print 3.3
