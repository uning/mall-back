<?php
require_once('../config.php');
$linkid = $_REQUEST['linkid'];
$gid = $_REQUEST["gift"];
$pid = $_REQUEST['pid'];
$ids = $_REQUEST['ids'];
echo '<script type="text/javascript">alert(1);</script>';
if($pid &&$ids && $linkid){
	$tw = TT::TTWeb();
	$_REQUEST['id']=$linkid;
	$_REQUEST['invalid'] = false;
	$tw->puto($_REQUEST);
	file_put_contents('store_invite.txt',print_r($_REQUEST,true));
}
file_put_contents('store_invite.txt',print_r($_REQUEST,true));
header('Location: '.RenrenConfig::$canvas_url.'?f=invite');
//echo "<pre>\n";
//print_r($_REQUEST);
	

