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
system("mkdir -p $myloc/data/$table/");
$uhf=fopen($uhfname,'w') or die("open $uhfname failed");

$cont_no = 0;
$end = $start + $num;
for($i=$start;$i<=$end;++$i){
	$data = $logt->get($i);		
//	$logt->out($i);
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


	$inp1 = $data['intp1'];
	$inp2 = $data['intp2'];
	$sp1 = $data['sp1'];
	$sp2 = $data['sp2'];
	if(!$sp1){
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
		if($m=='GoodsController.checkout'){
			$sell = $p['sell'];
			if($sell){
				foreach($sell as $gtag=>$sellnum){
					$dgr["$mpre@as@$gtag"]+=$sellnum;
					$sp1.="$gtag@"; 
				}
			}
		}
		if($m=='UserController.precheckout'){
			$sp1 = $p['days'];
		}
		if($m=='Gift.accept'){
			$gtags = $p['gids'];
			foreach( $gtags as $gtag ){
				$dgr["$mpre@$gtag"] += 1;
				$sp1.="$gtag@";
			}
		}
		if($m=='ItemController.buy'){
			$items = $p['d'];
			foreach( $items as $tag){
				$dgr["$mpre@$tag"] += 1;
				$sp1.="$tag@";
			}
		}
		if($m=='CarController.buy'){
			$cars = $p['c'];
			foreach( $cars as $tag){
				$dgr["$mpre@$tag"] += 1;
				$sp1.="$tag@";
			}
		}
		if($m=='GoodsController.exhibit_goods'){
			$tags = $p['tags'];
			foreach( $tags as $tag){
				$dgr["$mpre@$tag"] += 1;
				$sp1.="$tag@";
			}
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
	if(!$uid)
		$uid = $data['u'];
	
	//print_r($p);
	fputcsv($uhf,array($uid,$m,$tm,$inp1,$inp2,$sp1,$sp2));
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
