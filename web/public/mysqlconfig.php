<?php

define('IS_PRODUCTION',0);
define('CURRENT_PLATFORM','test'); //vk,renren,manyou,facebook //(const is for short alias )
//require_once CONTROLLER_ROOT.'BaseController.php';
//require_once LIB_ROOT.'ServerConfig.php';
require_once LIB_ROOT.'ServerConfig.php';
require_once LIB_ROOT.'DBModel.php';
require_once MODEL_ROOT.'ModelFactory.php';

class BasicConfig extends ServerConfig
{
    public static $lang='';
    
    const USERNUM_PERDB   = 3000000;
    const USERNUM_PERTTDB = 1000000;

	public static $main_mysql_db=array(
	   	array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'port'     =>'3306',
		'charset'     =>'utf8',
		'dbname'   => 'mall_data_0')
	);
}
