<?php
require_once('../config.php');
$linkid = $_REQUEST['linkid'];
$gid = $_REQUEST["gift"];
$pid = $_REQUEST['pid'];
$ids = $_REQUEST['ids'];
if($pid &&$ids && $linkid){
	$tw = TT::LinkTT();
	$value = $tw->getbyuidx('fid',$key);
	if(!$value){
		$_REQUEST['id']=$linkid;
		$_REQUEST['udate'] = date('Ymd').$pid;
		$_REQUEST['invalid'] = false;
		$tw->put($_REQUEST);
	}
	else {
		array_merge($value['ids'],$_REQUEST['ids']);
		$tw->put($value);
	}
	file_put_contents('store_invite1.txt',print_r($_REQUEST,true));
}
file_put_contents('store_invite2.txt',print_r($_REQUEST,true));
header('Location: '.RenrenConfig::$canvas_url.'?f=invite');
echo "<pre>\n";
print_r($_REQUEST);
	

