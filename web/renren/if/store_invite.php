<?php
require_once('../config.php');
$linkid = $_REQUEST['linkid'];
$gid = $_REQUEST["gift"];
$pid = $_REQUEST['pid'];
$ids = $_REQUEST['ids'];
if($pid &&$ids && $linkid){
	$tw = TT::LinkTT();
	$value = $tw->getbyuidx('uid',$pid);
	if(!$value){
		$value = array('uid'=>$pid,$linkid=>array(
		'date'=>date('Ymd'),
		'geted'=> array(),
		'ids' =>$_REQUEST['ids'],
		'gift'=>$gid
		),
		'today'=>array('date'=>date('Ymd'),'invite'=>$_REQUEST['ids'],'getted'=>array())
		);
		$tw->put($value);
		
	}
	else {
		$value[$linkid]=array('geted'=>array(),'ids'=>$_REQUEST['ids'],'gift'=>$gid,'date'=>date('Ymd'));
		if($value['today']['date']!=date('Ymd')){
			$value['today'] = date('Ymd');
			$value['today']['invite'] = $_REQUEST['ids'];
			$value['today']['getted'] = array();
		}
		else{
			array_merge($value['today']['invite'],$_REQUEST['ids'])	;
		}
		$tw->put($value);
	}
	print_r($value);
}
//header('Location: '.RenrenConfig::$canvas_url.'?f=invite');
	

