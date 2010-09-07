<?php
    require_once('config.php');
    $myloc=dirname(__FILE__);
    require_once 'renren.php';
    require_once $myloc.'/config.php';
    $renren = new Renren();
    $renren->api_key = RenrenConfig::$api_key;
    $renren->secret = RenrenConfig::$secret;
    $renren->init();
?>
<xn:if-is-app-user>

