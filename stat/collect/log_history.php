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

$table = 'log_history';
$uhfname = $myloc."/data/$table/$weekday.csv";
$uhf=fopen($uhfname,'w') or die("open $uhfname failed");

$cont_no = 0;
$end = $start + $num;
for($i=$start;$i<=$end;++$i){
	$data = $logt->get($i);		
	$tm = $data['tm'];
	if(!$data){
		continue;	
	}
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
	if(!$p){
		$logt->out($i);
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
	}
		$inp1=$p['p']['num'];
	if(!$sp1){
		$sp1=$p['p']['goodsTag'];
		$inp1=$p['p']['num'];
	}
	if(!$sp1){
		$sp1=$p['p']['c']['goodsTag'];
		$inp1=$p['p']['num'];
	}
	if(!$sp1){
		$sp1=$p['p']['c']['goodsTag'];
		$inp1=$p['p']['num'];
	}

	if($sp1){
		if(!$inp1)
			$inp1=1;
		$dgr[$mpre.'@'.$sp1]+=$inp1;
	}

	if($m=='GoodsController.checkout'){
	//	print_r($p);
		$sell = $p['sell'];
		if($sell){
			foreach($sell as $gid=>$num){
				$dgr["$mpre@as@$gid"]+=$num;	
			}
		} 	
	}	
	if($m=='UserController.precheckout'){
	//	print_r($p);
		$days = $p['days'];
		if($days){
//				$dgr["$mpre@as@$uid"]+=$num;
		    }
		} 	
	}	

	if($p['pid'])
		$sp2=$p['pid'];

	$uid = $p['u'];
	if(!$uid)
		$uid = $p['p']['u'];

	if(!$inp1){
		$inp1 = $p['p']['f']; 
	}
	//print_r($p);
	fputcsv($uhf,array($uid,$m,$tm,$inp1,$inp2,$sp1,$sp2));
//	$logt->out($i);
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
