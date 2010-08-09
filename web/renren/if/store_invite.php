<?php
require_once('../config.php');
header('Location: '.RenrenConfig::$canvas_url.'?f=invite');
$linkid = $_REQUEST['linkid'];
$gid = $_REQUEST["gift"];
$pid = $_REQUEST['pid'];
$ids = $_REQUEST['ids'];

if($pid &&$ids && $linkid){
	file_put_contents('store_invite.txt',print_r($_REQUEST,true));
	$tw = TT::TTWeb();
	$_REQUEST['id']=$linkid;
	$tw->puto($_REQUEST);
}
file_put_contents('store_invite.txt',print_r($_REQUEST,true));
//echo "<pre>\n";
//print_r($_REQUEST);
	

