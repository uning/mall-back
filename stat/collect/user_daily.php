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

	if(1 || $ud['at']+$gap>$now){//daily active user
		$dgr['login_num']++;
		if(!$data['f_num'])
		   $data['f_num']=0;
		fputcsv($uhf,array($uid,$date,$data['money'],$data['exp'],$data['gem'],$data['f_num']));
	}
	foreach(array(3,7,30) as $sd){//recent active user
		if($ud['at']+$sd*$gap>$now){
			$dgr['login_'.$sd.'num']++;
		}
	}
	if($ud['it']+$gap<$now){
		if($ud['ut']){
			$dgr['reinstall_num']++;
		}
		$dgr['install_num']++;
	}
	if($ud['ut']+$gap<$now){
		$dgr['unstall_num']++;
	}
}
store_varible($dgr);
$cmd = "mysql -u{$dbconfig['username']} -P{$dbconfig['port']}  -h{$dbconfig['host']} ";
if($dbconfig['password']){
  $cmd.=" -p'{$dbconfig['password']}' ";
}
$cmd .= $dbconfig['dbname'];
$cmd .=' -e "LOAD DATA INFILE \''.$uhfname.'\' INTO TABLE user_history  FIELDS TERMINATED BY \',\' ESCAPED BY \'\\\\\\\' LINES TERMINATED BY \'\n\';"';         
//echo $cmd;
system($cmd);
fclose($uhf);
