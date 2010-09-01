<?php
require_once '../config.php';

function changeUser($pid)
{
	
	$session = TTgenid::getbypid($pid);
	$uid = $session['id'];
	$tu = new TTUser($uid);
	//$tu->addExp();
	//$tu->get();
}
function ShareGift($fid,$pid,$ty)
{
	$obj = array(
			'uid' => $pid,
			'lid' => $fid,
			'type' =>3,
			'gift' => $ty,
			'clickTime' => 0,
			'count' => 15,
			'date' =>date('Ymd'),
			'rcv' => array($pid=>1)
		);
	$tt = TT::LinkTT();
	$tt->put($obj);
	changeUser($pid);
}
function shareTask($fid,$pid,$ty)
{

	$obj = array(
		'uid' => $pid,
		'lid' => $fid,
		'type' => 2,
		'task' => $ty,
		'clickTime' => 0,
		'count' => 0,
		'date' =>date('Ymd'),
		'rcv' => array($pid=>1)
	);
	$tt = TT::LinkTT();
	$tt->put($obj);
	changeUser($pid);
}
function shareGoldCoin($fid,$pid)
{

	$obj = array(
		'uid' => $pid,
		'lid' => $fid,
		'type' => 1,
		'clickTime' => 0,
		'count' => 0,
		'date' =>date('Ymd'),
		'rcv' => array($pid=>1)
	);
	$tt = TT::LinkTT();
	$id = $tt->put($obj);
	print_r($tt->getbyuidx('lid',$fid));
	changeUser($pid);
}
$type   = $_REQUEST['type'];
$fid = $_REQUEST['fid'];
$pid = $_REQUEST['pid'];
$ot = $_REQUEST['ot'];
switch ($type){

	case 1: 
		shareGoldCoin($fid,$pid);break;
	case 2:  
		shareTask($fid,$pid,$ot);break;
	case 3: 
		ShareGift($fid,$pid,$ot);break;
	default:break;
}
TTLog::record(array('m'=>'pub_feed','tm'=> $_SERVER['REQUEST_TIME'],'u'=>$pid,'sp2'=>$fid,'sp1'=>$type));
file_put_contents('stroefeed.txt',$_REQUEST);
print_r($_REQUEST);



