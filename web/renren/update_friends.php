<?php 
    include "config.php";
	echo "<pre>";
    $pid = $_REQUEST['xn_sig_user'];
	$sess = TTGenid::getbypid($pid);
	$uid = $sess['id'];
	$tu = new TTUser($uid);
	$session_key = $sess['session_key'];
	if(!$pid)
		die('no pid');
	if(!$session_key)
		die('no session key');
		
		
    $ar['pid']=$pid; 
    $ar['authat']=time();; 

	require_once 'renren.php';
	$ren = new Renren();
	$ren -> api_key =RenrenConfig::$api_key;
	$ren -> secret = RenrenConfig::$secret;
	$renren ->session_key = $session_key;
	$ren -> init( $session_key);
	$ret = $ren->api_client->friends_getAppFriends();
	
	print_r($ret); 
	
	//$fidstr =implode(',',$fids);
   //file_put_contents('friend.txt',TT::FRIEND_STAT." $uid add ".$fidstr);
    
	//$tu->putf( TT::FRIEND_STAT,$fidstr);

echo "OK";


