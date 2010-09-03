<?php
require_once 'renren.php';
require_once '../../config.php';
$ren = new Renren();
$ren -> api_key = RenrenConfig::$api_key;
$ren -> secret = RenrenConfig::$secret;
$ren -> init();
$re = $ren->api_client->users_getInfo(229483360);
print_r($re);  
