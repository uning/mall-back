<?php 
	require_once('../../config.php');
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
		function changeTab(id,hide)
		{
			var hide = document.getElementById(hide);
			hide.style.display = 'none';
			var tab = document.getElementById(id);
			tab.style.display = 'block';
		}
</script>
</head>
<?php 
$height = 650;
if($_REQUEST['gift'])
	$height = 750;
?>
<body bgcolor="#ffffff">
<div style="overflow: hidden;width:780px;height:<?php echo $height.'px';?>;border:#3399bb solid 1px;">
<table width="100%">
<tr>
<td align="right"><a  onclick="toFlash()" style="cursor: pointer;"><img src="../../static/images/css/close.png" border="0"/></a></td>
</tr>
</table>
<table width="700px">
<?php 
	require_once '../freeGift.php';
    
	
	
	$gid = $_REQUEST["gift"];
	$pid = $_REQUEST['pid'];
	$us = TTGenid::getbypid($pid);
	$exclude ="0";	
	$user = new TTUser($us['id']);
	$mode = 'all';
	if(!$gid){
		$mode= 'naf';
	}
	//$key  = date('Ymd').$pid;
	$tt = TT::LinkTT();
	$feed = $tt->getbyuidx('uid',$pid);
	if($feed)
	{
		$today = $feed['time'];
		$arr = '0';
		if($today==date('Ymd'))
		foreach ($feed['invite'] as $k=>$v){
				 $arr.=','.$k;
		}
	}
	
	$linkid = uniqid();
	
	$width = '740px';
	$accept_url = RenrenConfig::$canvas_url."accept.php?lid=$linkid";
	$content = '帮好友装货，卸货，在这里开电影院、盖厕所、做导购员，去好友那里抢客人，都在这里 !!&lt;xn:req-choice url=&quot;'.$accept_url.'&quot;label=&quot;赶快行动&quot;&gt;';
	//print_r($exclude);
	if($gid!=NULL&&$gid!=''){
		 $accept_gift_url = RenrenConfig::$canvas_url."accept.php?lid=$linkid";
		$content = '我在购物天堂送给你个'.$gift[$gid]['name'].',快来领取吧!'.'这个可是要达到'.$gift[$gid]['level'].'级才可以获得的哦'
		.'&lt;xn:req-choice url=&quot;'.$accept_gift_url.'&quot; label=&quot;领取礼物&quot;&gt;&lt;xn:req-choice url=&quot;'.$accept_url.' &quot; label=&quot;试试再说&quot;&gt;';
		echo '<tr><td align="center">';
		echo '<img src="../../static/images/giftIcon/'.$gift[$gid]['icon'].'"/>';
		
		echo '</td></tr>';
	}
	//$content.="&quot;&gt;"; 
	$sessionK= $_REQUEST['sessionK'];
	$store_url = RenrenConfig::$callback_url."if/store_invite.php?lid=$linkid&gift=$gid&pid=$pid&sessionK=$sessionK";
	//$store_url = "?linkid=$linkid&gift=$gid";
?>
<tr>
<td>
<div  id="recm">
   <xn:serverxnml style="width:740px;">
   <script type="text/xnml">
 	<xn:request-form content="<?php echo $content;?>" action="<?php echo $store_url;?>"> 
	<xn:multi-friend-selector-x actiontext="选择好友" max="50"  exclude_ids="<?php echo $arr;?>" mode="<?php echo $mode;?>" width="732px"/> 
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
<?php include FB_CURR.'/cs/gajs_init.php';?> 
