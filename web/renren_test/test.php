<?php
    $myloc=dirname(__FILE__);
    require_once  $myloc.'/bg/base.php'; 
	require_once LIB_ROOT.'ModelFactory.php';
	require_once LIB_ROOT.'AutoIncIdGenerator.php';
    $platform_id = "302268025";
    $pid = "renren302268025";
	$user_id = AutoIncIdGenerator::genid($platform_id);
	$p_user = AutoIncIdGenerator::genid($pid);
	echo "user_id = $user_id\n";
	echo "p_user = $p_user\n";
