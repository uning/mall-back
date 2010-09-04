<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
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

$page_num = 30;
$e= $u + $page_num;
$s= $u - $page_num;
$tc=TT::get_tt('genid','slave');
$num = $tc->num();
echo "total users: $num\n";
if($s>=0)
	echo "<a href='?start=$s'>prev</a> | ";
if($num>$u)
	echo "<a href='?start=$e'>next</a> | ";
$last = $num - $page_num +10;
echo "<a href='?start=$last'>last</a>  ";
echo "<hr/>\n";

for($i=$u;$i<$e;++$i)
{
	$t=TTGenid::getbyid($i);
	$pid = $t['pid'];
	$sk = $t['session_key'];
	if($pid){
		echo "{$pid}({$i})({$t['name']}):  <a target='_blank' href='../../renren/static/flash/loader_demo.php?pid=$pid'>play demo</a> | ";	
		if(is_numeric($pid)){
		echo "<a target='_blank' href='../../renren/update_info.php?xn_sig_user=$pid'>get platform data</a> | ";	
		//echo "<a target='_blank' href='../../renren/update_info.php?xn_sig_user=$pid&xn_sig_session_key=$sk'>update friends</a> ";	
		}
		echo "<a target='_blank' href='get_user.php?u=$i'>view data</a> \n";	
	}
}
if($s>=0)
	echo "<hr/><a href='?start=$s'>prev</a> | ";
if($num>$u)
	echo "<a href='?start=$e'>next</a>";
echo "<a href='?start=$last'>last</a>  ";
?>
</body>
</html>


