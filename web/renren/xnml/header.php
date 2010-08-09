<?php
    $myloc=dirname(__FILE__);
    require_once  $myloc.'/bg/base.php'; 
    require_once LIB_ROOT.'ModelFactory.php';
	require_once LIB_ROOT.'AutoIncIdGenerator.php';
    require_once LIB_ROOT.'renren/renren.php';
    require_once $myloc.'/config.php';
    $ts = filemtime($myloc."/css/style.css.php");
    $renren = new Renren();
	$renren->api_key = RenrenConfig::$api_key;
	$renren->secret = RenrenConfig::$secret;
	$renren->init();
?>
<xn:if-is-app-user>

