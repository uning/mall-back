<?php
require_once 'base.php';
require_once 'test_server.php';

function get_session($id,$is_pid=false)
{
	static $sess;
	if($sess)
		return $sess;
	if($is_pid)
		return $sess = TTGenid::getbypid($id);
	return $sess = TTGenid::getbyid($id);
}

function auth_renren()
{
	require_once'';
	
}

function auth_playcrab($key)
{
	static $use_auth = false;
	if(!$use_auth){
		return true;	
	} 
	$now = $_SERVER['REQUEST_TIME'];	
	if(!$key){
		$pid  = '';//get by 
		$sess = TTGenid::getbypid($pid);
		$kdata[]=$sess['pid'];
		$kdata[]=$sess['id'];
		$kdata[]=$now;
		return base64_encode(implode(':',$kdata));
	}
	$keyd = base64_decode($key);
	$kdata = explode(':',$keyd,3);
	if($kdata[2]<100){
		return false;
	}
	if($kdata[2]+3600 >$now)
		return $key;
	$kdata[2]=$now;
	return base64_encode(implode(':',$kdata));
	return md5($key.$secret)==$auth;
}


$sess = get_session(1);
print_r($sess);
$now = time();
$keyraw = $sess['pid'].':'.$sess['id'].':'.$now;
$keye  = base64_encode($keyraw);
$keye=rtrim($keye,'=');
echo "keye=$keye\n";
$keyd  = base64_decode($keye);
echo "keyd=$keyd $keyraw\n";
$kdata = explode(':',$keyd,3);
print_r($kdata);




