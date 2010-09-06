<?php 
    include "config.php";
    $pid = $_REQUEST['xn_sig_user'];
	
	$sess = TTGenid::getbypid($pid);
	$uid = $sess['id'];
	$tu = new TTUser($uid);
	
	$session_key = $_REQUEST['xn_sig_session_key'];
	//$pid ='253382225';
	if(!$pid)
		die('no pid');
    $ar['pid']=$pid; 
    $ar['authat']=time();; 

	require_once 'renren.php';
	$ren = new Renren();
	$ren -> api_key = RenrenConfig::$api_key;
	$ren -> secret = RenrenConfig::$secret;
	
	$renren ->session_key = $session_key;
	$ren -> init( $session_key);
	 
	$ret = $ren->api_client->users_getInfo(array($pid),array("uid","name","sex","star","zidou","vip","tinyurl","birthday","email_hash"));
	if($ret[0]['name']){
		$ar['icon']=$ret[0]['headurl'];
		unset($ret[0]['headurl']);
		unset($ret[0]['tinyurl']);
		foreach($ret[0] as $kk=>$vv){
			$ar[$kk]=$vv;
		}
	}
	$ar['session_key']=$session_key;
	TTGenid::genid($ar);
	echo '<pre>';
	print_r($ar);
	
	
	$ret = $ren->api_client->friends_getAppFriends();
	
	if($ret && $ret[0] && $ret[0]>0 ) {
		$fidstr =implode(',',$ret);
		$tu->putf( TT::FRIEND_STAT,$fidstr);
		echo "friends OK\n";
	}else{
		echo "failed\n";
	} 
	print_r($ret);  
	
	 

