<?php

$user = $argv[1];
$user = 8127;
$start = 300000;
$myloc = dirname(__FILE__);
require_once($myloc.'/config.php');

$logt = TT::get_tt('log');
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
$mail_body.="start=$start lognum = $num\n";
$table = 'log_history';
$uhfname = $myloc."/data/$table/$weekday.csv";
system("mkdir -p $myloc/data/$table/");
$uhf=fopen($uhfname,'w') or die("open $uhfname failed");

$cont_no = 0;
$end = $start + $num;
$end = $logt->put(null,array('ad'=>1,'add'=>2));
for($i=$start;$i<=$end;++$i){
	try{
		$data = $logt->get($i);		
	}catch (Exception $ee){
		print_r($ee);
	}
	$tm = $data['tm'];
	if(!$data){
		continue;	
	}
	if($i%1000==0){
		echo "process $i logs\n";
	}

	$s = $data['s'];
	$m = str_replace('::','.',$data['m']);
	$mpre = $m.'@'.$s;

	$dgr[$mpre] ++;
	$inp2=0;
	$inp1=0;
	$sp2='';
	$sp1='';

	$inp1 = $data['intp1'];
	$inp2 = $data['intp2'];
	$sp1 = $data['sp1'];
	$sp2 = $data['sp2'];
	$uid = $data['u'];
	if(!$sp1 && !$inp1){
		$p = json_decode($data['p'],true);
		if(!$p && !$sp1 && !$inp2){
			echo "no param remove $i\n";
			continue;	
		}
		$sp1=$p['goodsTag'];
		if(!$sp1){
			$sp1=$p['tag'];
			$inp1=$p['num'];
		}
		if(!$sp1){
			$sp1=$p['p']['tag'];
			$inp1=$p['p']['num'];
		}
		if(!$sp1){
			$sp1=$p['p']['goodsTag'];
			$inp1=$p['p']['num'];
		}
		if(!$sp1){
			$sp1=$p['p']['c']['goodsTag'];
			$inp1=$p['p']['c']['num'];
		}
		if($sp1){
			if(!$inp1)
				$inp1=1;
			$dgr[$mpre.'@'.$sp1]+=$inp1;
		}
		if($p['pid'])
			$sp2=$p['pid'];

		if(!$uid)
			$uid = $p['u'];
		if(!$uid)
			$uid = $p['p']['u'];

		if(!$inp1){
			$inp1 = $p['p']['f']; 
		}
	}
	if($uid == $user){
		$total ++;

		print_r($data);
		print_r($p);
		if($total>200)
                      break;
		continue;
	}

}
