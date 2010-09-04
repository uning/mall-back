<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
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
	die( "$u $pid no u get");
$tu = new TTUser($u);
print_r($data);
$tt=$tu->getAll(false);
print_r(TTExtend::processmap($tt));
print_r($tt);
?>
</body>
</html>
