<?php 
require_once('config.php');
require_once('renren.php');

?>
<style type="text/css">
.padding_content {
	padding: 8px;
}

.center {
	text-align: center;
}

.gift_img {
	padding: 17px 11px 0px 0px;
	text-align: center;
}

.gift_name {
	margin: 5px 17px 0px 5px;
	color: #70461e;
	font-weight: bold;
}

li.giftLocked .gift_name {
	color: #666666;
}

.gift_name span {
	display: block;
	height: 30px;
}

.giftIconImg {
	width: 75px;
	height: 75px;
}

.main_gift_cont ul {
	list-style-type: none;
}

.main_giftConfirm_cont {
	background:
		url("http://rrmall.playcrab.com/work/mall/backend/web/renren/static/images/css/gift_confirm_box.png")
		no-repeat scroll left top;
	width: 744px;
	height: 265px;
}

.main_giftConfirm_cont h3 {
	color: #559890;
	font-size: 18px;
	padding-top: 15px;
}

.gift_box_holder {
	padding: 34px 150px;
}

.gift_box_cont {
	background:
		url("http://rrmall.playcrab.com/work/mall/backend/web/renren/static/images/css/gift_confirm_gift.png")
		no-repeat scroll left top;
	width: 152px;
	height: 151px;
	padding-top: 20px;
	float: left;
}

.gift_from {
	padding: 55px 0px 0px 62px;
	float: left;
}

.gift_box_cont .giftConfirm_img {
	height: 115px;
}

.gift_box_cont .giftConfirm_name {
	color: #559890;
	font-weight: bold;
}

.from_box_cont {
	background: 
		url("http://rrmall.playcrab.com/work/mall/backend/web/renren/static/images/css/gift_confirm_user.png")
		no-repeat scroll left top;
	width: 117px;
	height: 115px;
	padding-left: 1px;
	padding-top: 23px;
	float: right;
	margin-top: 15px;
}

.giftFrom_img img {
	height: 50px;
}

.giftFrom_name {
	padding-top: 20px;
}

.giftFrom_name a:link
  giftFrom_name a:visited {
	color: #3b5998;
	font-weight: bold;
	text-decoration: underline;
}

.giftFrom_name a:hover
  giftFrom_name a:active {
	color: #559890;
	text-decoration: none;
}

.giftformsubmit {
	border-style: solid;
	border-color: #d9dfea #0e1f5b #0e1f5b #d9dfea;
	border-width: 1px;
	margin: 1px 5px;
	padding: 3px 10px;
	background-color: #3b5998;
	color: white;
	font-size: 12px;
	font-weight: bold;
	text-decoration: none;
	height: 25px;
}

.giftButtonFloat {
	float: center;
	margin-top: 1px;
	margin-bottom: 12px;
}

.giftformsubmit {
	border-style: solid;
	border-color: #d9dfea #0e1f5b #0e1f5b #d9dfea;
	border-width: 1px;
	margin: 1px 5px;
	padding: 3px 10px;
	background-color: #3b5998;
	color: white;
	font-size: 12px;
	font-weight: bold;
	text-decoration: none;
	height: 25px;
}

.giftButtonFloat {
	float: center;
	margin-top: 1px;
	margin-bottom: 12px;
} 
 
#content {
height: 700px;
font:12px/1.5 tahoma,arial,微软雅黑,宋体,sans-serif;
}
#header .logo {
    width: 195px;
    height: 46px;
    background: url("<?php echo RenrenConfig::$resource_urlp; ?>/images/logo.png?v=1") no-repeat 10px center transparent;
    text-indent: -9999px;
    float: left;
}

#header .logo  a {
    display: block;
    height: 36px;
} 
 
#navga ul { 
    margin: 0 0 5px 0px;
    padding-top: 14px;
}

#navga ul li {
    float: left;
    cursor: pointer;
    padding: 0 2px;
}

#navga ul li a {
    display: block;
    text-indent: -9999px;
    background: url("<?php echo RenrenConfig::$resource_urlp; ?>/images/nav.png") no-repeat left top;
    width: 95px;
    height: 32px; 
} 
#navga ul li.game a {
    background-position: 0 -1px;
}


