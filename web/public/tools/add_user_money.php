<html xmlns="http://www.w3.org/1999/xhtml"
xmlns:xn="http://www.renren.com/2009/xnml">
<head>
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"> </script>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/jsflash.js"></script>
<title>活力商城</title>
<body>
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
	echo "<p>PID: <input name='pid' type='text' /></p>";
}
	echo "<p>金币: <input name='money' value='5000' type='text' /></p>";
	echo "<p>宝石: <input name='gem' value='0' type='text' /></p>";
	


if($_REQUEST['money'] && $_REQUEST['gem'] &&$_REQUEST['money']>=0 &&$_REQUEST['gem']>=0&&$_REQUEST['money']<=1000000000 &&$_REQUEST['gem']<=1000){
	$u = $data['id'];
	if(!$u)
		die( "no u get");
	$tu = new TTUser($u); 
	$tu->numch('money',$_REQUEST['money']);
	$tu->numch('gem',$_REQUEST['gem']);
	echo "<p>OK</p>";

}else{
	echo "<p>输入不合法</p>";
}

echo "<p><input type='submit' value='确定'/></p></form>";

?>
</body>
</html>
