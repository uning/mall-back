<?php

/*
平台相关配置
缓存配置及连接获取
 */
define('IS_PRODUCTION',0);
define('CURRENT_PLATFORM','test'); //vk,renren,manyou,facebook //(const is for short alias )
# tools from lib
require_once LIB_ROOT.'CrabTools.php';
define('ZEND_ROOT',WORK_ROOT.'backend_common'.DS);
set_include_path(ZEND_ROOT. PATH_SEPARATOR.PATH_SEPARATOR.get_include_path());
require_once LIB_ROOT.'Logger.php';
require_once CONTROLLER_ROOT.'BaseController.php';
/*
require_once LIB_ROOT.'ServerConfig.php';
require_once LIB_ROOT.'AutoIncIdGenerator.php';
require_once LIB_ROOT.'MemcacheClient.php';
require_once LIB_ROOT.'DbText.php';
define('ZEND_ROOT',WORK_ROOT.'backend_common'.DS);
set_include_path(ZEND_ROOT. PATH_SEPARATOR.PATH_SEPARATOR.get_include_path());
require_once 'Zend'.DS.'Loader'.DS.'Autoloader.php';
require_once LIB_ROOT.'Logger.php';
#for zend
#useage:  Logger::debug,  Logger:::info,  Logger::error
*/
require_once "ttserver/TT.php";
require_once "ItemConfig.php";
require_once "AchieveConfig.php";
require_once "TaskConfig.php";
require_once "AdvertConfig.php";
require_once "UpgradeConfig.php";
require_once "AchieveUpdate.php";
/*
require_once MODEL_ROOT.'ModelFactory.php';
require_once LIB_ROOT.'cassandra/config.php';
CassandraConn::add_node('localhost', 9160);
*/

/*
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
    
    public static $messages_db=array(  //we use ttserver or memcachedb 
		array('127.0.0.1',11211)
	);	

	//for db text system  
	public static $text_mysql_db=array (
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'charset'     =>'utf8',
		'dbname'   => 'mall_ts');

	//for generate id
	public static $genid_mysql_db=array (
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'charset'     =>'utf8',
		'dbname'   => 'mall_genid');

	// Cache Settings 
	public static $cache_server=array(  //we use  memcache
		array('127.0.0.1',11211)
	);	

}*/

