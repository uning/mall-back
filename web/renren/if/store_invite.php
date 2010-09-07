<?php
require_once('../config.php');
require_once '../renren.php';
$linkid = $_REQUEST['lid'];
$gid = $_REQUEST["gift"];
$pid = $_REQUEST['pid'];
$ids = $_REQUEST['ids'];
if(!is_array($ids)){
	$ids = array($pid);
}
	$date = date('Ymd');
	$_REQUEST['date'] = $date;
	$tw = TT::LinkTT();
	$value = $tw->getbyuidx('uid',$pid);
	if(!$value)
	{
		$value = array('uid'=>$pid,'invite'=>array_flip($ids),'accepted'=>array(),'time'=>$date);
	}
	
	if(strtotime($value['time'])!=strtotime($date)){
		$value['time']=$date;
		$value['invite'] = array_flip($ids);
		$_REQUEST['ids'] = array_flip($ids);
	}
	else
	{
		$ids = array_flip($ids);
		foreach ($ids as $k=>$v){
			if(array_key_exists($k,$value['invite']))
			{
				unset($ids[$k]);
			}
			else
			{
				$value['invite'][$k]=$v;
			}
		}
		$_REQUEST['ids'] = $ids;
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
	$noti = '<xn:name uid="'.$pid.'" linked="true"/><a href="'.RenrenConfig::$canvas_url.'">正在玩购物天堂，邀请你去帮他装货、卸货，顺便帮他抢几个客人</a>';
	$idstr = '';
	foreach ($_REQUEST['ids'] as $k =>$id){
		$idstr.=$k.',';
	}
	$ids = substr($ids,0,strlen($idstr)-1);
	$r = $renren->api_client->notifications_send($idstr,$noti);
	
	header('Location: '.RenrenConfig::$canvas_url.'?f=invite&noti='.$r['result']);
	

