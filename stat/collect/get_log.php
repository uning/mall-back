<?php
$myloc = dirname(__FILE__);
require_once('config.php');
echo "<pre>\n";

$logt = TT::get_tt('log');
$logt->needSV=false;
$id = $logt->put(null,array('ad'=>1,'add'=>2));
echo "$id:\n";
print_r($logt->get($id));
#exit;

$num = $logt->num();
$tq = $logt->getQuery();
$tq->setLimit(1);
$r = $tq->search();
foreach($r as $k=>$v)
{
	$start = $k;
	print_r($r);
        break;	
}
if(!$start )
	die("no log start get ");


$cont_no = 0;
$end = $id;
$start = $end - 100;
if($start<1){
	$start = 1;
}
echo "start=$start end=$end lognum = $num\n";
for($i=$start;$i<=$end;++$i){
	$data =  $logt->get($i);		
	echo "$i:\n";
	print_r($data);
}

