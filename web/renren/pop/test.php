<?php
$array = array('blue', 'red', 'green', 'red2');

$key = array_search('green', $array); // $key = 2;
echo $key;
$key = array_search('red', $array);   // $key = 1;
echo $key;
?> 