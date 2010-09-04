<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>加金币和宝石</title>
<body>
<?php
require_once '../base.php';
$u=$_REQUEST['u'];
if(!$u){
 	$pid=$_REQUEST['pid'];  
    if($pid)
	  $data = TTGenid::getbypid($pid);
}else
	$data = TTGenid::getbyid($u);

if(!$data||$data['id']){
	echo "<p><form method='get'>PID: <input name='pid' type='text' /><input type='submit' value='获取用户' /> </form></p>";
}else{ 
    $u = $data['id'];
	if(!$u)
		die( "no u get");
    
	$tu = new TTUser($u); 
    echo "<p><a href='add_user_money.php'>换个用户</a>&nbsp&nbsp;&nbsp;<a href='get_user.php?u=".$u."'>用户信息</a><p>";
    echo "<p>现有金币".$tu->chMoney(0).",宝石".$tu->chGem(0)."</p><br/><br/>";
	
	echo "<form method='post'>";
	echo "<p>金币: <input name='money' value='5000' type='text' /></p>";
	echo "<p>宝石: <input ame='gem' value='0' type='text' /></p>";
	 
	if($_POST['money'] && $_POST['gem'] &&$_POST['money']>=0 &&$_POST['gem']>=0&&$_POST['money']<=1000000000 &&$_POST['gem']<=1000){
		
		$tu->numch('money',$_POST['money']);
		$tu->numch('gem',$_POST['gem']);
		echo "<p>OK".</p>"; 
	}else{
		echo "<p>输入不合法</p>";
	} 
    echo "<p><input type='submit' value='确定'/></p></form>";
}
?>
</body>
</html>
