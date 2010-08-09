<html 
xmlns="http://www.w3.org/1999/xhtml" 
	xmlns:xn="http://www.renren.com/2009/xnml">
<head>
<link rel="stylesheet" type="text/css" href="static/css/gift.css" />
</head>
<body>
<?php
require_once('config.php');
require_once('pop/freeGift.php');
$linkid = $_REQUEST['linkid'];
$irec = false;
$fromuser = false;
$touser = false;
if($linkid){
	$tw = TT::TTWeb();
	$irec = $tw->getbyid($linkid);
	$fromuser = $irec['pid'];	
	$touser = $_REQUEST['xn_sig_user'];	
}
if(!$irec || !$fromuser || !$touser){?>
<xn:if-is-app-user>
<xn:redirect url="<?php echo RenrenConfig::$canvas_url.'?from=uinvite';?>"/>
<xn:else>
<xn:redirect url="<?php $rurl = 'http://app.renren.com/apps/tos.do?api_key='.RenrenConfig::$api_key.'&v=1.0&next=';echo $rurl;?>"/>
 </xn:else>

</xn:if-is-app-user>
<?php 
exit ;
}else{ 
	
}
?>
<xn:if-is-app-user>
<?php
	$fsess = TTGenid::getbypid($fromuser);	
	$tsess = TTGenid::getbypid($touser);	
	$ftu = new TTUser($fsess['id']);
	$ttu = new TTUser($tsess['id']);
	$tudata=$ftu->getf(array('name','icon'));
	
	if($irec){
		$gid = $irec['gift'];
		if($gid){?>
		
			<div class="padding_content center">
			<div class="main_giftConfirm_cont">
			<h3>您接受了<?php echo $gift[$gid]['name'];?></h3>
			<div class="gift_box_holder">
				<div class="gift_box_cont">
					<div class="giftConfirm_img"><img src="<?php echo $gift[$gid]['icon'];?>"></div>
					<div class="giftConfirm_name"><span><?php echo $gift[$gid]['name'];?></span></div>
				</div>
				<div class="gift_from"><h3>From</h3></div>
				<div class="from_box_cont">
					<div class="giftFrom_img"><img src="<?php echo $tudata['icon'];?>"></div>
					<div class="giftFrom_name"><span><?php echo $tudata['name'];?></span></div>
				</div>
				<input type="button" name="acc" value="进入游戏" style="background-color: blue;" onclick=""></input>
			</div>
		</div>
		
	</div>
	<?php } }?>
<xn:else>
<xn:redirect url="<?php 
$next = RenrenConfig::$canvas_url."accept_gift.php?linkid=$linkid&f=auth"; 
$rurl = 'http://app.renren.com/apps/tos.do?api_key='.RenrenConfig::$api_key.'&v=1.0&next='.urlencode($next);
echo $rurl;?>"/>
 </xn:else>
</xn:if-is-app-user>
</body>
</html>