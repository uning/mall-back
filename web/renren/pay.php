<?php
require_once('config.php');
    include "./header.php";
?> 
<link rel="stylesheet"href="<?php echo RenrenConfig::$resource_urlp;?>css/main.css?2" />

  
<div id='is_install'></div>	

<div id='content'>
    <div class='container'>
        <div class='canvas'>
			<div id="header">
				<div id="navga">
				<div class="logo"><a href="http://apps.renren.com/livemall/" target="_top" title="开始游戏!">logo</a></div>
			   <div id="tabs">
				<ul class="clearfix tcenter">       
					<li class="game" id="flashTab" ><a class="active" href="#switchToFlash" >游戏</a></li>
					<li class="freegift"><a href="../pop/gift.php" id="freeGift" >免费礼物</a></li>
					<li class="invite" ><a href="../pop/invite/invite.php" id="invite" >邀请好友</a></li>
					<li class="faq"><a  href="../static/help/teach.html" class="fullpage" target="_blank">常见问题</a></li>
					<li class="problem"><a  href="javascript:alert('暂未开放');"  id="problem">问题反馈</a></li>
					<li class="payment" ><a  href="http://apps.renren.com/livemall/pay.php"   id ="pay">充值</a></li>
				</ul>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
 


<xn:else>
<img src="<?php echo RenrenConfig::$resource_urlp ?>images/genricbg.jpg"/>
<script>
var auth = false;
function authOK()
{
	auth = true;
	document.setLocation("<?php echo RenrenConfig::$canvas_url;?>?"+Math.random() ) ;
}
function authKO()
{
	auth = false;
	document.setLocation("http://app.renren.com/app/apps/list?origin=119") ;
}
var is_install=document.getElementById('is_install');
if(!Session.isApplicationAdded() || is_install == null ){
	Session.requireLogin(authOK,authKO);
}
</script>

</xn:else>

</xn:if-is-app-user>

