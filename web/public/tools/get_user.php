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
if($data['id'])
	$u = $data['id'];
if(!$data)
	die( "no u get");
$tu = new TTUser($u);
print_r($data);
$tt=$tu->getAll(false);
print_r(TTExtend::processmap($tt));
print_r($tt);
