<?php
require_once('../config.php');
$linkid = $_REQUEST['linkid'];
$gid = $_REQUEST["gift"];
$pid = $_REQUEST['pid'];
$ids = $_REQUEST['ids'];
/*if($pid &&$ids && $linkid){
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
	//print_r($value);
}*/
	$date = date('Ymd');
	$_REQUEST['date'] = $date;
	$tw = TT::LinkTT();
	$value = $tw->getbyuidx('uid',$pid);
	if(!$value)
	{
		$value = array('uid'=>$pid,'invite'=>$ids,'accepted'=>array(),'time'=>$date);
	}
	else 
	{
		if($value['time']!=$date){
			$value['time']=$date;
			$value['invite'] = $ids;
		}
		else
		{
			array_merge($value['invite'],$ids);
		}
	}
	$_REQUEST['geted'] =array();
	if($pid){
	$tw->put($value);
	$tw->put($_REQUEST);
	}
header('Location: '.RenrenConfig::$canvas_url.'?f=invite');
	

