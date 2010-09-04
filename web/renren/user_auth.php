<?php 
    include "config.php";
    $pid = $_REQUEST['xn_sig_user'];
	//$pid ='253382225';
	if(!$pid)
		die('no pid');
    $ar['pid']=$pid; 
    $ar['authat']=time();; 

	require_once 'renren.php';
	$ren = new Renren();
	$ren -> api_key ='a32cb73bea154d2c9d40703b66dc9142'; 
	$ren -> secret = '023a6201a9b04955b1af79b1e9037c16';
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
	echo '<pre>';
	print_r($ar);
    $sess = TTGenid::genid($ar);

