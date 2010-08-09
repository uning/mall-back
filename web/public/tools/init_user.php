<?php
require_once '../base.php';
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
	  die( "no param");
	 
	}
	$data = TTGenid::getbypid($pid);
}else
	$data = TTGenid::getbyid($u);
$u = $data['id'];
if(!$u)
	die( "no u get");
function  inituser($fromid,$toid)
{
  $otu = new TTUser($fromid);
  $tu = new TTUser($toid);
  $all = $otu->getAll(false);
  $allr=array();
  $now = time();
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
		
		$allr[$nk]=json_encode($v);
	  }else{
		$allr[$nk]=$nv;
	  }
  }
  $tt = TT::get_tt('main',$toid);
  $tt->put($allr);
  //print_r($tu->getAll());
	
}

inituser(48,$u);
