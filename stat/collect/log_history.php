<?php
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
	if($tm < $day_starttime)
		continue;
	if($tm > $day_endtime){
		echo "break at $i";
		$mail_body.="break at $i";
		break;
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
	if(!$uid){
		$notrec[$m]+=1;
		if($notrec[$m]==1){
			$msg = print_r($data);
			echo "incorrect record: $m $msg\n";
		}
		continue;
	}

	if($m=='pub_feed'){
		//sp1 = type
		$dgr["$m@$sp1"]+=1;
	}
	else if($m=='feed_back'){
		//sp1==type
		$dgr["$m@$sp1@$sp2"]+=1;
	}
	else if($m=='pub_invite'){
		//sp1 = gift 
		$dgr["$m@$sp1"]+=1;
	}
	else if($m=='accept_invite'){
		//sp1 = gift  sp2 =ÐÂ°²×°
		$pre = $m.$sp2;
		$dgr["$pre@$sp1@$sp2"]+=1;
	}
	else if($m=='help_open_shop'){
		//sp1 = oid sp2 = new one
		$dgr["$m@$sp1@$sp2"]+=1;
	}
	else if($m=='GoodsController.checkout'){
		$sell = $p['sell'];
		if($sell){
			foreach($sell as $gtag=>$sellnum){
				$dgr["$mpre@as@$gtag"]+=$sellnum;
				$sp1=$gtag;
				$inp1=$num;
				fputcsv($uhf,array($uid,$m,$tm,$inp1,$inp2,$sp1,$sp2));
			}
		}
	}

	else if($m=='UserController.precheckout'){
		$sp1 = $p['days'];
	}
	else if($m=='Gift.accept'){
		$gtags = $p['gids'];
		if($gtags)
			foreach( $gtags as $gtag ){
				$dgr["$mpre@$gtag"] += 1;
				$sp1=$gtag;
				fputcsv($uhf,array($uid,$m,$tm,$inp1,$inp2,$sp1,$sp2));
			}
	}
	else if($m=='ItemController.buy'){
		$items = $p['d'];
		if($items)
			foreach( $items as $tag){
				$dgr["$mpre@$tag"] += 1;
				$sp1.="$tag@";
				$sp1=$gtag;
				fputcsv($uhf,array($uid,$m,$tm,$inp1,$inp2,$sp1,$sp2));
			}
	}
	else if($m=='CarController.buy'){
		$cars = $p['c'];
		if($cars)
			foreach( $cars as $tag){
				$dgr["$mpre@$tag"] += 1;
				$sp1=$gtag;
				fputcsv($uhf,array($uid,$m,$tm,$inp1,$inp2,$sp1,$sp2));
			}
	}
	else if($m=='GoodsController.exhibit_goods'){
		$tags = $p['tags'];
		foreach( $tags as $tag){
			$dgr["$mpre@$tag"] += 1;
			$sp1=$gtag;
			fputcsv($uhf,array($uid,$m,$tm,$inp1,$inp2,$sp1,$sp2));
		}
	}else
		fputcsv($uhf,array($uid,$m,$tm,$inp1,$inp2,$sp1,$sp2));
}
include 'end_stat.php';
