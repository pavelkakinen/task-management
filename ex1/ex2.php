<?php

$list = [1, 2, 3, 2, 6];

var_dump(isInList($list, '3'));

var_dump(isInList([1, 2, 3], 4));

function isInList($list, $target): bool {
    // kood tuleb siia
    return in_array($target, $list, true);
}




