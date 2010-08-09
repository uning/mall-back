<?php
require_once 'base.php';
if(!defined('IS_PRODUCTION'))
die('undefine IS_PRODUCTION');
$server= get_server(CURRENT_PLATFORM);
$server->setProduction(IS_PRODUCTION);
$server->addDirectory(CONTROLLER_ROOT);
echo $server->handle();

