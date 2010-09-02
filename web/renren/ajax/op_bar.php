<?php
require_once('../config.php');
/*
$ret['query'] = $_REQUEST;
$ret['post'] = $_POST;
$ret['get'] = $_GET;
echo json_encode($ret);
*/
$op = $_REQUEST['op'];
$pid = $_REQUEST['xn_sig_user'];
if($op&&$pid){
	$sess = TTGenid::getbypid($pid);
	$uid = $sess['id'];
	if(!$sess && $uid){
		echo "{'s':'fail','msg':'not find $pid op=$op'}";
		return;
	}
	$tu = new   TTUser($uid);
	 $id = $tu->getdid('installbar',TT::OTHER_GROUP);
	 $barobj = $tu->getbyid($id);
	if($barobj[$op]){
		echo "{'s':'fail','msg':'not find $pid op=$op'}";
		return ;
	}else{
		$barobj['id'] = $id;
		$barobj[$op]['save'] = time();
		$tu->puto($barobj,TT::OTHER_GROUP,false);
		echo "{'s':'ok'}";
		return;
	}
}
echo "{'s':'fail','error':'pid=$pid op=$op'}";
