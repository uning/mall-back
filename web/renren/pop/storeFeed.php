<?php
require_once '../config.php';
function changeUser()
{
	$pid = $_REQUEST['pid'];
	$session = TTgenid::getbypid($pid);
	$uid = $session['id'];
	$tu = new TTUser($uid);
	//$tu->addExp();
	//$tu->get();
}
function ShareGift()
{
	$key =$_REQUEST['fid'];
	$gift = $_REQUEST['gift'];
	if(!$gift) return;
	$obj = array(
			'uid' => $_REQUEST['pid'],
			'fid' => $key,
			'type' =>3,
			'gift' => $gift,
			'clickTime' => 0,
			'count' => 15,
			'date' =>date('Ymd'),
			'rcv' => array(0)
		);
	$tt = TT::LinkTT();
	$tt->put($obj);
	changeUser();
}
function shareTask()
{
	$key = $_REQUEST['fid'];
	$task = $_REQUEST['task'];
	$obj = array(
		'uid' =>$_REQUEST['pid'],
		'fid' => $key,
		'type' => 2,
		'task' => $task,
		'clickTime' => 0,
		'count' => 0,
		'date' =>date('Ymd'),
		'rcv' => array(0)
	);
	$tt = TT::LinkTT();
	$tt->put($obj);
	changeUser();
}
function shareGoldCoin()
{
	$key = $_REQUEST['fid'];
	$obj = array(
		'uid'=> $_POST['pid'],
		'fid' => $key,
		'type' => 1,
		'clickTime' => 0,
		'count' => 0,
		'date' =>date('Ymd'),
		'rcv' => array(0)
	);
	$tt = TT::LinkTT();
	$tt->put($obj);
	changeUser();
}

$feedId = $_REQUEST['fid'];
$type   = $_REQUEST['type'];

switch ($type){

	case 1: 
		shareGoldCoin();break;
	case 2:  
		shareTask();break;
	case 3: 
		ShareGift();break;
	default:break;
}
TTLog::record(array('m'=>'pub_feed','tm'=> $_SERVER['REQUEST_TIME'],'u'=>$pid,'sp1'=>$fid,'sp2'=>$type));
file_put_contents('stroefeed.txt',$_REQUEST);



