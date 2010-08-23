<?php
require_once('../config.php');
$linkid = $_REQUEST['linkid'];
$gid = $_REQUEST["gift"];
$pid = $_REQUEST['pid'];
$ids = $_REQUEST['ids'];
$key  = date().$pid;
if($pid &&$ids && $linkid){
	$tw = TT::LinkTT();
	$value = $tw->getbyuidx('udate',$key);
	if(!$value){
		$value = array('udate'=>date('Ymd').$pid,$linkid=>array(
		'geted'=> array(),
		'ids' =>$_REQUEST['ids']
		));
		$tw->put($value);
		
	}
	else {
		$value[$linkid]=array('geted'=>array(),'ids'=>$_REQUEST['ids']);
		$tw->put($value);
	}
	print_r($value);
}
//header('Location: '.RenrenConfig::$canvas_url.'?f=invite');
	

