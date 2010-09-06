<?php
echo "<pre>\n";
require_once (dirname(__FILE__).'/base.php');
require_once (LIB_ROOT.'/JsonServer.php');

function record_time(&$start,$usage="",$unit=0)
{
	$end  = microtime(true);
	$cost=$end-$start;
	$cost=ceil(1000000*$cost);
	if($unit>0){
		$cost = ceil($cost/$unit);
	}
	if($usage)
		echo "$usage use time $cost us\n";
	$start = $end;
}


function dotest($m,$p=null)
{
	$server = new JsonServer;
	if(!$p)
		$p = JsonServer::getMethodLastCallParam($m);
	echo "method $m\n";
	echo "The params are these as follow:\n";
	print_r( $p );
	echo "The response are these as follow:\n";
	record_time($st);
	print_r($server->doRequest($m,$p));      
	record_time($st," $m ");
	echo "===============================================\n\n";
}
$add = $_REQUEST['add'];
$now = time();
$now += $add;
$pid = $_GET['pid'];
if($pid){
$sess = TTGenid::getbypid($pid);
$u = $sess['id'];
print_r($sess);
}
if(!$u)
$u = $_REQUEST['u'];
if(!$u)
die('nouser');
dotest('GoodsController.dcheckout',array('u'=>$u,'now'=>$now));
return;
