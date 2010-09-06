<?php 
	require_once('../config.php');
?>
<html
xmlns="http://www.w3.org/1999/xhtml"
	xmlns:xn="http://www.renren.com/2009/xnml"
>
<head>
<script src="<?php echo RenrenConfig::$resource_urlp;?>js/loader.js"></script>
<script type="text/javascript"  src="http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<style type="text/css">
 ul
{
	list-style-type:none;
	padding:0;
	margin:0;
}
li {
    list-style: none;
	
}
ul li
{
	margin:0;
	padding:0;
	float:left;
	list-style-type:none;
}
ul li a
{
	display:block;
	padding:6px 8px
}

</style>
<script type="text/javascript">
		function toFlash()
		{
			window.parent.switchToFlash(); 
		}
</script>
</head>
<body bgcolor="#ffffff">
<div style="overflow: hidden;width:750px;height:750px;border:#3399bb solid 1px;">
<table width="100%">
<tr>
<td align="right"><a  onclick="toFlash()" style="cursor: pointer;"><img src="../static/images/css/close.png" border="0"/></a></td>
</tr>
</table>
<table width="700px">
<?php 
require_once 'freeGift.php';
	$linkid = uniqid();
	$oid = $_REQUEST['oid'];
    $accept_url = RenrenConfig::$canvas_url."cinema.php?lid=$linkid";
	$content = '我正在玩购物天堂，我需要个开个'.$help[$oid]['name'].'，可是人手不够，需要你的帮忙 !!&lt;xn:req-choice url=&quot;'.$accept_url.'&quot;label=&quot;帮ta&quot;&gt;';
	$pid = $_REQUEST['pid'];
	$mode = 'all';
	$content.="&quot;&gt;"; 
	$store_url = RenrenConfig::$callback_url."if/store_link.php?lid=$linkid&pid=$pid&oid=$oid";
	//$store_url = "?linkid=$linkid&gift=$gid";
?>
<tr>
<td align="center">
	<img  src="../static/images/help/<?php echo $help[$oid]['sp']; ?>"/>
</td>
</tr>
<tr>
<td>
<div  id="recm">
   <xn:serverxnml style="width:740px;">
   <script type="text/xnml">
 	<xn:request-form content="<?php echo $content;?>" action="<?php echo $store_url;?>"> 
	<xn:multi-friend-selector-x actiontext="选择好友 帮你开启<?php $help[$oid]['name'];?>" max="30"   mode="all" width="732px"/> 
	</xn:request-form> 
 </script>
</xn:serverxnml> 
</div>

</td>
</tr>
</table>
</div>
<?php
 include FB_CURR.'/cs/baidutongji_js.php';
?> 

</body>
<script type="text/javascript">
var config = {
	useparent:1,//init log? server can force debug, just for develop
	fb:1//init fb
	};

PL.init('../../static/js/config.js',config);
</script>
</html>
