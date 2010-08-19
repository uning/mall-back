<?php
require_once('config.php');
    include "./header.php";
?> 

 <style>  
#header .logo {
    width: 165px;
    height: 46px;
    background: url("<?php echo RenrenConfig::$resource_urlp; ?>/images/logo.png?v=1") no-repeat center;
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
    background-position: 0 -352px;
}

#navga ul li.forum a.active, #navga ul li.forum a:hover {
    background-position: 0 -397px;
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

 </style>
  
<div id='is_install'></div>	

<div id='content'>
    <div class='container'>
        <div class='canvas'>
			<div id="header">
				<div id="navga">
				<div class="logo"><a href="http://apps.renren.com/livemall/" target="_top" title="开始游戏!">logo</a></div>
			   <div id="tabs">
				<ul class="clearfix tcenter">       
					<li class="game" id="flashTab" ><a class="active" href="http://apps.renren.com/livemall" >游戏</a></li>
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

