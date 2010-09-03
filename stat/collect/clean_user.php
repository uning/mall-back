<?php
$myloc = dirname(__FILE__);
require_once($myloc.'/config.php');
//$ret = $ren->api_client->users_getInfo(array('256225497'),array("uid","name","sex","star","zidou","vip","tinyurl","birthday","email_hash"));
//print_r($ret);
//return;


$user_num = $gtt->num(); 
echo "user_num = $user_num\n";
$now = time();
$gap=86400;


$table = 'user_incorrect';
print_r($tfs);
$uhfname = $myloc."/data/$table/$datestr.csv";
system("mkdir -p $myloc/data/$table/");
$uhf=fopen($uhfname,'w') or die("open $uhfname failed");
for($i=1;$i<=$user_num;++$i){
	$ud = $gtt->get($i);		
	$uid = $i; 
	$pid = $ud['pid'];
	if(!$ud || !$uid || !$pid)
		continue;	
	if(!is_numeric($pid))
		continue;
	//if($ud['name']!='' && strstr($ud['name'],'我很二')==''  && $ud['access_token']!='sdhf7*T)*Y*T&$&^FOUGF&*UDIUSGf'){
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

	$gtt->put($uid,$newd);
	print_r($ud);
	print_r($newd);
	continue;
	exit;
}
#foreach($clean as $pid=>$d){}
//print_r($clean);
exit;
//*
store_varible($dgr);
$cmd = "mysql -u{$dbconfig['username']} -P{$dbconfig['port']}  -h{$dbconfig['host']} ";
if($dbconfig['password']){
  $cmd.=" -p'{$dbconfig['password']}' ";
}
$cmd .= $dbconfig['dbname'];
$cmd .=' -e "LOAD DATA INFILE \''.$uhfname.'\' INTO TABLE '.$table.'  FIELDS TERMINATED BY \',\' ESCAPED BY \'\\\\\\\' LINES TERMINATED BY \'\n\';"';         
//*/
system($cmd);
fclose($uhf);
