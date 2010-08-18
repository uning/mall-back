<?php
$myloc = dirname(__FILE__);
require_once($myloc.'/config.php');

$logt = TT::get_tt('log');
$num = $logt->num();
echo "lognum = $num\n";
print_r($logt->get($num));
$tq = $logt->getQuery();
$tq->setLimit(1);
$r = $tq->search();
foreach($r as $k=>$v)
{
	$start = $k;
        break;	
}
if(!$start )
	die("no log start get ");

$table = 'user_actions';
$uhfname = $myloc."/data/$table/$weekday.csv";
$uhf=fopen($uhfname,'w') or die("open $uhfname failed");
for($i=$start;;++$i){
	$data = $logt->get($i);		
	$tm = $data['tm'];
	if(!$data || !$uid)
		continue;	
	if($tm > $now)
		break;
	$method = $data['m'];
	$p = json_decode($data['p'],true);
	$dgr[$method]++;
	if($method == 'UserController.login'){
		$sp1=$p['pid'];
	}
	else if($m == 'Advert.buy'){

	}
	else{

	}
	$uid = $p['u'];
	fputcsv($uhf,array($uid,$method,$tm,$inp1,$inp2,$sp1,$sp2));
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
