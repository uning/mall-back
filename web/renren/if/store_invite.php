<?php
require_once('../config.php');
require_once '../renren.php';
$linkid = $_REQUEST['lid'];
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
	$_REQUEST['geted'] =array(0);
	if($pid){
	$tw->put($value);
	$tw->put($_REQUEST);
	TTLog::record(array('m'=>'pub_invite','tm'=> $_SERVER['REQUEST_TIME'],'u'=>$pid,'sp2'=>$linkid,'sp1'=>$gid));
	}

	$sessionK = $_REQUEST['sessionK'];
	$renren = new Renren();
	$renren ->session_key = $sessionK;
	$renren ->api_key = RenrenConfig::$api_key;
	$renren ->secret = RenrenConfig::$secret;
	$renren->init($sessionK);
	$noti = '<xn:name uid="'.$pid.'" linked="true"/><a href="'.RenrenConfig::$canvas_url.'">喊你去帮他装货、卸货，顺便帮他抢几个客人</a>';
	$ids = ',';
	foreach ($_REQUEST['ids'] as $id){
		$ids.=$id;
	}
	$ids = substr($ids,1);
	$r = $renren->api_client->notifications_send($ids,$noti);
	
	header('Location: '.RenrenConfig::$canvas_url.'?f=invite&noti='.$r[0]['result']);
	

