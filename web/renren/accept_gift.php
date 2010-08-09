<?php
require_once('config.php');
$linkid = $_REQUEST['linkid'];
if($linkid){
	$tw = TT::TTWeb();
	$irec = $tw->getbyid($linkid);
	$fromuser = $irec['pid'];	
	$touser = $_REQUEST['xn_sig_user'];	
}
if(!$irec || !$fromu || !$tou){?>
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
	$ftu = new TTUser($fsess['id'];
	$ttu = new TTUser($tsess['id'];
	if($ire
		
  
?>
<xn:else>
<xn:redirect url="<?php 
$next = RenrenConfig::$canvas_url."accept_gift.php?linkid=$linkid&f=auth"; 
$rurl = 'http://app.renren.com/apps/tos.do?api_key='.RenrenConfig::$api_key.'&v=1.0&next='.urlencode($next);
echo $rurl;?>"/>
 </xn:else>

</xn:if-is-app-user>
