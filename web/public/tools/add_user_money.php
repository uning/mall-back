<?php
echo "<pre>\n<form method='get'>";
require_once '../base.php';
$u=$argv[1];
if(!$u){
	$u=$_REQUEST['u'];
}
if(!$u){
 	$pid=$_REQUEST['pid']; 
	if($pid)
		$data = TTGenid::getbypid($pid);
}else
	$data = TTGenid::getbyid($u);

if(!$data||$data['id']){
	echo "<p>PID: <input name='pid' type='text' /></p>"
}
	echo "<p>���: <input name='money' value='5000' type='text' /></p>"
	echo "<p>��ʯ: <input name='gem' value='0' type='text' /></p>"
	


if($_REQUEST['money'] && $_REQUEST['gem'] &&$_REQUEST['money']>=0 &&$_REQUEST['gem']>=0&&$_REQUEST['money']<=1000000000 &&$_REQUEST['gem']<=1000){
	$u = $data['id'];
	if(!$u)
		die( "no u get");
	$tu = new TTUser($u); 
	$tu->numch('money',$_REQUEST['money']);
	$tu->numch('gem',$_REQUEST['gem']);
	echo "<p>OK</p>";

}else{
	echo "<p>���벻�Ϸ�</p>";
}

echo "<p><input type='submit' value='ȷ��'/></p></form>"
