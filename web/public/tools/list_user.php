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

$e= $u + 30;
$s= $u - 30;
$tc=TT::get_tt('genid');
$num = $tc->num();
echo "total users: $num\n";
if($s>=0)
	echo "<a href='?start=$s'>prev</a> | ";
if($num>$u)
	echo "<a href='?start=$e'>next</a>";
echo "<hr/>\n";

for($i=$u;$i<$e;++$i)
{
	$t=TTGenid::getbyid($i);
	$pid = $t['pid'];
	if($t['pid']){
		echo "<a target='_blank' href='../../renren/static/flash/loader_demo.php?pid=$pid'>{$pid}({$i})({$t['name']})</a>\n";	
	}
}
if($s>=0)
	echo "<hr/><a href='?start=$s'>prev</a> | ";
if($num>$u)
	echo "<a href='?start=$e'>next</a>";
?>
</body>
</html>


