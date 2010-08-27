<?php
$myloc = dirname(__FILE__);
require_once('../base.php');
echo "<pre>\n";

$u=$argv[1];
if(!$u){
	$u=$_REQUEST['u'];
}
if(!$u){
	$pid = $argv[2];
	if(!$pid){
		$pid=$_REQUEST['pid'];
	}
	if(!$pid){
		$pid = $_COOKIE['user_name'];

	}
	if(!$pid){
		die( "no param");

	}
	$data = TTGenid::getbypid($pid);
}else
	$data = TTGenid::getbyid($u);
if($data['id'])
	$u = $data['id'];
if(!$u)
	die( "no u get");


print_r($data);
$now = time();
$datestr = date('Y-m-d',$now);
$weekday = date('N',$now);
$day_starttime = strtotime($datestr);
$day_endtime = $day_starttime + 86400;
$logt = TT::get_tt('log');
$logt->needSV=false;

$num = $logt->num();
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
echo "start=$start lognum = $num\n";

$table = 'log_history';
$uhfname = $myloc."/data/$table/$weekday.csv";

$cont_no = 0;
$end = $start + $num;
for($i=$start;$i<=$end;++$i){
	$data = $logt->get($i);		
	$tm = $data['tm'];
	if(!$data){
		continue;	
	}
	if($tm < $day_starttime)
		continue;
	if($tm > $day_endtime)
		break;
	$s = $data['s'];
	$m = str_replace('::','.',$data['m']);
	$mpre = $m.'@'.$s;

	$dgr[$mpre] ++;
	$inp2=0;
        $inp1=0;
	$sp2='';
	$sp1='';

	$p = json_decode($data['p'],true);

	$uid = $p['u'];
	if(!$uid)
		$uid = $p['p']['u'];
	if($uid == $u)
		print_r($data);
}

