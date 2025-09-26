<?php

$command = 'php weather.php --command min';

// shell_exec(<command>) runs the provided command as if it were executed from the
//                       command line and returns the output as a string.
// trim(<string>) removes withe space (space, tab, new-line) characters from
//                the start and the end of string

$output = trim(shell_exec($command));

print($output . PHP_EOL); // should print 6.2
