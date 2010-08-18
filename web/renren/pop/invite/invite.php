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
<body>
<table>
<tr>
<?php 
	require_once '../freeGift.php';
    $accept_url = RenrenConfig::$canvas_url."accept.php?linkid=$linkid";
	$content = '我正在玩购物天堂，推荐你也来玩一把，在这里白手起家 !!&lt;xn:req-choice url=&quot;'.$accept_url.'&quot;label=&quot;赶快行动&quot;&gt;';
	
	$gid = $_REQUEST["gift"];
	$pid = $_REQUEST['pid'];
	$us = TTGenid::getbypid($pid);
	$exclude ="";	
	$user = new TTUser($us['id']);
	$mode = 'all';
	if(!$gid){
		$mode= 'naf';
	}
	$key  = date('Ymd').$pid;
	$tt = TT::LinkTT();
	$feed = $tt->getbyuidx('udate',$key);
	if($feed)
	{
		$arr = $feed['ids'];
		//print_r($feed);
		//foreach($arr as $uid)
		//$exclude.=$uid.',';
		$linkid = $feed['linkid'];
	}
	else
	{
	$linkid = $pid.':'.uniqid();
	}
	$width = '760px';
	//print_r($exclude);
	if($gid!=NULL&&$gid!=''){
		 $accept_gift_url = RenrenConfig::$canvas_url."accept.php?linkid=$linkid";
		$content = '我在购物天堂送给你个'.$gift[$gid]['name'].',快来领取吧!'.'这个可是要达到'.$gift[$gid]['level'].'级才可以获得的哦'
		.'&lt;xn:req-choice url=&quot;'.$accept_gift_url.'&quot; label=&quot;领取礼物&quot;&gt;&lt;xn:req-choice url=&quot;'.$accept_url.' &quot; label=&quot;试试再说&quot;&gt;';
		echo '<tr><td align="center">';
		echo '<img src="../../static/images/giftIcon/'.$gift[$gid]['icon'].'"/>';
		
		echo '</td></tr>';
	}
	$content.="&quot;&gt;"; 
	$store_url = RenrenConfig::$callback_url."if/store_invite.php?linkid=$linkid&gift=$gid&pid=".$pid;
	//$store_url = "?linkid=$linkid&gift=$gid";
?>
<?php if($gid){?>
<tr>
<td>
<ul style="list-style-type:none;padding: 0;margin: 0;">
<li style="outline-style:none;"><a onclick="changeTab('recm','neibor')">推荐好友</a></li>

<li style="outline-style:none;"><a onclick="changeTab('neibor','recm')">我的邻居</a></li>

</ul>
</td>
</tr>
<?php }?>
<tr>
<td>
<div style="display:block;width: 96%" id="recm">
   <xn:serverxnml style="width:<?php echo $width;?>;">
   <script type="text/xnml">
 	<xn:request-form content="<?php echo $content;?>" action="<?php echo $store_url;?>"> 
	<xn:multi-friend-selector-x actiontext="选择好友" max="30"  exclude_ids="<?php echo $exclude;?>" mode="naf"/> 
	</xn:request-form> 
 </script>
</xn:serverxnml> 
</div>

</td>
</tr>
</table>
<div style="display:none;width: 96%" id="neibor">
	 <xn:serverxnml style="width:<?php echo $width;?>;">
   <script type="text/xnml">
 	<xn:request-form content="<?php echo $content;?>" action="<?php echo $store_url;?>"> 
	<xn:multi-friend-selector-x actiontext="选择好友" max="30"  exclude_ids="<?php echo $exclude;?>" mode="af"/> 
	</xn:request-form> 
 </script>
</xn:serverxnml> 
</div>
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
