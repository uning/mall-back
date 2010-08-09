<?php
/**
 *define some constants such as inc path
 */
error_reporting(E_ALL & ~E_NOTICE);
define('TM_FORMAT','Y-m-d H:i:s');

define('DS',DIRECTORY_SEPARATOR);

define('WEB_ROOT', dirname(__FILE__).DS);
define('WORK_ROOT',realpath(WEB_ROOT.DS.'..'.DS.'..'.DS.'..'.DS.'..').DS);
define('LIB_ROOT',WORK_ROOT.'backend_common'.DS.'phplib'.DS);

//define('PUBA_ROOT',WEB_ROOT.'..'.DS.'webreject'.DS);
define('PUBA_ROOT',WEB_ROOT.'webreject'.DS);
define('CONTROLLER_ROOT',PUBA_ROOT.'controller'.DS);
define('MODEL_ROOT',PUBA_ROOT.'model'.DS);
define('LOG_ROOT',WORK_ROOT.'log/appphp'.DS); //for write




//here two directory must be writable by user who run php
define('REQ_DATA_ROOT',WEB_ROOT.'tests'.DS.'request'.DS);
define('RES_DATA_ROOT',WEB_ROOT.'tests'.DS.'request'.DS);


date_default_timezone_set('Asia/Chongqing');

#other config
require_once WEB_ROOT.'config.php'; #platform specified config
require_once WEB_ROOT.'mysqlconfig.php';
