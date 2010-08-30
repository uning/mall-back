<?php
echo "<pre>\n";
require_once '../base.php';
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
$u=$argv[1];
$r=rand(1,5);

echo "user $u\n";
$u = rand(1,20);
if( $r<2 ){
	dotest('GoodsController.checkout',array('u'=>$u));
	exit;

}
record_time($st);
if(!$u){
	$u=$_REQUEST['u'];
}
if(!$u){
	$pid = $argv[2];
	if(!$pid){
		$pid=$_REQUEST['pid'];
	}
	if(!$pid){
		$pid = $_COOKIE['user_name'];

	}
	if(!$pid){
		die( "no param");

	}
	$data = TTGenid::getbypid($pid);
}else
	$data = TTGenid::getbyid($u);

$nu = $data['id'];
if($nu)
	$u=$nu;
if(!$data)
	die( "no u get");
$tu = new TTUser($u);
print_r($data);
$tt=$tu->getAll(false);
print_r(TTExtend::processmap($tt));
print_r($tt);
record_time($st,'end get');
