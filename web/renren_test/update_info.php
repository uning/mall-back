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
	//$pid ='253382225';
	if(!$pid)
		die('no pid');
    $ar['pid']=$pid; 
    $ar['authat']=time();; 

	require_once 'renren.php';
	$ren = new Renren();
	$ren -> api_key =RenrenConfig::$api_key;
	$ren -> secret = RenrenConfig::$secret;
	$ren -> init();
	$ret = $ren->api_client->users_getInfo(array($pid),array("uid","name","sex","star","zidou","vip","tinyurl","birthday","email_hash"));
	if($ret[0]['name']){
		$ar['icon']=$ret[0]['headurl'];
		unset($ret[0]['headurl']);
		unset($ret[0]['tinyurl']);
		foreach($ret[0] as $kk=>$vv){
			$ar[$kk]=$vv;
		}
	}
	print_r($ar);
    $sess = TTGenid::genid($ar);
?>
</body></html>
