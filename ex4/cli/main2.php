<?php

$command = 'php weather.php --command add --value 3.3';

$programState = trim(shell_exec($command));

// $programState should contain (among other things) the added value 3.3

print($programState . PHP_EOL); // prints something like 3.3%3B...
