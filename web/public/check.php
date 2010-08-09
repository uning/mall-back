<?php

function record_time(&$start,$usage="")
{
        $end  = microtime(true);
        $cost=$end-$start;
        $cost=ceil(1000000*$cost);
        if($usage)
        echo "$usage use time $cost us\n";
        $start  = $end;
}
echo "<pre>\n";
record_time($st);
require_once('base.php');
foreach(TT::$ttservers as $k=>$v){
	$type = $v['type'];
	//print_r($v);
	foreach($v['procs'] as $kk=>$ps){
		foreach($ps as $h){
			try{
			$flagg= "$k->$type->$kk->{$h['host']}:{$h['port']}";
			record_time($st);
			$tt = new $type($h['host'],$h['port']);
			$num = $tt->num();
			record_time($st,"getnum = $num");
			if($num !== '')
				echo "OK:$flagg\n";
                        }catch (Exception $e){
				echo "FAIL:$flagg\n";
                        }
		}	
	}	
}
require_once (LIB_ROOT.'/JsonServer.php');
JsonServer::registerController('Gift');
JsonServer::registerController('Man');
JsonServer::registerController('DataS');
JsonServer::registerController('UserController');
JsonServer::registerController('ItemController');
JsonServer::registerController('CarController');
JsonServer::registerController('GoodsController');
JsonServer::registerController('TaskController');
JsonServer::registerController('Friend');
function dotest($m,$p=null)
{
	$server = new JsonServer;
	if(!$p)
		$p = JsonServer::getMethodLastCallParam($m);
	if(!$p){
		echo "WARN:$m :param is null\n";
		return;
	}
	record_time($st);
	$r = $server->doRequest($m,$p);      
	if(!$r || !$r['s'])
	echo "FAIL:$m :func error\n";
	record_time($st,$m);
}
$allms = JsonServer::getAllMethod();
foreach($allms as $m){
	dotest($m);
}
		
