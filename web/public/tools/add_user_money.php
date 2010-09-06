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
$data = null;
if(!$u){
 	$pid=$_REQUEST['pid'];  
    if($pid)
	  $data = TTGenid::getbypid($pid);
}else
	$data = TTGenid::getbyid($u);

if(!$data||!$data['id']){
   echo "<p>查找用户</p>";
	echo "<p><form method='get'>PID: <input name='pid' type='text' /><input type='submit' value='获取用户' /> </form></p>";
}else{ 
   
    $u = $data['id'];
	$name = $data['name']; 
	
	if(!$u)
		die( "no u get");
    
	$tu = new TTUser($u); 
   
   
	echo "<p><a href='add_user_money.php'>换个用户</a>&nbsp&nbsp;&nbsp;<a target='_blank' href='get_user.php?u=".$u."'>用户信息</a><p>";
	 echo "<p>".$name."</p>";
   
	if( $_POST['money']!==null && $_POST['gem']!== null && $_POST['money']>=0 &&$_POST['gem']>=0&&$_POST['money']<=1000000000 &&$_POST['gem']<=1000){ 
		$tu->numch('money',$_POST['money']);
		$tu->numch('gem',$_POST['gem']);
		echo "<p>OK，  <a target='_blank' href='http://msg.renren.com/SendMessage.do?id=".$data['pid']."'>发送站内信通知".$name."</a></p>"; 
	    
			
	}else{
		echo "<p>输入要加的金币和宝石数</p>";
		
		echo "<form method='post'>";
	    echo "<p>金币: <input name='money' value='0' type='text' /></p>";
	    echo "<p>宝石: <input name='gem' value='0' type='text' /></p>";
	
        echo "<p><input type='submit' value='确定'/></p></form>";
	} 
	
	echo "<p>现有金币".$tu->chMoney(0).",宝石".$tu->chGem(0)."</p><br/><br/>";


}
?>
</body>
</html>
