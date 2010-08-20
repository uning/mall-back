<?php
echo "<pre>\n";
require_once '../base.php';
$u=$argv[1];
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

$u = $data['id'];
if(!$u)
	die( "no u get");
$tu = new TTUser($u);
$tu->numch('money',100000);
$tu->numch('gem',500);
echo "OK\n";
