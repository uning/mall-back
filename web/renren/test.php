<?php
/*	$myloc=dirname(__FILE__);
    require_once  $myloc.'/bg/base.php'; 
	require_once LIB_ROOT.'ModelFactory.php';
	require_once LIB_ROOT.'AutoIncIdGenerator.php';
    $platform_id = "302268025";
    $pid = "renren302268025";
	$user_id = AutoIncIdGenerator::genid($platform_id);
	$p_user = AutoIncIdGenerator::genid($pid);
	echo "user_id = $user_id\n";
	echo "p_user = $p_user\n";
require_once('config.php');
$linkid =':4c60c7314f3ef';
$tw = TT::TTWeb();
	echo 'i.m here<br>';	
	$irec = $tw->getbyid($linkid);
	print_r($irec);*/
require_once 'config.php';
$user = new TTUser(13);
$v = $user->getbyid('13:o:4c7dd4debd11e');
print_r($v);
$v['tag']=60002;
$user->puto($v);
?>
