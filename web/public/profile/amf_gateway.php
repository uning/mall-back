<?php

$st=microtime(true);
require_once '../base.php';


try{

	//	require_once CONTROLLER_ROOT.'UserController.php';

	$server= get_server();
	$server->setProduction(false);


	$server->addDirectory(CONTROLLER_ROOT);
	//$server->setClass('UserController');

	$response = $server->handle();
	echo $response;
	$et=microtime(true);
	$cost=$et-$st;
	$cost=ceil(1000000*$cost)." us";
	CrabTools::myprint($cost,RES_DATA_ROOT."/$et.process_time");
}
catch (Exception $e){
	CrabTools::mydump($e,RES_DATA_ROOT.'/handler.exception');
	CrabTools::mydump($server,RES_DATA_ROOT.'/server.obj');
	CrabTools::mydump($response,RES_DATA_ROOT.'/response.str');

}
