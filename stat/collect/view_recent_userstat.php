<?php
echo "<pre>\n";
$myloc = dirname(__FILE__);
require_once($myloc.'/config.php');

$user_num = $gtt->num(); 
echo "user_num = $user_num\n";
$now = time();
$gap=86400;
$$lnum = $argv[1];
if(!$lnum)
	$$lnum = $_REQUEST['num'];
if(!$lnum)
$lnum = 300;
$start = $user_num - $lnum;
if($start <1 )
 $start = 1;
$table = 'user_history';
for($i=$start;$i<=$user_num;++$i){
	$ud = $gtt->get($i);		
	$uid = $i;
	$pid = $ud['pid'];
	if(!$ud || !$uid || !$pid)
		continue;	
	if(!is_numeric($pid))
		continue;
	$tu = new TTuser($uid,true);
	$fnames = array('money','exp','gem','friend_count');
	foreach($fnames as $fn)
		$dids[] = $tu->getdid($fn);
	$dids[] = $tu->getoid('mannual',TT::OTHER_GROUP);
	$dids[] = $tu->getoid('installbar',TT::OTHER_GROUP);
	$data = $tu->getbyids($dids);
	$level=$tu->getLevel($data['exp']);
	$dgr["level_$level"]+=1;
	//print_r($data);
	//exit;
	//count man 
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

	$accesstime = $ud['at'];
	$unstalltime = $ud['ut'];
	$installtime = $ud['it'];
	$authtime = $ud['authat'];
	if($accesstime > $day_starttime ){//daily active user
		$dgr['login_num']++;
		if(!$data['f_num'])
		   $data['f_num']=0;
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
			++$dgr['auth_noinstall_num'];
		else
			++$dgr['auth_num'];
		++$dgr['total_auth_num'];
	}
	if($installtime>$day_starttime){
		if($unstalltime<$installtime){
			if($unstalltime)
				$dgr['reinstall_num']++;
			else
				$dgr['install_num']++;
		}else{
			$dgr['in_un_num']++;//当天卸载人数
		}
	}
	if($unstalltime>$accesstime){
		$dgr['unstall_num']++;
	}

	
}
print_r($dgr);
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
