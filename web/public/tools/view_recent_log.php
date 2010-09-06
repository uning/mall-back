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


$cont_no = 0;
$end = $id;
$vnum=$_REQUEST['num'];
$u=$_REQUEST['u'];
if(!$vnum)
	$vnum = 50;

	$start = $end - $vnum;
	if($start<1){
		$start = 1;
	}
for($i=$start;$i<=$end;++$i){
	$data =  $logt->get($i);		
	if(!$u){
		echo "$i:\n";
		print_r($data);
		continue;
	}

	$uid = $data['u'];
	$p=array();
	if(!$uid){
		$p = json_decode($data['p'],true);

		$uid = $p['u'];
	}
	if(!$uid)
		$uid = $p['p']['u'];
	if($uid == $u)
		if($p)
			print_r($p);
		else
			print_r($data);

	if(!$uid&&$data){
		echo "$i has no user record \n";
		print_r($data);
	}
}

