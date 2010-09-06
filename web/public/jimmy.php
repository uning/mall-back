<?php
require_once (dirname(__FILE__).'/base.php');
require_once (LIB_ROOT.'/JsonServer.php');
//ini_set("memory_limit","20M");

function dotest($m,$p=null)
{
	$server = new JsonServer;
	if(!$p)
		$p = JsonServer::getMethodLastCallParam($m);
	echo "method $m\n";
        echo "The params are these as follow:\n";
        print_r( $p );
        echo "The response are these as follow:\n";
        print_r($server->doRequest($m,$p));      
        echo "===============================================\n\n";
}
dotest( 'Tool.add_friends',$p = array( 'pids'=>"task00,tianyuan,wely111,pb02005100,test965" ) );
//dotest('CarController.go_goods',$p= array('u'=>25,'tag'=>1001));