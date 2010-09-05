<?php
$myloc = dirname(__FILE__);
require_once($myloc.'/config.php');

$user_num = $gtt->num(); 
echo "user_num = $user_num\n";
$mail_body.="user_num = $user_num\n";
$now = time();
$gap=86400;


$table = 'user_history';
print_r($tfs);
$uhfname = $myloc."/data/$table/$weekday.csv";
system("mkdir -p $myloc/data/$table/");
$uhf=fopen($uhfname,'w') or die("open $uhfname failed");
for($i=1;$i<=$user_num;++$i){
	$ud = $gtt->get($i);		
	$uid = $i;
	$pid = $ud['pid'];
	if(!$ud){
		echo "get user $i failed\n";
		continue;
	}
	if( !$uid || !$pid){
		echo "user $i have no pid\n";
		continue;
	}	
	if(!is_numeric($pid))
		continue;
	$accesstime = $ud['at'];
	$unstalltime = $ud['ut'];
	$authtime = $ud['authat'];
	if($authtime >$day_endtime){
		if($i - $lastau == 1){
		 echo "break at user $i\n"; 
		 $mail_body.="break at user $i\n"; 
		}
	}
	$tu = new TTuser($uid,true);
	$fnames = array('money','exp','gem','friend_count','it');
	foreach($fnames as $fn)
		$dids[] = $tu->getdid($fn);
	$dids[] = $tu->getoid('mannual',TT::OTHER_GROUP);
	$dids[] = $tu->getoid('installbar',TT::OTHER_GROUP);
	$data = $tu->getbyids($dids);

	$level=$tu->getLevel($data['exp']);
	$dgr["level_$level"]+=1;

	$mano = $data['mannual'];
	$ino = $data['installbar'];
	if($mano){
		end($mano);
		$o = each($mano);
		$dgr['mannual_'.$o['key']]+=1;
	}
	if($ino){
		foreach($ino as $k=>$v){
			if($k!='id')
				$dgr['installbar_'.$k]+=1;
		}
	}
	$dgr['gem']+=$data['gem'];
	$dgr['money']+=$data['money'];
	$dgr['exp']+=$data['exp'];

	if($accesstime > $day_starttime ){//daily active user
		$dgr['login_num']++;
		if(!$data['f_num'])
		   $data['f_num']=0;
		fputcsv($uhf,array($uid,$datestr,$data['money'],$data['exp'],$data['gem'],$data['friend_count']));
	}
	foreach(array(3,7,30) as $sd){//recent active user
		if($accesstime+$sd*$gap>$day_starttime){
			$dgr['login_'.$sd.'num']++;
		}
	}
	
	if($authtime>$day_starttime ){
		if($unstalltime>$authtime)
			++$dgr['auth_unauth_num'];
		else if($accesstime <$authtime)
			++$dgr['auth_noplay_num'];
		else
			++$dgr['auth_num'];
		++$dgr['total_auth_num'];
	}
	if($unstalltime>$accesstime){
		$dgr['unstall_num']++;
	}
	if(!$accesstime)
		$dgr['total_noacctive_user']+=1;

	$dgr['total_user']+=1;
	if($i%100==0)
		echo "$uid user proessed \n";

///#platform apicall get 
	if($ud['name']!='' && strstr($ud['name'],'我很二')==''  ){
		continue;
	}
	echo "$uid $pid: {$ud['name']}\n";
	$change = false;
	$newd=array();
	foreach($ud as $kk=>$vv){
		if($kk=='locale' || $kk=='session_key'||$kk=='had_permissions' || $kk=='updated_time'||$kk=='access_token'){
			$change = true;
			continue;
		}
		$newd[$kk] = $vv;
	}
	if($ud['name']=='' || strstr($ud['name'],'我很二')){
		$ret = $ren->api_client->users_getInfo(array($pid),array("uid","name","sex","star","zidou","vip","tinyurl","birthday","email_hash"));
		if($ret[0]['name']){
			$newd['icon']=$ret[0]['headurl'];
			unset($ret[0]['headurl']);
			unset($ret[0]['tinyurl']);
			foreach($ret[0] as $kk=>$vv){
				$newd[$kk]=$vv;
			}
			$change = true;

		}else{
			echo "uid $uid pid=$pid call api failed\n";
		}

	}

	if(strstr($newd['name'],'我很二')){
		$change = true;
		$newd['name']='';
		print_r($ud);
	}

	$gttw->put($uid,$newd);

	
}
include 'end_stat.php';
