<?php
//header("Content-type: text/json; charset=utf-8");
require_once 'base.php';
require_once 'JsonServer.php';

try{
	$server= new JsonServer();
	echo $server->handle();
}catch (Exception $e)
{
	$ret['s']='KO';
	$ret['msg']=$e->getMessage();
	echo json_encode($ret);
}


