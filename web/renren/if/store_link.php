<?php
require_once('../config.php');
$linkid = $_REQUEST['linkid'];
$pid = $_REQUEST['pid'];
$ids = $_REQUEST['ids'];
if($pid &&$ids && $linkid){
	$tw = TT::LinkTT();
	$value = $tw->getbyuidx('linkid',$linkid);
	if(!$value){
		$value = array('linkid'=>$linkid,
		'uid'=>$pid,
		'date'=>date('Ymd'),
		'geted'=> array(),
		'ids' =>$_REQUEST['ids']
		);
		$tw->put($value);
		
	}
	
}
header('Location: '.RenrenConfig::$canvas_url.'?c=invite');