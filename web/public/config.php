<?php

define('IS_PRODUCTION',0);
define('CURRENT_PLATFORM','test'); //vk,renren,manyou,facebook //(const is for short alias )
# tools from lib
require_once LIB_ROOT.'CrabTools.php';
define('ZEND_ROOT',WORK_ROOT.'backend_common'.DS);
set_include_path(ZEND_ROOT.PATH_SEPARATOR.get_include_path());
require_once LIB_ROOT.'Logger.php';

require_once "ttserver/test_config.php";
require_once "ItemConfig.php";
require_once "AdvertConfig.php";
require_once "UpgradeConfig.php";
