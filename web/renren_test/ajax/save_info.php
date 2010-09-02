<?php
require_once('../config.php');
$sess = $_POST;
unset($sess['uid']);
unset($sess['tinyurl']);
$sess['pid'] = $_REQUEST['uid'];
$sess['name']=$_REQUEST['name'];
$sess['icon']=$_REQUEST['tinyurl'];
print_r($_POST);
print_r($_REQUEST);
if(!$sess['pid'])
	die('{"e":"no pid set"}');
$sess = TTGenid::genid($sess);
$uid = $sess['id'];
if(!$uid){
	echo "{'s':'fail','msg':'not find $pid op=$op'}";
	return;
}
echo "{'s':'ok','error':'pid=$pid op=$op'}";

