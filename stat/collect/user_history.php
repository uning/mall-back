<?php
$myloc = dirname(__FILE__);
require_once($myloc.'/config.php');

$user_num = $gtt->num(); 
echo "user_num = $user_num\n";
$now = time();
$gap=86400;


$table = 'user_history';
print_r($tfs);
$uhfname = $myloc."/data/$table/$weekday.csv";
system("mkdir -p $myloc/data/$table/");
$uhf=fopen($uhfname,'w') or die("open $uhfname failed");
for($i=1;$i<=$user_num;++$i){
	$ud = $gtt->get($i);		
	$uid = $ud['id'];
	if(!$ud || !$uid)
		continue;	
	$tu = new TTuser($uid);
	$data = $tu->getf(array('money','exp','gem','f_num'));
	$dgr['gem']+=$data['gem'];
	$dgr['money']+=$data['money'];
	$dgr['exp']+=$data['exp'];

	$accesstime = $ud['at'];
	$unstalltime = $ud['ut'];
	$installtime = $ud['it'];
	$authtime = $ud['auth_at'];
	if($accesstime > $day_starttime ){//daily active user
		$dgr['login_num']++;
		if(!$data['f_num'])
		   $data['f_num']=0;
		fputcsv($uhf,array($uid,$datestr,$data['money'],$data['exp'],$data['gem'],$data['f_num']));
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
	if($unstalltime>$day_starttime){
		$dgr['unstall_num']++;
	}
	
}
store_varible($dgr);
$cmd = "mysql -u{$dbconfig['username']} -P{$dbconfig['port']}  -h{$dbconfig['host']} ";
if($dbconfig['password']){
  $cmd.=" -p'{$dbconfig['password']}' ";
}
$cmd .= $dbconfig['dbname'];
$cmd .=' -e "LOAD DATA INFILE \''.$uhfname.'\' INTO TABLE '.$table.'  FIELDS TERMINATED BY \',\' ESCAPED BY \'\\\\\\\' LINES TERMINATED BY \'\n\';"';         
//echo $cmd;
system($cmd);
fclose($uhf);
