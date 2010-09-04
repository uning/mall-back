<?php 
require_once('config.php');
require_once('renren.php');
require_once('pop/freeGift.php');
$linkid = $_REQUEST['lid'];	
$linkid ='4c8205480c843';
$tw = TT::LinkTT();
$link = $tw->getbyuidx('lid',$linkid);
$touser = $_REQUEST['xn_sig_user'];
$touser = 45182749;
?>
<xn:if-is-app-user>
<div id='is_install'></div>
<?php
	$fromuser = $link['pid'];
	$fsess = TTGenid::getbypid($fromuser);	
	$tsess = TTGenid::getbypid($touser);	
	$ftu = new TTUser($fsess['id']);
	$ttu = new TTUser($tsess['id']);
	$att = $ttu ->getdata('authat');
	$ut = $ttu ->getdata('ut');
	$gemd = $ttu -> getdata('gemd');
	print_r($link);
	print_r($att);
	print_r($ut);
	print_r($gemd);
	if($link['gift']){
		$lg = $link['gift'];
	}
	else
	   $lg = 0;
	$new  = 1;
	if($_REQUEST['new']){
	 	$new = 1;
	 }

	TTLog::record(array('m'=>'accept_invite','tm'=> $_SERVER['REQUEST_TIME'],'u'=>$touser,'sp1'=>$lg,'sp2'=>$new));
	//$tudata=$ftu->getf(array('name','icon'));
	$getted = $link['geted'];
	$ids = $link['ids'];
	$invite = false;
	foreach($ids as $id){
		if($id==$touser){
				$invite = true;
				break;
				}
		}  
	$got = false;
	foreach ($getted as $u){
			if($u==$touser){
				$got = true;
					break;
					}
		}
	$diff = time() - $att;
	if($invite&&$new ==1&&!$gemd&&!$ut&&$diff<60){
		$ftu->numch('invite_num',1);
		$cid = $ftu->getoid('copilot',TT::OTHER_GROUP );	    
		$copilot = $ftu->getbyid( $cid );
		print_r($copilot);
		$copilot['id'] = $cid;
		$copilot['bag'][2004] += 1;
		$ftu->puto($copilot);
		$ttu->change('gemd',1);
		print_r( $ftu->getbyid( $cid ));
	}				
	if($invite){
	$gid = $link['gift'];
	if($gid){?>
		<div id='content'>
	<div class='container'>
        <div class='canvas'>
			<div id="header">
				<div id="navga">
				<div class="logo"><a href="<?php echo RenrenConfig::$canvas_url;?>" target="_top" title="开始游戏!">logo</a></div>
			   <div id="tabs">
				<ul class="clearfix tcenter">       
					<li class="game" id="flashTab" ><a  href="<?php echo RenrenConfig::$canvas_url;?>" >游戏</a></li>
					<li class="freegift"><a class="active"  href="<?php echo RenrenConfig::$canvas_url;?>?a=freeGift" id="freeGift" >免费礼物</a></li>
					<li class="invite" ><a href="<?php echo RenrenConfig::$canvas_url;?>?a=invite" >邀请好友</a></li>
					<li class="faq"><a id='faq'  href="<?php echo RenrenConfig::$canvas_url;?>?a=faq" >常见问题</a></li>
					<li class="forum"><a href="<?php echo RenrenConfig::$group_url; ?>" class="fullpage" target='_blank' id="forum">论坛</a></li>
					<li class="payment" ><a  href="<?php echo RenrenConfig::$canvas_url;?>pay.php"   id ="pay">充值</a></li>
				</ul>
				</div>
				</div>
			</div>
			</div>
			</div>
	<div class="padding_content center" style="overflow: hidden;">
	<div class="main_giftConfirm_cont">
	<h3>您接受了<?php echo $gift[$gid]['name'];?></h3>
	<div class="gift_box_holder">
	<div class="gift_box_cont">
	<div class="giftConfirm_img"><img
		src="<?php echo RenrenConfig::$resource_urlp; ?>/images/giftIcon/<?php echo $gift[$gid]['icon'];?>"></img>
	</div>
	<div class="giftConfirm_name"><span><?php echo $gift[$gid]['name'];?></span></div>
	</div>
	<div class="gift_from">
	<h3>From</h3>
	</div>
	<div class="from_box_cont">
	<div class="giftFrom_img"><xn:profile-pic uid="<?php echo $fromuser;?>" linked="false" size="tiny" /></div>
	<div class="giftFrom_name"><span><xn:name uid="<?php echo $fromuser;?>" linked="false" shownetwork="false" /></span></div>
	</div>
	</div>
	</div>
	</div>
	<div style="text-align: center;">
	<?php 
		if($got) {?>
			
				<h3>您的礼物已经领取，请在仓库中查收。</h3>
			
		<?php }?>
	         <a href='<?php echo RenrenConfig::$canvas_url;?>' class='giftformsubmit' >返回游戏</a>	
	</div>
	</div>
		<?php 
		if(!$got){
			$id = $ttu->getdid( '',$gift[$gid]['group'] );
			$data['tag'] = $gid;
			$data['id'] = $id;
			$data['pos']='s';
			$ttu->puto( $data ); 
			}
		}
		if(!$got){
		$link['geted'][] = $touser;
		$tw->put($link);
		}
		if(!$gid){?>
		<xn:redirect url="<?php echo RenrenConfig::$canvas_url;?>" />
		<?php }
		}
		?>
	<xn:else>
<img src="<?php echo RenrenConfig::$resource_urlp ?>images/genericbg.jpg"/>
<script>
var auth = false;
function authOK()
{
	auth = true;
	document.setLocation("<?php echo RenrenConfig::$canvas_url;?>accept.php?linkid=<?php echo $linkid; ?>&new="+Math.random() ) ;
}
function authKO()
{
	auth = false;
	document.setLocation("<?php echo RenrenConfig::$canvas_url;?>") ;
}
var is_install=document.getElementById('is_install');
if(!Session.isApplicationAdded() || is_install == null ){
	Session.requireLogin(authOK,authKO);
}
</script>
</xn:else>
</xn:if-is-app-user>