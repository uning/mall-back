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
	//print_r($tt->getbyuidx('lid',$fid));
	changeUser($pid);
}
function helpOpenShop($fid,$pid,$ot)
{
	$obj = array(
		'uid' => $pid,
		'lid' => $fid,
		'frd' => $_REQUEST['frd'],
		'type' => 1,
		'clickTime' => 0,
		'count' => 0,
		'oid' => $ot,
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
	case 4:
		helpOpenShop($fid,$pid,$ot);
	default:break;
}
TTLog::record(array('m'=>'pub_feed','tm'=> $_SERVER['REQUEST_TIME'],'u'=>$pid,'sp2'=>$ot,'sp1'=>$type));
file_put_contents('stroefeed.txt',$_REQUEST);
print_r($_REQUEST);



