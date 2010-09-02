<?php
require_once('../config.php');
$pid = $_POST['pid'];
$fids = $_POST['fids'];

if(!$pid|| !$fids)
	die('params');
$sess = TTGenid::getbypid($pid);
$uid = $sess['id'];
if(!$uid){
	die('FAIL');
}
$tu = new TTUser($uid);
$fidstr =implode(',',$fids);
//file_put_contents('friend.txt',TT::FRIEND_STAT." $uid add ".$fidstr);
$tu->putf( TT::FRIEND_STAT,$fidstr);

echo "OK";
