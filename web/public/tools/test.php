<?php
$rbyt=1;
$rbyt1=2;
$t = &$rbyt;
echo $t."\n";
$t = &$rbyt1;
echo $t."\n";
echo $rbyt."\n";
$obj = array(
		'uid' =>12345,
		'id' => 543,
		'type' => 2,
		'task' => '3ed',
		'clickTime' => 0,
		'count' => 0,
		'date' =>date('Ymd'),
		'rcv' => array(1,2,3)
	);
echo json_encode($obj);