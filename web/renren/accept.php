
<?php
require_once('config.php');
require_once('pop/freeGift.php');
$linkid = $_REQUEST['linkid'];
$tw = TT::LinkTT();
list($pid,$str) = split(':',$linkid);
$udate = $pid.':'.date('Ymd');
$irec = $tw->getbyuidx('udate',$udate);
$fromuser = $pid;	
$touser = $_REQUEST['xn_sig_user'];	
if(!$irec[$linkid]){?>
<xn:if-is-app-user>
	<xn:redirect url="<?php echo RenrenConfig::$canvas_url.'?from=uinvite';?>" />
	<xn:else>
		<xn:redirect
			url="<?php $rurl = 'http://app.renren.com/apps/tos.do?api_key='.RenrenConfig::$api_key.'&v=1.0&next='.RenrenConfig::$canvas_url;echo $rurl;?>" />
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
	
	$getted = $irec[$linkid]['geted'];
	if(!$getted[$touser]){
		$ftu->numch('invite_num',1);
	}
	if(!$getted[$touser]){
		$gid = $irec['gift'];
		if($gid){?>
	<div class="padding_content center" style="overflow: hidden;">
	<div class="main_giftConfirm_cont">
		<h3>您接受了<?php echo $gift[$gid]['name'];?></h3>
	<div class="gift_box_holder">
		<div class="gift_box_cont">
			<div class="giftConfirm_img">
			<img src="http://rrmall.playcrab.com/work/mall/backend/web/renren/static/images/giftIcon/<?php echo $gift[$gid]['icon'];?>"></img>
			</div>
			<div class="giftConfirm_name"><span><?php echo $gift[$gid]['name'];?></span></div>
		</div>
		<div class="gift_from">
			<h3>From</h3>
		</div>
		<div class="from_box_cont">
			<div class="giftFrom_img"><img src="<?php echo $tudata['icon'];?>" /></div>
			<div class="giftFrom_name"><span><?php echo $tudata['name'];?></span></div>
		</div>
	</div>
	</div>
	</div>
	<div align="center"><a href="http://apps.renren.com/livemall"
		class="giftformsubmit giftButtonFloat">~游戏去~</a>
	</div>
		<?php 
			$id = $ttu->getdid( '',$gift[$gid]['group'] );
			$data['tag'] = $gid;
			$data['id'] = $id;
			$data['pos']='s';
			$ttu->puto( $data ); 
		}
		//$irec['invalid'] =  1;
		$getted[$touser] = 1;
		$irec[$linkid]['geted'] = $getted;
		$tw->put($irec);
		if(!$gid){?>
		<xn:redirect url="<?php echo RenrenConfig::$canvas_url;?>" />
		<?php }
		}
		else{?>
			<div align="center"><a href="http://apps.renren.com/livemall"
				class="giftformsubmit giftButtonFloat">~游戏去~</a></div>
		<?php }?>
	<xn:else>
		<xn:redirect
			url="<?php 
$next = RenrenConfig::$canvas_url."accept.php?linkid=$linkid"; 
$rurl = 'http://app.renren.com/apps/tos.do?api_key='.RenrenConfig::$api_key.'&v=1.0&next='.urlencode($next);
echo $rurl;?>" />
	</xn:else>
</xn:if-is-app-user>
