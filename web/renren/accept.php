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
if(!$irec){?>
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
	<style type="text/css">
 .padding_content { padding: 8px; }
 .center { text-align: center; }
 .gift_img { padding: 17px 11px 0px 0px; text-align: center; }
 .gift_name { margin: 5px 17px 0px 5px; color: #70461e; font-weight: bold; }
 li.giftLocked .gift_name { color: #666666; }
 .gift_name span { display: block; height: 30px; }
 .giftIconImg { width: 75px; height: 75px; }
 .main_gift_cont ul { list-style-type: none; }
 .main_giftConfirm_cont { background: #ffffff url("http://assets.frontierville.zynga.com/production/R.0.8.001.19073/assets/images/gift/gift_confirm_box.png") no-repeat scroll left top; width: 744px; height: 265px; }
 .main_giftConfirm_cont h3 { color: #559890; font-size: 18px; padding-top: 15px; }
 .gift_box_holder { padding: 34px 150px; }
 .gift_box_cont { background: #ffffff url("http://assets.frontierville.zynga.com/production/R.0.8.001.19073/assets/images/gift/gift_confirm_gift.png") no-repeat scroll left top; width: 152px; height: 151px; padding-top: 20px; float: left; }
 .gift_from { padding: 55px 0px 0px 62px; float: left; }
 .gift_box_cont .giftConfirm_img { height: 115px; }
 .gift_box_cont .giftConfirm_name { color: #559890; font-weight: bold; }
 .from_box_cont { background: #ffffff url("http://assets.frontierville.zynga.com/production/R.0.8.001.19073/assets/images/gift/gift_confirm_user.png") no-repeat scroll left top; width: 117px; height: 115px; padding-left: 1px; padding-top: 23px; float: right; margin-top: 15px; }
 .giftFrom_img img { height: 50px; }
 .giftFrom_name { padding-top: 20px; }
 .giftFrom_name a:link,  .giftFrom_name a:visited { color: #3b5998; font-weight: bold; text-decoration: underline; }
 .giftFrom_name a:hover,  .giftFrom_name a:active { color: #559890; text-decoration: none; }
.giftformsubmit { border-style: solid; border-color: #d9dfea #0e1f5b #0e1f5b #d9dfea; border-width: 1px; margin: 1px 5px; padding: 3px 10px; background-color: #3b5998; color: white; font-size: 12px; font-weight: bold; text-decoration: none; height: 25px; }
.giftButtonFloat { float: right; margin-top: 1px; margin-bottom: 12px; }
	</style>
		<div class="padding_content center">
		<div class="main_giftConfirm_cont">
			<h3>您接受了<?php echo $gift[$gid]['name'];?></h3>
			<div class="gift_box_holder">
				<div class="gift_box_cont">
					<div class="giftConfirm_img"><img src="http://rrmall.playcrab.com/work/mall/backend/web/renren/static/images/giftIcon/<?php echo $gift[$gid]['icon'];?>"></div>
					<div class="giftConfirm_name"><span><?php echo $gift[$gid]['name'];?></span></div>
				</div>
				<div class="gift_from"><h3>From</h3></div>
				<div class="from_box_cont">
					<div class="giftFrom_img"><img src="<?php echo $tudata['icon'];?>"></div>
					<div class="giftFrom_name"><span><?php echo $tudata['name'];?></span></div>
				</div>
			</div>
		</div>
		</div>
		<div align="center">
			<input type="button" name="skip" value="跳过" class="giftformsubmit giftButtonFloat"  style="cursor: pointer;"/>
		</div>
		<?php 
		}
		$irec['invalid'] =  true;
		$tw->updateo($irec);
		if(!$gid){?>
		<xn:redirect url="<?php echo RenrenConfig::$canvas_url;?>"/>
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
