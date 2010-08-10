<link rel="stylesheet" type="text/css" href="static/css/gift.css" />
<?php
require_once('config.php');
require_once('pop/freeGift.php');
$linkid = $_REQUEST['linkid'];
$irec = false;
$tw = TT::TTWeb();
if($linkid){
	
	$irec = $tw->getbyid($linkid);
}
$fromuser = $irec['pid'];	
$touser = $_REQUEST['xn_sig_user'];	
if(!$irec || !$fromuser||!$touser){?>
<xn:if-is-app-user>
<xn:redirect url="<?php echo RenrenConfig::$canvas_url.'?from=uinvite';?>"/>
<xn:else>
<xn:redirect url="<?php $rurl = 'http://app.renren.com/apps/tos.do?api_key='.RenrenConfig::$api_key.'&v=1.0&next='.RenrenConfig::$canvas_url;echo $rurl;?>"/>
 </xn:else>
</xn:if-is-app-user>
<?php 
		exit ;
}
?>
<xn:if-is-app-user>
<?php
	$fsess = TTGenid::getbypid($fromuser);	
	$tsess = TTGenid::getbypid($touser);	
	$ftu = new TTUser($fsess['id']);
	$ttu = new TTUser($tsess['id']);
	$tudata=$ftu->getf(array('name','icon'));
	if(!$irec['invalid']){
		$ftu->numch('invite_num',1);
	}
	if(!$irec['invalid']){
		$gid = $irec['gift'];
		if($gid){?>
		
		<div class="padding_content center">
		<div class="main_giftConfirm_cont">
			<h3>您接受了<?php echo $gift[$gid]['name'];?></h3>
			<div class="gift_box_holder">
				<div class="gift_box_cont">
					<div class="giftConfirm_img"><img src="<?php echo $gift[$value[$gid]]['icon'];?>"></div>
					<div class="giftConfirm_name"><span><?php echo $gift[$value[$gid]]['name'];?></span></div>
				</div>
				<div class="gift_from"><h3>From</h3></div>
				<div class="from_box_cont">
					<div class="giftFrom_img"><img src="<?php echo $tudata['icon'];?>"></div>
					<div class="giftFrom_name"><span><?php echo $tudata['name'];?></span></div>
				</div>
			</div>
		</div>
		</div>
		<div>
			<input type="button" name="sure" value="确定" onclick="window.location = ">
		</div>
		<?php 
		}
		$irec['invalid'] =  true;
		$tw->updateo($irec);
		if(!$gid){?>
		<xn:redirect url="<?php echo RenrenConfig::$canvas_url.'?from=uinvite';?>"/>
		<?php }
		}
		?>
<xn:else>
<xn:redirect url="<?php 
$next = RenrenConfig::$canvas_url."accept.php?linkid=$linkid"; 
$rurl = 'http://app.renren.com/apps/tos.do?api_key='.RenrenConfig::$api_key.'&v=1.0&next='.urlencode($next);
echo $rurl;?>"/>
 </xn:else>
</xn:if-is-app-user>
