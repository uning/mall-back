<?php
require_once 'base.php';
require_once  LIB_ROOT.'JsonServer.php';

echo "<pre>\n";
echo "WEB_ROOT=".WEB_ROOT;


JsonServer::registerController('FriendController');

$cc=new ReflectionClass('FriendController'); 
$ms = $cc->getMethods();
foreach($ms as $m){
	echo $m->name." : ".$m->getDocComment()."\n";
		    	
}
//var_dump($cc->getDocComment());



exit;

try{
	$server= new JsonServer();
	echo $server->handle();
}catch (Exception $e)
{
	$ret['s']='KO';
	$ret['msg']=$e->getMessage();
	echo json_encode($ret);
}




