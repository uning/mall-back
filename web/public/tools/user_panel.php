<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<form>
平台id:<input name='pid'></input>
<input type=submit value='查询'></input>
</form>
<form>
内部id:<input name='start'></input>
<input type=submit value='查询'></input>
</form>
<?php
require_once '../base.php';
echo "<pre>\n";
$u=$argv[1];
if(!$u){
	$u=$_REQUEST['start'];
}
if(!$u){
	$pid = $argv[2];
}
if(!$u){
	$pid = 1;
}
 
$tc=TT::get_tt('genid','slave');
 
 
$pid = $_REQUEST['pid'];
if($pid){
	$sess=TTGenid::getbypid($pid);
	$u=$sess['id'];
	if(!$u){
		die("not find pid $pid");
	}
}

 $t=TTGenid::getbyid($u);
$pid = $t['pid'];
$sk = $t['session_key'];
if($pid){
	echo "{$pid}({$u})({$t['name']}):  <a target='_blank' href='../../renren/static/flash/loader_demo.php?pid=$pid'>play demo</a> | ";	
	if(is_numeric($pid)){
    	echo "<a target='_blank' href='../../renren/update_info.php?xn_sig_user=$pid'>更新平台信息</a> | ";	
		echo "<a target='panel'  href='../../renren/update_friends.php?xn_sig_user=$pid'>更新好友信息</a> | ";	
		echo "<a target='panel' href='add_user_money.php?pid=$pid'>加金币或宝石</a> | ";
		echo "<a target='panel' href='view_user_deals.php?u=$u'>最近操作记录</a> | "; 
		echo "<a target='_blank' href='http://msg.renren.com/SendMessage.do?id=$pid'>发人人站内信</a> | ";	
	}
	echo "<a target='panel' href='get_user.php?u=$u'>查看数据</a> \n";	
}
 
?>
<iframe scrolling="no" height="1100" frameborder="0" width="800" name='panel'></iframe>

</body>
</html>


