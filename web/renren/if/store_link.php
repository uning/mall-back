<?php
require_once('../config.php');
$linkid = $_REQUEST['linkid'];
$pid = $_REQUEST['pid'];
$ids = $_REQUEST['ids'];
$oid = $_REQUEST['oid'];
if($pid &&$ids && $linkid&&$oid){
	$tw = TT::LinkTT();
	$value = $tw->getbyuidx('linkid',$linkid);
	if(!$value){
		/*$value = array('linkid'=>$linkid,
		'uid'=>$pid,
		'date'=>date('Ymd'),
		'geted'=> array(),
		'ids' =>$_REQUEST['ids']*/
		//);
		
		$_REQUEST['geted'] = array();
		$_REQUEST['time'] = date('Ymd');
		$tw->put($_REQUEST);
		//print_r($_REQUEST);
		TTLog::record(array('m'=>'open_shop_invite','tm'=> $_SERVER['REQUEST_TIME'],'u'=>$pid));
	}
	
}
header('Location: '.RenrenConfig::$canvas_url.'?c=invite');