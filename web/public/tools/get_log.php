<?php
$myloc = dirname(__FILE__);
require_once('../base.php');
echo "<pre>\n";

$now = time();
$datestr = date('Y-m-d',$now);
$weekday = date('N',$now);
$day_starttime = strtotime($datestr);
$day_endtime = $day_starttime + 86400;
$logt = TT::get_tt('log');
$logt->needSV=false;
$id = $logt->genUid();
echo "$id:\n";
#exit;

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


$cont_no = 0;
$end = $id;
$start = $end - 10;
if($start<1){
	$start = 1;
}
echo "start=$start end=$end   lognum = $num\n";
for($i=$start;$i<=$end;++$i){
	$data =  $logt->get($i);		
	echo "$i:\n";
	print_r($data);
}

