<?php

require_once 'base.php';
//返回
function get_friends($uid,$sess=null,$tu=null)
{
	require_once WEB_ROOT.'../renren/renren.php';
	require_once WEB_ROOT.'../renren/config.php';
	if(!$sess){
		$sess = TTGenid::getbyid($uid);
	}
	$pid = $sess['pid'];
	$sk = $sess['session_key'];
	
	$ren = new Renren();
	$ren -> api_key =RenrenConfig::$api_key;
	$ren -> secret = RenrenConfig::$secret;
	$renren ->session_key = $sk;
	$ren -> init( $sk);
	$ret= $ren->api_client->friends_getAppFriends();
	if($ret[0]>1){
		return implode(',',$ret);
	}
	return '';
}