#navga ul li.game a.active, #navga ul li.game a:hover {
    background-position: 0 -45px;
    outline:none;
	blr:expression(this.onFocus=this.blur());
	
}

#navga ul li.freegift a {
    background-position: 0 -88px;
}

#navga ul li.freegift a.active, #navga ul li.freegift a:hover {
    background-position: 0 -133px;
	outline:none;
	blr:expression(this.onFocus=this.blur());
}

#navga ul li.invite a {
    background-position: 0 -176px;
}

#navga ul li.invite a.active, #navga ul li.invite a:hover {
    background-position: 0 -221px;
}

/*payment*/
#navga ul li.faq a {
    background-position: 0 -263px;
}

#navga ul li.faq a.active, #navga ul li.faq a:hover {
    background-position: 0 -308px;
    outline:none;
	blr:expression(this.onFocus=this.blur());
}

#navga ul li.forum a {
    background-position: 0 -351px;
}

#navga ul li.forum a.active, #navga ul li.forum a:hover {
    background-position: 0 -396px;
}
#navga ul li.payment a {
    background-position: 0 -440px;
}

#navga ul li.payment a.active, #navga ul li.payment a:hover {
    background-position: 0 -487px;
    outline:none;
	blr:expression(this.onFocus=this.blur());
}
#navga ul li.problem a {
    background-position: 0 -532px;
}

#navga ul li.problem a.active, #navga ul li.problem a:hover {
    background-position: 0 -579px;
    outline:none;
	blr:expression(this.onFocus=this.blur());
}

.giftformsubmit {
background-color:#3B5998;
border-color:#D9DFEA #0E1F5B #0E1F5B #D9DFEA;
border-style:solid;
border-width:1px;
color:white;
font-size:12px;
font-weight:bold;
height:25px;
margin:1px 5px;
padding:3px 10px;
text-decoration:none;
}
</style>
<?php
require_once('pop/freeGift.php');
$linkid = $_REQUEST['lid'];
$linkid='4c84b502095ce';
$tw = TT::LinkTT();
$link = $tw->getbyuidx('lid',$linkid);
print_r($link);
print_r($tw->getbyuidx('uid',$link['pid']));
$touser = $_REQUEST['xn_sig_user'];
?>
<xn:if-is-app-user>
<div id='is_install'></div>
<?php
$touser = 45182749;
	$fromuser = $link['pid'];
	$fsess = TTGenid::getbypid($fromuser);	
	$tsess = TTGenid::getbypid($touser);	
	$ftu = new TTUser($fsess['id']);
	$ttu = new TTUser($tsess['id']);
	$att = $tsess['authat'];
	$ut = $tsess['ut'];
	$gemd = $tsess['gemd'];
	if($link['gift']){
		$lg = $link['gift'];
	}
	else
	   $lg = 0;
	$new  = 0;
	if($_REQUEST['new']){
	 	$new = 1;
	 }

	TTLog::record(array('m'=>'accept_invite','tm'=> $_SERVER['REQUEST_TIME'],'u'=>$fromuser,'sp1'=>$lg,'sp2'=>$new,'intp1'=>$touser));
	//$tudata=$ftu->getf(array('name','icon'));
	$getted = $link['geted'];
	$ids = $link['ids'];
	if(strtotime($link['date'])<strtotime('20100906')){
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
	}	
	else{
		$invite = false;
	if(array_key_exists($touser,$ids))
				$invite = true;
				$got = false;
	if(array_key_exists($touser,$getted))
				$got = true;
	} 
	
			
	
	if($invite&&$new ==1&&!$gemd&&!$ut&&!$got){
		$ftu->numch('invite_num',1);
		$cid = $ftu->getoid('copilot',TT::OTHER_GROUP );	    
		$copilot = $ftu->getbyid( $cid );
		$copilot['id'] = $cid;
		$copilot['bag'][2004] += 1;
		$ftu->puto($copilot);
		$tsess['gemd']=1;
		TTGenid::update($tsess,$tsess['id']);
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
		$link['geted'][$touser] =1 ;
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
