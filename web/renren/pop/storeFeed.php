<?php
function changeUser()
{
	$pid = $_POST['pid'];
	$session = TTgenid::getbypid($pid);
	$uid = $session['id'];
	$tu = new TTUser($uid);
	//$tu->addExp();
	//$tu->get();
}
function ShareGift()
{
	$key =$_POST['fid'];
	$gift = $_POST['gift'];
	if(!$gift) return;
	$obj = array(
			'uid' => $_POST['pid'],
			'fid' => $key,
			'type' =>3,
			'gift' => $gift,
			'clickTime' => 0,
			'count' => 15,
			'date' =>date('Ymd'),
			'rcv' => array()
		);
	$tt = TT::LinkTT();
	$tt->put($obj);
	changeUser();
}
function shareTask()
{
	$key = $_POST['fid'];
	$task = $_POST['task'];
	$obj = array(
		'uid' =>$_POST['pid'],
		'fid' => $key,
		'type' => 2,
		'task' => $task,
		'clickTime' => 0,
		'count' => 0,
		'date' =>date('Ymd'),
		'rcv' => array()
	);
	$tt = TT::LinkTT();
	$tt->put($obj);
	changeUser();
}
function shareGoldCoin()
{
	$key = $_POST['fid'];
	$obj = array(
		'uid'=> $_POST['pid'],
		'fid' => $key,
		'type' => 1,
		'clickTime' => 0,
		'count' => 0,
		'date' =>date('Ymd'),
		'rcv' => array()
	);
	$tt = TT::LinkTT();
	$tt->put($obj);
	changeUser();
}

$feedId = $_POST['fid'];
$type   = $_POST['type'];

switch ($type){

	case 1: 
		shareGoldCoin();break;
	case 2:  
		shareTask();break;
	case 3: 
		ShareGift();break;
	default:break;
}
echo 'i,m hered';



