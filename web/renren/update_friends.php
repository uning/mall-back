<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
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

	require_once 'renren.php';
	$ren = new Renren();
	$ren -> api_key =RenrenConfig::$api_key;
	$ren -> secret = RenrenConfig::$secret;
	$renren ->session_key = $session_key;
	$ren -> init( $session_key);
	$ret = $ren->api_client->friends_getAppFriends();
	
	
	if($ret && $ret[0] && $ret[0]>0 ) {
		$fidstr =implode(',',$ret);
		$tu->putf( TT::FRIEND_STAT,$fidstr);
		echo "OK\n";
	}else{
		echo "failed\n";
	}
	
	print_r($ret);  



?>
</body>
</html>


