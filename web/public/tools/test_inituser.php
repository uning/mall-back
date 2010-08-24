<?php
require_once '../base.php';
$otu = new TTUser(2);
$all = $otu->getAll(false);
$now = time();
$tc=TT::get_tt('genid');
$num = $tc->num();
echo "init start from $num\n";
for($i=$num+1;$i<300000;$i++)
{
    $id = $tc->put(null,array('pid'=>'test'.$i,'at'=>$now));
	$tt = TT::get_tt('main',$toid);
	$allr=array();
	$tu = new TTUser($i);
	$toid=$i;
	foreach($all as $k=>$v){
		$arr = explode(':',$k);
		$arr[0]=$toid;
		$nk =  implode(':',$arr);
		$nv = $v;
		$isobj = TTExtend::checkObj($v);
		if($isobj){
			$v['id'] = $nk;
			if(isset($v['stime'])){
				$v['stime']= $now - 50;
			}
			if(isset($v['pos']['y'])){
				$arr = explode(':',$v['pos']['y']);
				if(count($arr)>1&& $arr[0]=$toid){
					$arr[0]=$toid;
					$v['pos']['y']=implode(':',$arr);
				}
			}
			if(isset($v['goods'])){
				//foreach(

			}

			$allr[$nk]=json_encode($v);
		}else{
			$allr[$nk]=$nv;
		}
	}
	if($i%100==0){
		echo "$i user init \n";
	}
	$tt->put($allr);
	//print_r($allr);
   // print_r($tu->getAll());
//	break;
	
}
